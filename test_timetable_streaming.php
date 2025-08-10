<?php

require_once 'vendor/autoload.php';

use App\Models\Exam\ExamLiveSession;
use App\Models\Master\Timetable\Timetable;

echo "=== Testing Timetable Streaming Functionality ===\n\n";

// Test 1: Check if timetable data exists
echo "1. Checking available timetables:\n";
try {
    $timetables = Timetable::with('module')->take(5)->get();
    foreach ($timetables as $timetable) {
        echo "   - ID: {$timetable->id}, Name: " . ($timetable->name ?? 'No Name') . "\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check exam live sessions with timetable
echo "2. Checking live sessions with timetable:\n";
try {
    $sessions = ExamLiveSession::with(['user', 'timetable.module'])
        ->where('is_active', true)
        ->take(5)
        ->get();

    foreach ($sessions as $session) {
        echo "   - Session ID: {$session->id}, User: " . ($session->user->name ?? 'No User');
        echo ", Timetable: " . ($session->timetable_id ?? 'No Timetable');
        echo ", Camera: {$session->camera_status}\n";
    }

    if ($sessions->isEmpty()) {
        echo "   No active sessions found\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test timetable filtering
echo "3. Testing timetable filtering:\n";
try {
    $firstTimetable = Timetable::first();
    if ($firstTimetable) {
        echo "   Testing with timetable ID: {$firstTimetable->id}\n";

        $filteredSessions = ExamLiveSession::where('is_active', true)
            ->where('timetable_id', $firstTimetable->id)
            ->whereIn('camera_status', ['active', 'pending'])
            ->with(['user', 'timetable.module'])
            ->get();

        echo "   Found " . $filteredSessions->count() . " sessions for this timetable\n";
    } else {
        echo "   No timetables found to test with\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
