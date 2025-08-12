<?php

namespace App\Livewire\Admin\Master\Question;

use Exception;
use Livewire\Component;
use App\Helpers\AlertHelper;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Question\Topic;
use App\Services\Module\ModuleService;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\QuestionType;
use App\Services\Question\QuestionService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Spatie\LivewireFilepond\WithFilePond;
use Throwable;

class AdminMasterQuestionIndex extends Component
{
    use WithPagination, WithFileUploads, WithFilePond;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $topic_id, $material_category_id, $material_id, $question_type_id ,$question, $description, $weight_correct, $weight_incorrect;
    public $topics = [], $material_categories = [], $materials = [], $question_types = [];
    public $images = [], $old_images = [];

    public function render()
    {
        $questions = Question::select('id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'weight_correct', 'weight_incorrect')->search($this->search);
        return view('livewire.admin.master.question.admin-master-question-index', [
            'questions' => $questions->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        $this->topics         = Topic::select('id', 'name')->get();
        $this->question_types = QuestionType::select('id', 'name')->get();
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

    public function hydrate ()
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
        $this->reset(['data_id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'question', 'description', 'images', 'weight_correct', 'weight_incorrect']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
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
                'material_category_id.exists' => 'Kategori materi soal tidak valid.',
                'material_id.exists'          => 'Materi soal tidak valid.',
                'question_type_id.required'   => 'Tipe soal wajib diisi.',
                'question_type_id.exists'     => 'Tipe soal tidak valid.',
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
}
