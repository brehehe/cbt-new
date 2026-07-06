<?php

namespace App\Livewire\Admin\Master\Study;

use App\Helpers\AlertHelper;
use App\Models\Study\Study;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterStudyIndex extends Component
{
    use WithPagination;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;

    public $data_id;

    public $name;

    public $description;

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function edit($id)
    {
        $study = Study::findOrFail($id);
        $this->data_id = $study->id;
        $this->name = $study->name;
        $this->description = $study->description;
        $this->openModal();
    }

    public function closeModal()
    {
        $this->resetError();
        $this->resetValidation();

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function resetError()
    {
        $this->reset(['name', 'description', 'data_id']);
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            Study::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                ]
            );
            DB::commit();
            $this->closeModal();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan');

            return;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving study data: '.$e->getMessage());
            AlertHelper::error('Error', 'Terjadi kesalahan saat menyimpan data.');

            return;
        }
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda Yakin Ingin Menghapus Data Ini?', $id);
    }

    public function delete($data)
    {
        $data = $data[0];

        try {
            DB::beginTransaction();
            $study = Study::findOrFail($data);
            $study->delete();
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus');

            return;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting study data: '.$e->getMessage());
            AlertHelper::error('Error', 'Terjadi kesalahan saat menghapus data.');

            return;
        }
    }

    public function render()
    {
        $studies = Study::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.study.admin-master-study-index', [
            'studies' => $studies,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
