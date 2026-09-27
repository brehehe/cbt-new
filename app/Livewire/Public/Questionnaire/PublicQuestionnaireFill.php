<?php

namespace App\Livewire\Public\Questionnaire;

use App\Models\Company\Company;
use App\Models\Master\Questionnaire\Questionnaire;
use App\Models\Master\Questionnaire\QuestionnaireAnswer;
use App\Models\Master\Questionnaire\QuestionnaireResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class PublicQuestionnaireFill extends Component
{
    public $slug;
    public $questionnaire;

    // Respondent identity
    public $is_logged_in = false;
    public $user_id = null;
    public $respondent_name = '';
    public $respondent_nim = '';

    // Answers mapped by safe alphanumeric question keys (e.g. q_01a0e2f5_...)
    public $answers = [];

    // Submission status
    public $isSubmitted = false;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->questionnaire = Questionnaire::withoutGlobalScopes()
            ->where('slug', $slug)
            ->with(['sections.questions'])
            ->firstOrFail();

        // Auto-detect logged-in user
        if (Auth::check()) {
            $user = Auth::user();
            $this->is_logged_in = true;
            $this->user_id = $user->id;
            $this->respondent_name = $user->name;
            $this->respondent_nim = $user->nim ?? $user->username ?? '';
        }

        // Initialize default answers using safe JS-compliant keys
        foreach ($this->questionnaire->sections as $sec) {
            foreach ($sec->questions as $q) {
                $safeKey = 'q_' . str_replace('-', '_', $q->id);
                $this->answers[$safeKey] = null;
            }
        }

        // Auto-recover answers if submitted previously via GET query parameters
        $inputAnswers = Request::input('answers', []);
        if (is_array($inputAnswers)) {
            foreach ($inputAnswers as $rawId => $val) {
                $safeKey = 'q_' . str_replace('-', '_', $rawId);
                if (array_key_exists($safeKey, $this->answers)) {
                    $this->answers[$safeKey] = is_numeric($val) ? (int) $val : $val;
                }
            }
        }
    }

    public function setScore($questionId, $score)
    {
        $safeKey = 'q_' . str_replace('-', '_', $questionId);
        $this->answers[$safeKey] = (int) $score;
    }

    public function submit()
    {
        // 1. Validate respondent info
        $rules = [
            'respondent_name' => 'required|string|min:2|max:255',
        ];

        if ($this->questionnaire->require_nim) {
            $rules['respondent_nim'] = 'required|string|min:3|max:50';
        }

        $customMessages = [
            'respondent_name.required' => 'Nama lengkap wajib diisi.',
            'respondent_name.min' => 'Nama lengkap minimal 2 karakter.',
            'respondent_nim.required' => 'NIM / Nomor Induk Mahasiswa wajib diisi.',
        ];

        // 2. Validate mandatory questions using safe keys
        foreach ($this->questionnaire->sections as $sec) {
            foreach ($sec->questions as $q) {
                $safeKey = 'q_' . str_replace('-', '_', $q->id);
                if ($q->is_required) {
                    $rules["answers.{$safeKey}"] = 'required';
                    $customMessages["answers.{$safeKey}.required"] = "Butir '{$q->question_text}' wajib diisi / dipilih.";
                }
            }
        }

        try {
            $this->validate($rules, $customMessages);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            $this->dispatch('scroll-to-first-error');
            throw $ve;
        }

        try {
            DB::beginTransaction();

            $response = QuestionnaireResponse::create([
                'questionnaire_id' => $this->questionnaire->id,
                'user_id' => $this->user_id,
                'respondent_name' => trim($this->respondent_name),
                'respondent_nim' => trim($this->respondent_nim),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'submitted_at' => now(),
            ]);

            foreach ($this->questionnaire->sections as $sec) {
                foreach ($sec->questions as $q) {
                    $safeKey = 'q_' . str_replace('-', '_', $q->id);
                    $rawAns = $this->answers[$safeKey] ?? null;

                    if ($rawAns !== null && $rawAns !== '') {
                        $isNumericScore = in_array($q->question_type, ['rating_5', 'likert_5']);

                        QuestionnaireAnswer::create([
                            'response_id' => $response->id,
                            'question_id' => $q->id,
                            'score' => $isNumericScore ? (int) $rawAns : null,
                            'text_answer' => !$isNumericScore ? (string) $rawAns : null,
                        ]);
                    }
                }
            }

            DB::commit();
            $this->isSubmitted = true;
            $this->dispatch('clean-url');
            $this->dispatch('scroll-to-top');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('submit_error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function rendering()
    {
        if ($this->getErrorBag()->isNotEmpty()) {
            $this->dispatch('scroll-to-first-error');
        }
    }

    public function fillAgain()
    {
        $this->isSubmitted = false;
        foreach ($this->questionnaire->sections as $sec) {
            foreach ($sec->questions as $q) {
                $safeKey = 'q_' . str_replace('-', '_', $q->id);
                $this->answers[$safeKey] = null;
            }
        }
        $this->dispatch('clean-url');
        $this->dispatch('scroll-to-top');
    }

    public function render()
    {
        $company = Company::first();

        $viewErrorBag = new \Illuminate\Support\ViewErrorBag();
        $viewErrorBag->put('default', $this->getErrorBag());

        return view('livewire.public.questionnaire.fill', [
            'company' => $company,
            'questionnaire' => $this->questionnaire,
            'isSubmitted' => $this->isSubmitted,
            'is_logged_in' => $this->is_logged_in,
            'answers' => $this->answers,
            'respondent_name' => $this->respondent_name,
            'respondent_nim' => $this->respondent_nim,
            'errors' => $viewErrorBag,
        ])->extends('layout.auth.app')->section('content');
    }
}
