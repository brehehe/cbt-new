<?php

namespace App\Livewire\Admin\Master\Question;

use Exception;
use Livewire\Component;
use App\Helpers\AlertHelper;
use App\Models\Master\Question\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Topic;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\Question;
use App\Services\Question\QuestionService;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\MaterialCategory;
use App\Services\Answer\AnswerService;
use Livewire\WithFileUploads;
use Spatie\LivewireFilepond\WithFilePond;
use Storage;
use Throwable;

class AdminMasterQuestionUpdate extends Component
{
    use WithFileUploads, WithFilePond;
    protected $search;

    public $get_question;
    public $data_id, $topic_id, $material_category_id, $material_id, $question_type_id ,$question, $description, $weight_correct, $weight_incorrect;
    public $topics = [], $material_categories = [], $materials = [], $question_types = [];
    public $images = [];

    public $answer_id, $answer_context, $answer_description, $answer_correct;
    public $answer_images = [];

    public function render()
    {
        $answers = $this->get_question->answers()->search($this->search)->get();
        return view('livewire.admin.master.question.admin-master-question-update', [
            'answers' => $answers
        ])->extends('layout.app')->section('content');
    }

    public function mount($id)
    {
        $this->get_question         = Question::findOrFail($id);
        $this->data_id              = $this->get_question?->id;
        $this->topic_id             = $this->get_question?->topic_id;
        $this->material_category_id = $this->get_question?->material_category_id;
        $this->material_id          = $this->get_question?->material_id;
        $this->question_type_id     = $this->get_question?->question_type_id;
        $this->question             = $this->get_question?->question;
        $this->description          = $this->get_question?->description;
        $this->weight_correct       = $this->get_question?->weight_correct;
        $this->weight_incorrect     = $this->get_question?->weight_incorrect;

        $this->topics              = Topic::select('id', 'name')->get();
        $this->material_categories = MaterialCategory::select('id', 'topic_id', 'name')->where('topic_id', $this->get_question?->topic_id)->get();
        $this->question_types      = QuestionType::select('id', 'name')->get();
        $this->materials           = Material::select('id', 'material_category_id', 'name')->where('material_category_id', $this->get_question?->material_category_id)->get();

        foreach (json_decode($this->get_question?->images, true) ?? [] as $key => $image) {
            $this->images[] = asset('storage'.$image);
        }
    }

    public function updated()
    {
        //
    }

    public function updatedTopicId($value)
    {
        $value = !$value ? null : $value;
        $this->material_category_id = null;
        $this->material_id = null;
        $this->material_categories = MaterialCategory::select('id', 'topic_id', 'name')
            ->where('topic_id', $value)
            ->get();
    }

    public function updatedMaterialCategoryId($value)
    {
        $value = !$value ? null : $value;
        $this->material_id = null;
        $this->materials = Material::select('id', 'material_category_id', 'name')
            ->where('material_category_id', $value)
            ->get();
    }

    public function submitQuestion()
    {
        $this->validate(
            [
                'topic_id'             => 'required|exists:topics,id',
                'material_category_id' => 'nullable|exists:material_categories,id',
                'material_id'          => 'nullable|exists:materials,id',
                'question_type_id'     => 'required|exists:question_types,id',
                'question'             => 'required',
                'images.*'             => 'nullable|image|mimes:jpg,jpeg,png',
                'description'          => 'nullable',
            ],
            [
                'topic_id.required'           => 'Topik soal wajib diisi.',
                'material_category_id.exists' => 'Kategori materi soal tidak valid.',
                'material_id.exists'          => 'Materi soal tidak valid.',
                'question_type_id.required'   => 'Tipe soal wajib diisi.',
                'question_type_id.exists'     => 'Tipe soal tidak valid.',
                'question.required'           => 'Pertanyaan wajib diisi.',
                'images.*.image'              => 'Gambar wajib berupa gambar.',
                'images.*.mimes'              => 'Gambar hanya berformat : .jpg, .jpeg, .png.',
            ]
        );

        try {
            DB::beginTransaction();
                 $request = [
                    'id'                   => $this->data_id,
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
            Log::error('Ada Kesalahaan saat AdminMasterQuestionUpdate => submitQuestion', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['answer_id', 'answer_context', 'answer_description', 'answer_correct', 'answer_images']);
        $this->dispatch('close-modal', ['id' => 'modal-images']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function modalImages()
    {
        return $this->dispatch('open-modal', ['id' => 'modal-images']);
    }

    public function submitAnswer()
    {
        $this->validate(
            [
                'answer_context'     => 'required',
                'answer_images.*'    => 'nullable|image|mimes:jpg,jpeg,png',
                'answer_description' => 'nullable',
            ],
            [
                'answer_context.required' => 'Konteks jawaban wajib diisi.',
                'answer_images.*.image'   => 'Gambar jawaban wajib berupa gambar.',
                'answer_images.*.mimes'   => 'Gambar jawaban hanya berformat : .jpg, .jpeg, .png.',
            ]
        );

        try {
            DB::beginTransaction();
                 $request = [
                    'id'         => $this->answer_id,
                    'company_id' => Auth::user()?->company?->id,
                    'alphabet'   => null,
                    'context'    => $this->answer_context,
                    'images'     => $this->answer_images,
                    'is_correct' => $this->answer_correct,
                ];

                $answer = app(AnswerService::class)->updateOrCreate($this->get_question, $request);
                if (!$answer) {
                    throw new Exception("Ada kesalahaan saat AnswerService => updateOrCreate", 500);
                }

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterQuestionUpdate => submitAnswer', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result               = Answer::findOrFail($id);
        $this->answer_id      = $result?->id;
        $this->answer_context = $result?->context;
        $this->answer_correct = $result?->is_correct;
        $this->openModal();
    }

    public function toggleAnswerCorrect($id)
    {
        $result               = Answer::findOrFail($id);
        $this->answer_id      = $result?->id;
        $this->answer_context = $result?->context;
        $this->answer_correct = true;
        $this->submitAnswer();
    }
}
