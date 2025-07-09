<?php

namespace App\Livewire\Admin\Master\Icd;

use App\Models\Icd\Icd10;
use App\Models\Icd\Icd9;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterIcdIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $perPage = 5;
    public $search = '';
    public $tab;

    public function mount() {
        $this->changeTab('icd-10');
    }

    public function changeTab($tab) {
        $this->reset('search');
        $this->resetPage();
        $this->tab = $tab;
    }

    public function render()
    {
        if ($this->tab === 'icd-10') {
            $querys = Icd10::search($this->search)
                ->orderBy('code', 'asc');
        } elseif ($this->tab === 'icd-9') {
            $querys = Icd9::search($this->search)
                ->orderBy('code', 'asc');
        }

        return view('livewire.admin.master.icd.admin-master-icd-index', [
            'querys' => $querys->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
