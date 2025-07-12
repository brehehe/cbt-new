<?php

namespace App\Livewire\Admin\Master\Regulation;

use App\Helpers\AlertHelper;
use App\Models\Master\Regulation\Regulation;
use DB;
use Livewire\Component;
use Log;
use Livewire\WithPagination;

class AdminMasterRegulationIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    public $data_id;
    public $description;
    public $type;

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal-regulation']);
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'type',
            'description',
        ]);
        return $this->dispatch('close-modal', ['id' => 'modal-regulation']);
    }

    public function edit($id)
    {
        $data = Regulation::find($id);
        $this->data_id = $data->id;
        $this->description = $data->description;
        $this->type = $data->type;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = Regulation::find($id[0]);
            $data->delete();
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal dihapus!');
            return Log::info('Gagal Menghapus Data Regulasi : ' . $th);
        }
    }

    public function submit()
    {
        $this->validate([
            'description' => 'required',
            'type' => 'required',
        ]);

        try {
            DB::beginTransaction();
            Regulation::updateOrCreate([
                'id' => $this->data_id,
            ], [
                'type' => $this->type,
                'description' => $this->description,
            ]);
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!');
            return Log::info('Gagal Menyimpan Data Regulasi : ' . $th);
        }
    }

    public function render()
    {
        $regulation = Regulation::query()
            ->when($this->search, function ($query, $search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.regulation.admin-master-regulation-index', [
            'regulations' => $regulation,
        ])
            ->extends('layout.app')
            ->section('content')
        ;
    }
}
