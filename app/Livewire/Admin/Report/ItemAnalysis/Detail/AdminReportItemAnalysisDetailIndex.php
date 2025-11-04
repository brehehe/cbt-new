<?php

namespace App\Livewire\Admin\Report\ItemAnalysis\Detail;

use Livewire\Component;
use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableModule;
use App\Models\Timetable\TimetableQuestion;
use App\Models\Timetable\TimetableAnswer;
use App\Models\User\UserTimetable;
use App\Models\User\UserModuleQuestion;
use Illuminate\Support\Facades\DB;

class AdminReportItemAnalysisDetailIndex extends Component
{
    protected $paginationTheme = 'bootstrap';

    public $timetableId;
    public $timetable;
    public $timetableModule;
    public $timetableQuestions;
    public $userTimetables;
    public $itemAnalysisData = [];
    public $perPage = 10;
    public $search = '';

    public function mount($id)
    {
        $this->timetableId = $id;
        $this->loadTimetableData();
        $this->calculateItemAnalysis();
    }

    public function loadTimetableData()
    {
        $this->timetable = Timetable::with(['module', 'timetableModule'])->findOrFail($this->timetableId);
        $this->timetableModule = $this->timetable->timetableModule;
        $this->timetableQuestions = $this->timetableModule->questions()->with('answers')->get();
        $this->userTimetables = UserTimetable::where('timetable_id', $this->timetableId)
            ->where('status', 'done')
            ->with(['user', 'userModuleQuestions'])
            ->get();
    }

    public function calculateItemAnalysis()
    {
        foreach ($this->timetableQuestions as $question) {
            $this->itemAnalysisData[$question->id] = $this->analyzeItem($question);
        }
    }

