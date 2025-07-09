<?php

namespace App\Livewire\Admin\Master\Product\Factory;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Product\ProductFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterProductFactoryIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';

    public $perPage = 5;
    public $data_id;
    public $name;
    public $description;

    public function edit($id) {
        $productFactory = ProductFactory::findOrFail($id);
        $this->data_id = $productFactory->id;
        $this->name = $productFactory->name;
        $this->description = $productFactory->description;

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
            'name' => 'required',
            'description' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            ProductFactory::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->dispatch('close-modal', ['id' => 'modal']);
            $this->reset(['data_id','name','description']);
            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product factory', [
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

            $productFactory = ProductFactory::findOrFail($itemId);
            if ($productFactory) {
                $productFactory->delete();

                DB::commit();
                return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
            }

            DB::rollBack();
            Log::error('Product factory not found for deletion', ['id' => $itemId]);
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product factory', [
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
        $this->reset(['data_id','name','description']);
        $this->dispatch('close-modal', ['id' => $modalId]);
    }

    public function openModal($modalId)
    {
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function render()
    {
        $products = ProductFactory::search($this->search)
        ->select('id','name','description','company_id')
        ->with('company:id,name')
        ->where('company_id', auth()->user()->company_id);

        return view('livewire.admin.master.product.factory.admin-master-product-factory-index',[
            'products'=> $products->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
