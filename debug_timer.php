<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\User\UserTimetable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

try {
    // Simulate user authentication for testing
    $user = User::first();
    if (!$user) {
        echo "No users found in database\n";
        exit;
    }

    echo "=== DEBUG CBT TIMER ISSUE ===\n";
    echo "User: {$user->name} (ID: {$user->id})\n\n";

    // Check user timetables
    $userTimetables = UserTimetable::where('user_id', $user->id)->get();
    echo "Total UserTimetables for user: {$userTimetables->count()}\n";

    foreach ($userTimetables as $ut) {
        echo "- ID: {$ut->id}, Status: {$ut->status}, Start: " . ($ut->start_exam ?? 'NULL') . "\n";
    }

    // Check active exam
    $activeExam = UserTimetable::where('user_id', $user->id)
        ->whereIn('status', ['exam', 'warning'])
        ->first();

    if ($activeExam) {
        echo "\n=== ACTIVE EXAM FOUND ===\n";
        echo "ID: {$activeExam->id}\n";
        echo "Status: {$activeExam->status}\n";
        echo "Start Exam: " . ($activeExam->start_exam ?? 'NULL') . "\n";
        echo "Timetable ID: " . ($activeExam->timetable_id ?? 'NULL') . "\n";

        // Check timetable relation
        if ($activeExam->timetable_id) {
            $timetable = DB::table('timetables')->where('id', $activeExam->timetable_id)->first();
            if ($timetable) {
                echo "Timetable found: {$timetable->name}\n";
                echo "Module ID: " . ($timetable->module_id ?? 'NULL') . "\n";

                // Check module
                if ($timetable->module_id) {
                    $module = DB::table('modules')->where('id', $timetable->module_id)->first();
                    if ($module) {
                        echo "Module found: {$module->name}\n";
                        echo "Duration: {$module->duration} minutes\n";

                        // Calculate remaining time
                        if ($activeExam->start_exam) {
                            $startTime = new DateTime($activeExam->start_exam);
                            $endTime = clone $startTime;
                            $endTime->modify("+{$module->duration} minutes");
                            $now = new DateTime();

                            $remaining = $endTime->getTimestamp() - $now->getTimestamp();

                            echo "Start Time: {$startTime->format('Y-m-d H:i:s')}\n";
                            echo "End Time: {$endTime->format('Y-m-d H:i:s')}\n";
                            echo "Current Time: {$now->format('Y-m-d H:i:s')}\n";
                            echo "Remaining Seconds: {$remaining}\n";

                            if ($remaining > 0) {
                                $hours = floor($remaining / 3600);
                                $minutes = floor(($remaining % 3600) / 60);
                                $seconds = $remaining % 60;
                                echo "Formatted Time: {$hours}:{$minutes}:{$seconds}\n";
                            } else {
                                echo "EXAM TIME EXPIRED!\n";
                            }
                        }
                    } else {
                        echo "Module NOT found!\n";
                    }
                } else {
                    echo "No module_id in timetable\n";
                }
            } else {
                echo "Timetable NOT found!\n";
            }
        } else {
            echo "No timetable_id in UserTimetable\n";
        }
    } else {
        echo "\n=== NO ACTIVE EXAM ===\n";
        echo "User has no exam with status 'exam' or 'warning'\n";

        // Check if we need to create test data
        echo "\nWould you like to create test exam data? (Checking existing data first)\n";

        $examCount = UserTimetable::where('user_id', $user->id)->count();
        if ($examCount === 0) {
            echo "No UserTimetable records exist for this user.\n";
            echo "This might be why the timer shows 00:00:00\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
