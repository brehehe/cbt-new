<?php

namespace App\Services\Exam;

use Illuminate\Support\Facades\Storage;

class RecordingFinalizer
{
    /**
     * Finalize recording chunks for a given UserTimetable ID.
     * Returns an array with manifest path, merged video path (if any), total size and chunk count.
     */
    public static function finalizeForUserTimetable(string $userTimetableId): array
    {
        $baseDir = 'exam_recordings/chunks/' . $userTimetableId;
        $disk = Storage::disk('public');

        $manifestPath = $baseDir . '/manifest.json';
        $mergedRelPath = null;
        $totalSize = 0;
        $chunkFiles = [];

        if ($disk->exists($baseDir)) {
            $files = $disk->files($baseDir);
            $chunkFiles = array_values(array_filter($files, function ($f) {
                return str_contains($f, '_chunk_') && str_ends_with($f, '.webm');
            }));

            // Sort by chunk number
            usort($chunkFiles, function ($a, $b) {
                $na = (int) preg_replace('/.*_chunk_(\d+)\.webm$/', '$1', $a);
                $nb = (int) preg_replace('/.*_chunk_(\d+)\.webm$/', '$1', $b);
                return $na <=> $nb;
            });

            foreach ($chunkFiles as $f) {
                $totalSize += $disk->size($f);
            }
        }

        // Write manifest JSON
        $manifest = [
            'user_timetable_id' => $userTimetableId,
            'chunk_count' => count($chunkFiles),
            'chunks' => $chunkFiles,
            'finalized_at' => now()->toISOString(),
        ];
        $disk->put($manifestPath, json_encode($manifest));

        // Attempt to merge chunks using ffmpeg, then fallback to byte concat
        try {
            if (count($chunkFiles) > 0) {
                // Use the first chunk's recording id prefix to name merged file
                $first = $chunkFiles[0];
                $prefix = basename($first, '.webm');
                $prefix = preg_replace('/_chunk_\d+$/', '', $prefix);
                $mergedRelPath = $baseDir . '/' . $prefix . '_merged.webm';
                $mergedAbsPath = $disk->path($mergedRelPath);

                $ffmpegPath = trim((string) shell_exec('which ffmpeg'));
                if (!empty($ffmpegPath)) {
                    // Concat demuxer requires a list file
                    $concatListPath = $disk->path($baseDir . '/concat.txt');
                    $listLines = [];
                    foreach ($chunkFiles as $cf) {
                        $listLines[] = "file '" . $disk->path($cf) . "'";
                    }
                    file_put_contents($concatListPath, implode(PHP_EOL, $listLines));

                    @unlink($mergedAbsPath);
                    $cmd = escapeshellcmd($ffmpegPath) . ' -y -f concat -safe 0 -i ' . escapeshellarg($concatListPath) . ' -c copy ' . escapeshellarg($mergedAbsPath) . ' 2>&1';
                    $output = [];
                    $ret = 0;
                    exec($cmd, $output, $ret);
                    if (!($ret === 0 && file_exists($mergedAbsPath) && filesize($mergedAbsPath) > 0)) {
                        // Try concat protocol
                        $protoParts = [];
                        foreach ($chunkFiles as $cf) {
                            $protoParts[] = $disk->path($cf);
                        }
                        $concatProto = 'concat:' . implode('|', $protoParts);
                        @unlink($mergedAbsPath);
                        $output = [];
                        $ret = 0;
                        $cmd = escapeshellcmd($ffmpegPath) . ' -y -i ' . escapeshellarg($concatProto) . ' -c copy ' . escapeshellarg($mergedAbsPath) . ' 2>&1';
                        exec($cmd, $output, $ret);
                        if (!($ret === 0 && file_exists($mergedAbsPath) && filesize($mergedAbsPath) > 0)) {
                            // Re-encode to normalize
                            @unlink($mergedAbsPath);
                            $output = [];
                            $ret = 0;
                            $cmd = escapeshellcmd($ffmpegPath) . ' -y -f concat -safe 0 -i ' . escapeshellarg($concatListPath) . ' -fflags +genpts -c:v libvpx -b:v 2M -c:a libvorbis -b:a 128k ' . escapeshellarg($mergedAbsPath) . ' 2>&1';
                            exec($cmd, $output, $ret);
                            if (!($ret === 0 && file_exists($mergedAbsPath) && filesize($mergedAbsPath) > 0)) {
                                $mergedRelPath = null;
                            }
                        }
                    }
                }

                // Fallback to byte-concat if ffmpeg failed/unavailable
                if ($mergedRelPath === null) {
                    $ebmlHeader = "\x1A\x45\xDF\xA3";
                    $startIndex = 0;
                    foreach ($chunkFiles as $idx => $cf) {
                        $abs = $disk->path($cf);
                        $firstBytes = @file_get_contents($abs, false, null, 0, 32);
                        if ($firstBytes !== false && strpos($firstBytes, $ebmlHeader) !== false) {
                            $startIndex = $idx;
                            break;
                        }
                    }

                    @unlink($mergedAbsPath ?? $disk->path($baseDir . '/merged.webm'));
                    $targetPath = $mergedAbsPath ?? $disk->path($baseDir . '/merged.webm');
                    for ($i = $startIndex; $i < count($chunkFiles); $i++) {
                        $srcAbs = $disk->path($chunkFiles[$i]);
                        $data = @file_get_contents($srcAbs);
                        if ($data === false) {
                            continue;
                        }
                        file_put_contents($targetPath, $data, FILE_APPEND);
                    }

                    if (file_exists($targetPath) && filesize($targetPath) > 0) {
                        $mergedRelPath = str_replace($disk->path(''), '', $targetPath);
                    }
                }
            }
        } catch (\Throwable $t) {
            // Swallow errors and fallback to manifest only
            $mergedRelPath = null;
        }

        // Update manifest with merged video if available
        if ($mergedRelPath) {
            $manifest['merged_video'] = $mergedRelPath;
            $disk->put($manifestPath, json_encode($manifest));
        }

        return [
            'manifest' => $manifestPath,
            'merged_video' => $mergedRelPath,
            'total_size' => $totalSize,
            'chunk_count' => count($chunkFiles),
        ];
    }
}