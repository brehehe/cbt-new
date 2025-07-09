<?php

namespace App\Livewire\Admin\Purchase\MailOrder\Detail;

use App\Helpers\AlertHelper;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Models\Supplier\Supplier;
use App\Traits\Purchase\PurchaseRequisitionTrait;
use App\Traits\Supplier\SupplierTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AdminPurchaseMailOrderDetailIndex extends Component
{
    use PurchaseRequisitionTrait, SupplierTrait;

    public $purchase_requisition_id;

    public $suppliers = [];

    // Supplier
    public $supplier_id;

    public $name_supplier;

    public $email_supplier;

    public $phone_supplier;

    public $address_supplier;

    public $number;
    public $status;

    public function mount()
    {
        $this->purchase_requisition_id = Session::get('purchase_requisition_id');
        $purchaseRequisition = $this->getPurchaseRequisition($this->purchase_requisition_id);
        $this->supplier_id = $purchaseRequisition?->supplier_id;
        $this->number = $purchaseRequisition?->number;
        $this->status = $purchaseRequisition?->purchaseOrder?->status ?? 'draft';
        $this->suppliers = $this->getSuppliers();
    }

    public function openModalSupplier()
    {
        $this->dispatch('open-modal', ['id' => 'modalSupplier']);
    }

    public function closeModalSupplier()
    {
        $this->resetValidation();
        $this->reset(['suppliers']);
        $this->suppliers = $this->getSuppliers();

        $this->dispatch('close-modal', ['id' => 'modalSupplier']);
    }

    public function submitSupplier()
    {
        $this->validate([
            'name_supplier' => 'required',
            'email_supplier' => 'required|email|unique:suppliers,email,NULL,id,company_id,'.Auth::user()->company_id,
            'phone_supplier' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $supplier = Supplier::create(
                [
                    'name' => $this->name_supplier,
                    'email' => $this->email_supplier,
                    'phone' => $this->phone_supplier,
                    'address' => $this->address_supplier,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();

            $this->closeModalSupplier();
            $this->supplier_id = $supplier->id;

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving supplier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function saveSupplier()
    {
        $this->validate([
            'supplier_id' => 'required',
        ]);
        try {
            DB::beginTransaction();

            PurchaseRequisition::find($this->purchase_requisition_id)->update(['supplier_id' => $this->supplier_id]);

            DB::commit();

            return AlertHelper::success('Berhasil', 'Data Supplier berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving supplier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function render()
    {
        return view('livewire.admin.purchase.mail-order.detail.admin-purchase-mail-order-detail-index', [
            'purchaseRequisitionItems' => $this->getPurchaseRequisitionItems($this->purchase_requisition_id),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
