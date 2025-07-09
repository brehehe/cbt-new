<?php

namespace App\Livewire\Admin\Master\Recipe;

use App\Helpers\AlertHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductType;
use DB;
use Livewire\Component;
use LivewireAlert;
use Log;
use Session;
use Livewire\WithPagination;

class AdminMasterRecipeIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function mount()
    {
        Session::forget('product_id');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($data)
    {
        $itemId = $data[0];

        try {
            DB::beginTransaction();

            $productFactory = Product::findOrFail($itemId);
            if ($productFactory) {
                $productFactory->delete();

                DB::commit();
                return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
            }

            DB::rollBack();
            Log::error('Product not found for deletion', ['id' => $itemId]);
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product', [
                'id' => $itemId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function createProduct()
    {
        Session::forget('product_id');
        return redirect()->route('user.master.recipe.detail');
    }

    public function editProduct($id)
    {
        Session::put('product_id', $id);
        return redirect()->route('user.master.recipe.detail');
    }

    public function render()
    {
        $product_type_id = ProductType::select('id')->where('name', 'Resep')->first()->id;

        $products = Product::search($this->search)
            ->whereIn('product_type_id', [$product_type_id])
            ->select('id', 'sku_number', 'name', 'description', 'company_id', 'product_type_id')
            ->with(['company:id,name', 'productType:id,name'])
            ->orderBy('name', 'asc')
            ->where('company_id', auth()->user()->company_id);

        return view('livewire.admin.master.recipe.admin-master-recipe-index', [
            'products' => $products->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
