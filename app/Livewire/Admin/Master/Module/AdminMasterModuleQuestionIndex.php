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
use App\Models\Study\Study;
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
    public $get_studys = [], $studys = [], $is_all_study = false;

    public function render()
    {
        $module_questions = $this->get_module?->moduleQuestions()->select('id', 'module_id', 'question_id', 'study_id')->get();

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

        if (Auth::user()?->hasRole('Dosen')) {
            $studyIds = Auth::user()?->studys ?? []; // array dari JSON
            $this->get_studys = Study::whereIn('id', $studyIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->get_studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }

        $this->studys           = json_decode($this->get_module?->studys) ?? [];
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
                'studys'           => 'required|array',
            ],
            [
                'question_type_id.required' => 'Tipe soal wajib diisi.',
                'question_type_id.exists'   => 'Tipe soal tidak valid.',
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
        $this->questions = Question::select('id', 'question_type_id', 'question', 'study_id')
            ->whereIn('study_id', $this->studys)
            ->with(['study:id,name'])
            ->whereNotIn('id', $this->get_module?->moduleQuestions()->pluck('question_id')->toArray() ?? [])
            ->where('question_type_id', $this->question_type_id)->whereHas('answers', function ($query) {
                $query->where('is_correct', true);
            })->get();
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
                'study_id'    => Question::whereIn('id', $this->question_id)->pluck('study_id')->first()->id ?? null,
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
