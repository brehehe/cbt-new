<?php

namespace App\Livewire\Admin\Master\Question;

use Storage;
use Exception;
use Throwable;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Helpers\AlertHelper;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Topic;
use App\Models\Master\Question\Answer;
use App\Services\Answer\AnswerService;
use App\Models\Master\Question\Material;
use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Question;
use App\Services\Question\QuestionService;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\MaterialCategory;
use App\Traits\UploadFile;
use App\Models\Study\Study;

class AdminMasterQuestionUpdate extends Component
{
    use WithFileUploads, UploadFile;
    public $search;

    public $isEditingAnswer = false;

    public $get_question;
    public $data_id, $topic_id, $material_category_id, $material_id, $question_type_id, $question, $description, $latex, $weight_correct, $weight_incorrect,$category_question_id;
    public $topics = [], $material_categories = [], $materials = [], $question_types = [], $category_questions = [];
    public $images = [], $old_images = [], $new_images = [];

    public $answer_id, $answer_context, $answer_description, $answer_latex, $answer_correct, $answer_alphabet;
    public $answer_images = [], $old_answer_images = [], $answer_new_images = [];
    public $studys = [], $study_id;

    public function render()
    {
        $answers = $this->get_question->answers()->orderBy('order', 'asc')->orderBy('alphabet', 'asc')->search($this->search)->get();
        return view('livewire.admin.master.question.admin-master-question-update', [
            'answers' => $answers
        ])->extends('layout.app')->section('content');
    }

    public function mount($id)
    {
        $this->get_question         = Question::findOrFail($id);
        $this->data_id              = $this->get_question?->id;
        $this->normalizeAnswerAlphabet($this->data_id);
        $this->topic_id             = $this->get_question?->topic_id;
        $this->material_category_id = $this->get_question?->material_category_id;
        $this->material_id          = $this->get_question?->material_id;
        $this->question_type_id     = $this->get_question?->question_type_id;
        $this->question             = $this->get_question?->question;
        $this->description          = $this->get_question?->description;
        $this->latex                = $this->get_question?->latex;
        $this->weight_correct       = $this->get_question?->weight_correct;
        $this->weight_incorrect     = $this->get_question?->weight_incorrect;
        $this->category_question_id = $this->get_question?->category_question_id;
        $this->topics              = Topic::select('id', 'name')->get();
        $this->material_categories = MaterialCategory::select('id', 'topic_id', 'name')->where('topic_id', $this->get_question?->topic_id)->get();
        $this->question_types      = QuestionType::select('id', 'name')->get();
        $this->category_questions  = CategoryQuestion::select('id', 'name')->get();
        $this->materials           = Material::select('id', 'material_category_id', 'name')->where('material_category_id', $this->get_question?->material_category_id)->get();

        foreach (json_decode($this->get_question?->images, true) ?? [] as $image) {
            // Normalize path to strip /storage/ prefix for internal array handling
            $cleanPath = Str::after($image, '/storage/');
            $cleanPath = ltrim($cleanPath, '/');
            
            $this->old_images[] = $cleanPath;
            $this->images[]     = $cleanPath;
        }

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
        // } else {
        //     $this->studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        // }
        $this->studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();

        $this->study_id = $this->get_question?->study_id;

        // dd($this->old_images, $this->images);
    }

