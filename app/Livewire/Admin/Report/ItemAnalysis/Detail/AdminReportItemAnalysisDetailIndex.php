<?php

namespace App\Livewire\Admin\Report\ItemAnalysis\Detail;

use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

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
        $this->timetable = Timetable::withoutGlobalScopes()->with(['module', 'timetableModule'])->findOrFail($this->timetableId);
        $this->timetableModule = $this->timetable->timetableModule;

        // Fetch questions without restricting to is_check
        $questions = collect();
        if ($this->timetableModule) {
            $questions = $this->timetableModule->questions()
                ->withoutGlobalScopes()
                ->with(['answers' => function ($q) {
                    $q->withoutGlobalScopes()->orderBy('order', 'asc');
                }, 'user'])
                ->orderBy('order', 'asc')
                ->get();
        }

        // Fallback 1: via timetable_module_id = timetable_id
        if ($questions->isEmpty()) {
            $questions = \App\Models\Timetable\TimetableQuestion::withoutGlobalScopes()
                ->with(['answers' => function ($q) {
                    $q->withoutGlobalScopes()->orderBy('order', 'asc');
                }, 'user'])
                ->where(function ($q) {
                    $q->whereHas('timetableModule', function ($tm) {
                        $tm->where('timetable_id', $this->timetableId);
                    })
                    ->orWhere('timetable_module_id', $this->timetableId);
                })
                ->orderBy('order', 'asc')
                ->get();
        }

        // Fallback 2: via user_module_questions for this timetable
        if ($questions->isEmpty()) {
            $questionIds = UserModuleQuestion::withoutGlobalScopes()
                ->whereHas('userTimetable', function ($q) {
                    $q->withoutGlobalScopes()->where('timetable_id', $this->timetableId);
                })
                ->whereNotNull('timetable_question_id')
                ->pluck('timetable_question_id')
                ->unique()
                ->values();

            if ($questionIds->isNotEmpty()) {
                $questions = \App\Models\Timetable\TimetableQuestion::withoutGlobalScopes()
                    ->with(['answers' => function ($q) {
                        $q->withoutGlobalScopes()->orderBy('order', 'asc');
                    }, 'user'])
                    ->whereIn('id', $questionIds)
                    ->orderBy('order', 'asc')
                    ->get();
            }
        }

        $this->timetableQuestions = $questions;

        // Load all participants for this timetable regardless of strict done status
        $this->userTimetables = UserTimetable::withoutGlobalScopes()
            ->where('timetable_id', $this->timetableId)
            ->with(['user', 'userModuleQuestions'])
            ->get();
    }

    public function calculateItemAnalysis()
    {
        if (empty($this->timetableQuestions)) {
            $this->loadTimetableData();
        }

        // Pre-calculate user scores once
        $userScores = [];
        foreach ($this->userTimetables as $userTimetable) {
            $totalCorrect = $userTimetable->userModuleQuestions->where('status', 'correct')->count();
            $userScores[$userTimetable->id] = [
                'score' => $totalCorrect,
                'user_id' => $userTimetable->user_id,
                'user_name' => $userTimetable->user?->name ?? 'Peserta',
            ];
        }

        uasort($userScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Eager load all responses for this timetable once
        $allUserResponses = UserModuleQuestion::withoutGlobalScopes()
            ->whereHas('userTimetable', function ($query) {
                $query->withoutGlobalScopes()->where('timetable_id', $this->timetableId);
            })
            ->with(['userTimetable.user', 'timetableAnswer'])
            ->get()
            ->groupBy('timetable_question_id');

        $this->itemAnalysisData = [];
        foreach ($this->timetableQuestions as $question) {
            $responses = $allUserResponses->get($question->id, collect());
            $this->itemAnalysisData[$question->id] = $this->analyzeItem($question, $responses, $userScores);
        }
    }

    public function analyzeItem($question, $userResponses = null, $userScores = null)
    {
        if ($userResponses === null) {
            $userResponses = UserModuleQuestion::withoutGlobalScopes()
                ->where('timetable_question_id', $question->id)
                ->whereHas('userTimetable', function ($query) {
                    $query->withoutGlobalScopes()->where('timetable_id', $this->timetableId);
                })
                ->with(['userTimetable.user', 'timetableAnswer'])
                ->get();
        }

        $totalParticipants = $userResponses->count();

        if ($totalParticipants == 0) {
            return $this->getEmptyAnalysis($question);
        }

        // Calculate basic statistics
        $correctAnswers = $userResponses->where('status', 'correct')->count();
        $incorrectAnswers = $totalParticipants - $correctAnswers;

        // Difficulty Index (P) = Jumlah yang benar / Total peserta
        $difficultyIndex = $totalParticipants > 0 ? $correctAnswers / $totalParticipants : 0;

        // Build user scores if not passed
        if ($userScores === null) {
            $userScores = [];
            foreach ($this->userTimetables as $userTimetable) {
                $totalCorrect = $userTimetable->userModuleQuestions->where('status', 'correct')->count();
                $userScores[$userTimetable->id] = [
                    'score' => $totalCorrect,
                    'user_id' => $userTimetable->user_id,
                    'user_name' => $userTimetable->user?->name ?? 'Peserta',
                ];
            }
            uasort($userScores, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });
        }

        // Calculate discrimination index using upper and lower groups
        $discriminationData = $this->calculateDiscrimination($question, $userScores, $userResponses);

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
            'reliability_contribution' => $this->calculateReliabilityContribution($difficultyIndex, $discriminationData['discrimination_index']),
        ];
    }

    public function calculateDiscrimination($question, $userScores, $userResponses = null)
    {
        $totalUsers = count($userScores);

        // Take 27% from top and bottom (minimum 1 user each)
        $groupSize = max(1, (int) ceil($totalUsers * 0.27));

        // Avoid overlapping groups on very small samples
        if ($groupSize * 2 > $totalUsers) {
            $groupSize = (int) floor($totalUsers / 2);
        }

        if ($groupSize < 1) {
            return [
                'discrimination_index' => 0,
                'discrimination_level' => 'Tidak dapat dihitung (peserta < 2)',
                'upper_group_correct' => 0,
                'lower_group_correct' => 0,
                'upper_group_total' => 0,
                'lower_group_total' => 0,
            ];
        }

        $upperGroup = array_slice($userScores, 0, $groupSize, true);
        $lowerGroup = array_slice($userScores, -$groupSize, $groupSize, true);

        $upperGroupIds = array_keys($upperGroup);
        $lowerGroupIds = array_keys($lowerGroup);

        if ($userResponses !== null) {
            $upperResponses = $userResponses->whereIn('user_timetable_id', $upperGroupIds);
            $lowerResponses = $userResponses->whereIn('user_timetable_id', $lowerGroupIds);

            $upperCorrect = $upperResponses->where('status', 'correct')->count();
            $lowerCorrect = $lowerResponses->where('status', 'correct')->count();

            $upperTotal = $upperResponses->count();
            $lowerTotal = $lowerResponses->count();
        } else {
            $upperCorrect = UserModuleQuestion::withoutGlobalScope('user_scope')
                ->where('timetable_question_id', $question->id)
                ->whereIn('user_timetable_id', $upperGroupIds)
                ->where('status', 'correct')
                ->count();

            $lowerCorrect = UserModuleQuestion::withoutGlobalScope('user_scope')
                ->where('timetable_question_id', $question->id)
                ->whereIn('user_timetable_id', $lowerGroupIds)
                ->where('status', 'correct')
                ->count();

            $upperTotal = UserModuleQuestion::withoutGlobalScope('user_scope')
                ->where('timetable_question_id', $question->id)
                ->whereIn('user_timetable_id', $upperGroupIds)
                ->count();

            $lowerTotal = UserModuleQuestion::withoutGlobalScope('user_scope')
                ->where('timetable_question_id', $question->id)
                ->whereIn('user_timetable_id', $lowerGroupIds)
                ->count();
        }

        $discriminationIndex = 0;
        if ($upperTotal > 0 && $lowerTotal > 0) {
            $discriminationIndex = ($upperCorrect / $upperTotal) - ($lowerCorrect / $lowerTotal);
        }

        return [
            'discrimination_index' => round($discriminationIndex, 3),
            'discrimination_level' => $this->getDiscriminationLevel($discriminationIndex),
            'upper_group_correct' => $upperCorrect,
            'lower_group_correct' => $lowerCorrect,
            'upper_group_total' => $upperTotal,
            'lower_group_total' => $lowerTotal,
        ];
    }

    public function analyzeAnswerOptions($question, $userResponses)
    {
        if ($question->type === 'essay') {
            return [];
        }
        $options = $question->answers;
        $optionAnalysis = [];
        $totalCount = $userResponses->count();

        foreach ($options as $option) {
            $selectedCount = $userResponses->where('timetable_answer_id', $option->id)->count();
            $percentage = $totalCount > 0 ? ($selectedCount / $totalCount) * 100 : 0;

            $optionAnalysis[] = [
                'option' => $option,
                'selected_count' => $selectedCount,
                'percentage' => round($percentage, 1),
                'is_correct' => (bool) $option->is_correct,
            ];
        }

        // Count unanswered (no timetable_answer_id or status is unanswered)
        $unanswered = $userResponses->filter(function ($r) {
            return empty($r->timetable_answer_id) || $r->status === 'unanswered';
        })->count();

        if ($unanswered > 0) {
            $percentage = $totalCount > 0 ? ($unanswered / $totalCount) * 100 : 0;
            $optionAnalysis[] = [
                'option' => (object) ['alphabet' => 'X', 'context' => 'Tidak dijawab'],
                'selected_count' => $unanswered,
                'percentage' => round($percentage, 1),
                'is_correct' => false,
            ];
        }

        return $optionAnalysis;
    }

    public function getDifficultyLevel($index)
    {
        if ($index >= 0.7) {
            return 'Mudah';
        }
        if ($index >= 0.3) {
            return 'Sedang';
        }

        return 'Sukar';
    }

    public function getDiscriminationLevel($index)
    {
        if ($index >= 0.4) {
            return 'Sangat Baik';
        }
        if ($index >= 0.3) {
            return 'Baik';
        }
        if ($index >= 0.2) {
            return 'Cukup';
        }
        if ($index >= 0.1) {
            return 'Buruk';
        }

        return 'Sangat Buruk';
    }

    public function calculateReliabilityContribution($difficulty, $discrimination)
    {
        // Kontribusi terhadap reliabilitas = P × Q × D
        // P = difficulty index, Q = 1-P, D = discrimination index
        $q = 1 - $difficulty;

        return round($difficulty * $q * $discrimination, 4);
    }

    public function getEmptyAnalysis($question)
    {
        return [
            'question' => $question,
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
            'reliability_contribution' => 0,
        ];
    }

    public function render()
    {
        return \view('livewire.admin.report.item-analysis.detail.admin-report-item-analysis-detail-index', [
            'itemAnalysisData' => $this->itemAnalysisData,
        ])->extends('layout.app')->section('content');
    }

    public function exportPdf()
    {
        if (empty($this->timetableQuestions) || empty($this->itemAnalysisData)) {
            $this->loadTimetableData();
            $this->calculateItemAnalysis();
        }

        $pdf = Pdf::loadView('livewire.admin.report.item-analysis.detail.admin-report-item-analysis-detail-pdf', [
            'timetable' => $this->timetable,
            'timetableModule' => $this->timetableModule,
            'timetableQuestions' => $this->timetableQuestions,
            'userTimetables' => $this->userTimetables,
            'itemAnalysisData' => $this->itemAnalysisData,
        ])->setPaper('a4', 'landscape');

        $fileName = 'analisis-butir-soal-'.($this->timetableId ?? 'timetable').'.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
}
