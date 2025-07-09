<?php

namespace App\Livewire\Admin\Master\Product\Unit;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use App\Models\Unit\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterProductUnitIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';

    public $perPage = 5;
    public $data_id;
    public $product_id;
    public $unit_id;
    public $quantity;
    public $products = [];
    public $units = [];

    public function mount() {
        $this->products = Product::select('id','name')->get()->toArray();
        $this->units = Unit::select('id','name')->get()->toArray();
    }

    public function edit($id) {
        $productUnit = ProductUnit::findOrFail($id);
        $this->data_id = $productUnit->id;
        $this->product_id = $productUnit->product_id;
        $this->unit_id = $productUnit->unit_id;
        $this->quantity = $productUnit->quantity;

        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when perPage changes
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function submit() {
        $this->validate([
            'product_id' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            ProductUnit::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'product_id' => $this->product_id,
                    'unit_id' => $this->unit_id,
                    'quantity' => $this->quantity,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->dispatch('close-modal', ['id' => 'modal']);
            $this->reset(['data_id','product_id','unit_id','quantity']);
            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product unit', [
                'id' => $this->data_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function confirmDelete($id) {
        LivewireAlert::title('Delete?')
        ->text('Apakah Anda yakin ingin menghapus data ini?')
        ->withConfirmButton('Delete', '#1E3A8A')
        ->withCancelButton('Batal')
        ->confirmButtonColor('#1E3A8A')
        ->denyButtonColor('#dc3545')
        ->withOptions([
            'customClass' => [
                'title' => 'text-lg font-bold text-start',
                'content' => 'text-start text-sm',
                'popup' => 'text-left',
            ],
        ])
        ->onConfirm('delete', ['id' => $id])
        ->show();
    }

    public function delete($data) {
        $itemId = $data['id'];

        try {
            DB::beginTransaction();

            $productRack = ProductUnit::findOrFail($itemId);
            if ($productRack) {
                $productRack->delete();

                DB::commit();
                return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
            }

            DB::rollBack();
            Log::error('Product unit not found for deletion', ['id' => $itemId]);
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product unit', [
                'id' => $itemId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function closeModal($modalId)
    {
        $this->resetValidation();
        $this->reset(['data_id','product_id','unit_id','quantity']);
        $this->dispatch('close-modal', ['id' => $modalId]);
    }

    public function openModal($modalId)
    {
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function render()
    {
        $productUnits = ProductUnit::search($this->search)
        ->select('id','product_id','unit_id','quantity','company_id')
        ->with('company:id,name','product:id,name','unit:id,name')
        ->where('company_id', auth()->user()->company_id);

        return view('livewire.admin.master.product.unit.admin-master-product-unit-index',[
            'productUnits' => $productUnits->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
