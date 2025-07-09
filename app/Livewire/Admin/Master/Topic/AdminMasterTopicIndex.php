<?php

namespace App\Livewire\Admin\Master\Topic;

use App\Models\Master\Question\Topic;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterTopicIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public function render()
    {
        $topics = Topic::orderBy('order', 'asc');
        return view('livewire.admin.master.topic.admin-master-topic-index', [
            'topics' => $topics->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->perPage();
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['data_id', 'name']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }
}
