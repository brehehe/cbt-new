<?php

namespace App\Livewire\Admin\Master\Product\Category;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Product\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminMasterProductCategoryIndex extends Component
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
    public $normal;
    public $recipe;
    public $price;

    public function edit($id) {
        $productCategory = ProductCategory::findOrFail($id);
        $this->data_id = $productCategory->id;
        $this->name = $productCategory->name;
        $this->description = $productCategory->description;
        $this->normal = $productCategory->normal;
        $this->recipe = $productCategory->recipe;
        $this->price = number_format($productCategory->price, 0, ',', '.');

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
            'normal' => 'required|numeric|min:1|max:100',
            // 'recipe' => 'required|numeric|min:1|max:100',
            'price'=>'nullable'
        ],[
            'name.required' => 'Nama wajib diisi',
            'normal.required' => 'Normal wajib diisi',
            'recipe.required' => 'Recipe wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            $price = intval(Str::replace('.','',$this->price));
            $normal = intval(Str::replace('.','',$this->normal));
            $recipe = intval(Str::replace('.','',$this->recipe));

            ProductCategory::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'normal' => $normal ?? 0,
                    'recipe' => $recipe ?? 0,
                    'price' => $price,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->dispatch('close-modal', ['id' => 'modal']);
            $this->reset(['data_id','name','description','normal','recipe','price']);
            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product category', [
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

            $productCategory = ProductCategory::findOrFail($itemId);
            if ($productCategory) {
                $productCategory->delete();

                DB::commit();
                return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
            }

            DB::rollBack();
            Log::error('Product category not found for deletion', ['id' => $itemId]);
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product category', [
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
        $this->reset(['data_id','name','description','normal','recipe','price']);
        $this->dispatch('close-modal', ['id' => $modalId]);
    }

    public function openModal($modalId)
    {
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function render()
    {
        $products = ProductCategory::search($this->search)
        ->select('id','name','description','normal','recipe','price','company_id')
        ->where('company_id', auth()->user()->company_id)
        ->with('company:id,name');

        return view('livewire.admin.master.product.category.admin-master-product-category-index',[
            'products'=> $products->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
