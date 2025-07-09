<?php

namespace App\Livewire\Admin\Report\Purchase\Detail;

use App\Traits\Purchase\PurchaseOrderTrait;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class AdminReportPurchaseDetailIndex extends Component
{
    use PurchaseOrderTrait;

    public $purchase_order_id, $purchase_order_item_id, $purchase_order_item, $quantity_arrival, $hna, $hna_ppn, $price, $sub_total, $getQuantityAccepted, $ppn;
    public $hna_old, $hna_ppn_old, $price_old, $quantity_detail, $ppn_old;

    public $batch_numbers = [];

    public function mount()
    {
        $this->purchase_order_id = Session::get('purchase_order_id');
    }

    public function render()
    {
        return view('livewire.admin.report.purchase.detail.admin-report-purchase-detail-index',  [
            'purchaseOrder' => $this->getPurchaseOrder($this->purchase_order_id)
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
