<?php

namespace App\Livewire\Admin\Master\Questionnaire;

use App\Exports\QuestionnaireResponseExport;
use App\Helpers\AlertHelper;
use App\Models\Master\Questionnaire\Questionnaire;
use App\Models\Master\Questionnaire\QuestionnaireQuestion;
use App\Models\Master\Questionnaire\QuestionnaireResponse;
use App\Models\Master\Questionnaire\QuestionnaireSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminMasterQuestionnaireDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $slug;
    public $activeTab = 'results'; // 'results', 'manage_questions', 'responses', 'settings'

    // Search for responses tab
    public $responseSearch = '';
    public $responsePerPage = 10;

    // Response detail modal
    public $selectedResponse = null;

    // Question form state
    public $editingQuestionId = null;
    public $questionSectionId = null;
    public $question_text = '';
    public $question_type = 'likert_5'; // rating_5, likert_5, text, input_name
    public $min_label = 'Sangat tidak setuju/tidak pernah';
    public $max_label = 'Sangat setuju/selalu';
    public $is_required = true;
    public $showQuestionModal = false;

    // Section form state
    public $editingSectionId = null;
    public $section_title = '';
    public $section_description = '';
    public $showSectionModal = false;

    // Settings form state
    public $setting_title = '';
    public $setting_slug = '';
    public $setting_description = '';
    public $setting_academic_year = '';
    public $setting_semester = '';
    public $setting_is_active = true;
    public $setting_allow_guest = true;
    public $setting_require_nim = true;

    protected $queryString = [
        'activeTab' => ['except' => 'results'],
        'responseSearch' => ['except' => ''],
    ];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadQuestionnaireSettings();
    }

    public function loadQuestionnaireSettings()
    {
        $q = Questionnaire::withoutGlobalScopes()->where('slug', $this->slug)->firstOrFail();
        $this->setting_title = $q->title;
        $this->setting_slug = $q->slug;
        $this->setting_description = $q->description;
        $this->setting_academic_year = $q->academic_year;
        $this->setting_semester = $q->semester;
        $this->setting_is_active = $q->is_active;
        $this->setting_allow_guest = $q->allow_guest;
        $this->setting_require_nim = $q->require_nim;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // --- SECTION MANAGEMENT ---

    public function openAddSectionModal()
    {
        $this->editingSectionId = null;
        $this->section_title = '';
        $this->section_description = '';
        $this->showSectionModal = true;
    }

    public function openEditSectionModal($sectionId)
    {
        $sec = QuestionnaireSection::findOrFail($sectionId);
        $this->editingSectionId = $sec->id;
        $this->section_title = $sec->title;
        $this->section_description = $sec->description;
        $this->showSectionModal = true;
    }

    public function closeSectionModal()
    {
        $this->showSectionModal = false;
        $this->reset(['editingSectionId', 'section_title', 'section_description']);
    }

    public function saveSection()
    {
        $this->validate([
            'section_title' => 'required|string|max:255',
            'section_description' => 'nullable|string',
        ], [
            'section_title.required' => 'Judul bagian wajib diisi.',
        ]);

        $q = Questionnaire::withoutGlobalScopes()->where('slug', $this->slug)->firstOrFail();

        if ($this->editingSectionId) {
            $sec = QuestionnaireSection::findOrFail($this->editingSectionId);
            $sec->update([
                'title' => $this->section_title,
                'description' => $this->section_description,
            ]);
            AlertHelper::success('Berhasil', 'Bagian kuisioner berhasil diperbarui.');
        } else {
            $maxOrder = QuestionnaireSection::where('questionnaire_id', $q->id)->max('order') ?? 0;
            QuestionnaireSection::create([
                'questionnaire_id' => $q->id,
                'title' => $this->section_title,
                'description' => $this->section_description,
                'order' => $maxOrder + 1,
            ]);
            AlertHelper::success('Berhasil', 'Bagian baru berhasil ditambahkan.');
        }

        $this->closeSectionModal();
    }

    public function deleteSection($sectionId)
    {
        $sec = QuestionnaireSection::findOrFail($sectionId);
        $sec->delete();
        AlertHelper::success('Berhasil', 'Bagian dan pertanyaan di dalamnya berhasil dihapus.');
    }

    // --- QUESTION MANAGEMENT ---

    public function openAddQuestionModal($sectionId)
    {
        $this->editingQuestionId = null;
        $this->questionSectionId = $sectionId;
        $this->question_text = '';
        $this->question_type = 'likert_5';
        $this->min_label = 'Sangat tidak setuju/tidak pernah';
        $this->max_label = 'Sangat setuju/selalu';
        $this->is_required = true;
        $this->showQuestionModal = true;
    }

    public function openEditQuestionModal($questionId)
    {
        $q = QuestionnaireQuestion::findOrFail($questionId);
        $this->editingQuestionId = $q->id;
        $this->questionSectionId = $q->section_id;
        $this->question_text = $q->question_text;
        $this->question_type = $q->question_type;
        $this->min_label = $q->min_label;
        $this->max_label = $q->max_label;
        $this->is_required = $q->is_required;
        $this->showQuestionModal = true;
    }

    public function updatedQuestionType($type)
    {
        if ($type === 'likert_5') {
            $this->min_label = 'Sangat tidak setuju/tidak pernah';
            $this->max_label = 'Sangat setuju/selalu';
        } elseif ($type === 'rating_5') {
            $this->min_label = 'Sangat buruk';
            $this->max_label = 'Sangat baik';
        } else {
            $this->min_label = null;
            $this->max_label = null;
        }
    }

    public function closeQuestionModal()
    {
        $this->showQuestionModal = false;
        $this->reset([
            'editingQuestionId', 'questionSectionId', 'question_text',
            'question_type', 'min_label', 'max_label', 'is_required',
        ]);
    }

    public function saveQuestion()
    {
        $this->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:rating_5,likert_5,text,input_name',
            'min_label' => 'nullable|string|max:100',
            'max_label' => 'nullable|string|max:100',
            'is_required' => 'boolean',
        ], [
            'question_text.required' => 'Teks pertanyaan wajib diisi.',
        ]);

        if ($this->editingQuestionId) {
            $q = QuestionnaireQuestion::findOrFail($this->editingQuestionId);
            $q->update([
                'question_text' => $this->question_text,
                'question_type' => $this->question_type,
                'min_label' => $this->min_label,
                'max_label' => $this->max_label,
                'is_required' => $this->is_required,
            ]);
            AlertHelper::success('Berhasil', 'Pertanyaan berhasil diperbarui.');
        } else {
            $maxOrder = QuestionnaireQuestion::where('section_id', $this->questionSectionId)->max('order') ?? 0;
            QuestionnaireQuestion::create([
                'section_id' => $this->questionSectionId,
                'question_text' => $this->question_text,
                'question_type' => $this->question_type,
                'min_label' => $this->min_label,
                'max_label' => $this->max_label,
                'is_required' => $this->is_required,
                'order' => $maxOrder + 1,
            ]);
            AlertHelper::success('Berhasil', 'Pertanyaan baru berhasil ditambahkan.');
        }

        $this->closeQuestionModal();
    }

    public function deleteQuestion($questionId)
    {
        $q = QuestionnaireQuestion::findOrFail($questionId);
        $q->delete();
        AlertHelper::success('Berhasil', 'Pertanyaan berhasil dihapus.');
    }

    public function moveQuestion($questionId, $direction)
    {
        $q = QuestionnaireQuestion::findOrFail($questionId);
        $swapQ = QuestionnaireQuestion::where('section_id', $q->section_id)
            ->where('order', $direction === 'up' ? '<' : '>', $q->order)
            ->orderBy('order', $direction === 'up' ? 'desc' : 'asc')
            ->first();

        if ($swapQ) {
            $currentOrder = $q->order;
            $q->order = $swapQ->order;
            $swapQ->order = $currentOrder;
            $q->save();
            $swapQ->save();
        }
    }

    // --- RESPONSE DETAIL MODAL ---

    public function viewResponseDetail($responseId)
    {
        $this->selectedResponse = QuestionnaireResponse::with(['answers.question.section', 'user'])
            ->find($responseId);
    }

    public function closeResponseDetail()
    {
        $this->selectedResponse = null;
    }

    public function deleteResponse($responseId)
    {
        $resp = QuestionnaireResponse::findOrFail($responseId);
        $resp->delete();
        AlertHelper::success('Berhasil', 'Respon berhasil dihapus.');
        if ($this->selectedResponse && $this->selectedResponse->id === $responseId) {
            $this->selectedResponse = null;
        }
    }

    // --- SETTINGS UPDATE ---

    public function saveSettings()
    {
        $q = Questionnaire::withoutGlobalScopes()->where('slug', $this->slug)->firstOrFail();

        $this->validate([
            'setting_title' => 'required|string|max:255',
            'setting_slug' => "required|string|max:255|unique:questionnaires,slug,{$q->id}|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/",
            'setting_description' => 'nullable|string',
            'setting_academic_year' => 'nullable|string|max:50',
            'setting_semester' => 'nullable|string|max:50',
            'setting_is_active' => 'boolean',
            'setting_allow_guest' => 'boolean',
            'setting_require_nim' => 'boolean',
        ], [
            'setting_title.required' => 'Judul kuisioner wajib diisi.',
            'setting_slug.required' => 'Slug URL wajib diisi.',
            'setting_slug.unique' => 'Slug sudah digunakan oleh kuisioner lain.',
            'setting_slug.regex' => 'Format slug harus berupa huruf kecil, angka, dan strip.',
        ]);

        $oldSlug = $q->slug;
        $q->update([
            'title' => $this->setting_title,
            'slug' => $this->setting_slug,
            'description' => $this->setting_description,
            'academic_year' => $this->setting_academic_year,
            'semester' => $this->setting_semester,
            'is_active' => $this->setting_is_active,
            'allow_guest' => $this->setting_allow_guest,
            'require_nim' => $this->setting_require_nim,
        ]);

        AlertHelper::success('Berhasil', 'Pengaturan kuisioner berhasil diperbarui.');

        if ($oldSlug !== $this->setting_slug) {
            return redirect()->route('admin.master.kuisioner.detail', ['slug' => $this->setting_slug]);
        }
    }

    // --- EXCEL EXPORT ---

    public function exportExcel()
    {
        $q = Questionnaire::withoutGlobalScopes()->where('slug', $this->slug)->firstOrFail();
        $filename = 'Hasil-Kuisioner-' . Str::slug($q->title) . '-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new QuestionnaireResponseExport($q->id), $filename);
    }

    public function render()
    {
        $questionnaire = Questionnaire::withoutGlobalScopes()
            ->where('slug', $this->slug)
            ->with([
                'sections.questions.answers',
                'responses',
            ])
            ->firstOrFail();

        // Calculate analytics per question
        $analytics = [];
        $totalResponses = $questionnaire->responses->count();

        foreach ($questionnaire->sections as $sec) {
            foreach ($sec->questions as $q) {
                if (in_array($q->question_type, ['rating_5', 'likert_5'])) {
                    $scores = $q->answers->pluck('score')->filter()->values();
                    $avg = $scores->count() > 0 ? round($scores->avg(), 2) : 0;
                    $distribution = [
                        1 => $scores->filter(fn($s) => $s == 1)->count(),
                        2 => $scores->filter(fn($s) => $s == 2)->count(),
                        3 => $scores->filter(fn($s) => $s == 3)->count(),
                        4 => $scores->filter(fn($s) => $s == 4)->count(),
                        5 => $scores->filter(fn($s) => $s == 5)->count(),
                    ];
                    $analytics[$q->id] = [
                        'average' => $avg,
                        'count' => $scores->count(),
                        'distribution' => $distribution,
                    ];
                }
            }
        }

        // Responses pagination
        $responses = QuestionnaireResponse::where('questionnaire_id', $questionnaire->id)
            ->when($this->responseSearch, function ($query) {
                $s = '%' . $this->responseSearch . '%';
                $query->where(function ($sub) use ($s) {
                    $sub->where('respondent_name', 'ilike', $s)
                        ->orWhere('respondent_nim', 'ilike', $s);
                });
            })
            ->with(['user', 'answers'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($this->responsePerPage);

        return view('livewire.admin.master.questionnaire.detail', [
            'questionnaire' => $questionnaire,
            'analytics' => $analytics,
            'totalResponses' => $totalResponses,
            'responses' => $responses,
            'activeTab' => $this->activeTab,
            'responseSearch' => $this->responseSearch,
            'selectedResponse' => $this->selectedResponse,
            'showQuestionModal' => $this->showQuestionModal,
            'editingQuestionId' => $this->editingQuestionId,
            'showSectionModal' => $this->showSectionModal,
            'editingSectionId' => $this->editingSectionId,
        ])->extends('layout.app')->section('content');
    }
}
