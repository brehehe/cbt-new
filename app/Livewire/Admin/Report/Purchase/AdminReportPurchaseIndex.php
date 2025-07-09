<?php

namespace App\Livewire\Admin\Report\Purchase;

use App\Models\PurchaseOrder\PurchaseOrder;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportPurchaseIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
    }

    public function resetDates()
    {
        $this->reset(['start_date', 'end_date']);
    }

    public function render()
    {
        $purchaseOrder = PurchaseOrder::where('company_id', auth()->user()->company_id)
            ->when($this->start_date, function ($query, $start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($this->end_date, function ($query, $end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->where('status', 'success')
            ->latest()
            ->orderBy('order', 'desc')
            ->paginate($this->perPage);

        $totalPurchase = PurchaseOrder::where('company_id', auth()->user()->company_id)
            ->when($this->start_date, function ($query, $start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($this->end_date, function ($query, $end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->where('status', 'success')
            ->sum('grand_total_real');

        return view('livewire.admin.report.purchase.admin-report-purchase-index', [
            'purchase_orders' => $purchaseOrder,
            'total_purchase' => $totalPurchase,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function confirmDetail($id)
    {
        Session::put('purchase_order_id', $id);
        return redirect()->route('user.report.purchase.detail');
    }
}
