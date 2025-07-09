<?php

namespace App\Livewire\Admin\Master\Product\Detail;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Product\Product;
use App\Models\Product\ProductType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterProductDetailIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function confirmDelete($id)
    {
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

    public function delete($data)
    {
        $itemId = $data['id'];

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

        return redirect()->route('user.master.product.detail.data');
    }

    public function editProduct($id)
    {
        Session::put('product_id', $id);
        return redirect()->route('user.master.product.detail.data');
    }

    public function render()
    {
        $product_type_id = ProductType::select('id')->whereIn('name', ['Tindakan', 'Resep', 'Paket'])->get()->pluck('id')->toArray();

        $products = Product::search($this->search)
            ->whereNotIn('product_type_id', $product_type_id)
            ->select('id', 'sku_number', 'name', 'description', 'company_id', 'product_type_id')
            ->with(['company:id,name', 'productType:id,name'])
            ->orderBy('name', 'asc')
            ->where('company_id', auth()->user()->company_id);

        return view('livewire.admin.master.product.detail.admin-master-product-detail-index', [
            'products' => $products->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
