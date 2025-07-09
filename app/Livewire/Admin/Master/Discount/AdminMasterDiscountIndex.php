<?php

namespace App\Livewire\Admin\Master\Discount;

use App\Helpers\AlertHelper;
use App\Models\Discount\Discount;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminMasterDiscountIndex extends Component
{
    use WithPagination;
    public $search = '';
    public $perPage = 5;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $data_id;
    public $name;
    public $description;
    public $start_date;
    public $end_date;
    public $discount_type;
    public $discount_value;

    public function hydrate() {
        $this->resetPage();
    }

    public function openModal() {
        $this->discount_type = 'rupiah'; // Set default discount type
        return $this->dispatch('open-modal',['id'=>'modal']);
    }

    public function closeModal() {
        $this->reset(['data_id', 'name', 'description', 'start_date', 'end_date', 'discount_type', 'discount_value']);
        return $this->dispatch('close-modal',['id'=>'modal']);
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete','Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        $discount = Discount::findOrFail($id[0]);
        if ($discount) {
            $discount->delete();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Data tidak ditemukan.');
        }
    }

    public function edit($id) {
        $discount = Discount::findOrFail($id);
        if ($discount) {
            $this->data_id = $discount->id;
            $this->name = $discount->name;
            $this->description = $discount->description;
            $this->start_date = $discount->start_date;
            $this->end_date = $discount->end_date;
            $this->discount_type = $discount->discount_type;
            $this->discount_value = $discount->discount_value;

            return $this->openModal();
        } else {
            AlertHelper::error('Gagal', 'Data tidak ditemukan.');
        }
    }

    public function updatedDiscountType() {
        $this->discount_value = null; // Reset discount_value when discount_type changes
    }

    public function updatedDiscountValue() {
        if ($this->discount_type === 'percentage') {
            $discount_value = $this->discount_value;
            $this->discount_value = $discount_value > 100 ? 100 : ($discount_value < 0 ? 0 : $discount_value);
        }
    }

    public function save() {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_type' => 'required|in:rupiah,percentage',
            'discount_value' => 'required',
        ]);

        if ($this->data_id) {
            $discount = Discount::findOrFail($this->data_id);
        } else {
            $discount = new Discount();
        }

        $discount->name = $this->name;
        $discount->description = $this->description;
        $discount->start_date = $this->start_date;
        $discount->end_date = $this->end_date;
        $discount->discount_type = $this->discount_type;
        $discount->discount_value = $this->discount_type === 'percentage' ? $this->discount_value : intval(Str::replace('.', '', $this->discount_value));
        $discount->save();

        AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        return $this->closeModal();
    }

    public function render()
    {
        $discounts = Discount::search($this->search)
            ->orderBy('order', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.discount.admin-master-discount-index', [
            'discounts' => $discounts
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
