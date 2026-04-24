<?php

namespace App\Livewire\Admin\Master\Question;

use App\Exports\QuestionExport;
use App\Helpers\AlertHelper;
use App\Imports\Question\QuestionImport;
use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
use App\Services\Question\QuestionService;
use App\Traits\UploadFile;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LivewireFilepond\WithFilePond;
use Throwable;

class AdminMasterQuestionIndex extends Component
{
    use UploadFile, WithFilePond, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 5;

    public $search;

    public $selectedQuestions = [];

    public $selectAll = false;

    public $bulkCategoryQuestionId;

    public $data_id;

    public $topic_id;

    public $material_category_id;

    public $material_id;

    public $question_type_id;

    public $type;

    public $question;

    public $description;

    public $latex;

    public $weight_correct;

    public $weight_incorrect;

    public $category_question_id;

    public $topics = [];

    public $material_categories = [];

    public $materials = [];

    public $question_types = [];

    public $category_questions = [];

    public $images = [];

    public $old_images = [];

    public $new_images = [];

    public $studys = [];

    public $study_id;

    public $filterStudyId;

    public $filterQuestionTypeId;

    public $filterTopicId;

    public $filterDifficulty;

    public $filterCategoryQuestionId;

    public $study_id_import;

    public $file_import;

    public $import_type = 'pg';

    public $isLimited = false;

    public function render()
    {
        $questions = $this->buildQuestionsQuery();

        return view('livewire.admin.master.question.admin-master-question-index', [
            'questions' => $questions->paginate($this->perPage),
        ])->extends('layout.app')->section('content');
    }

    protected function buildQuestionsQuery()
    {
        $questions = Question::select('id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'weight_correct', 'weight_incorrect', 'study_id', 'difficulty', 'category_question_id', 'type')
            ->search($this->search)
            ->where('is_simulation', 'false')
            ->orderBy('created_at', 'desc')
            ->orderBy('order', 'desc')
            ->orderBy('question', 'asc');

        if ($this->filterStudyId) {
            $questions->where('study_id', $this->filterStudyId);
        }

        if ($this->filterQuestionTypeId) {
            $questions->where('question_type_id', $this->filterQuestionTypeId);
        }

        if ($this->filterTopicId) {
            $questions->where('topic_id', $this->filterTopicId);
        }

        if ($this->filterDifficulty) {
            $questions->where('difficulty', $this->filterDifficulty);
        }

        if ($this->filterCategoryQuestionId) {
            $questions->where('category_question_id', $this->filterCategoryQuestionId);
        }

        return $questions;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedQuestions = $this->buildQuestionsQuery()
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedQuestions = [];
        }
    }

    public function updatedSelectedQuestions()
    {
        $this->selectAll = false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedFilterStudyId()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedFilterQuestionTypeId()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedFilterTopicId()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedFilterDifficulty()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedFilterCategoryQuestionId()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    protected function resetSelection()
    {
        $this->selectedQuestions = [];
        $this->selectAll = false;
    }

    public function applyBulkCategory()
    {
        $this->validate([
            'bulkCategoryQuestionId' => 'required|exists:category_questions,id',
        ], [
            'bulkCategoryQuestionId.required' => 'Kategori soal wajib dipilih.',
            'bulkCategoryQuestionId.exists' => 'Kategori soal tidak valid.',
        ]);

        if (count($this->selectedQuestions) === 0) {
            return AlertHelper::error('Gagal', 'Pilih minimal satu soal.');
        }

        try {
            DB::beginTransaction();

            Question::whereIn('id', $this->selectedQuestions)
                ->update(['category_question_id' => $this->bulkCategoryQuestionId]);

            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterQuestionIndex => applyBulkCategory', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat memperbarui kategori soal.');
        }

        $this->resetSelection();
        $this->bulkCategoryQuestionId = null;

        return AlertHelper::success('Berhasil', 'Kategori soal berhasil diperbarui.');
    }

    public function mount()
    {
        $this->topics = Topic::select('id', 'name')->get();
        $this->question_types = QuestionType::select('id', 'name')->get();
        $this->category_questions = CategoryQuestion::select('id', 'name')->get();
        // if (Auth::user()?->hasRole('Dosen')) {
        //     $studyIds = Auth::user()?->studys ?? [];

        //     // Ensure $studyIds is always an array
        //     if (is_string($studyIds)) {
        //         $studyIds = json_decode($studyIds, true) ?? [];
        //     }

        //     // Ensure it's an array and not null
        //     $studyIds = is_array($studyIds) ? $studyIds : [];

        //     $this->studys = Study::whereIn('id', $studyIds)
        //         ->orderBy('name', 'asc')
        //         ->pluck('name', 'id')
        //         ->toArray();
        //     $this->study_id = array_key_first($this->studys);
        // } else {
        //     $this->studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        // }
        $this->studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
    }

