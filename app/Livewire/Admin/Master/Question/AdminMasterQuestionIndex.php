<?php

namespace App\Livewire\Admin\Master\Question;

use Exception;
use Livewire\Component;
use App\Helpers\AlertHelper;
use App\Imports\Question\QuestionImport;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Question\Topic;
use App\Services\Module\ModuleService;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\QuestionType;
use App\Models\Study\Study;
use App\Services\Question\QuestionService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LivewireFilepond\WithFilePond;
use Throwable;

class AdminMasterQuestionIndex extends Component
{
    use WithPagination, WithFileUploads, WithFilePond;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 5, $search;

    public $data_id, $topic_id, $material_category_id, $material_id, $question_type_id, $question, $description, $weight_correct, $weight_incorrect;
    public $topics = [], $material_categories = [], $materials = [], $question_types = [];
    public $images = [], $old_images = [], $studys = [], $study_id;
    public $filterStudyId, $filterQuestionTypeId, $filterTopicId;
    public $study_id_import, $file_import;

    public function render()
    {
        $questions = Question::select('id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'weight_correct', 'weight_incorrect', 'study_id')
            ->search($this->search);

        if ($this->filterStudyId) {
            $questions->where('study_id', $this->filterStudyId);
        }

        if ($this->filterQuestionTypeId) {
            $questions->where('question_type_id', $this->filterQuestionTypeId);
        }

        if ($this->filterTopicId) {
            $questions->where('topic_id', $this->filterTopicId);
        }

        return view('livewire.admin.master.question.admin-master-question-index', [
            'questions' => $questions->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        $this->topics         = Topic::select('id', 'name')->get();
        $this->question_types = QuestionType::select('id', 'name')->get();
        if (Auth::user()?->hasRole('Dosen')) {
            $studyIds = Auth::user()?->studys ?? []; // array dari JSON
            $this->studys = Study::whereIn('id', $studyIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
            $this->study_id = array_key_first($this->studys);
        } else {
            $this->studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }
    }

    public function updated()
    {
        //
    }

    public function updatedTopicId($value)
    {
        $this->material_category_id = null;
        $this->material_id = null;
        $this->material_categories = MaterialCategory::select('id', 'topic_id', 'name')
            ->where('topic_id', $value)
            ->get();
    }

    public function updatedMaterialCategoryId($value)
    {
        $this->material_id = null;
        $this->materials = Material::select('id', 'material_category_id', 'name')
            ->where('material_category_id', $value)
            ->get();
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
        $this->reset(['data_id', 'study_id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'images', 'weight_correct', 'weight_incorrect', 'study_id_import', 'file_import']);
        $this->dispatch('close-modal', ['id' => 'modal-import-question']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'study_id'             => 'required|exists:studies,id',
                'topic_id'             => 'required|exists:topics,id',
                'material_category_id' => 'nullable|exists:material_categories,id',
                'material_id'          => 'nullable|exists:materials,id',
                'question_type_id'     => 'required|exists:question_types,id',
                'question'             => 'required',
                'images.*'             => 'nullable|file|mimetypes:image/jpg,image/jpeg,image/png',
                'description'          => 'nullable',
            ],
            [
                'topic_id.required'           => 'Topik soal wajib diisi.',
                'study_id.required'           => 'Prodi wajib diisi.',
                'material_category_id.exists' => 'Kategori materi soal tidak valid.',
                'material_id.exists'          => 'Materi soal tidak valid.',
                'question_type_id.required'   => 'Tipe Ujian wajib diisi.',
                'question_type_id.exists'     => 'Tipe Ujian tidak valid.',
                'question.required'           => 'Pertanyaan wajib diisi.',
                'images.*.file'               => 'Gambar wajib berupa file.',
                'images.*.mimes'              => 'Gambar hanya berformat : .jpg, .jpeg, .png.',
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id'                   => $this->data_id,
                'user_id'              => Auth::user()?->id,
                'company_id'           => Auth::user()?->company?->id,
                'topic_id'             => $this->topic_id,
                'study_id'             => $this->study_id,
                'material_category_id' => $this->material_category_id,
                'material_id'          => $this->material_id,
                'question_type_id'     => $this->question_type_id,
                'question'             => $this->question,
                'images'               => $this->images,
                'old_images'           => $this->old_images,
                'description'          => $this->description,
                'weight_correct'       => $this->weight_correct,
                'weight_incorrect'     => $this->weight_incorrect,
            ];

            $question = app(QuestionService::class)->updateOrCreate($request);
            if (!$question) {
                throw new Exception("Ada kesalahaan saat QuestionService => updateOrCreate", 500);
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

    public function openModalImport()
    {
        return $this->dispatch('open-modal', ['id' => 'modal-import-question']);
    }

    public function importQuestion()
    {
        // dd($this->study_id_import, $this->file_import);
        $this->validate(
            [
                'study_id_import' => 'nullable|exists:studies,id',
                'file_import'     => 'required|file|mimes:xls,xlsx|max:10240',   // 10 MB
            ],
            [
                'study_id_import.required' => 'Prodi wajib diisi.',
                'study_id_import.exists'   => 'Prodi tidak valid.',
                'file_import.file'         => 'File import soal wajib berupa file.',
                'file_import.mimes'        => 'File import soal hanya berformat Excel: .xls atau .xlsx.',
                'file_import.max'          => 'Ukuran file maksimal 10 MB.',
            ]
        );

        try {
            // dd($this->study_id_import, $this->file_import);
            Excel::import(
                new QuestionImport((string) $this->study_id_import),
                $this->file_import // <- TemporaryUploadedFile OK
            );

        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => importQuestion', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat import data soal');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil diimport, silahkan tunggu beberapa saat.');
    }
}
