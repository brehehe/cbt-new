<?php

namespace App\Livewire\Admin\Master\Module;

use App\Helpers\AlertHelper;
use App\Jobs\Module\SaveManualModuleQuestionsJob;
use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
use App\Services\Module\ModuleQuestionService;
use App\Services\Module\ModuleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\LivewireFilepond\WithFilePond;
use Throwable;

class AdminMasterModuleQuestionIndex extends Component
{
    use WithFilePond, WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 8;

    public $search;

    public $perPageModule = 5;

    public $get_module;

    public $question_types;

    public $data_id;

    public $question_type_id;

    public $name;

    public $duration;

    public $description;

    public $random_question;

    public $question_pick_type = 'manual';

    public $is_all_questions = false;

    public $module_question_id;

    public $question_id = [];

    public $get_studys = [];

    public $studys = [];

    public $is_all_study = false;

    public $topics = [];

    public $filterStudyId;

    public $filterQuestionTypeId;

    public $filterTopicId;

    public $selected_all = [];

    public $openQuestion = false;

    public $category_questions = [];

    public $category_question_settings = [];

    public $category_question_limits = [];

    public $topic_question_settings = [];

    public $topic_question_limits = [];

    public $material_categories = [];

    public $material_category_question_settings = [];

    public $material_category_question_limits = [];

    public $searchCategory = '';

    public $searchTopic = '';

    public $searchMaterialCategory = '';

