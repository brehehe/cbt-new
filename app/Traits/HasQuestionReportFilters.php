<?php

namespace App\Traits;

use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasQuestionReportFilters
{
    public $filterTopicId = '';

    public $filterMaterialCategoryId = '';

    public $filterMaterialId = '';

    public $filterQuestionTypeId = '';

    public $filterCategoryQuestionId = '';

    public function updatedFilterTopicId()
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedFilterMaterialCategoryId()
    {
        $this->filterMaterialId = '';
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedFilterMaterialId()
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedFilterQuestionTypeId()
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedFilterCategoryQuestionId()
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function resetQuestionFilters(): void
    {
        $this->filterTopicId = '';
        $this->filterMaterialCategoryId = '';
        $this->filterMaterialId = '';
        $this->filterQuestionTypeId = '';
        $this->filterCategoryQuestionId = '';
        if (property_exists($this, 'search')) {
            $this->search = '';
        }
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function hasActiveQuestionFilters(): bool
    {
        return ! empty($this->filterTopicId)
            || ! empty($this->filterMaterialCategoryId)
            || ! empty($this->filterMaterialId)
            || ! empty($this->filterQuestionTypeId)
            || ! empty($this->filterCategoryQuestionId)
            || (! empty($this->search));
    }

    public function getTopicsProperty()
    {
        $companyId = Auth::user()?->company_id;

        return Topic::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getMaterialCategoriesProperty()
    {
        $companyId = Auth::user()?->company_id;

        return MaterialCategory::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getMaterialsProperty()
    {
        $companyId = Auth::user()?->company_id;

        return Material::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->when($this->filterMaterialCategoryId, fn ($q) => $q->where('material_category_id', $this->filterMaterialCategoryId))
            ->orderBy('name')
            ->get(['id', 'name', 'material_category_id']);
    }

    public function getQuestionTypesProperty()
    {
        $companyId = Auth::user()?->company_id;

        return QuestionType::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getCategoryQuestionsProperty()
    {
        $companyId = Auth::user()?->company_id;

        return CategoryQuestion::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Apply the question filters and comprehensive search to a query builder.
     */
    public function applyQuestionFilters($query, string $tablePrefix = 'questions')
    {
        $query->when($this->filterTopicId, fn ($q) => $q->where("{$tablePrefix}.topic_id", $this->filterTopicId))
            ->when($this->filterMaterialCategoryId, fn ($q) => $q->where("{$tablePrefix}.material_category_id", $this->filterMaterialCategoryId))
            ->when($this->filterMaterialId, fn ($q) => $q->where("{$tablePrefix}.material_id", $this->filterMaterialId))
            ->when($this->filterQuestionTypeId, fn ($q) => $q->where("{$tablePrefix}.question_type_id", $this->filterQuestionTypeId))
            ->when($this->filterCategoryQuestionId, fn ($q) => $q->where("{$tablePrefix}.category_question_id", $this->filterCategoryQuestionId));

        if (! empty($this->search)) {
            $term = trim($this->search);
            $op = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $termLike = '%'.$term.'%';

            $query->where(function ($q) use ($termLike, $op, $tablePrefix) {
                $q->where("{$tablePrefix}.question", $op, $termLike)
                    ->orWhere("{$tablePrefix}.description", $op, $termLike);

                if ($tablePrefix === 'timetable_questions') {
                    $q->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                        $sub->select(DB::raw(1))
                            ->from('timetable_answers')
                            ->whereColumn('timetable_answers.timetable_question_id', "{$tablePrefix}.id")
                            ->where('timetable_answers.context', $op, $termLike);
                    });
                } else {
                    $q->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                        $sub->select(DB::raw(1))
                            ->from('answers')
                            ->whereColumn('answers.question_id', "{$tablePrefix}.id")
                            ->where('answers.context', $op, $termLike);
                    });
                }

                $q->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                    $sub->select(DB::raw(1))
                        ->from('topics')
                        ->whereColumn('topics.id', "{$tablePrefix}.topic_id")
                        ->where('topics.name', $op, $termLike);
                })
                    ->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                        $sub->select(DB::raw(1))
                            ->from('material_categories')
                            ->whereColumn('material_categories.id', "{$tablePrefix}.material_category_id")
                            ->where('material_categories.name', $op, $termLike);
                    })
                    ->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                        $sub->select(DB::raw(1))
                            ->from('materials')
                            ->whereColumn('materials.id', "{$tablePrefix}.material_id")
                            ->where('materials.name', $op, $termLike);
                    })
                    ->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                        $sub->select(DB::raw(1))
                            ->from('category_questions')
                            ->whereColumn('category_questions.id', "{$tablePrefix}.category_question_id")
                            ->where('category_questions.name', $op, $termLike);
                    })
                    ->orWhereExists(function ($sub) use ($termLike, $op, $tablePrefix) {
                        $sub->select(DB::raw(1))
                            ->from('question_types')
                            ->whereColumn('question_types.id', "{$tablePrefix}.question_type_id")
                            ->where('question_types.name', $op, $termLike);
                    });
            });
        }

        return $query;
    }
}
