<?php

namespace App\Livewire\Admin\Logistic\Return;

use App\Helpers\AlertHelper;
use App\Models\PurchaseReturn\PurchaseReturn;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminLogisticReturnIndex extends Component
{
    use WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function mount() {
        Session::forget('purchase_return_id');
    }

    public function openDetail($id = null)
    {
        if ($id) {
            Session::put('purchase_return_id', $id);
        } else {
            Session::forget('purchase_return_id');
        }
        return redirect()->route('user.purchase.return.detail');
    }

    public function render()
    {
        $return = PurchaseReturn::search(trim($this->search))
            ->with('branch:id,name', 'company:id,name')
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('order', 'desc');

        return view('livewire.admin.logistic.return.admin-logistic-return-index',[
                'returns' => $return->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
