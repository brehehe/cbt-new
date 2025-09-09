<?php

// Test script untuk validasi item analysis
// Run: php test_item_analysis.php

require_once 'vendor/autoload.php';

use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserTimetable;
use App\Models\User\UserModuleQuestion;

echo "=== Testing Item Analysis System ===\n\n";

// Test 1: Check available timetables with completed exams
echo "1. Checking available timetables with completed exams:\n";
try {
    $timetables = Timetable::with(['timetableModule', 'userTimetables' => function ($query) {
        $query->where('status', 'done');
    }])->get();

    foreach ($timetables as $timetable) {
        $completedCount = $timetable->userTimetables->count();
        if ($completedCount > 0) {
            echo "   - Timetable: {$timetable->name} ({$timetable->id})\n";
            echo "     Completed exams: {$completedCount}\n";
            echo "     Module: " . ($timetable->timetableModule->name ?? 'No module') . "\n";
            echo "     Questions: " . ($timetable->timetableModule->questions()->count() ?? 0) . "\n\n";
        }
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

// Test 2: Simulate item analysis calculation
echo "2. Testing item analysis calculation for first available timetable:\n";
try {
    $timetable = Timetable::whereHas('userTimetables', function ($query) {
        $query->where('status', 'done');
    })->first();

    if ($timetable) {
        echo "   Selected timetable: {$timetable->name}\n";

        $userTimetables = UserTimetable::where('timetable_id', $timetable->id)
            ->where('status', 'done')
            ->with(['user', 'userModuleQuestions'])
            ->get();

        echo "   Total participants: " . $userTimetables->count() . "\n";

        if ($timetable->timetableModule) {
            $questions = $timetable->timetableModule->questions()->with('answers')->get();
            echo "   Total questions: " . $questions->count() . "\n";

            // Test analysis for first question
            if ($questions->count() > 0) {
                $firstQuestion = $questions->first();
                echo "\n   Analyzing first question: " . substr($firstQuestion->question ?? 'No question text', 0, 50) . "...\n";

                $userResponses = UserModuleQuestion::where('timetable_question_id', $firstQuestion->id)
                    ->whereHas('userTimetable', function ($query) use ($timetable) {
                        $query->where('timetable_id', $timetable->id)
                            ->where('status', 'done');
                    })
                    ->with(['userTimetable.user', 'timetableAnswer'])
                    ->get();

                $totalParticipants = $userResponses->count();
                $correctAnswers = $userResponses->where('status', 'correct')->count();
                $difficultyIndex = $totalParticipants > 0 ? $correctAnswers / $totalParticipants : 0;

                echo "     Total responses: {$totalParticipants}\n";
                echo "     Correct answers: {$correctAnswers}\n";
                echo "     Difficulty index: " . round($difficultyIndex, 3) . "\n";
                echo "     Difficulty level: " . ($difficultyIndex >= 0.7 ? 'Easy' : ($difficultyIndex >= 0.3 ? 'Medium' : 'Hard')) . "\n";

                // Test discrimination calculation
                if ($totalParticipants >= 10) {
                    $userScores = [];
                    foreach ($userTimetables as $userTimetable) {
                        $totalCorrect = $userTimetable->userModuleQuestions()->where('status', 'correct')->count();
                        $userScores[$userTimetable->id] = $totalCorrect;
                    }

                    arsort($userScores);
                    $groupSize = max(3, floor(count($userScores) * 0.27));
                    $upperGroup = array_slice($userScores, 0, $groupSize, true);
                    $lowerGroup = array_slice($userScores, -$groupSize, $groupSize, true);

                    $upperCorrect = UserModuleQuestion::where('timetable_question_id', $firstQuestion->id)
                        ->whereIn('user_timetable_id', array_keys($upperGroup))
                        ->where('status', 'correct')
                        ->count();

                    $lowerCorrect = UserModuleQuestion::where('timetable_question_id', $firstQuestion->id)
                        ->whereIn('user_timetable_id', array_keys($lowerGroup))
                        ->where('status', 'correct')
                        ->count();

                    $discriminationIndex = ($upperCorrect / $groupSize) - ($lowerCorrect / $groupSize);

                    echo "     Upper group correct: {$upperCorrect}/{$groupSize}\n";
                    echo "     Lower group correct: {$lowerCorrect}/{$groupSize}\n";
                    echo "     Discrimination index: " . round($discriminationIndex, 3) . "\n";
                    echo "     Discrimination level: " . ($discriminationIndex >= 0.4 ? 'Excellent' : ($discriminationIndex >= 0.2 ? 'Good' : 'Poor')) . "\n";
                } else {
                    echo "     Not enough participants for discrimination analysis (need at least 10)\n";
                }
            }
        }
    } else {
        echo "   No timetable with completed exams found\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n3. Testing option analysis:\n";
try {
    if (isset($firstQuestion) && isset($userResponses)) {
        $options = $firstQuestion->answers;
        echo "   Question has " . $options->count() . " options:\n";

        foreach ($options as $index => $option) {
            $selectedCount = $userResponses->where('timetable_answer_id', $option->id)->count();
            $percentage = $userResponses->count() > 0 ? ($selectedCount / $userResponses->count()) * 100 : 0;
            $letter = chr(65 + $index); // A, B, C, D, etc.

            echo "     Option {$letter}: {$selectedCount} selected (" . round($percentage, 1) . "%) ";
            echo $option->is_correct ? "[CORRECT]" : "";
            echo "\n";
        }

        // Check unanswered
        $unanswered = $userResponses->whereNull('timetable_answer_id')->count();
        if ($unanswered > 0) {
            $percentage = ($unanswered / $userResponses->count()) * 100;
            echo "     Unanswered: {$unanswered} (" . round($percentage, 1) . "%)\n";
        }
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test completed ===\n";