    private function normalizeAnswerAlphabet(string $questionId): void
    {
        $answers = Answer::withoutGlobalScope('user_scope')
            ->where('question_id', $questionId)
            ->orderBy('created_at', 'asc')
            ->get();

        $needsUpdate = $answers->contains(function ($answer) {
            return empty($answer->alphabet) || (int) $answer->order <= 0;
        });

        if (!$needsUpdate) {
            return;
        }

        foreach ($answers as $index => $answer) {
            $order = $index + 1;
            $alphabet = chr(64 + $order);

            if ((int) $answer->order !== $order || $answer->alphabet !== $alphabet) {
                $answer->forceFill([
                    'order' => $order,
                    'alphabet' => $alphabet,
                ])->saveQuietly();
            }
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

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus jawaban ini?', $id);
    }

    public function delete($id)
    {
        $answerId = is_array($id) ? ($id[0] ?? null) : $id;
        if (!$answerId) {
            return AlertHelper::error('Gagal', 'ID jawaban tidak valid.');
        }

        try {
            DB::beginTransaction();

            Answer::withoutGlobalScope('user_scope')
                ->where('question_id', $this->data_id)
                ->where('id', $answerId)
                ->delete();

            $this->normalizeAnswerAlphabet($this->data_id);

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterQuestionUpdate => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus jawaban');
        }

        return AlertHelper::success('Berhasil', 'Jawaban berhasil dihapus.');
    }

    public function updatedMaterialCategoryId($value)
    {
        $value = !$value ? null : $value;
        $this->material_id = null;
        $this->materials = Material::select('id', 'material_category_id', 'name')
            ->where('material_category_id', $value)
            ->get();
    }

    public function updatedCategoryQuestionId($value)
    {
        $this->category_question_id = !$value ? null : $value;
    }

    public function submitQuestion()
    {
        $this->category_question_id = $this->category_question_id ?: null;

        $this->validate(
            [
                'topic_id'             => 'required|exists:topics,id',
                'material_category_id' => 'nullable|exists:material_categories,id',
                'material_id'          => 'nullable|exists:materials,id',
                'question_type_id'     => 'required|exists:question_types,id',
                'question'             => 'required',
                'study_id'             => 'required|exists:studies,id',
                // 'images.*'             => 'nullable|image|mimes:jpg,jpeg,png',
                'description'          => 'nullable',
                'category_question_id'  => 'nullable|exists:category_questions,id',
                    'latex'                => 'nullable',
            ],
            [
                'study_id.required'           => 'Prodi wajib diisi.',
                'topic_id.required'           => 'Topik soal wajib diisi.',
                'material_category_id.exists' => 'Kategori materi soal tidak valid.',
                'material_id.exists'          => 'Materi soal tidak valid.',
                'question_type_id.required'   => 'Tipe Ujian wajib diisi.',
                'question_type_id.exists'     => 'Tipe Ujian tidak valid.',
                'question.required'           => 'Pertanyaan wajib diisi.',
                'images.*.image'              => 'Gambar wajib berupa gambar.',
                'images.*.mimes'              => 'Gambar hanya berformat : .jpg, .jpeg, .png.',
                'category_question_id.exists' => 'Kategori soal tidak valid.',
            ]
        );

        try {
            DB::beginTransaction();
            
            // Debug: Log data yang akan dikirim ke service
            Log::info('📝 Livewire submit gambar soal', [
                'total_images' => count($this->images),
                'total_old_images' => count($this->old_images),
                'images' => $this->images,
                'old_images' => $this->old_images,
            ]);
            
            $request = [
                'id'                   => $this->data_id,
                'company_id'           => Auth::user()?->company?->id,
                'topic_id'             => $this->topic_id,
                'material_category_id' => $this->material_category_id,
                'material_id'          => $this->material_id,
                'question_type_id'     => $this->question_type_id,
                'question'             => $this->question,
                'images'               => $this->images,
                'study_id'             => $this->study_id,
                'old_images'           => $this->old_images,
                'description'          => $this->description,
                'weight_correct'       => $this->weight_correct,
                'weight_incorrect'     => $this->weight_incorrect,
                'category_question_id' => $this->category_question_id,
                    'latex'                => $this->latex,
            ];

            $question = app(QuestionService::class)->updateOrCreate($request);
            if (!$question) {
                throw new Exception("Ada kesalahaan saat QuestionService => updateOrCreate", 500);
            }
            
            $this->get_question = $question;

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

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function openModal()
    {
        $this->isEditingAnswer = false;
        $this->reset(['answer_id', 'answer_context', 'answer_description', 'answer_latex', 'answer_correct', 'answer_images', 'old_answer_images', 'answer_alphabet']);
        $this->answer_correct = false;
        $this->dispatch('reset-answer-latex-preview');
        $this->dispatch('sync-answer-latex-preview');
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        
        // Reset gambar ke state database agar perubahan filepond tidak disimpan jika dibatalkan
        $this->images = [];
        $this->old_images = [];
        foreach (json_decode($this->get_question?->images, true) ?? [] as $image) {
            $cleanPath = Str::after($image, '/storage/');
            $cleanPath = ltrim($cleanPath, '/');
            $this->old_images[] = $cleanPath;
            $this->images[]     = $cleanPath;
        }
        
        $this->reset(['answer_id', 'answer_context', 'answer_description', 'answer_latex', 'answer_correct', 'answer_images', 'old_answer_images', 'answer_alphabet']);
        $this->dispatch('close-modal', ['id' => 'modal-images']);
        $this->dispatch('close-modal', ['id' => 'modal-answer-images']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function modalImages()
    {
        // Reset dan reload gambar dari database agar filepond menampilkan state terbaru
        $this->images = [];
        $this->old_images = [];
        
        foreach (json_decode($this->get_question?->images, true) ?? [] as $image) {
            $cleanPath = Str::after($image, '/storage/');
            $cleanPath = ltrim($cleanPath, '/');
            $this->old_images[] = $cleanPath;
            $this->images[]     = $cleanPath;
        }
        
        return $this->dispatch('open-modal', ['id' => 'modal-images']);
    }

    public function submitAnswer()
    {
        $this->answer_correct = (bool) $this->answer_correct;
        $this->validate(
            [
                'answer_context'     => 'required',
                // 'answer_images.*'    => 'nullable|image|mimes:jpg,jpeg,png',
                'answer_description' => 'nullable',
                    'answer_latex'       => 'nullable',
            ],
            [
                'answer_context.required' => 'Konteks jawaban wajib diisi.',
                // 'answer_images.*.image'   => 'Gambar jawaban wajib berupa gambar.',
                // 'answer_images.*.mimes'   => 'Gambar jawaban hanya berformat : .jpg, .jpeg, .png.',
            ]
        );

        try {
            DB::beginTransaction();
            
            // Debug: Log data yang akan dikirim ke service
            Log::info('📝 Livewire submit gambar jawaban', [
                'total_images' => count($this->answer_images),
                'total_old_images' => count($this->old_answer_images),
                'answer_images' => $this->answer_images,
                'old_answer_images' => $this->old_answer_images,
            ]);
            
            $request = [
                'id'         => $this->answer_id,
                'company_id' => Auth::user()?->company?->id,
                'alphabet'   => $this->answer_alphabet,
                'context'    => $this->answer_context,
                'images'     => $this->answer_images,
                'old_images' => $this->old_answer_images,
                'is_correct' => (bool) $this->answer_correct,
                    'latex'      => $this->answer_latex,
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
        $this->answer_alphabet = $result?->alphabet;
        $this->answer_context = $result?->context;
        $this->answer_latex   = $result?->latex;
        $this->answer_correct = $result?->is_correct;
        $this->isEditingAnswer = true;
        $this->dispatch('open-modal', ['id' => 'modal']);
        return $this->dispatch('sync-answer-latex-preview');
    }

    public function toggleAnswerCorrect($id)
    {
        $answer = Answer::withoutGlobalScope('user_scope')->findOrFail($id);
        $answer->is_correct = true;
        $answer->save();
    }

    public function modalAnswerImage($id, $alphabet)
    {
        $result                = Answer::findOrFail($id);
        $this->answer_alphabet = $alphabet;
        $this->answer_id       = $result?->id;
        $this->answer_context  = $result?->context;
        $this->answer_latex    = $result?->latex;
        $this->answer_correct  = $result?->is_correct;
        
        // Reset dan reload gambar dari database agar filepond menampilkan state terbaru
        $this->answer_images = [];
        $this->old_answer_images = [];
        
        foreach (json_decode($result?->images, true) ?? [] as $image) {
            $cleanPath = Str::after($image, '/storage/');
            $cleanPath = ltrim($cleanPath, '/');
            $this->answer_images[]     = $cleanPath;
            $this->old_answer_images[] = $cleanPath;
        }
        // dd($this->answer_images, $this->old_answer_images);
        // $this->dispatch('initFilepondWithImages', $this->answer_images);
        return $this->dispatch('open-modal', ['id' => 'modal-answer-images']);
    }

    public function updatedNewImages($value)
    {
        $folder = "/public/question/" . \Carbon\Carbon::now()->isoFormat('Y') . '/' . \Carbon\Carbon::now()->isoFormat('MM');
        
        foreach ($this->new_images as $new_image) {
            $upload = $this->uploadFile($new_image, $folder);
            // $upload[1] is the saved filename (e.g., xxx.webp)
            $this->images[] = 'question/' . \Carbon\Carbon::now()->isoFormat('Y') . '/' . \Carbon\Carbon::now()->isoFormat('MM') . '/' . $upload[1];
        }
        $this->new_images = [];
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function updatedAnswerNewImages($value)
    {
        $folder = "/public/answer/" . \Carbon\Carbon::now()->isoFormat('Y') . '/' . \Carbon\Carbon::now()->isoFormat('MM');
        
        foreach ($this->answer_new_images as $new_image) {
            $upload = $this->uploadFile($new_image, $folder);
            $this->answer_images[] = 'answer/' . \Carbon\Carbon::now()->isoFormat('Y') . '/' . \Carbon\Carbon::now()->isoFormat('MM') . '/' . $upload[1];
        }
        $this->answer_new_images = [];
    }

    public function removeAnswerImage($index)
    {
        if (isset($this->answer_images[$index])) {
            unset($this->answer_images[$index]);
            $this->answer_images = array_values($this->answer_images);
        }
    }
}
