<?php

namespace App\Services\Exam;

use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamRecording;
use Exception;
use Log;
use Storage;

/**
 * Class ExamMonitoringService.
 */
class ExamMonitoringService
{
    public function startRecording($userTimetableId, $userId)
    {
        try {
            $recording = ExamRecording::create([
                'user_timetable_id' => $userTimetableId,
                'user_id' => $userId,
                'video_path' => $this->generateVideoPath($userTimetableId, $userId),
                'chunk_number' => '1',
                'status' => 'recording',
                'started_at' => now(),
                'metadata' => [
                    'browser' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                ]
            ]);

            return $recording;
        } catch (Exception $e) {
            Log::error('Failed to start recording: ' . $e->getMessage());
            throw $e;
        }
    }

    public function saveVideoChunk($userTimetableId, $userId, $videoData, $chunkNumber)
    {
        try {
            $videoPath = $this->generateVideoPath($userTimetableId, $userId, $chunkNumber);

            // Decode base64 video data
            $videoContent = base64_decode(preg_replace('#^data:video/\w+;base64,#i', '', $videoData));

            // Save to storage
            Storage::put($videoPath, $videoContent);

            // Update or create recording entry
            $recording = ExamRecording::updateOrCreate(
                [
                    'user_timetable_id' => $userTimetableId,
                    'user_id' => $userId,
                    'chunk_number' => $chunkNumber,
                ],
                [
                    'video_path' => $videoPath,
                    'file_size' => strlen($videoContent),
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );

            return $recording;
        } catch (Exception $e) {
            Log::error('Failed to save video chunk: ' . $e->getMessage());
            throw $e;
        }
    }

    public function logAlert($userTimetableId, $userId, $alertType, $description, $metadata = [])
    {
        try {
            $alert = ExamAlert::create([
                'user_timetable_id' => $userTimetableId,
                'user_id' => $userId,
                'alert_type' => $alertType,
                'description' => $description,
                'metadata' => array_merge($metadata, [
                    'timestamp' => now()->toISOString(),
                    'browser' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                ]),
                'detected_at' => now(),
            ]);

            return $alert;
        } catch (Exception $e) {
            Log::error('Failed to log alert: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateVideoPath($userTimetableId, $userId, $chunkNumber = null)
    {
        $basePath = "exam-recordings/{$userTimetableId}/{$userId}";

        if ($chunkNumber) {
            return "{$basePath}/chunk_{$chunkNumber}.webm";
        }

        return "{$basePath}/recording_" . now()->format('Y-m-d_H-i-s') . ".webm";
    }

    public function getRecordingsByTimetable($userTimetableId)
    {
        return ExamRecording::where('user_timetable_id', $userTimetableId)
            ->orderBy('chunk_number')
            ->get();
    }

    public function getAlertsByTimetable($userTimetableId)
    {
        return ExamAlert::where('user_timetable_id', $userTimetableId)
            ->orderBy('detected_at', 'desc')
            ->get();
    }
}
