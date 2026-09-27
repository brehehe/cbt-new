<?php

namespace App\Livewire\Admin\Master\Questionnaire;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Master\Questionnaire\Questionnaire;
use App\Models\Master\Questionnaire\QuestionnaireQuestion;
use App\Models\Master\Questionnaire\QuestionnaireSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class AdminMasterQuestionnaireCreate extends Component
{
    public $title = '';
    public $slug = '';
    public $description = '';
    public $academic_year = '2025/2026';
    public $semester = 'Ganjil';
    public $is_active = true;
    public $allow_guest = true;
    public $require_nim = true;
    public $use_template = true; // Use standard medical evaluation template by default

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:questionnaires,slug|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        'description' => 'nullable|string',
        'academic_year' => 'nullable|string|max:50',
        'semester' => 'nullable|string|max:50',
        'is_active' => 'boolean',
        'allow_guest' => 'boolean',
        'require_nim' => 'boolean',
    ];

    protected $messages = [
        'title.required' => 'Judul kuisioner wajib diisi.',
        'slug.required' => 'Slug URL wajib diisi.',
        'slug.unique' => 'Slug sudah digunakan oleh kuisioner lain. Silakan ubah.',
        'slug.regex' => 'Format slug harus berupa huruf kecil, angka, dan strip (contoh: evaluasi-modul-2026).',
    ];

    public function updatedTitle($value)
    {
        if (empty($this->slug) || Str::slug($this->title) === $this->slug) {
            $baseSlug = Str::slug($value);
            $slug = $baseSlug;
            $counter = 1;
            while (Questionnaire::withoutGlobalScopes()->where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $this->slug = $slug;
        }
    }

    public function generateSlug()
    {
        $baseSlug = Str::slug($this->title ?: 'kuisioner-baru');
        $slug = $baseSlug;
        $counter = 1;
        while (Questionnaire::withoutGlobalScopes()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $this->slug = $slug;
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $company = Company::first();
            $companyId = Auth::user()->company_id ?? $company?->id;

            $questionnaire = Questionnaire::create([
                'company_id' => $companyId,
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->description,
                'academic_year' => $this->academic_year,
                'semester' => $this->semester,
                'is_active' => $this->is_active,
                'allow_guest' => $this->allow_guest,
                'require_nim' => $this->require_nim,
                'created_by' => Auth::id(),
            ]);

            // If user checked template, clone template questions
            if ($this->use_template) {
                $template = Questionnaire::withoutGlobalScopes()
                    ->where('slug', 'evaluasi-modul-mahasiswa')
                    ->with(['sections.questions'])
                    ->first();

                if ($template) {
                    foreach ($template->sections as $sec) {
                        $newSec = QuestionnaireSection::create([
                            'questionnaire_id' => $questionnaire->id,
                            'title' => $sec->title,
                            'description' => $sec->description,
                            'order' => $sec->order,
                        ]);

                        foreach ($sec->questions as $q) {
                            QuestionnaireQuestion::create([
                                'section_id' => $newSec->id,
                                'question_text' => $q->question_text,
                                'question_type' => $q->question_type,
                                'min_label' => $q->min_label,
                                'max_label' => $q->max_label,
                                'is_required' => $q->is_required,
                                'order' => $q->order,
                            ]);
                        }
                    }
                } else {
                    // Create default section
                    QuestionnaireSection::create([
                        'questionnaire_id' => $questionnaire->id,
                        'title' => 'Bagian 1: Evaluasi Umum',
                        'description' => 'Isikan penilaian Anda di bawah ini.',
                        'order' => 1,
                    ]);
                }
            } else {
                // Create single default empty section
                QuestionnaireSection::create([
                    'questionnaire_id' => $questionnaire->id,
                    'title' => 'Bagian 1: Evaluasi Umum',
                    'description' => 'Silakan tambahkan butir pertanyaan pada bagian ini.',
                    'order' => 1,
                ]);
            }

            DB::commit();

            session()->flash('saved', [
                'title' => 'Kuisioner Berhasil Dibuat!',
                'text' => 'Anda sekarang dapat mengelola pertanyaan dan memantau hasil pengisian.',
            ]);

            return redirect()->route('admin.master.kuisioner.detail', ['slug' => $questionnaire->slug]);
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.master.questionnaire.create')
            ->extends('layout.app')
            ->section('content');
    }
}
