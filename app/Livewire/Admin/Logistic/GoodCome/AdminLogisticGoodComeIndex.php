<?php

namespace App\Livewire\Admin\Logistic\GoodCome;

use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Traits\Purchase\PurchaseRequisitionTrait;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminLogisticGoodComeIndex extends Component
{
    use PurchaseRequisitionTrait, WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;

    public $purchase_requisition_id;
    public $number;

    public function mount() {
        Session::forget('purchase_order_id');
    }

    public function openCreatePO($id) {
        $this->purchase_requisition_id = $id;
        Session::put('detail', 'createPO');
        $this->dispatch('open-modal', ['id' => 'modalCreatePO']);
    }

    public function closeCreatePO() {
        $this->reset(['purchase_requisition_id','number']);
        $this->dispatch('close-modal', ['id' => 'modalCreatePO']);
    }

    public function createPO() {
        $this->validate([
            'number' => 'required',
        ]);

        $this->createPurchaseOrder($this->purchase_requisition_id, $this->number);

        if (Session::get('detail') == 'detail') {

            Session::forget('detail');
            return $this->detail($this->purchase_requisition_id);
        }

        $this->closeCreatePO();

    }

    public function detail($id) {
        $purchaseRequisition = PurchaseRequisition::find($id);

        if ($purchaseRequisition->purchase_order_id) {
            Session::forget('detail');
            Session::put('purchase_order_id', $purchaseRequisition->purchase_order_id);
            return redirect()->route('user.logistic.good-come.detail');
        }

        Session::put('detail', 'detail');

        $this->purchase_requisition_id = $id;
        $this->dispatch('open-modal', ['id' => 'modalCreatePO']);
    }

    public function render()
    {
        $query = $this->getPurchaseRequisitionWithoutRejectWithSupplierPaginates();
        return view('livewire.admin.logistic.good-come.admin-logistic-good-come-index', [
            'purchaseRequisitions' => $query,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
