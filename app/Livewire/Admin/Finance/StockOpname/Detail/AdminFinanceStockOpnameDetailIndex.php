<?php

namespace App\Livewire\Admin\Finance\StockOpname\Detail;

use App\Models\Finance\Finance;
use Livewire\Component;
use Carbon\Carbon;

class AdminFinanceStockOpnameDetailIndex extends Component
{
    public $financeId;
    public $code, $description, $date, $type, $grand_total, $total_excess_value, $total_loss_value, $details = [];

    public function mount()
    {
        $this->financeId = session('finance_stock_opname_id', null);

        if ($this->financeId === null) {
            return redirect()->route('user.finance.stock-opname');
        } else {
            $finance = Finance::find($this->financeId);
            if ($finance === null) {
                return redirect()->route('user.finance.stock-opname');
            }

            $this->code = $finance->code;
            $this->description = $finance->description;
            $this->date = $finance->date ? Carbon::parse($finance->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $this->type = $finance->type;
            $this->grand_total = number_format($finance->grand_total, 0, ',', '.');
            $this->total_excess_value = number_format($finance->total_excess_value, 0, ',', '.');
            $this->total_loss_value = number_format($finance->total_loss_value, 0, ',', '.');

            $this->getDetails();
        }
    }

    public function getDetails()
    {
        $finance = Finance::find($this->financeId);
        if ($finance) {
            $this->details = $finance->items()->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product ? $item->product->name : 'Unknown Product',
                    'quantity_system' => $item->stockOpnameItem ? $item->stockOpnameItem->quantity_system : 0,
                    'quantity_fisik' => $item->stockOpnameItem ? $item->stockOpnameItem->quantity : 0,
                    'quantity' => $item->quantity,
                    'price' => number_format($item->price, 0, ',', '.'),
                    'loss_value' => number_format($item->loss_value, 0, ',', '.'),
                    'excess_value' => number_format($item->excess_value, 0, ',', '.'),
                ];
            })->toArray();
        }
    }

    public function render()
    {
        return view('livewire.admin.finance.stock-opname.detail.admin-finance-stock-opname-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
