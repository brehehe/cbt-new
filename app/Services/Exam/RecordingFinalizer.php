<?php

namespace App\Services\Exam;

use App\Models\Exam\ExamRecording;
use Illuminate\Support\Facades\Storage;

class RecordingFinalizer
{
    /**
     * Finalize ALL recording chunks for a specific UserTimetable (across multiple sessions).
     * Returns an array with manifest path, merged video path (if any), total size and chunk count.
     */
    public static function finalizeFullExamRecording(string $userTimetableId): array
    {
        $baseDir = 'exam_recordings/chunks/'.$userTimetableId;
        $disk = Storage::disk('public');

        // Fetch all related recording sessions
        $recordings = ExamRecording::where('user_timetable_id', $userTimetableId)
            ->orderBy('start_time', 'asc')
            ->get();

        $recordingIds = $recordings->pluck('id')->toArray();
        $manifestPath = $baseDir.'/full_manifest.json';
        $mergedRelPath = null;
        $totalSize = 0;
        $chunkFiles = [];

        if ($disk->exists($baseDir) && count($recordingIds) > 0) {
            $files = $disk->files($baseDir);
            $chunkFiles = array_values(array_filter($files, function ($f) use ($recordingIds) {
                // Check if file starts with any of our session IDs
                foreach ($recordingIds as $rid) {
                    if (str_contains($f, $rid.'_chunk_')) {
                        return true;
                    }
                }

                return false;
            }));

            // Attempt to merge chunks using ffmpeg
            try {
                // Sort by recording session time, then by chunk number
                usort($chunkFiles, function ($a, $b) use ($recordingIds) {
                    $ridA = explode('_chunk_', basename($a))[0];
                    $ridB = explode('_chunk_', basename($b))[0];

                    if ($ridA !== $ridB) {
                        $orderA = array_search($ridA, $recordingIds);
                        $orderB = array_search($ridB, $recordingIds);

                        return $orderA <=> $orderB;
                    }

                    $na = (int) preg_replace('/.*_chunk_(\d+)\.webm$/', '$1', $a);
                    $nb = (int) preg_replace('/.*_chunk_(\d+)\.webm$/', '$1', $b);

                    return $na <=> $nb;
                });

                foreach ($chunkFiles as $f) {
                    $totalSize += $disk->size($f);
                }

                if (count($chunkFiles) > 0) {
                    $mergedRelPath = $baseDir.'/full_exam_recording.webm';
                    $mergedAbsPath = $disk->path($mergedRelPath);
                    @unlink($mergedAbsPath);

                    if (count($chunkFiles) === 1) {
                        // Only 1 chunk, no need to merge, just copy it
                        copy($disk->path($chunkFiles[0]), $mergedAbsPath);
                    } else {
                        $ffmpegPath = trim((string) shell_exec('which ffmpeg'));
                        if (! empty($ffmpegPath)) {
                            $concatListPath = $disk->path($baseDir.'/concat.txt');
                            $listLines = [];
                            foreach ($chunkFiles as $cf) {
                                $listLines[] = "file '".$disk->path($cf)."'";
                            }
                            file_put_contents($concatListPath, implode(PHP_EOL, $listLines));

                            $cmd = escapeshellcmd($ffmpegPath).' -y -f concat -safe 0 -i '.escapeshellarg($concatListPath).' -fflags +genpts -c:v libvpx-vp9 -speed 6 -crf 32 -b:v 0 -c:a libvorbis -b:a 128k '.escapeshellarg($mergedAbsPath).' 2>&1';
                            $output = [];
                            $ret = 0;
                            exec($cmd, $output, $ret);

                            if (! ($ret === 0 && file_exists($mergedAbsPath) && filesize($mergedAbsPath) > 0)) {
                                // Fallback raw concat if transcoding failed (rare)
                                @unlink($mergedAbsPath);
                                foreach ($chunkFiles as $cf) {
                                    file_put_contents($mergedAbsPath, file_get_contents($disk->path($cf)), FILE_APPEND);
                                }
                            }
                        } else {
                            // Fallback raw concat if ffmpeg missing
                            foreach ($chunkFiles as $cf) {
                                file_put_contents($mergedAbsPath, file_get_contents($disk->path($cf)), FILE_APPEND);
                            }
                        }
                    }

                    if (! file_exists($mergedAbsPath) || filesize($mergedAbsPath) == 0) {
                        $mergedRelPath = null;
                    }
                }
            } catch (\Throwable $t) {
                // Swallow errors and fallback to manifest only
                $mergedRelPath = null;
            }
        }

        // Update all related recording records for this timetable
        ExamRecording::where('user_timetable_id', $userTimetableId)->update([
            'video_path' => $mergedRelPath ?: $manifestPath,
            'file_size' => $totalSize,
            'status' => 'completed',
        ]);

        return [
            'manifest' => $manifestPath,
            'merged_video' => $mergedRelPath,
            'total_size' => $totalSize,
            'chunk_count' => count($chunkFiles),
        ];
    }
}
