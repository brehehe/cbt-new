<?php

namespace App\Services\Report;

use App\Models\Master\Question\Question;
use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemAnalysisService
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

    public $filters = [];

    public function mount($id, array $filters = [])
    {
        $this->timetableId = $id;
        $this->filters = $filters;
        $this->loadTimetableData();
        $this->calculateItemAnalysis();
    }

    protected function filterQuestionsQuery($query)
    {
        if (empty($this->filters)) {
            return $query;
        }

        $filters = $this->filters;

        $query->when(! empty($filters['filterTopicId']), fn ($q) => $q->where('timetable_questions.topic_id', $filters['filterTopicId']))
            ->when(! empty($filters['filterMaterialCategoryId']), fn ($q) => $q->where('timetable_questions.material_category_id', $filters['filterMaterialCategoryId']))
            ->when(! empty($filters['filterMaterialId']), fn ($q) => $q->where('timetable_questions.material_id', $filters['filterMaterialId']))
            ->when(! empty($filters['filterQuestionTypeId']), fn ($q) => $q->where('timetable_questions.question_type_id', $filters['filterQuestionTypeId']))
            ->when(! empty($filters['filterCategoryQuestionId']), fn ($q) => $q->where('timetable_questions.category_question_id', $filters['filterCategoryQuestionId']));

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query;
    }

    public function loadTimetableData()
    {
        $this->timetable = Timetable::query()->with('timetableModule')->findOrFail($this->timetableId);
        $this->timetableModule = $this->timetable->timetableModule;

        // Fetch questions without restricting to is_check
        $questions = collect();
        if ($this->timetableModule) {
            $questionsQuery = $this->timetableModule->questions()
                ->withoutGlobalScopes()
                ->with(['answers' => function ($q) {
                    $q->withoutGlobalScopes()->orderBy('order', 'asc');
                }]);
            $questions = $this->filterQuestionsQuery($questionsQuery)
                ->orderBy('order', 'asc')
                ->get();
        }

        // Fallback 1: via timetable_module_id = timetable_id
        if ($questions->isEmpty()) {
            $questionsQuery = TimetableQuestion::withoutGlobalScopes()
                ->with(['answers' => function ($q) {
                    $q->withoutGlobalScopes()->orderBy('order', 'asc');
                }])
                ->where(function ($q) {
                    $q->whereHas('timetableModule', function ($tm) {
                        $tm->where('timetable_id', $this->timetableId);
                    })
                        ->orWhere('timetable_module_id', $this->timetableId);
                });
            $questions = $this->filterQuestionsQuery($questionsQuery)
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
                $questionsQuery = TimetableQuestion::withoutGlobalScopes()
                    ->with(['answers' => function ($q) {
                        $q->withoutGlobalScopes()->orderBy('order', 'asc');
                    }])
                    ->whereIn('id', $questionIds);
                $questions = $this->filterQuestionsQuery($questionsQuery)
                    ->orderBy('order', 'asc')
                    ->get();
            }
        }

        $this->timetableQuestions = $questions;

    }

    public function calculateItemAnalysis()
    {
        if ($this->timetableQuestions === null) {
            $this->loadTimetableData();
        }

        // One response set supplies both total scores and per-question statistics.
        $responses = UserModuleQuestion::withoutGlobalScopes()
            ->whereHas('userTimetable', function ($query) {
                $query->withoutGlobalScopes()->where('timetable_id', $this->timetableId);
            })
            ->get(['user_timetable_id', 'timetable_question_id', 'timetable_answer_id', 'status']);
        $scores = $this->scoresFromResponses($responses);
        $byQuestion = $responses->groupBy('timetable_question_id');
        $this->itemAnalysisData = [];
        foreach ($this->timetableQuestions as $question) {
            $this->itemAnalysisData[$question->id] = $this->analyzeItem($question, $byQuestion->get($question->id, collect()), $scores);
        }
    }

    protected function scoresFromResponses($responses): array
    {
        return $responses->groupBy('user_timetable_id')
            ->map(fn ($answers) => ['score' => $answers->where('status', 'correct')->count()])
            ->sortByDesc('score')->all();
    }

    public function analyzeItem($question, $userResponses, $userScores)
    {
        $totalParticipants = $userResponses->count();

        if ($totalParticipants == 0) {
            return $this->getEmptyAnalysis($question);
        }

        // Calculate basic statistics
        $correctAnswers = $userResponses->where('status', 'correct')->count();
        $pendingAnswers = $userResponses->where('status', 'check')->count();
        $incorrectAnswers = $totalParticipants - $correctAnswers - $pendingAnswers;

        // Difficulty Index (P) = Jumlah yang benar / Total peserta
        $difficultyIndex = $totalParticipants > 0 ? $correctAnswers / $totalParticipants : 0;

        // Calculate discrimination index using upper and lower groups
        $discriminationData = $this->calculateDiscrimination($question, $userScores, $userResponses);

        // Analyze answer options
        $optionAnalysis = $this->analyzeAnswerOptions($question, $userResponses);

        return [
            'question' => $question,
            'total_participants' => $totalParticipants,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'pending_answers' => $pendingAnswers,
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

    public function calculateDiscrimination($question, $userScores, $userResponses)
    {
        $userScores = array_intersect_key($userScores, array_flip($userResponses->pluck('user_timetable_id')->all()));
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

        $upperResponses = $userResponses->whereIn('user_timetable_id', $upperGroupIds);
        $lowerResponses = $userResponses->whereIn('user_timetable_id', $lowerGroupIds);
        $upperCorrect = $upperResponses->where('status', 'correct')->count();
        $lowerCorrect = $lowerResponses->where('status', 'correct')->count();
        $upperTotal = $upperResponses->count();
        $lowerTotal = $lowerResponses->count();

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
            $selectedCount = $userResponses->where('status', '!=', 'unanswered')->where('timetable_answer_id', $option->id)->count();
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

        $percentage = $totalCount > 0 ? ($unanswered / $totalCount) * 100 : 0;
        $optionAnalysis[] = [
            'option' => (object) ['alphabet' => 'X', 'context' => 'Tidak dijawab'],
            'selected_count' => $unanswered,
            'percentage' => round($percentage, 1),
            'is_correct' => false,
        ];

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
            'pending_answers' => 0,
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

    public function aggregate(string $questionId): array
    {
        return $this->aggregateMany([$questionId])[$questionId];
    }

    /** Load a page or an export in a fixed number of queries, independent of question count. */
    public function aggregateMany(array $questionIds): array
    {
        $questionIds = array_values(array_unique($questionIds));
        if ($questionIds === []) {
            return [];
        }
        $questions = Question::withTrashed()->whereIn('id', $questionIds)->get()->keyBy('id');
        // Preserve authorization and missing-question behavior of the single-question lookup.
        foreach ($questionIds as $id) {
            if (! $questions->has($id)) {
                throw (new ModelNotFoundException)->setModel(Question::class, [$id]);
            }
        }
        $snapshots = TimetableQuestion::withoutGlobalScope('user_scope')
            ->whereIn('question_id', $questionIds)->with('answers')->get()->keyBy('id');
        $companyId = auth()->user()?->company_id;
        $responses = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->whereIn('timetable_question_id', $snapshots->keys())
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where(fn ($q) => $q->whereNull('is_show')->orWhere('is_show', true))
            ->get(['user_timetable_id', 'timetable_question_id', 'timetable_answer_id', 'status']);
        $scoreResponses = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->whereIn('user_timetable_id', $responses->pluck('user_timetable_id')->unique())
            ->get(['user_timetable_id', 'status']);
        $scores = $this->scoresFromResponses($scoreResponses);
        $byQuestion = $responses->groupBy(fn ($row) => $snapshots[$row->timetable_question_id]->question_id);
        $result = [];
        foreach ($questionIds as $id) {
            $result[$id] = $this->aggregateLoaded($questions[$id], $byQuestion->get($id, collect()), $snapshots, $scores);
        }

        return $result;
    }

    protected function aggregateLoaded($question, $responses, $snapshots, $scores): array
    {
        $result = $this->getEmptyAnalysis($question);
        $result['aggregate_note'] = 'Gabungan riwayat yang ditampilkan. Kelompok 27% dihitung per jadwal/versi soal. Peserta dihitung per pengerjaan; opsi yang berubah ditampilkan terpisah.';
        $options = [];
        foreach ($responses->groupBy('timetable_question_id') as $rows) {
            $snapshot = $snapshots->get($rows->first()->timetable_question_id);
            if (! $snapshot) {
                continue;
            }
            $part = $this->analyzeItem($snapshot, $rows, $scores);
            foreach (['total_participants', 'correct_answers', 'incorrect_answers', 'pending_answers', 'upper_group_correct', 'lower_group_correct', 'upper_group_total', 'lower_group_total'] as $key) {
                $result[$key] += $part[$key];
            }
            foreach ($part['option_analysis'] as $index => $option) {
                $label = $option['option']->alphabet ?? chr(65 + $index);
                $key = $label.'|'.$option['option']->context.'|'.(int) $option['is_correct'];
                if (! isset($options[$key])) {
                    $options[$key] = $option;
                    $options[$key]['option'] = (object) ['alphabet' => $label, 'context' => $option['option']->context];
                    $options[$key]['selected_count'] = 0;
                }
                $options[$key]['selected_count'] += $option['selected_count'];
            }
        }
        $total = $result['total_participants'];
        $p = $total ? $result['correct_answers'] / $total : 0;
        $d = $result['upper_group_total'] && $result['lower_group_total']
            ? $result['upper_group_correct'] / $result['upper_group_total'] - $result['lower_group_correct'] / $result['lower_group_total'] : 0;
        $result['difficulty_index'] = round($p, 3);
        $result['difficulty_level'] = $total ? $this->getDifficultyLevel($p) : 'Tidak ada data';
        $result['discrimination_index'] = round($d, 3);
        $result['discrimination_level'] = $result['upper_group_total'] ? $this->getDiscriminationLevel($d) : 'Tidak dapat dihitung';
        $result['reliability_contribution'] = $this->calculateReliabilityContribution($p, $d);
        foreach ($options as &$option) {
            $option['percentage'] = $total ? round(100 * $option['selected_count'] / $total, 1) : 0;
        }
        $result['option_analysis'] = array_values($options);

        return $result;
    }
}
