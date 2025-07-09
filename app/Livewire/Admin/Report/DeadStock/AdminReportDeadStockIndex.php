<?php

namespace App\Livewire\Admin\Report\DeadStock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeadStock\DeadStock;
use App\Models\ProductStock\ProductStock;
use App\Models\Branch\Branch;
use App\Helpers\AlertHelper;
use Illuminate\Support\Str;

class AdminReportDeadStockIndex extends Component
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
        $deadStocks = DeadStock::search($this->search)
            ->when($this->start_date && $this->end_date, function ($query) {
                $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
            })
            ->latest()
            ->paginate($this->perPage);


        return view('livewire.admin.report.dead-stock.admin-report-dead-stock-index', [
            'deadStocks' => $deadStocks,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
