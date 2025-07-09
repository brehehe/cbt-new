<?php

namespace App\Livewire\Admin\Purchase\MailOrder;

use App\Helpers\AlertHelper;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Traits\Purchase\PurchaseRequisitionTrait;
use App\Traits\Supplier\SupplierTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPurchaseMailOrderIndex extends Component
{
    use PurchaseRequisitionTrait, SupplierTrait, WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;

    // Supplier
    public $suppliers;


    public function mount()
    {
        Session::forget('purchase_requisition_id');

        $this->suppliers = $this->getSuppliers();
    }

    public function detail($id)
    {
        Session::put('purchase_requisition_id', $id);

        return redirect()->route('user.purchase.mail-order.detail');
    }

    public function confirmDelete($id) {
        LivewireAlert::title('Batal?')
        ->text('Apakah Anda yakin ingin mengbatalkan Surat Pesanan data ini?')
        ->withConfirmButton('Batal', '#dc3545')
        ->withCancelButton('Batal')
        ->confirmButtonColor('#dc3545')
        ->denyButtonColor('#dc3545')
        ->withOptions([
            'customClass' => [
                'title' => 'text-lg font-bold text-start',
                'content' => 'text-start text-sm',
                'popup' => 'text-left',
            ],
        ])
        ->onConfirm('delete', ['id' => $id])
        ->show();
    }

    public function delete($data) {
        $itemId = $data['id'];

        try {
            DB::beginTransaction();

            $purchaseRequisition = PurchaseRequisition::findOrFail($itemId);
            if ($purchaseRequisition) {
                $purchaseRequisition->status = 'reject';
                $purchaseRequisition->save();

                DB::commit();
                return AlertHelper::success('Berhasil', 'Data berhasil Dibatalkan Surat Pesanan.');
            }

            DB::rollBack();
            Log::error('Product category not found for deletion', ['id' => $itemId]);
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Purchase Requisition', [
                'id' => $itemId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function updatedStartDate($value)
    {
        $this->reset(['end_date']);
    }

    public function resetDates() {
        $this->reset(['start_date', 'end_date']);
    }

    public function render()
    {
        $query = $this->getPurchaseRequisitionPaginates();

        return view('livewire.admin.purchase.mail-order.admin-purchase-mail-order-index', [
            'purchaseRequisitions' => $query,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
