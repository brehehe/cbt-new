<?php

namespace App\Services\Report;

use App\Models\Master\Question\Question;
use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

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

        $questionCols = [
            'timetable_questions.id',
            'timetable_questions.question_id',
            'timetable_questions.timetable_module_id',
            'timetable_questions.type',
            'timetable_questions.order',
            'timetable_questions.question',
            'timetable_questions.description',
            'timetable_questions.topic_id',
            'timetable_questions.material_category_id',
            'timetable_questions.material_id',
            'timetable_questions.question_type_id',
            'timetable_questions.category_question_id',
        ];

        // Fetch questions without restricting to is_check
        $questions = collect();
        if ($this->timetableModule) {
            $questionsQuery = $this->timetableModule->questions()
                ->withoutGlobalScopes()
                ->select($questionCols)
                ->with(['answers' => function ($q) {
                    $q->withoutGlobalScopes()
                        ->select(['id', 'timetable_question_id', 'alphabet', 'context', 'is_correct', 'order'])
                        ->orderBy('order', 'asc');
                }]);
            $questions = $this->filterQuestionsQuery($questionsQuery)
                ->orderBy('order', 'asc')
                ->get();
        }

        // Fallback 1: via timetable_module_id = timetable_id
        if ($questions->isEmpty()) {
            $questionsQuery = TimetableQuestion::withoutGlobalScopes()
                ->select($questionCols)
                ->with(['answers' => function ($q) {
                    $q->withoutGlobalScopes()
                        ->select(['id', 'timetable_question_id', 'alphabet', 'context', 'is_correct', 'order'])
                        ->orderBy('order', 'asc');
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
                    ->select($questionCols)
                    ->with(['answers' => function ($q) {
                        $q->withoutGlobalScopes()
                            ->select(['id', 'timetable_question_id', 'alphabet', 'context', 'is_correct', 'order'])
                            ->orderBy('order', 'asc');
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

        if ($this->timetableQuestions->isEmpty()) {
            $this->itemAnalysisData = [];

            return;
        }

        // 1. Get all participant user_timetable_ids for this timetable
        $userTimetableIds = DB::table('user_timetables')
            ->where('timetable_id', $this->timetableId)
            ->whereNull('deleted_at')
            ->pluck('id')
            ->toArray();

        if (empty($userTimetableIds)) {
            $this->itemAnalysisData = [];
            foreach ($this->timetableQuestions as $question) {
                $this->itemAnalysisData[$question->id] = $this->getEmptyAnalysis($question);
            }

            return;
        }

        // 2. Fast score calculation in DB to rank participants for upper/lower 27% groups
        $userScoresRaw = DB::table('user_module_questions as umq')
            ->join('user_timetables as ut', 'ut.id', '=', 'umq.user_timetable_id')
            ->where('ut.timetable_id', $this->timetableId)
            ->where('umq.status', 'correct')
            ->whereNull('umq.deleted_at')
            ->whereNull('ut.deleted_at')
            ->groupBy('umq.user_timetable_id')
            ->select('umq.user_timetable_id', DB::raw('COUNT(*) as score'))
            ->pluck('score', 'umq.user_timetable_id')
            ->toArray();

        $scores = [];
        foreach ($userTimetableIds as $utId) {
            $scores[$utId] = ['score' => (int) ($userScoresRaw[$utId] ?? 0)];
        }
        uasort($scores, fn ($a, $b) => $b['score'] <=> $a['score']);

        $totalUsers = count($scores);
        $groupSize = max(1, (int) ceil($totalUsers * 0.27));
        if ($groupSize * 2 > $totalUsers) {
            $groupSize = (int) floor($totalUsers / 2);
        }

        $upperGroupIds = [];
        $lowerGroupIds = [];
        if ($groupSize >= 1) {
            $upperGroupIds = array_keys(array_slice($scores, 0, $groupSize, true));
            $lowerGroupIds = array_keys(array_slice($scores, -$groupSize, $groupSize, true));
        }

        // 3. Question-level participant, correct, and pending counts in a single query
        $questionIds = $this->timetableQuestions->pluck('id')->toArray();
        $qStats = DB::table('user_module_questions as umq')
            ->join('user_timetables as ut', 'ut.id', '=', 'umq.user_timetable_id')
            ->where('ut.timetable_id', $this->timetableId)
            ->whereIn('umq.timetable_question_id', $questionIds)
            ->whereNull('umq.deleted_at')
            ->whereNull('ut.deleted_at')
            ->groupBy('umq.timetable_question_id')
            ->select(
                'umq.timetable_question_id',
                DB::raw('COUNT(*) as total_participants'),
                DB::raw("COUNT(CASE WHEN umq.status = 'correct' THEN 1 END) as correct_answers"),
                DB::raw("COUNT(CASE WHEN umq.status = 'check' THEN 1 END) as pending_answers")
            )
            ->get()
            ->keyBy('timetable_question_id');

        // 4. Discrimination group statistics in fast queries
        $upperDiscrim = collect();
        $lowerDiscrim = collect();
        if (! empty($upperGroupIds) && ! empty($lowerGroupIds)) {
            $upperDiscrim = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $upperGroupIds)
                ->whereIn('timetable_question_id', $questionIds)
                ->whereNull('deleted_at')
                ->groupBy('timetable_question_id')
                ->select(
                    'timetable_question_id',
                    DB::raw('COUNT(*) as upper_total'),
                    DB::raw("COUNT(CASE WHEN status = 'correct' THEN 1 END) as upper_correct")
                )
                ->get()
                ->keyBy('timetable_question_id');

            $lowerDiscrim = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $lowerGroupIds)
                ->whereIn('timetable_question_id', $questionIds)
                ->whereNull('deleted_at')
                ->groupBy('timetable_question_id')
                ->select(
                    'timetable_question_id',
                    DB::raw('COUNT(*) as lower_total'),
                    DB::raw("COUNT(CASE WHEN status = 'correct' THEN 1 END) as lower_correct")
                )
                ->get()
                ->keyBy('timetable_question_id');
        }

        // 5. Option choice counts in a single grouped query
        $optionRows = DB::table('user_module_questions as umq')
            ->join('user_timetables as ut', 'ut.id', '=', 'umq.user_timetable_id')
            ->where('ut.timetable_id', $this->timetableId)
            ->whereIn('umq.timetable_question_id', $questionIds)
            ->whereNull('umq.deleted_at')
            ->whereNull('ut.deleted_at')
            ->whereNotNull('umq.timetable_answer_id')
            ->where(function ($q) {
                $q->whereNull('umq.status')->orWhere('umq.status', '!=', 'unanswered');
            })
            ->groupBy('umq.timetable_question_id', 'umq.timetable_answer_id')
            ->select('umq.timetable_question_id', 'umq.timetable_answer_id', DB::raw('COUNT(*) as selected_count'))
            ->get();

        $optionCounts = [];
        foreach ($optionRows as $r) {
            $optionCounts[$r->timetable_question_id][$r->timetable_answer_id] = (int) $r->selected_count;
        }

        // 6. Unanswered count per question in a single query
        $unansweredCounts = DB::table('user_module_questions as umq')
            ->join('user_timetables as ut', 'ut.id', '=', 'umq.user_timetable_id')
            ->where('ut.timetable_id', $this->timetableId)
            ->whereIn('umq.timetable_question_id', $questionIds)
            ->whereNull('umq.deleted_at')
            ->whereNull('ut.deleted_at')
            ->where(function ($q) {
                $q->whereNull('umq.timetable_answer_id')->orWhere('umq.status', 'unanswered');
            })
            ->groupBy('umq.timetable_question_id')
            ->select('umq.timetable_question_id', DB::raw('COUNT(*) as unanswered_count'))
            ->pluck('unanswered_count', 'umq.timetable_question_id')
            ->toArray();

        // 7. Assemble $this->itemAnalysisData without instantiating thousands of Eloquent models
        $this->itemAnalysisData = [];
        foreach ($this->timetableQuestions as $question) {
            $stat = $qStats->get($question->id);
            $totalParticipants = $stat ? (int) $stat->total_participants : 0;

            if ($totalParticipants == 0) {
                $this->itemAnalysisData[$question->id] = $this->getEmptyAnalysis($question);
                continue;
            }

            $correctAnswers = (int) ($stat->correct_answers ?? 0);
            $pendingAnswers = (int) ($stat->pending_answers ?? 0);
            $incorrectAnswers = $totalParticipants - $correctAnswers - $pendingAnswers;
            $difficultyIndex = $totalParticipants > 0 ? $correctAnswers / $totalParticipants : 0;

            // Discrimination calculation
            $uStat = $upperDiscrim->get($question->id);
            $lStat = $lowerDiscrim->get($question->id);
            $upperCorrect = $uStat ? (int) $uStat->upper_correct : 0;
            $lowerCorrect = $lStat ? (int) $lStat->lower_correct : 0;
            $upperTotal = $uStat ? (int) $uStat->upper_total : 0;
            $lowerTotal = $lStat ? (int) $lStat->lower_total : 0;

            $discriminationIndex = 0;
            if ($upperTotal > 0 && $lowerTotal > 0) {
                $discriminationIndex = ($upperCorrect / $upperTotal) - ($lowerCorrect / $lowerTotal);
            }

            $discriminationLevel = $groupSize < 1
                ? 'Tidak dapat dihitung (peserta < 2)'
                : $this->getDiscriminationLevel($discriminationIndex);

            // Option analysis
            $optionAnalysis = [];
            if ($question->type !== 'essay') {
                $options = $question->answers;
                $qOptionCounts = $optionCounts[$question->id] ?? [];

                foreach ($options as $option) {
                    $selectedCount = $qOptionCounts[$option->id] ?? 0;
                    $percentage = $totalParticipants > 0 ? ($selectedCount / $totalParticipants) * 100 : 0;

                    $optionAnalysis[] = [
                        'option' => $option,
                        'selected_count' => $selectedCount,
                        'percentage' => round($percentage, 1),
                        'is_correct' => (bool) $option->is_correct,
                    ];
                }

                $unanswered = (int) ($unansweredCounts[$question->id] ?? 0);
                $percentage = $totalParticipants > 0 ? ($unanswered / $totalParticipants) * 100 : 0;
                $optionAnalysis[] = [
                    'option' => (object) ['alphabet' => 'X', 'context' => 'Tidak dijawab'],
                    'selected_count' => $unanswered,
                    'percentage' => round($percentage, 1),
                    'is_correct' => false,
                ];
            }

            $this->itemAnalysisData[$question->id] = [
                'question' => $question,
                'total_participants' => $totalParticipants,
                'correct_answers' => $correctAnswers,
                'incorrect_answers' => $incorrectAnswers,
                'pending_answers' => $pendingAnswers,
                'difficulty_index' => round($difficultyIndex, 3),
                'difficulty_level' => $this->getDifficultyLevel($difficultyIndex),
                'discrimination_index' => round($discriminationIndex, 3),
                'discrimination_level' => $discriminationLevel,
                'upper_group_correct' => $upperCorrect,
                'lower_group_correct' => $lowerCorrect,
                'upper_group_total' => $upperTotal,
                'lower_group_total' => $lowerTotal,
                'option_analysis' => $optionAnalysis,
                'reliability_contribution' => $this->calculateReliabilityContribution($difficultyIndex, $discriminationIndex),
            ];
        }

        $sortBy = $this->filters['sortBy'] ?? 'order';
        $sortDir = strtolower($this->filters['sortDirection'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        uasort($this->itemAnalysisData, function ($a, $b) use ($sortBy, $sortDir) {
            if ($sortBy === 'difficulty') {
                $valA = $a['difficulty_index'] ?? 0;
                $valB = $b['difficulty_index'] ?? 0;
            } elseif ($sortBy === 'discrimination') {
                $valA = $a['discrimination_index'] ?? 0;
                $valB = $b['discrimination_index'] ?? 0;
            } elseif ($sortBy === 'participants') {
                $valA = $a['total_participants'] ?? 0;
                $valB = $b['total_participants'] ?? 0;
            } elseif ($sortBy === 'correct') {
                $valA = $a['correct_answers'] ?? 0;
                $valB = $b['correct_answers'] ?? 0;
            } else {
                $valA = (int) ($a['question']->order ?? 0);
                $valB = (int) ($b['question']->order ?? 0);
            }

            if ($valA == $valB) {
                return ((int) ($a['question']->order ?? 0)) <=> ((int) ($b['question']->order ?? 0));
            }

            return $sortDir === 'asc' ? ($valA <=> $valB) : ($valB <=> $valA);
        });
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
        $uniqueUserTimetableIds = $responses->pluck('user_timetable_id')->unique()->filter()->values()->all();
        $scores = [];
        if (! empty($uniqueUserTimetableIds)) {
            $scoresRaw = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $uniqueUserTimetableIds)
                ->where('status', 'correct')
                ->whereNull('deleted_at')
                ->groupBy('user_timetable_id')
                ->select('user_timetable_id', DB::raw('COUNT(*) as score'))
                ->pluck('score', 'user_timetable_id')
                ->toArray();

            foreach ($uniqueUserTimetableIds as $utId) {
                $scores[$utId] = ['score' => (int) ($scoresRaw[$utId] ?? 0)];
            }
            uasort($scores, fn ($a, $b) => $b['score'] <=> $a['score']);
        }
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
