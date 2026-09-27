<?php

namespace App\Livewire\Admin\Master\Questionnaire;

use App\Helpers\AlertHelper;
use App\Models\Master\Questionnaire\Questionnaire;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterQuestionnaireIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    public $filter_status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $questionnaire = Questionnaire::withoutGlobalScopes()->find($id);
        if ($questionnaire) {
            $questionnaire->is_active = !$questionnaire->is_active;
            $questionnaire->save();

            $statusText = $questionnaire->is_active ? 'diaktifkan' : 'dinonaktifkan';
            AlertHelper::success('Berhasil', "Kuisioner berhasil {$statusText}");
        }
    }

    public function deleteQuestionnaire($id)
    {
        $questionnaire = Questionnaire::withoutGlobalScopes()->find($id);
        if ($questionnaire) {
            $questionnaire->delete();
            AlertHelper::success('Berhasil', 'Kuisioner berhasil dihapus');
        }
    }

    public function render()
    {
        $query = Questionnaire::withoutGlobalScopes()
            ->withCount(['sections', 'questions', 'responses'])
            ->when($this->search, function ($q) {
                $q->search($this->search);
            })
            ->when($this->filter_status !== '', function ($q) {
                $q->where('is_active', $this->filter_status === '1');
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.master.questionnaire.index', [
            'questionnaires' => $query->paginate($this->perPage),
        ])->extends('layout.app')->section('content');
    }
}
