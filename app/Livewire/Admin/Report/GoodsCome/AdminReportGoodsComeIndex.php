<?php

namespace App\Livewire\Admin\Report\GoodsCome;

use App\Models\Product\ProductStockHistory;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportGoodsComeIndex extends Component
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
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function resetDates()
    {
        $this->reset(['start_date', 'end_date']);
    }

    public function render()
    {
        $productStockHistorys = ProductStockHistory::search($this->search)
            ->whereNotNull('purchase_order_item_id')
            ->when($this->start_date, function ($query, $start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($this->end_date, function ($query, $end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->where('type', 'in');
        return view('livewire.admin.report.goods-come.admin-report-goods-come-index', [
            'productStockHistorys' => $productStockHistorys->paginate($this->perPage)
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