    public function render()
    {
        $questionPickType = $this->question_pick_type ?? 'manual';
        $module_questions = collect();

        if ($this->get_module) {
            $moduleQuestionsQuery = $this->get_module->moduleQuestions()
                ->with(['question.study', 'question.questionType'])
                ->select('id', 'module_id', 'question_id', 'study_id');

            if ($questionPickType === 'manual') {
                $moduleQuestionsQuery->where(function ($q) {
                    $q->whereNull('question_pick_type')
                        ->orWhere('question_pick_type', 'manual');
                });
            } else {
                $moduleQuestionsQuery->where('question_pick_type', $questionPickType);
            }

            if (! empty(trim($this->search))) {
                $moduleQuestionsQuery->whereHas('question', function ($q) {
                    $q->search($this->search);
                });
            }

            $module_questions = $moduleQuestionsQuery
                ->orderBy('id', 'desc')
                ->paginate($this->perPageModule, ['*'], 'module_questions_page');
        }

        $questions = [];
        if ($this->openQuestion) {
            $moduleId = $this->get_module?->id;
            $questionsQuery = Question::with(['topic', 'study', 'categoryQuestion', 'questionType'])
                ->select('id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'weight_correct', 'weight_incorrect', 'study_id', 'difficulty', 'category_question_id', 'type')
                ->whereIn('study_id', $this->get_studys ? array_keys($this->get_studys) : []);

            if ($moduleId) {
                $questionsQuery->whereNotIn('id', function ($query) use ($moduleId, $questionPickType) {
                    $query->select('question_id')
                        ->from('module_questions')
                        ->where('module_id', $moduleId)
                        ->whereNull('deleted_at');

                    $user = Auth::user();
                    if ($user && ! $user->hasRole('Anonymous') && optional($user->company)->id) {
                        $query->where('company_id', $user->company->id);
                    }

                    if ($questionPickType === 'manual') {
                        $query->where(function ($q) {
                            $q->whereNull('question_pick_type')
                                ->orWhere('question_pick_type', 'manual');
                        });
                    } else {
                        $query->where('question_pick_type', $questionPickType);
                    }
                });
            }

            if (! empty(trim($this->search))) {
                $questionsQuery->search($this->search);
            }

            if ($this->filterStudyId) {
                $questionsQuery->where('study_id', $this->filterStudyId);
            }

            if ($this->filterTopicId) {
                $questionsQuery->where('topic_id', $this->filterTopicId);
            }

            $questions = $questionsQuery->orderBy('id', 'desc')->paginate($this->perPage);
        }

        $filteredCategoryQuestions = CategoryQuestion::select('id', 'name')
            ->when($this->searchCategory, function ($query) {
                $query->where('name', 'ILIKE', '%' . $this->searchCategory . '%');
            })
            ->get();

        $filteredTopics = Topic::select('id', 'name')
            ->when($this->searchTopic, function ($query) {
                $query->where('name', 'ILIKE', '%' . $this->searchTopic . '%');
            })
            ->get();

        $filteredMaterialCategories = \App\Models\Master\Question\MaterialCategory::select('id', 'name')
            ->when($this->searchMaterialCategory, function ($query) {
                $query->where('name', 'ILIKE', '%' . $this->searchMaterialCategory . '%');
            })
            ->get();

        return view('livewire.admin.master.module.admin-master-module-question-index', [
            'module_questions' => $module_questions,
            'questions' => $questions,
            'category_questions' => $filteredCategoryQuestions,
            'topics' => $filteredTopics,
            'material_categories' => $filteredMaterialCategories,
        ])->extends('layout.app')->section('content');
    }

    public function mount($id)
    {
        $this->get_module = Module::findOrFail($id);
        $this->data_id = $this->get_module?->id;
        $this->question_type_id = $this->get_module?->question_type_id;
        $this->name = $this->get_module?->name;
        $this->duration = $this->get_module?->duration;
        $this->description = $this->get_module?->description;
        $this->random_question = $this->get_module?->random_question;
        $this->question_pick_type = $this->get_module?->question_pick_type ?? 'manual';
        $this->is_all_study = $this->get_module?->is_all_study;
        $this->is_all_questions = $this->get_module?->is_all_questions ?? false;

        $this->studys = json_decode($this->get_module?->studys) ?? [];
        $this->category_questions = CategoryQuestion::select('id', 'name')->get();
        $this->topics = Topic::select('id', 'name')->get();
        $this->initializeCategoryQuestionSettings();
        $this->loadCategoryQuestionLimits();
        $this->applyCategoryQuestionSettings($this->get_module?->category_question_settings ?? []);
        $this->initializeTopicQuestionSettings();
        $this->loadTopicQuestionLimits();
        $this->applyTopicQuestionSettings($this->get_module?->topic_question_settings ?? []);
        $this->material_categories = \App\Models\Master\Question\MaterialCategory::select('id', 'name')->get();
        $this->initializeMaterialCategoryQuestionSettings();
        $this->loadMaterialCategoryQuestionLimits();
        $this->applyMaterialCategoryQuestionSettings($this->get_module?->material_category_question_settings ?? []);

        if (Auth::user()?->hasRole('Dosen')) {
            $studyIds = Auth::user()?->studys ?? [];

            // Ensure $studyIds is always an array
            if (is_string($studyIds)) {
                $studyIds = json_decode($studyIds, true) ?? [];
            }

            // Ensure it's an array and not null
            $studyIds = is_array($studyIds) ? $studyIds : [];

            $this->get_studys = Study::whereIn('id', $studyIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->get_studys = Study::whereIn('id', $this->studys)->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }

        $this->question_types = QuestionType::select('id', 'name')->get();
    }

    public function updatedQuestionTypeId()
    {
        $this->loadCategoryQuestionLimits();
        $this->loadTopicQuestionLimits();
        $this->loadMaterialCategoryQuestionLimits();
    }

    public function updatedTopicQuestionSettings($value, $key)
    {
        if (! is_string($key)) {
            return;
        }

        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        [$topicId, $field] = $parts;
        if (! in_array($field, ['default', 'easy', 'medium', 'hard'], true)) {
            return;
        }

        $max = (int) ($this->topic_question_limits[$topicId][$field] ?? 0);
        $current = (int) ($this->topic_question_settings[$topicId][$field] ?? 0);
        if ($current > $max) {
            $this->topic_question_settings[$topicId][$field] = $max;
        }
        if ($current < 0) {
            $this->topic_question_settings[$topicId][$field] = 0;
        }
    }

    public function updatedMaterialCategoryQuestionSettings($value, $key)
    {
        if (! is_string($key)) {
            return;
        }

        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        [$materialCategoryId, $field] = $parts;
        if (! in_array($field, ['default', 'easy', 'medium', 'hard'], true)) {
            return;
        }

        $max = (int) ($this->material_category_question_limits[$materialCategoryId][$field] ?? 0);
        $current = (int) ($this->material_category_question_settings[$materialCategoryId][$field] ?? 0);
        if ($current > $max) {
            $this->material_category_question_settings[$materialCategoryId][$field] = $max;
        }
        if ($current < 0) {
            $this->material_category_question_settings[$materialCategoryId][$field] = 0;
        }
    }

    public function updatedCategoryQuestionSettings($value, $key)
    {
        if (! is_string($key)) {
            return;
        }

        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        [$categoryId, $field] = $parts;
        if (! in_array($field, ['default', 'easy', 'medium', 'hard'], true)) {
            return;
        }

        $max = (int) ($this->category_question_limits[$categoryId][$field] ?? 0);
        $current = (int) ($this->category_question_settings[$categoryId][$field] ?? 0);
        if ($current > $max) {
            $this->category_question_settings[$categoryId][$field] = $max;
        }
        if ($current < 0) {
            $this->category_question_settings[$categoryId][$field] = 0;
        }
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['module_question_id', 'question_id', 'selected_all', 'filterStudyId', 'filterQuestionTypeId', 'filterTopicId', 'search', 'material_category_question_settings', 'searchCategory', 'searchTopic', 'searchMaterialCategory']);
        $this->perPage = 8;
        $this->openQuestion = false;
        $this->initializeMaterialCategoryQuestionSettings();
        $this->dispatch('close-modal', ['id' => 'modal-module-question']);

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function choiceQuestion($question_id)
    {
        // Kalau id sudah ada → hapus (uncheck)
        if (isset($this->selected_all[$question_id]) && $this->selected_all[$question_id]) {
            unset($this->selected_all[$question_id]);
        }
        // Kalau id belum ada → tambahkan (check)
        else {
            $this->selected_all[$question_id] = true;
        }
    }

    public function submitModule()
    {
        $this->validate(
            [
                'question_type_id' => 'required|exists:question_types,id',
                'name' => 'required',
                'description' => 'nullable',
                'duration' => 'required|numeric|min:1',
                'studys' => 'required|array',
                'question_pick_type' => 'required|in:manual,category,topic,material_category',
                'category_question_settings.*.enabled' => 'nullable|boolean',
                'category_question_settings.*.default' => 'nullable|integer|min:0',
                'category_question_settings.*.easy' => 'nullable|integer|min:0',
                'category_question_settings.*.medium' => 'nullable|integer|min:0',
                'category_question_settings.*.hard' => 'nullable|integer|min:0',
                'topic_question_settings.*.enabled' => 'nullable|boolean',
                'topic_question_settings.*.default' => 'nullable|integer|min:0',
                'topic_question_settings.*.easy' => 'nullable|integer|min:0',
                'topic_question_settings.*.medium' => 'nullable|integer|min:0',
                'topic_question_settings.*.hard' => 'nullable|integer|min:0',
                'material_category_question_settings.*.enabled' => 'nullable|boolean',
                'material_category_question_settings.*.default' => 'nullable|integer|min:0',
                'material_category_question_settings.*.easy' => 'nullable|integer|min:0',
                'material_category_question_settings.*.medium' => 'nullable|integer|min:0',
                'material_category_question_settings.*.hard' => 'nullable|integer|min:0',
            ],
            [
                'question_type_id.required' => 'Tipe Ujian wajib diisi.',
                'question_type_id.exists' => 'Tipe Ujian tidak valid.',
                'name.required' => 'Nama modul wajib diisi.',
                'duration.required' => 'Durasi pengerjaan modul wajib diisi.',
                'duration.numeric' => 'Durasi pengerjaan modul hanya bernilai angka.',
                'duration.min' => 'Durasi pengerjaan modul minimal 1 menit.',
                'studys.required' => 'Prodi wajib dipilih.',
                'studys.array' => 'Prodi tidak valid.',
                'question_pick_type.required' => 'Tipe pengambilan soal wajib dipilih.',
                'question_pick_type.in' => 'Tipe pengambilan soal tidak valid.',
            ]
        );

        $this->resetErrorBag('category_question_settings');
        $this->resetErrorBag('topic_question_settings');
        $this->resetErrorBag('material_category_question_settings');

        if (! $this->is_all_questions) {
            foreach ($this->category_question_settings as $categoryId => $settings) {
                if (! ($settings['enabled'] ?? false)) {
                    continue;
                }
                $limits = $this->category_question_limits[$categoryId] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];

                foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                    $value = (int) ($settings[$difficulty] ?? 0);
                    $max = (int) ($limits[$difficulty] ?? 0);
                    if ($value > $max) {
                        $this->addError(
                            "category_question_settings.{$categoryId}.{$difficulty}",
                            "Jumlah soal {$difficulty} melebihi maksimal tersedia ({$max})."
                        );
                    }
                }
            }

            foreach ($this->topic_question_settings as $topicId => $settings) {
                if (! ($settings['enabled'] ?? false)) {
                    continue;
                }
                $limits = $this->topic_question_limits[$topicId] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];

                foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                    $value = (int) ($settings[$difficulty] ?? 0);
                    $max = (int) ($limits[$difficulty] ?? 0);
                    if ($value > $max) {
                        $this->addError(
                            "topic_question_settings.{$topicId}.{$difficulty}",
                            "Jumlah soal {$difficulty} melebihi maksimal tersedia ({$max})."
                        );
                    }
                }
            }