    public function updatedNewImages($value)
    {
        // Append chosen temporary files by storing them permanently
        $folder = '/public/question/'.Carbon::now()->isoFormat('Y').'/'.Carbon::now()->isoFormat('MM');

        foreach ($this->new_images as $new_image) {
            $upload = $this->uploadFile($new_image, $folder);
            $this->images[] = 'question/'.Carbon::now()->isoFormat('Y').'/'.Carbon::now()->isoFormat('MM').'/'.$upload[1];
        }

        // Reset the input model so it fires updated hook on the next upload
        $this->new_images = [];
        $this->old_images = $this->images;
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            // Re-index array
            $this->images = array_values($this->images);
            $this->old_images = $this->images;
        }
    }

    public function updated()
    {
        //
    }

    public function updatedTopicId($value)
    {
        $value = ! $value ? null : $value;
        $this->material_category_id = null;
        $this->material_id = null;
        $this->material_categories = MaterialCategory::select('id', 'topic_id', 'name')
            ->where('topic_id', $value)
            ->get();
    }

    public function updatedMaterialCategoryId($value)
    {
        $value = ! $value ? null : $value;
        $this->material_id = null;
        $this->materials = Material::select('id', 'material_category_id', 'name')
            ->where('material_category_id', $value)
            ->get();
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
        $this->reset(['data_id', 'study_id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'type', 'question', 'description', 'latex', 'images', 'weight_correct', 'weight_incorrect', 'study_id_import', 'file_import', 'category_question_id', 'import_type']);
        $this->dispatch('close-modal', ['id' => 'modal-import-question']);

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'study_id' => 'required|exists:studies,id',
                'topic_id' => 'required|exists:topics,id',
                'category_question_id' => 'required|exists:category_questions,id',
                'material_category_id' => 'nullable|exists:material_categories,id',
                'material_id' => 'nullable|exists:materials,id',
                'question_type_id' => 'required|exists:question_types,id',
                'type' => 'required|in:single,multiple,essay',
                'question' => 'required',
                'latex' => 'nullable',
                'images.*' => 'nullable|file|mimetypes:image/jpg,image/jpeg,image/png',
                'description' => 'nullable',
            ],
            [
                'topic_id.required' => 'Topik soal wajib diisi.',
                'study_id.required' => 'Prodi wajib diisi.',
                'material_category_id.exists' => 'Kategori materi soal tidak valid.',
                'material_id.exists' => 'Materi soal tidak valid.',
                'question_type_id.required' => 'Tipe Ujian wajib diisi.',
                'question_type_id.exists' => 'Tipe Ujian tidak valid.',
                'type.required' => 'Jenis soal wajib diisi.',
                'type.in' => 'Jenis soal tidak valid.',
                'question.required' => 'Pertanyaan wajib diisi.',
                'images.*.file' => 'Gambar wajib berupa file.',
                'images.*.mimes' => 'Gambar hanya berformat : .jpg, .jpeg, .png.',
                'category_question_id.exists' => 'Kategori soal tidak valid.',
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id' => $this->data_id,
                'user_id' => Auth::user()?->id,
                'company_id' => Auth::user()?->company?->id,
                'topic_id' => $this->topic_id,
                'study_id' => $this->study_id,
                'material_category_id' => $this->material_category_id,
                'material_id' => $this->material_id,
                'question_type_id' => $this->question_type_id,
                'type' => $this->type ?? Question::TYPE_SINGLE,
                'question' => $this->question,
                'latex' => $this->latex,
                'images' => $this->images,
                'old_images' => $this->old_images,
                'description' => $this->description,
                'weight_correct' => $this->weight_correct,
                'weight_incorrect' => $this->weight_incorrect,
                'category_question_id' => $this->category_question_id,
            ];

            $question = app(QuestionService::class)->updateOrCreate($request);
            if (! $question) {
                throw new Exception('Ada kesalahaan saat QuestionService => updateOrCreate', 500);
            }

            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => submit', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            app(QuestionService::class)->delete($id[0]);
            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => delete', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
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
                'file_import' => 'required|file|mimes:xls,xlsx|max:10240',   // 10 MB
            ],
            [
                'study_id_import.required' => 'Prodi wajib diisi.',
                'study_id_import.exists' => 'Prodi tidak valid.',
                'file_import.file' => 'File import soal wajib berupa file.',
                'file_import.mimes' => 'File import soal hanya berformat Excel: .xls atau .xlsx.',
                'file_import.max' => 'Ukuran file maksimal 10 MB.',
            ]
        );

        try {
            // dd($this->study_id_import, $this->file_import);
            Excel::import(
                new QuestionImport((string) $this->study_id_import, $this->import_type),
                $this->file_import // <- TemporaryUploadedFile OK
            );

        } catch (Exception|Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => importQuestion', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat import data soal');
        }

        $this->closeModal();

        return AlertHelper::success('Berhasil', 'Data berhasil diimport, silahkan tunggu beberapa saat.');
    }

    public function exportExcel()
    {
        if (! config('app.export_question')) {
            return AlertHelper::error('Gagal', 'Fitur export dinonaktifkan.');
        }

        $questions = $this->buildQuestionsQuery()
            ->with(['study', 'topic', 'categoryQuestion', 'answers'])
            ->get();

        return Excel::download(new QuestionExport($questions), 'bank-soal-'.date('Y-m-d-H-i-s').'.xlsx');
    }

    public function exportPdf()
    {
        if (! config('app.export_question')) {
            return AlertHelper::error('Gagal', 'Fitur export dinonaktifkan.');
        }

        $questions = $this->buildQuestionsQuery()
            ->with(['study', 'topic', 'categoryQuestion', 'answers'])
            ->get();

        $company = Auth::user()->company;

        $pdf = Pdf::loadView('livewire.admin.master.question.admin-master-question-pdf', [
            'questions' => $questions,
            'company' => $company,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'bank-soal-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }
}