    public function analyzeItem($question)
    {
        // Get all user responses for this question
        $userResponses = UserModuleQuestion::where('timetable_question_id', $question->id)
            ->whereHas('userTimetable', function ($query) {
                $query->where('timetable_id', $this->timetableId)
                    ->where('status', 'done');
            })
            ->with(['userTimetable.user', 'timetableAnswer'])
            ->get();

        $totalParticipants = $userResponses->count();

        if ($totalParticipants == 0) {
            return $this->getEmptyAnalysis();
        }

        // Calculate basic statistics
        $correctAnswers = $userResponses->where('status', 'correct')->count();
        $incorrectAnswers = $totalParticipants - $correctAnswers;

        // Difficulty Index (P) = Jumlah yang benar / Total peserta
        $difficultyIndex = $totalParticipants > 0 ? $correctAnswers / $totalParticipants : 0;

        // Get scores for discrimination analysis
        $userScores = [];
        foreach ($this->userTimetables as $userTimetable) {
            $totalCorrect = $userTimetable->userModuleQuestions()->where('status', 'correct')->count();
            $userScores[$userTimetable->id] = [
                'score' => $totalCorrect,
                'user_id' => $userTimetable->user_id,
                'user_name' => $userTimetable->user->name
            ];
        }

        // Sort by total score (descending)
        uasort($userScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Calculate discrimination index using upper and lower 27% groups
        $discriminationData = $this->calculateDiscrimination($question, $userScores);

        // Analyze answer options
        $optionAnalysis = $this->analyzeAnswerOptions($question, $userResponses);

        return [
            'question' => $question,
            'total_participants' => $totalParticipants,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'difficulty_index' => round($difficultyIndex, 3),
            'difficulty_level' => $this->getDifficultyLevel($difficultyIndex),
            'discrimination_index' => $discriminationData['discrimination_index'],
            'discrimination_level' => $discriminationData['discrimination_level'],
            'upper_group_correct' => $discriminationData['upper_group_correct'],
            'lower_group_correct' => $discriminationData['lower_group_correct'],
            'upper_group_total' => $discriminationData['upper_group_total'],
            'lower_group_total' => $discriminationData['lower_group_total'],
            'option_analysis' => $optionAnalysis,
            'reliability_contribution' => $this->calculateReliabilityContribution($difficultyIndex, $discriminationData['discrimination_index'])
        ];
    }

    public function calculateDiscrimination($question, $userScores)
    {
        $totalUsers = count($userScores);

        if ($totalUsers < 10) {
            return [
                'discrimination_index' => 0,
                'discrimination_level' => 'Tidak dapat dihitung (peserta < 10)',
                'upper_group_correct' => 0,
                'lower_group_correct' => 0,
                'upper_group_total' => 0,
                'lower_group_total' => 0
            ];
        }

        // Take 27% from top and bottom (minimum 3 users each)
        $groupSize = max(3, floor($totalUsers * 0.27));

        $upperGroup = array_slice($userScores, 0, $groupSize, true);
        $lowerGroup = array_slice($userScores, -$groupSize, $groupSize, true);

        $upperGroupIds = array_keys($upperGroup);
        $lowerGroupIds = array_keys($lowerGroup);

        // Count correct answers in each group
        $upperCorrect = UserModuleQuestion::where('timetable_question_id', $question->id)
            ->whereIn('user_timetable_id', $upperGroupIds)
            ->where('status', 'correct')
            ->count();

        $lowerCorrect = UserModuleQuestion::where('timetable_question_id', $question->id)
            ->whereIn('user_timetable_id', $lowerGroupIds)
            ->where('status', 'correct')
            ->count();

        // Discrimination Index = (Upper Correct / Upper Total) - (Lower Correct / Lower Total)
        $discriminationIndex = ($upperCorrect / $groupSize) - ($lowerCorrect / $groupSize);

        return [
            'discrimination_index' => round($discriminationIndex, 3),
            'discrimination_level' => $this->getDiscriminationLevel($discriminationIndex),
            'upper_group_correct' => $upperCorrect,
            'lower_group_correct' => $lowerCorrect,
            'upper_group_total' => $groupSize,
            'lower_group_total' => $groupSize
        ];
    }

    public function analyzeAnswerOptions($question, $userResponses)
    {
        $options = $question->answers;
        $optionAnalysis = [];

        foreach ($options as $option) {
            $selectedCount = $userResponses->where('timetable_answer_id', $option->id)->count();
            $percentage = $userResponses->count() > 0 ? ($selectedCount / $userResponses->count()) * 100 : 0;

            $optionAnalysis[] = [
                'option' => $option,
                'selected_count' => $selectedCount,
                'percentage' => round($percentage, 1),
                'is_correct' => $option->is_correct
            ];
        }

        // Count unanswered
        $unanswered = $userResponses->whereNull('timetable_answer_id')->count();
        if ($unanswered > 0) {
            $percentage = ($unanswered / $userResponses->count()) * 100;
            $optionAnalysis[] = [
                'option' => (object)['alphabet' => 'X', 'context' => 'Tidak dijawab'],
                'selected_count' => $unanswered,
                'percentage' => round($percentage, 1),
                'is_correct' => false
            ];
        }

        return $optionAnalysis;
    }

    public function getDifficultyLevel($index)
    {
        if ($index >= 0.7) return 'Mudah';
        if ($index >= 0.3) return 'Sedang';
        return 'Sukar';
    }

    public function getDiscriminationLevel($index)
    {
        if ($index >= 0.4) return 'Sangat Baik';
        if ($index >= 0.3) return 'Baik';
        if ($index >= 0.2) return 'Cukup';
        if ($index >= 0.1) return 'Buruk';
        return 'Sangat Buruk';
    }

    public function calculateReliabilityContribution($difficulty, $discrimination)
    {
        // Kontribusi terhadap reliabilitas = P × Q × D
        // P = difficulty index, Q = 1-P, D = discrimination index
        $q = 1 - $difficulty;
        return round($difficulty * $q * $discrimination, 4);
    }

    public function getEmptyAnalysis()
    {
        return [
            'total_participants' => 0,
            'correct_answers' => 0,
            'incorrect_answers' => 0,
            'difficulty_index' => 0,
            'difficulty_level' => 'Tidak ada data',
            'discrimination_index' => 0,
            'discrimination_level' => 'Tidak ada data',
            'upper_group_correct' => 0,
            'lower_group_correct' => 0,
            'upper_group_total' => 0,
            'lower_group_total' => 0,
            'option_analysis' => [],
            'reliability_contribution' => 0
        ];
    }

    public function render()
    {
        return \view('livewire.admin.report.item-analysis.detail.admin-report-item-analysis-detail-index', [
            'itemAnalysisData' => $this->itemAnalysisData
        ])->extends('layout.app')->section('content');
    }
}
