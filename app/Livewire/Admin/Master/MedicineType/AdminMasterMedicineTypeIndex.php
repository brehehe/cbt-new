<?php

namespace App\Livewire\Admin\Master\MedicineType;

use App\Helpers\AlertHelper;
use App\Models\MedicineType\MedicineType;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterMedicineTypeIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $perPage = 5;
    public $search = '';
    public $data_id;
    public $name;
    public $service_price;
    public $is_single = false;

    public function openModal() {
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal() {
        $this->resetValidation();
        $this->reset(['data_id', 'name', 'service_price', 'is_single']);
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function edit($id) {
        $medicineType = MedicineType::findOrFail($id);
        $this->data_id = $medicineType->id;
        $this->name = $medicineType->name;
        $this->service_price = number_format($medicineType->service_price, 0, ',', '.');
        $this->is_single = $medicineType->is_single;

        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function confirmDelete($id) {
        return AlertHelper::confirmDelete('delete', 'Anda Yakin Menghapus Data Ini?', $id);
    }
    public function delete($id) {
        $medicineType = MedicineType::findOrFail($id[0]);
        $medicineType->delete();
        return AlertHelper::success('Berhasil Menghapus Data', 'Data Berhasil Dihapus');
    }

    public function submit() {
        $this->validate([
            'name' => 'required|string|max:255',
            'service_price' => 'required',
        ]);

        $service_price = intval(str_replace('.', '', $this->service_price));

        MedicineType::updateOrCreate(
            ['id' => $this->data_id],
            [
                'name' => $this->name,
                'service_price' => $service_price,
                'is_single' => $this->is_single,
            ]
        );

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data Berhasil Disimpan');
    }

    public function render()
    {
        $medicineTypes = MedicineType::query()
            ->where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where('name', 'ilike', '%' . $this->search . '%');
            })
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.medicine-type.admin-master-medicine-type-index', [
            'medicineTypes' => $medicineTypes,
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
