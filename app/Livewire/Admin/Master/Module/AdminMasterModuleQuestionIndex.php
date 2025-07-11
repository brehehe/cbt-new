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
use App\Services\Module\ModuleQuestionService;
use Throwable;

class AdminMasterModuleQuestionIndex extends Component
{
    use WithPagination, WithFileUploads, WithFilePond;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $get_module, $question_types;
    public $data_id, $question_type_id, $name, $duration, $description, $random_question;
    public $module_question_id, $question_id = [], $questions = [];

    public function render()
    {
        $module_questions = $this->get_module?->moduleQuestions()->select('id', 'module_id', 'question_id')->get();

        return view('livewire.admin.master.module.admin-master-module-question-index', [
            'module_questions' => $module_questions,
            'questions'        => $this->questions
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

        $this->question_types = QuestionType::select('id', 'name')->get();
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['module_question_id', 'question_id', 'questions']);
        $this->dispatch('close-modal', ['id' => 'modal-module-question']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submitModule()
    {
        $this->validate(
            [
                'question_type_id' => 'required|exists:question_types,id',
                'name'             => 'required',
                'description'      => 'nullable',
                'duration'         => 'required|numeric|min:1',
            ],
            [
                'question_type_id.required' => 'Tipe soal wajib diisi.',
                'question_type_id.exists'   => 'Tipe soal tidak valid.',
                'name.required'             => 'Nama modul wajib diisi.',
                'duration.required'         => 'Durasi pengerjaan modul wajib diisi.',
                'duration.numeric'          => 'Durasi pengerjaan modul hanya bernilai angka.',
                'duration.min'              => 'Durasi pengerjaan modul minimal 1 menit.',
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
        $this->questions = Question::select('id', 'question_type_id', 'question')->where('question_type_id', $this->question_type_id)->get();
        return $this->dispatch('open-modal', ['id' => 'modal-module-question']);
    }

    public function submitModuleQuestion()
    {
        $this->validate(
            [
                'question_id' => 'required',
            ],
            [
                'question_id.required' => 'Data soal wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id'          => $this->data_id,
                'company_id'  => Auth::user()?->company?->id,
                'module_id'   => $this->get_module?->id,
                'question_id' => $this->question_id,
            ];

            app(ModuleQuestionService::class)->updateOrCreate($request);

            DB::commit();
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
}
