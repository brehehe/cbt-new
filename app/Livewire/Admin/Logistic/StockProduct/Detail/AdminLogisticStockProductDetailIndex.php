<?php

namespace App\Livewire\Admin\Logistic\StockProduct\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Models\StockOpname\HistoryStockOpnameItem;
use App\Models\StockOpname\StockOpname;
use App\Models\StockOpname\StockOpnameItem;
use App\Traits\Product\ProductTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class AdminLogisticStockProductDetailIndex extends Component
{
    use WithPagination, ProductTrait;

    protected $queryString = [
        'pageProduct' => ['except' => 1],
        'searchProduct' => ['except' => ''],
    ];
    public $searchProduct = '', $perPageProduct = 5;
    public $code, $description, $date, $stock_opname_id, $status;
    public $search_sku;
    public $detailOpnames = [];
    public $productOld;

    public function mount() {
        $stockOpname = StockOpname::find(Session::get('stock_opname_id'));

        if ($stockOpname) {
            $this->stock_opname_id = $stockOpname->id;
            $this->code = $stockOpname->code;
            $this->description = $stockOpname->description;
            $this->status = $stockOpname->status;
            $this->date = Carbon::parse($stockOpname->date)->format('Y-m-d');
        } else {
            $this->date = now()->format('Y-m-d');
            $this->code = 'SO'.date('ymd').str_pad(StockOpname::where('created_at', Carbon::now())->count() + 1, 4, '0', STR_PAD_LEFT);
        }

        $this->details();
    }

    public function updatedSearchSku()
    {
        $this->search_sku = ltrim($this->search_sku);

        $this->choiceProductChange();
    }

    public function choiceProductChange()
    {
        $product = Product::where('sku_number', $this->search_sku)->first();

        if ($product) {
            $this->reset('search_sku');

            $productStock = ProductStock::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->first();

            if (!$productStock || $productStock->quantity <= 0) {
                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            }

            $productPrice = ProductPrice::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->where('is_updated', true)
                ->first();

            if (!$productPrice) {
                return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
            }

            if ($this->stock_opname_id) {
                $stockOpnameItem = StockOpnameItem::where('product_id', $product->id)
                    ->where('stock_opname_id', $this->stock_opname_id)
                    ->exists();
                if ($stockOpnameItem) {
                    return AlertHelper::error('Gagal', 'Produk sudah ada di dalam daftar.');
                }

                StockOpnameItem::create([
                    'stock_opname_id' => $this->stock_opname_id,
                    'product_id' => $product->id,
                    'product_expired_date_id' => null, // Ganti dengan ID tanggal kadaluarsa jika ada
                    'quantity' => 0,
                    'quantity_system' => $productStock->quantity,
                    'quantity_difference' => 0,
                    'hpp_average' => $productPrice->hpp_average,
                    'loss_value' => 0,
                    'excess_value' => 0,
                    'description' => null,
                    'company_id' => Auth::user()->company_id, // Ganti dengan ID perusahaan jika ada
                    'branch_id' => Branch::where('company_id', Auth::user()->company_id)->first()->id,
                ]);
            } else {
                $historyStockOpnameItem = HistoryStockOpnameItem::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->exists();

                if ($historyStockOpnameItem) {
                    return AlertHelper::error('Gagal', 'Produk sudah ada di dalam daftar.');
                }

                HistoryStockOpnameItem::create([
                    'product_id' => $product->id,
                    'product_expired_date_id' => null, // Ganti dengan ID tanggal kadaluarsa jika ada
                    'quantity' => 0,
                    'quantity_system' => $productStock->quantity,
                    'quantity_difference' => 0,
                    'hpp_average' => $productPrice->hpp_average,
                    'loss_value' => 0,
                    'excess_value' => 0,
                    'description' => null,
                    'company_id' => Auth::user()->company_id, // Ganti dengan ID perusahaan jika ada
                    'branch_id' => Branch::where('company_id', Auth::user()->company_id)->first()->id,
                ]);
            }

            $this->details();
        } else {
            return AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
        }
    }

    public function details()
    {
        $this->detailOpnames = [];

        if ($this->stock_opname_id) {
            $stockOpnameItems = StockOpnameItem::where('stock_opname_id', $this->stock_opname_id)
                ->get()
                ->toArray();

            foreach ($stockOpnameItems as $stockOpnameItem) {
                $this->detailOpnames[] = [
                    'id' => $stockOpnameItem['id'],
                    'product_id' => $stockOpnameItem['product_id'],
                    'sku_number' => Product::find($stockOpnameItem['product_id'])->sku_number,
                    'product_name' => Product::find($stockOpnameItem['product_id'])->name,
                    'quantity'=> number_format($stockOpnameItem['quantity'],0,',','.'),
                    'quantity_system' => number_format($stockOpnameItem['quantity_system'],0,',','.'),
                    'quantity_difference' => number_format($stockOpnameItem['quantity_difference'],0,',','.'),
                    'hpp_average' => number_format($stockOpnameItem['hpp_average'],0,',','.'),
                    'loss_value' => number_format($stockOpnameItem['loss_value'],0,',','.'),
                    'excess_value' => number_format($stockOpnameItem['excess_value'],0,',','.'),
                    'description' => $stockOpnameItem['description'],
                    'company_name'=>Auth::user()->company->name,
                ];
            }
        } else {
             $historyStockOpnameItems = HistoryStockOpnameItem::whereNull('stock_opname_item_id')->where('company_id', Auth::user()->company_id)
            ->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id)
            ->get()
            ->toArray();

            foreach ($historyStockOpnameItems as $historyStockOpnameItem) {
                $this->detailOpnames[] = [
                    'id' => $historyStockOpnameItem['id'],
                    'product_id' => $historyStockOpnameItem['product_id'],
                    'sku_number' => Product::find($historyStockOpnameItem['product_id'])->sku_number,
                    'product_name' => Product::find($historyStockOpnameItem['product_id'])->name,
                    'quantity'=> number_format($historyStockOpnameItem['quantity'],0,',','.'),
                    'quantity_system' => number_format($historyStockOpnameItem['quantity_system'],0,',','.'),
                    'quantity_difference' => number_format($historyStockOpnameItem['quantity_difference'],0,',','.'),
                    'hpp_average' => number_format($historyStockOpnameItem['hpp_average'],0,',','.'),
                    'loss_value' => number_format($historyStockOpnameItem['loss_value'],0,',','.'),
                    'excess_value' => number_format($historyStockOpnameItem['excess_value'],0,',','.'),
                    'description' => $historyStockOpnameItem['description'],
                    'company_name'=>Auth::user()->company->name,
                ];
            }
        }
    }

    public function updatedDetailOpnames()
    {
        foreach ($this->detailOpnames as $key => $detailOpname) {
            $quantity = intval(str_replace('.', '', $detailOpname['quantity']));
            $quantitySystem = intval(str_replace('.', '', $detailOpname['quantity_system']));
            $quantityDifference = intval(str_replace('.', '', $detailOpname['quantity_difference']));
            $quantityDifference = $quantity - $quantitySystem;
            $hppAverage = intval(str_replace('.', '', $detailOpname['hpp_average']));
            $lossValue = intval(str_replace('.', '', $detailOpname['loss_value']));
            $lossValue = $quantity == 0 ? 0 : ($quantityDifference < 0 ? $quantityDifference * $hppAverage : 0);
            $excessValue = intval(str_replace('.', '', $detailOpname['excess_value']));
            $excessValue = $quantity == 0 ? 0 : ($quantityDifference > 0 ? $quantityDifference * $hppAverage : 0);

            $this->detailOpnames[$key]['quantity'] = number_format($quantity, 0, ',', '.');
            $this->detailOpnames[$key]['quantity_system'] = number_format($quantitySystem, 0, ',', '.');
            $this->detailOpnames[$key]['quantity_difference'] = number_format($quantityDifference, 0, ',', '.');
            $this->detailOpnames[$key]['hpp_average'] = number_format($hppAverage, 0, ',', '.');
            $this->detailOpnames[$key]['loss_value'] = number_format($lossValue, 0, ',', '.');
            $this->detailOpnames[$key]['excess_value'] = number_format($excessValue, 0, ',', '.');

            if ($this->stock_opname_id) {
                StockOpnameItem::where('id', $detailOpname['id'])
                    ->update([
                        'quantity' => $quantity,
                        'quantity_system' => $quantitySystem,
                        'quantity_difference' => $quantityDifference,
                        'hpp_average' => $hppAverage,
                        'loss_value' => $lossValue,
                        'excess_value' => $excessValue,
                    ]);
            } else {
                HistoryStockOpnameItem::where('id', $detailOpname['id'])
                ->update([
                    'quantity' => $quantity,
                    'quantity_system' => $quantitySystem,
                    'quantity_difference' => $quantityDifference,
                    'hpp_average' => $hppAverage,
                    'loss_value' => $lossValue,
                    'excess_value' => $excessValue,
                ]);
            }

        }
    }

    public function updatedSearchProduct()
    {
        $this->resetPage('pageProduct');
    }

    public function updatedPerPageProduct()
    {
        $this->resetPage('pageProduct');
    }

    public function openModal()
    {
        $this->productOld = true;
        $this->dispatch('open-modal', ['id' => 'modalProduct']);
    }

    public function closeModal()
    {
        $this->productOld = false;
        $this->dispatch('close-modal', ['id' => 'modalProduct']);
        $this->reset('searchProduct');
        $this->resetPage('pageProduct');
    }

    public function choiceProduct($product_id) {
        $product = Product::find($product_id);
        $this->search_sku = $product->sku_number;

        $this->choiceProductChange();
        $this->closeModal();
    }


    public function confirmDelete($id) {
        return AlertHelper::confirmDelete('delete', 'Apakah anda yakin ingin menghapus produk ini?', [
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $historyStockOpnameItem = HistoryStockOpnameItem::find($id[0]['id']);

        if ($historyStockOpnameItem) {
            $historyStockOpnameItem->delete();
            AlertHelper::success('Berhasil', 'Produk berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
        }

        $this->details();
    }

    public function confirmSave($type)
    {

        return AlertHelper::confirmSave('save', "Apakah anda yakin ingin menyimpan ".Str::title($type)." Stok Opname?",[
            'type'=>$type,
        ]);
    }

    public function save($data) {
        foreach ($this->detailOpnames as $key => $value) {
            $this->detailOpnames[$key]['quantity'] = intval(Str::replace('.', '', $value['quantity']));
        }

        $this->validate([
            'code' => 'required',
            'date'=>'required',
            'description' => 'required',
            'detailOpnames.*.quantity' => 'required|gt:0',
        ],[
            'detailOpnames.*.quantity.required' => 'Field Quantity harus diisi.',
            'detailOpnames.*.quantity.gt' => 'Field Quantity harus lebih besar dari 0.',
        ]);

        $authUser = Auth::user();
        $branchId = Branch::where('company_id', $authUser->company_id)->first()->id;

        $totalLossValue = $this->stock_opname_id
            ? StockOpnameItem::where('stock_opname_id', $this->stock_opname_id)->sum('loss_value')
            : HistoryStockOpnameItem::whereNull('stock_opname_item_id')
                ->where('company_id', $authUser->company_id)
                ->where('branch_id', $branchId)
                ->sum('loss_value');

        $totalExcessValue = $this->stock_opname_id
            ? StockOpnameItem::where('stock_opname_id', $this->stock_opname_id)->sum('excess_value')
            : HistoryStockOpnameItem::whereNull('stock_opname_item_id')
                ->where('company_id', $authUser->company_id)
                ->where('branch_id', $branchId)
                ->sum('excess_value');

        $dataStock = [
            'code' => $this->code,
            'date' => $this->date,
            'description' => $this->description,
            'total_loss_value' => $totalLossValue,
            'total_excess_value' => $totalExcessValue,
            'user_id' => $authUser->id,
            'status' => $data['type'],
        ];

        if ($this->stock_opname_id) {
            $stockOpname = StockOpname::find($this->stock_opname_id);
            $stockOpname->update($dataStock);

            foreach ($this->detailOpnames as $item) {
                StockOpnameItem::where('id', $item['id'])->update([
                    'quantity' => intval(Str::replace('.', '', $item['quantity'])),
                    'quantity_system' => intval(Str::replace('.', '', $item['quantity_system'])),
                    'quantity_difference' => intval(Str::replace('.', '', $item['quantity_difference'])),
                    'hpp_average' => intval(Str::replace('.', '', $item['hpp_average'])),
                    'loss_value' => intval(Str::replace('.', '', $item['loss_value'])),
                    'excess_value' => intval(Str::replace('.', '', $item['excess_value'])),
                    'description' => $item['description'],
                ]);
            }
        } else {
            $dataStock += [
                'company_id' => $authUser->company_id,
                'branch_id' => $branchId,
            ];

            $stockOpname = StockOpname::create($dataStock);

            foreach ($this->detailOpnames as $item) {
                $newItem = StockOpnameItem::create([
                    'stock_opname_id' => $stockOpname->id,
                    'product_id' => $item['product_id'],
                    'quantity' => intval(Str::replace('.', '', $item['quantity'])),
                    'quantity_system' => intval(Str::replace('.', '', $item['quantity_system'])),
                    'quantity_difference' => intval(Str::replace('.', '', $item['quantity_difference'])),
                    'hpp_average' => intval(Str::replace('.', '', $item['hpp_average'])),
                    'loss_value' => intval(Str::replace('.', '', $item['loss_value'])),
                    'excess_value' => intval(Str::replace('.', '', $item['excess_value'])),
                    'description' => $item['description'],
                    'company_id' => $authUser->company_id,
                    'branch_id' => $branchId,
                ]);

                HistoryStockOpnameItem::where('id', $item['id'])->update([
                    'stock_opname_item_id' => $newItem->id,
                ]);
            }
        }


        session()->flash('saved', [
                'title' => 'Stok Opname Berhasil!',
                'text' => 'Anda berhasil menyimpan Stok Opname!',
            ]);
        return redirect()->route('user.logistic.stock-product');
    }

    public function confirmApprove($type) {
        return AlertHelper::confirmSave('approve', "Apakah anda yakin ingin menyetujui ".Str::title($type)." Stok Opname?",[
            'type'=>$type,
        ]);
    }

    public function approve($data) {
        $stockOpname = StockOpname::find($this->stock_opname_id);
        $stockOpname->update([
            'status' => $data['type'],
            'approved_at'=>Carbon::now(),
            'approved_by' => Auth::user()->id,
        ]);

        if ($data['type'] == 'approved') {
            foreach ($this->detailOpnames as $item) {
                ProductStock::where('product_id', $item['product_id'])
                    ->where('company_id', Auth::user()->company_id)
                    ->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id)
                    ->increment('quantity', intval(Str::replace('.', '', $item['quantity_difference'])));
            }
        }

       session()->flash('saved', [
            'title' => 'Berhasil!',
            'text' => 'Stock Opname Berhasil '.Str::title($data['type']).'!',
        ]);
        return redirect()->route('user.logistic.stock-product');
    }

    public function render()
    {
        return view('livewire.admin.logistic.stock-product.detail.admin-logistic-stock-product-detail-index',[
            'products' => $this->productOld ? $this->getProductPaginates() : [],
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
