<?php

namespace App\Livewire\Admin\Master\HowToUse;

use App\Helpers\AlertHelper;
use App\Models\HowToUse\HowToUse;
use App\Models\HowToUse\HowToUser;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterHowToUseIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // atau 'tailwind' sesuai UI

    protected $queryString = [
        // 'page' => ['except' => 1],
        'search' => ['except' => ''],
    ];

    public $perPage = 5;
    public $search = '';
    public $data_id;
    public $name;
    public $description;
    public $day;
    public $time;

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
        $this->reset(['data_id', 'name', 'description']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function edit($id)
    {
        $how_to_use = HowToUse::findOrFail($id);
        $this->data_id = $how_to_use->id;
        $this->name = $how_to_use->name;
        $this->description = $how_to_use->description;
        $this->day = $how_to_use->day;
        $this->time = $how_to_use->time;
        $this->openModal();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'day' => 'required|integer|min:0',
            'time' => 'required|integer|min:0',
        ]);

        HowToUse::updateOrCreate(
            ['id' => $this->data_id],
            [
                'name' => $this->name,
                'description' => $this->description ?? null,
                'day' => $this->day,
                'time' => $this->time,
            ]
        );

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Aturan Pakai berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus role ini?', $id);
    }

    public function delete($id)
    {
        $how_to_use = HowToUse::findOrFail($id);
        $how_to_use->delete();

        return AlertHelper::success('Berhasil', 'Role berhasil dihapus.');
    }

    public function render()
    {
        $how_to_use = HowToUse::where('company_id', auth()->user()->company_id)
            ->search($this->search)
            ->orderBy('order', 'asc');

        return view('livewire.admin.master.how-to-use.admin-master-how-to-use-index', [
            'how_to_uses' => $how_to_use->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
