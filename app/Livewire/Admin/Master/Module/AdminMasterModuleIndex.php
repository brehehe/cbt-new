<?php

namespace App\Livewire\Admin\Master\Module;

use Exception;
use Livewire\Component;
use App\Models\Category\CategoryQuestion;
use App\Helpers\AlertHelper;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\QuestionType;
use App\Models\Study\Study;
use App\Services\Module\ModuleService;
use App\Services\QuestionType\QuestionTypeService;
use App\Models\Master\Question\Topic;
use Throwable;

class AdminMasterModuleIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $question_types;
    public $data_id, $question_type_id, $name, $duration, $description, $random_question;
    public $question_pick_type = 'manual';
    public $updateRandomQuestion;
    public $get_studys = [], $studys = [], $is_all_study = false;
    public $category_questions = [];
    public $category_question_settings = [];
    public $category_question_limits = [];
    public $topics = [];
    public $topic_question_settings = [];
    public $topic_question_limits = [];

    public function render()
    {
        $modules = Module::withoutGlobalScope('user_scope')->search($this->search)->select('id', 'question_type_id', 'name', 'duration', 'description', 'random_question', 'studys')
            ->with([
                'questionType:id,name'
            ])
            ->where('company_id', Auth::user()?->company?->id)
            ->orderBy('order', 'desc');
        return view('livewire.admin.master.module.admin-master-module-index', [
            'modules' => $modules->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
        $this->question_types = QuestionType::select('id', 'name')->get();
        $this->category_questions = CategoryQuestion::select('id', 'name')->get();
        $this->initializeCategoryQuestionSettings();
        $this->loadCategoryQuestionLimits();
        $this->topics = Topic::select('id', 'name')->get();
        $this->initializeTopicQuestionSettings();
        $this->loadTopicQuestionLimits();

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
            $this->get_studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }
    }

    public function updatedIsAllStudy($value)
    {
        if ($value) {
            $this->studys = array_keys($this->get_studys);
        } else {
            $this->studys = [];
        }

        $this->initializeCategoryQuestionSettings();
    }

    public function updatedQuestionTypeId()
    {
        $this->loadCategoryQuestionLimits();
        $this->loadTopicQuestionLimits();
    }

    public function updatedTopicQuestionSettings($value, $key)
    {
        if (!is_string($key)) {
            return;
        }

        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        [$topicId, $field] = $parts;
        if (!in_array($field, ['default', 'easy', 'medium', 'hard'], true)) {
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

    public function updatedCategoryQuestionSettings($value, $key)
    {
        if (!is_string($key)) {
            return;
        }

        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            return;
        }

        [$categoryId, $field] = $parts;
        if (!in_array($field, ['default', 'easy', 'medium', 'hard'], true)) {
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

    public function getTotalQuestionsProperty()
    {
        $total = 0;
        foreach ($this->category_question_settings as $settings) {
            if (!($settings['enabled'] ?? false)) {
                continue;
            }
            $total += (int) ($settings['default'] ?? 0);
            $total += (int) ($settings['easy'] ?? 0);
            $total += (int) ($settings['medium'] ?? 0);
            $total += (int) ($settings['hard'] ?? 0);
        }

        return $total;
    }

    public function getTotalTopicQuestionsProperty()
    {
        $total = 0;
        foreach ($this->topic_question_settings as $settings) {
            if (!($settings['enabled'] ?? false)) {
                continue;
            }
            $total += (int) ($settings['default'] ?? 0);
            $total += (int) ($settings['easy'] ?? 0);
            $total += (int) ($settings['medium'] ?? 0);
            $total += (int) ($settings['hard'] ?? 0);
        }

        return $total;
    }

    // public function hydrate()
    // {
    //     $this->resetPage();
    // }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['data_id', 'question_type_id', 'name', 'duration', 'description', 'random_question', 'studys', 'is_all_study', 'category_question_settings', 'topic_question_settings', 'question_pick_type']);
        $this->initializeCategoryQuestionSettings();
        $this->initializeTopicQuestionSettings();
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'question_type_id' => 'required|exists:question_types,id',
                'name'             => 'required',
                'description'      => 'nullable',
                'duration'         => 'required|numeric|min:1',
                'studys'           => 'required|array',
                'question_pick_type' => 'required|in:manual,category,topic',
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
            ],
            [
                'question_type_id.required' => 'Tipe Ujian wajib diisi.',
                'question_type_id.exists'   => 'Tipe Ujian tidak valid.',
                'name.required'             => 'Nama modul wajib diisi.',
                'duration.required'         => 'Durasi pengerjaan modul wajib diisi.',
                'duration.numeric'          => 'Durasi pengerjaan modul hanya bernilai angka.',
                'duration.min'              => 'Durasi pengerjaan modul minimal 1 menit.',
                'studys.required'           => 'Prodi wajib dipilih.',
                'studys.array'              => 'Prodi tidak valid.',
                'question_pick_type.required' => 'Tipe pengambilan soal wajib dipilih.',
                'question_pick_type.in' => 'Tipe pengambilan soal tidak valid.',
            ]
        );

        $this->resetErrorBag('category_question_settings');
        $this->resetErrorBag('topic_question_settings');
        foreach ($this->category_question_settings as $categoryId => $settings) {
            if (!($settings['enabled'] ?? false)) {
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
            if (!($settings['enabled'] ?? false)) {
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

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        try {
            DB::beginTransaction();
            $request = [
                'id'               => $this->data_id,
                'user_id'          => Auth::user()?->id,
                'company_id'       => Auth::user()?->company?->id,
                'question_type_id' => $this->question_type_id,
                'name'             => $this->name,
                'duration'         => $this->duration,
                'random_question'  => $this->random_question,
                'description'      => $this->description,
                'studys'           => $this->studys,
                'is_all_study'     => $this->is_all_study,
                'question_pick_type' => $this->question_pick_type,
                'category_question_settings' => $this->getFilteredCategoryQuestionSettings(),
                'topic_question_settings' => $this->getFilteredTopicQuestionSettings(),
            ];

            $module = app(ModuleService::class)->updateOrCreate($request);
            if (!$module) {
                throw new Exception("Ada kesalahaan saat ModuleService => updateOrCreate", 500);
            }

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result                 = Module::findOrFail($id);
        $this->data_id          = $result?->id;
        $this->question_type_id = $result?->question_type_id;
        $this->name             = $result?->name;
        $this->duration         = $result?->duration;
        $this->random_question  = $result?->random_question;
        $this->description      = $result?->description;
        $this->question_pick_type = $result?->question_pick_type ?? 'manual';
        $this->is_all_study      = $result?->is_all_study ?? false;
        $this->studys            = json_decode($result?->studys ?? '[]', true) ?? [];
        $this->applyCategoryQuestionSettings($result?->category_question_settings ?? []);
        $this->applyTopicQuestionSettings($result?->topic_question_settings ?? []);
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function toggleRandomQuestion($id)
    {
        try {
            DB::beginTransaction();
            $module = Module::find($id);
            if ($module) {
                $module->random_question = !$module->random_question;
                $module->save();
            }
            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => toggleRandomQuestion', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat mengubah data');
        }
    }

    public function delete($id)
    {
        try {
            app(ModuleService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => delete', $error);
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
                'easy'    => 0,
                'medium'  => 0,
                'hard'    => 0,
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
            if (!isset($this->category_question_settings[$categoryId])) {
                continue;
            }
            $this->category_question_settings[$categoryId] = [
                'enabled' => true,
                'default' => (int) ($settings['default'] ?? 0),
                'easy'    => (int) ($settings['easy'] ?? 0),
                'medium'  => (int) ($settings['medium'] ?? 0),
                'hard'    => (int) ($settings['hard'] ?? 0),
            ];
        }
    }

    private function getFilteredCategoryQuestionSettings(): array
    {
        $filtered = [];
        foreach ($this->category_question_settings as $categoryId => $settings) {
            if (!($settings['enabled'] ?? false)) {
                continue;
            }

            $filtered[$categoryId] = [
                'default' => (int) ($settings['default'] ?? 0),
                'easy'   => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard'   => (int) ($settings['hard'] ?? 0),
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
                'easy'    => 0,
                'medium'  => 0,
                'hard'    => 0,
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
            if (!isset($this->topic_question_settings[$topicId])) {
                continue;
            }
            $this->topic_question_settings[$topicId] = [
                'enabled' => true,
                'default' => (int) ($settings['default'] ?? 0),
                'easy'    => (int) ($settings['easy'] ?? 0),
                'medium'  => (int) ($settings['medium'] ?? 0),
                'hard'    => (int) ($settings['hard'] ?? 0),
            ];
        }
    }

    private function getFilteredTopicQuestionSettings(): array
    {
        $filtered = [];
        foreach ($this->topic_question_settings as $topicId => $settings) {
            if (!($settings['enabled'] ?? false)) {
                continue;
            }

            $filtered[$topicId] = [
                'default' => (int) ($settings['default'] ?? 0),
                'easy'   => (int) ($settings['easy'] ?? 0),
                'medium' => (int) ($settings['medium'] ?? 0),
                'hard'   => (int) ($settings['hard'] ?? 0),
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
            if (!isset($limits[$row->category_question_id])) {
                continue;
            }
            $difficulty = $row->difficulty ?? 'default';
            if (!isset($limits[$row->category_question_id][$difficulty])) {
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
            if (!isset($limits[$row->topic_id])) {
                continue;
            }
            $difficulty = $row->difficulty ?? 'default';
            if (!isset($limits[$row->topic_id][$difficulty])) {
                $limits[$row->topic_id][$difficulty] = 0;
            }
            $limits[$row->topic_id][$difficulty] = (int) $row->total;
        }

        $this->topic_question_limits = $limits;
    }
}
