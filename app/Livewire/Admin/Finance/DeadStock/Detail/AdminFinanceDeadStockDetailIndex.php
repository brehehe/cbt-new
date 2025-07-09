<?php

namespace App\Livewire\Admin\Finance\DeadStock\Detail;

use Livewire\Component;

class AdminFinanceDeadStockDetailIndex extends Component
{
    public $financeId;
    public $code, $description, $date, $type, $grand_total, $details = [];

    public function mount()
    {
        $this->financeId = session('finance_dead_stock_id', null);

        if ($this->financeId === null) {
            return redirect()->route('user.finance.dead-stock');
        } else {
            $finance = \App\Models\Finance\Finance::find($this->financeId);
            if ($finance === null) {
                return redirect()->route('user.finance.dead-stock');
            }

            $this->code = $finance->code;
            $this->description = $finance->description;
            $this->date = $finance->date ? \Carbon\Carbon::parse($finance->date)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d');
            $this->grand_total = number_format($finance->grand_total, 0, ',', '.');

            $this->getDetails();
        }
    }

    public function getDetails()
    {
        $finance = \App\Models\Finance\Finance::find($this->financeId);
        if ($finance) {
            $this->details = $finance->items()->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product ? $item->product->name : 'Unknown Product',
                    'quantity' => number_format($item->quantity, 0, ',', '.'),
                    'price' => number_format($item->price, 0, ',', '.'),
                    'sub_total' => number_format($item->sub_total, 0, ',', '.'),
                ];
            })->toArray();
        }
    }

    public function render()
    {
        return view('livewire.admin.finance.dead-stock.detail.admin-finance-dead-stock-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