            foreach ($this->material_category_question_settings as $materialCategoryId => $settings) {
                if (! ($settings['enabled'] ?? false)) {
                    continue;
                }
                $limits = $this->material_category_question_limits[$materialCategoryId] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];

                foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                    $value = (int) ($settings[$difficulty] ?? 0);
                    $max = (int) ($limits[$difficulty] ?? 0);
                    if ($value > $max) {
                        $this->addError(
                            "material_category_question_settings.{$materialCategoryId}.{$difficulty}",
                            "Jumlah soal {$difficulty} melebihi maksimal tersedia ({$max})."
                        );
                    }
                }
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        try {
            DB::beginTransaction();
            $request = [
                'id' => $this->data_id,
                'user_id' => Auth::user()?->id,
                'company_id' => Auth::user()?->company?->id,
                'question_type_id' => $this->question_type_id,
                'name' => $this->name,
                'duration' => $this->duration,
                'random_question' => $this->random_question,
                'description' => $this->description,
                'studys' => $this->studys,
                'is_all_study' => $this->is_all_study,
                'is_all_questions' => $this->is_all_questions,
                'question_pick_type' => $this->question_pick_type,
                'category_question_settings' => $this->getFilteredCategoryQuestionSettings(),
                'topic_question_settings' => $this->getFilteredTopicQuestionSettings(),
                'material_category_question_settings' => $this->getFilteredMaterialCategoryQuestionSettings(),
            ];

            $module = app(ModuleService::class)->updateOrCreate($request);
            if (! $module) {
                throw new Exception('Ada kesalahaan saat ModuleService => updateOrCreate', 500);
            }

            if ($this->is_all_questions) {
                ModuleQuestion::withoutGlobalScope('user_scope')
                    ->where('module_id', $module->id)
                    ->update([
                        'company_id' => Auth::user()?->company?->id,
                        'question_pick_type' => $this->question_pick_type,
                    ]);
            } else {
                if ($this->question_pick_type === 'category' && ! empty($request['category_question_settings'])) {
                    $categoryIds = array_keys($request['category_question_settings']);
                    $questionIds = Question::withoutGlobalScope('user_scope')
                        ->whereIn('category_question_id', $categoryIds)
                        ->pluck('id')
                        ->toArray();

                    if (! empty($questionIds)) {
                        ModuleQuestion::withoutGlobalScope('user_scope')
                            ->where('module_id', $module->id)
                            ->whereIn('question_id', $questionIds)
                            ->update([
                                'company_id' => Auth::user()?->company?->id,
                                'question_pick_type' => 'category',
                            ]);
                    }
                } elseif ($this->question_pick_type === 'topic' && ! empty($request['topic_question_settings'])) {
                    $topicIds = array_keys($request['topic_question_settings']);
                    $questionIds = Question::withoutGlobalScope('user_scope')
                        ->whereIn('topic_id', $topicIds)
                        ->pluck('id')
                        ->toArray();

                    if (! empty($questionIds)) {
                        ModuleQuestion::withoutGlobalScope('user_scope')
                            ->where('module_id', $module->id)
                            ->whereIn('question_id', $questionIds)
                            ->update([
                                'company_id' => Auth::user()?->company?->id,
                                'question_pick_type' => 'topic',
                            ]);
                    }
                } elseif ($this->question_pick_type === 'material_category' && ! empty($request['material_category_question_settings'])) {
                    $materialCategoryIds = array_keys($request['material_category_question_settings']);
                    $questionIds = Question::withoutGlobalScope('user_scope')
                        ->whereIn('material_category_id', $materialCategoryIds)
                        ->pluck('id')
                        ->toArray();

                    if (! empty($questionIds)) {
                        ModuleQuestion::withoutGlobalScope('user_scope')
                            ->where('module_id', $module->id)
                            ->whereIn('question_id', $questionIds)
                            ->update([
                                'company_id' => Auth::user()?->company?->id,
                                'question_pick_type' => 'material_category',
                            ]);
                    }
                }
            }

            $this->get_module = $module;
            $this->is_all_questions = $module->is_all_questions ?? false;
            $this->applyCategoryQuestionSettings($module->category_question_settings ?? []);
            $this->applyTopicQuestionSettings($module->topic_question_settings ?? []);
            $this->applyMaterialCategoryQuestionSettings($module->material_category_question_settings ?? []);

            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleQuestionIndex => submitModule', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function modalModuleQuestion()
    {
        $this->openQuestion = true;

        return $this->dispatch('open-modal', ['id' => 'modal-module-question']);
    }

    public function submitModuleQuestion()
    {
        if (count($this->selected_all) == 0) {
            return AlertHelper::error('Gagal', 'Pilih soal terlebih dahulu');
        }

        try {
            $questionIds = $this->selected_all ? array_keys($this->selected_all) : [];

            // Dispatch background job using supervisor to avoid timeout and N+1 query errors
            SaveManualModuleQuestionsJob::dispatch(
                $this->get_module?->id,
                $questionIds,
                Auth::user()?->company?->id
            );

            $this->closeModal();

            return AlertHelper::success('Berhasil', 'Penyimpanan soal sedang diproses di background. Mohon tunggu beberapa saat.');
        } catch (Exception|Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleQuestionIndex => submitModuleQuestion', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }
    }

    public function exportPdf()
    {
        if (! $this->get_module) {
            return AlertHelper::error('Gagal', 'Modul tidak ditemukan.');
        }

        // Get all module questions with their question, study, topic, category, answers
        $moduleQuestions = $this->get_module->moduleQuestions()
            ->with(['question.study', 'question.topic', 'question.categoryQuestion', 'question.answers'])
            ->get();

        $questions = $moduleQuestions->map(fn ($mq) => $mq->question)->filter();

        if ($questions->isEmpty()) {
            return AlertHelper::error('Gagal', 'Tidak ada soal dalam modul ini untuk di-export.');
        }

        $company = Auth::user()->company;

        $pdf = Pdf::loadView('livewire.admin.master.module.admin-master-module-question-pdf', [
            'module' => $this->get_module,
            'questions' => $questions,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'modul-soal-'.Str::slug($this->get_module->name).'-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            app(ModuleQuestionService::class)->delete($id[0]);
        } catch (Exception|Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleQuestionIndex => delete', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }

    private function initializeCategoryQuestionSettings(): void
    {
        $this->category_question_settings = [];
        foreach ($this->category_questions as $category) {
            $this->category_question_settings[$category->id] = [
                'enabled' => false,
                'default' => 0,
                'easy' => 0,
                'medium' => 0,
                'hard' => 0,
            ];
        }
    }

    private function applyCategoryQuestionSettings($existingSettings): void
    {
        $this->initializeCategoryQuestionSettings();

        if (is_string($existingSettings)) {
            $existingSettings = json_decode($existingSettings, true) ?? [];
        }

        foreach ($existingSettings ?? [] as $categoryId => $settings) {
            if (! isset($this->category_question_settings[$categoryId])) {
                continue;
            }
            $this->category_question_settings[$categoryId] = [
                'enabled' => true,
                'default' => (int) ($settings['default'] ?? 0),
                'easy' => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard' => (int) ($settings['hard'] ?? 0),
            ];
        }
    }

    private function getFilteredCategoryQuestionSettings(): array
    {
        $filtered = [];
        foreach ($this->category_question_settings as $categoryId => $settings) {
            if (! ($settings['enabled'] ?? false)) {
                continue;
            }

            $filtered[$categoryId] = [
                'default' => (int) ($settings['default'] ?? 0),
                'easy' => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard' => (int) ($settings['hard'] ?? 0),
            ];
        }

        return $filtered;
    }

    private function initializeTopicQuestionSettings(): void
    {
        $this->topic_question_settings = [];
        foreach ($this->topics as $topic) {
            $this->topic_question_settings[$topic->id] = [
                'enabled' => false,
                'default' => 0,
                'easy' => 0,
                'medium' => 0,
                'hard' => 0,
            ];
        }
    }

    private function applyTopicQuestionSettings($existingSettings): void
    {
        $this->initializeTopicQuestionSettings();

        if (is_string($existingSettings)) {
            $existingSettings = json_decode($existingSettings, true) ?? [];
        }

        foreach ($existingSettings ?? [] as $topicId => $settings) {
            if (! isset($this->topic_question_settings[$topicId])) {
                continue;
            }
            $this->topic_question_settings[$topicId] = [
                'enabled' => true,
                'default' => (int) ($settings['default'] ?? 0),
                'easy' => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard' => (int) ($settings['hard'] ?? 0),
            ];
        }
    }

    private function getFilteredTopicQuestionSettings(): array
    {
        $filtered = [];
        foreach ($this->topic_question_settings as $topicId => $settings) {
            if (! ($settings['enabled'] ?? false)) {
                continue;
            }

            $filtered[$topicId] = [
                'default' => (int) ($settings['default'] ?? 0),
                'easy' => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard' => (int) ($settings['hard'] ?? 0),
            ];
        }

        return $filtered;
    }

    private function loadCategoryQuestionLimits(): void
    {
        $limits = [];
        foreach ($this->category_questions as $category) {
            $limits[$category->id] = [
                'default' => 0,
                'easy' => 0,
                'medium' => 0,
                'hard' => 0,
            ];
        }

        $query = Question::withoutGlobalScope('user_scope')
            ->select('category_question_id', DB::raw("COALESCE(difficulty, 'default') as difficulty"), DB::raw('count(*) as total'))
            ->whereNotNull('category_question_id');

        if ($this->question_type_id) {
            $query->where('question_type_id', $this->question_type_id);
        }

        $rows = $query->groupBy('category_question_id', DB::raw("COALESCE(difficulty, 'default')"))->get();

        foreach ($rows as $row) {
            if (! isset($limits[$row->category_question_id])) {
                continue;
            }
            $difficulty = $row->difficulty ?? 'default';
            if (! isset($limits[$row->category_question_id][$difficulty])) {
                $limits[$row->category_question_id][$difficulty] = 0;
            }
            $limits[$row->category_question_id][$difficulty] = (int) $row->total;
        }

        $this->category_question_limits = $limits;
    }

    private function loadTopicQuestionLimits(): void
    {
        $limits = [];
        foreach ($this->topics as $topic) {
            $limits[$topic->id] = [
                'default' => 0,
                'easy' => 0,
                'medium' => 0,
                'hard' => 0,
            ];
        }

        $query = Question::withoutGlobalScope('user_scope')
            ->select('topic_id', DB::raw("COALESCE(difficulty, 'default') as difficulty"), DB::raw('count(*) as total'))
            ->whereNotNull('topic_id');

        if ($this->question_type_id) {
            $query->where('question_type_id', $this->question_type_id);
        }

        $rows = $query->groupBy('topic_id', DB::raw("COALESCE(difficulty, 'default')"))->get();

        foreach ($rows as $row) {
            if (! isset($limits[$row->topic_id])) {
                continue;
            }
            $difficulty = $row->difficulty ?? 'default';
            if (! isset($limits[$row->topic_id][$difficulty])) {
                $limits[$row->topic_id][$difficulty] = 0;
            }
            $limits[$row->topic_id][$difficulty] = (int) $row->total;
        }

        $this->topic_question_limits = $limits;
    }

    private function initializeMaterialCategoryQuestionSettings(): void
    {
        $this->material_category_question_settings = [];
        foreach ($this->material_categories as $material_category) {
            $this->material_category_question_settings[$material_category->id] = [
                'enabled' => false,
                'default' => 0,
                'easy' => 0,
                'medium' => 0,
                'hard' => 0,
            ];
        }
    }

    private function applyMaterialCategoryQuestionSettings($existingSettings): void
    {
        $this->initializeMaterialCategoryQuestionSettings();

        if (is_string($existingSettings)) {
            $existingSettings = json_decode($existingSettings, true) ?? [];
        }

        foreach ($existingSettings ?? [] as $materialCategoryId => $settings) {
            if (! isset($this->material_category_question_settings[$materialCategoryId])) {
                continue;
            }
            $this->material_category_question_settings[$materialCategoryId] = [
                'enabled' => true,
                'default' => (int) ($settings['default'] ?? 0),
                'easy' => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard' => (int) ($settings['hard'] ?? 0),
            ];
        }
    }

    private function getFilteredMaterialCategoryQuestionSettings(): array
    {
        $filtered = [];
        foreach ($this->material_category_question_settings as $materialCategoryId => $settings) {
            if (! ($settings['enabled'] ?? false)) {
                continue;
            }

            $filtered[$materialCategoryId] = [
                'default' => (int) ($settings['default'] ?? 0),
                'easy' => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard' => (int) ($settings['hard'] ?? 0),
            ];
        }

        return $filtered;
    }

    private function loadMaterialCategoryQuestionLimits(): void
    {
        $limits = [];
        foreach ($this->material_categories as $material_category) {
            $limits[$material_category->id] = [
                'default' => 0,
                'easy' => 0,
                'medium' => 0,
                'hard' => 0,
            ];
        }

        $query = Question::withoutGlobalScope('user_scope')
            ->select('material_category_id', DB::raw("COALESCE(difficulty, 'default') as difficulty"), DB::raw('count(*) as total'))
            ->whereNotNull('material_category_id');

        if ($this->question_type_id) {
            $query->where('question_type_id', $this->question_type_id);
        }

        $rows = $query->groupBy('material_category_id', DB::raw("COALESCE(difficulty, 'default')"))->get();

        foreach ($rows as $row) {
            if (! isset($limits[$row->material_category_id])) {
                continue;
            }
            $difficulty = $row->difficulty ?? 'default';
            if (! isset($limits[$row->material_category_id][$difficulty])) {
                $limits[$row->material_category_id][$difficulty] = 0;
            }
            $limits[$row->material_category_id][$difficulty] = (int) $row->total;
        }

        $this->material_category_question_limits = $limits;
    }
}
