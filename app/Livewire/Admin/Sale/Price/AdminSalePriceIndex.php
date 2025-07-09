<?php

namespace App\Livewire\Admin\Sale\Price;

use App\Helpers\AlertHelper;
use App\Traits\Product\ProductPriceTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSalePriceIndex extends Component
{
    use WithPagination, ProductPriceTrait;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function generate(){
        DB::beginTransaction();
        try {
            $this->generatePrice();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal generate product price: '.$e->getMessage());
            return AlertHelper::error('Gagal','Gagal generate product price', $e->getMessage());
        }
        return AlertHelper::success('Berhasil','Berhasil Generate Harga Jual');
    }

    public function updatePrice(){
        DB::beginTransaction();
        try {
            $this->generateFixedPrice();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal generate product price: '.$e->getMessage());
            return AlertHelper::error('Gagal','Gagal generate product price', $e->getMessage());
        }

        return AlertHelper::success('Berhasil','Berhasil Menyimpan Harga Jual');
    }

    public function confirmUpdatePrice() {
        LivewireAlert::title('Simpan Harga Jual?')
            ->text('Apakah Anda yakin ingin menyimpan harga jual ini?')
            ->withConfirmButton('Simpan', '#1E3A8A')
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
            ->onConfirm('updatePrice')
            ->show();
    }

    public function render()
    {
        return view('livewire.admin.sale.price.admin-sale-price-index',[
            'productPrices' => $this->getProductPrices()->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
