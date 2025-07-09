<?php

namespace App\Livewire\Admin\Sale\Report\Detail;

use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AdminSaleReportDetailIndex extends Component
{
    public $transactionId, $transaction;

    public function mount() {
        $transaction_id = Session::get('transaction_id');

        if ($transaction_id) {
            $this->transactionId = $transaction_id;

            $transaction = Transaction::find($this->transactionId);

            if ($transaction) {
                $this->transaction = $transaction;
            } else {
                return redirect()->route('user.sale.report-sale');
            }
        } else {
            return redirect()->route('user.sale.report-sale');
        }
    }

    public function render()
    {
        return view('livewire.admin.sale.report.detail.admin-sale-report-detail-index')
        ->extends('layout.app')
        ->section('content');
    }
}
