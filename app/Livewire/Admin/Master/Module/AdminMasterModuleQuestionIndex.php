<?php

namespace App\Livewire\Admin\Master\Module;

use Exception;
use Livewire\Component;
use App\Helpers\AlertHelper;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\Question;
use App\Services\Module\ModuleService;
use Spatie\LivewireFilepond\WithFilePond;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
use App\Services\Module\ModuleQuestionService;
use Throwable;

class AdminMasterModuleQuestionIndex extends Component
{
    use WithPagination, WithFileUploads, WithFilePond;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 8, $search;

    public $get_module, $question_types;
    public $data_id, $question_type_id, $name, $duration, $description, $random_question;
    public $module_question_id, $question_id = [];
    public $get_studys = [], $studys = [], $is_all_study = false, $topics = [];
    public $filterStudyId, $filterQuestionTypeId, $filterTopicId;
    public $selected_all = [], $openQuestion = false;

    public function render()
    {
        $module_questions = $this->get_module?->moduleQuestions()->select('id', 'module_id', 'question_id', 'study_id')->get();

        $questions = Question::select('id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'weight_correct', 'weight_incorrect', 'study_id')
            ->whereNotIn('id', $this->get_module?->moduleQuestions()->pluck('question_id')->toArray() ?? [])
            ->whereIn('study_id', $this->get_studys ? array_keys($this->get_studys) : [])
            ->search($this->search);

        if ($this->filterStudyId) {
            $questions->where('study_id', $this->filterStudyId);
        }

        // if ($this->filterQuestionTypeId) {
        //     $questions->where('question_type_id', $this->filterQuestionTypeId);
        // }

        if ($this->filterTopicId) {
            $questions->where('topic_id', $this->filterTopicId);
        }

        return view('livewire.admin.master.module.admin-master-module-question-index', [
            'module_questions' => $module_questions,
            'questions'        => $this->openQuestion ? $questions->orderBy('id', 'desc')->paginate($this->perPage) : [],
        ])->extends('layout.app')->section('content');
    }

    public function mount($id)
    {
        $this->get_module       = Module::findOrFail($id);
        $this->data_id          = $this->get_module?->id;
        $this->question_type_id = $this->get_module?->question_type_id;
        $this->name             = $this->get_module?->name;
        $this->duration         = $this->get_module?->duration;
        $this->description      = $this->get_module?->description;
        $this->random_question  = $this->get_module?->random_question;

        $this->studys           = json_decode($this->get_module?->studys) ?? [];

        if (Auth::user()?->hasRole('Dosen')) {
            $studyIds = Auth::user()?->studys ?? []; // array dari JSON
            $this->get_studys = Study::whereIn('id', $studyIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->get_studys = Study::whereIn('id', $this->studys)->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }

        $this->topics           = Topic::select('id', 'name')->get();
        $this->question_types   = QuestionType::select('id', 'name')->get();
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['module_question_id', 'question_id', 'selected_all', 'filterStudyId', 'filterQuestionTypeId', 'filterTopicId', 'search']);
        $this->perPage = 8;
        $this->openQuestion = false;
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
                'name'             => 'required',
                'description'      => 'nullable',
                'duration'         => 'required|numeric|min:1',
                'studys'           => 'required|array',
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
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id'               => $this->data_id,
                'company_id'       => Auth::user()?->company?->id,
                'question_type_id' => $this->question_type_id,
                'name'             => $this->name,
                'duration'         => $this->duration,
                'random_question'  => $this->random_question,
                'description'      => $this->description,
                'studys'           => $this->studys,
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
            DB::beginTransaction();
            $request = [
                'id'          => $this->data_id,
                'company_id'  => Auth::user()?->company?->id,
                'module_id'   => $this->get_module?->id,
                'question_id' => $this->selected_all ? array_keys($this->selected_all) : [],
                'study_id'    => Question::whereIn('id', $this->question_id)->pluck('study_id')->first()->id ?? null,
            ];

            app(ModuleQuestionService::class)->updateOrCreate($request);

            DB::commit();
            $this->closeModal();
            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleQuestionIndex => submitModuleQuestion', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            app(ModuleQuestionService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleQuestionIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
