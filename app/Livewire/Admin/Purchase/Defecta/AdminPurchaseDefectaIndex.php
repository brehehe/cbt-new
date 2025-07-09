<?php

namespace App\Livewire\Admin\Purchase\Defecta;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Defecta\Defecta;
use App\Models\Product\ProductUnit;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Models\PurchaseRequisition\PurchaseRequisitionItem;
use App\Services\Defecta\DefectaService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPurchaseDefectaIndex extends Component
{
    use WithPagination;

    protected $queryString = [
        'search' => ['except' => ''],
        // 'page' => ['except' => 1],
    ];

    public $perPage = 10;
    public $search = '';

    public $selectAll = false;
    public $selected = [];

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = Defecta::query()
                ->select('id')
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->where('status','new');

            $this->selected = $query->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $query = Defecta::query()
                ->select('id')
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->where('status','new')
                ->count();

        $this->selectAll = count($this->selected) === $query;
    }

    public function save() {
        if (count($this->selected) === 0) {
            AlertHelper::error('Gagal', 'Pilih data yang ingin disubmit terlebih dahulu');
            return;
        }

        foreach ($this->selected as $key => $value) {
            $defecta = Defecta::find($value);
            $purchaseRequisitionItem = PurchaseRequisitionItem::where('product_id', $defecta->product_id)
            ->where('company_id',Auth::user()->company_id)
            ->where('status', 'draft')
            ->first();

            if ($purchaseRequisitionItem) {
                $purchaseRequisitionItem->quantity = $purchaseRequisitionItem->quantity + $defecta->minimum_stock;
                $productUnit = $purchaseRequisitionItem->product_unit_id ? ProductUnit::find($purchaseRequisitionItem->product_unit_id) : null;

                $quantityProdukUnit = $productUnit ? $productUnit->quantity : 0;
                $quantityProdukPurchaseRequisitionItem = $purchaseRequisitionItem->quantity;

                // Fix: Add validation to prevent division by zero
                if ($quantityProdukUnit > 0) {
                    $quantity_detail = ceil($quantityProdukPurchaseRequisitionItem / $quantityProdukUnit);
                    $quantity_real = $quantity_detail * $quantityProdukUnit;
                } else {
                    // Handle the case when product unit quantity is 0 or null
                    $quantity_detail = $quantityProdukPurchaseRequisitionItem; // or 1, depending on your business logic
                    $quantity_real = $quantityProdukPurchaseRequisitionItem;
                }

                $purchaseRequisitionItem->product_unit_id = $purchaseRequisitionItem->product_unit_id;
                $purchaseRequisitionItem->quantity_detail = $quantity_detail;
                $purchaseRequisitionItem->quantity_real = $quantity_real;
                $purchaseRequisitionItem->save();
            } else {
                PurchaseRequisitionItem::create([
                    'product_id' => $defecta->product_id,
                    'branch_id' => $defecta->branch_id,
                    'company_id' => $defecta->company_id,
                    'product_name' => $defecta->product->name,
                    'unit_id' => $defecta->product->unit_id,
                    'product_unit_id' => null,
                    'quantity' => $defecta->minimum_stock,
                    'quantity_detail' => 0,
                    'quantity_real' => 0,
                ]);
            }

            $defecta->status = 'submitted';
            $defecta->save();
        }


        AlertHelper::success('Berhasil', 'Data berhasil Disimpan');
    }

    public function confirmSave()
    {
        if (count($this->selected) === 0) {
            AlertHelper::error('Gagal', 'Pilih data yang ingin diproses terlebih dahulu');
            return;
        }

        return AlertHelper::confirmSave('save','Yakin ingin mengirim data ini?');
    }

    public function confirmReject()
    {
        if (count($this->selected) === 0) {
            AlertHelper::error('Gagal', 'Pilih data yang ingin diproses terlebih dahulu');
            return;
        }

        return AlertHelper::confirmDelete('reject','Yakin ingin menolak data ini?');
    }

    public function reject()
    {
        foreach ($this->selected as $key => $value) {
            $defecta = Defecta::find($value);
            $defecta->status = 'rejected';
            $defecta->save();
        }
    }

    public function render()
    {
        $defecta = Defecta::search(trim($this->search))
        ->where('company_id', auth()->user()->company_id)
        ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
        ->where('status','new')
        ->with(['product', 'branch'])
        ->orderBy('order', 'desc');

        return view('livewire.admin.purchase.defecta.admin-purchase-defecta-index', [
            'defectas' => $defecta->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
