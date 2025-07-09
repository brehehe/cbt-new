<?php

namespace App\Livewire\Admin\Logistic\GoodCome\Detail;

use App\Helpers\AlertHelper;
use App\Models\SystemSetting\SystemSetting;
use App\Traits\Purchase\PurchaseOrderTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Str;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class AdminLogisticGoodComeDetailIndex extends Component
{
    use PurchaseOrderTrait;

    public $purchase_order_id, $purchase_order_item_id, $purchase_order_item, $quantity_arrival, $hna, $hna_ppn, $price, $sub_total, $getQuantityAccepted, $ppn;
    public $hna_old, $hna_ppn_old, $price_old, $quantity_detail, $ppn_old;

    public $batch_numbers = [];

    public function mount()
    {
        $this->purchase_order_id = Session::get('purchase_order_id');
    }

    public function detail($id)
    {
        $this->purchase_order_item_id = $id;
        $this->purchase_order_item = $this->getPurchaseOrderItem($this->purchase_order_id, $this->purchase_order_item_id);
        $this->hna = number_format($this->purchase_order_item->hna, 0, '', '.');
        $this->hna_old = number_format($this->purchase_order_item->hna, 0, '', '.');
        $this->hna_ppn = number_format($this->purchase_order_item->hna_ppn, 0, '', '.');
        $this->hna_ppn_old = number_format($this->purchase_order_item->hna_ppn, 0, '', '.');
        $this->ppn = number_format($this->purchase_order_item->ppn, 0, '', '.');
        $this->ppn_old = number_format($this->purchase_order_item->ppn, 0, '', '.');
        $this->price = number_format($this->purchase_order_item->price, 0, '', '.');
        $this->price_old = number_format($this->purchase_order_item->price, 0, '', '.');
        $this->sub_total = number_format($this->purchase_order_item->sub_total, 0, '', '.');
        $this->quantity_detail = $this->purchase_order_item->quantity;
        $this->dispatch('open-modal', ['id' => 'modalAccepted']);
    }

    public function closeModal()
    {
        $this->reset(['purchase_order_item_id', 'purchase_order_item', 'batch_numbers', 'quantity_arrival', 'hna', 'hna_ppn', 'price', 'sub_total', 'getQuantityAccepted', 'ppn', 'hna_old', 'hna_ppn_old', 'price_old', 'quantity_detail']);
        $this->dispatch('close-modal', ['id' => 'modalAccepted']);
    }

    public function updatedQuantityArrival()
    {
        $this->quantity_arrival = $this->quantity_arrival ?? 0;
        $this->quantity_arrival = intval($this->quantity_arrival);
        $this->quantity_arrival = $this->quantity_arrival < 0 ? 0 : ($this->quantity_arrival > $this->purchase_order_item->quantity_less ? $this->purchase_order_item->quantity_less : $this->quantity_arrival);

        if ($this->quantity_arrival > 0) {
            $this->addBatchNumber();
        } else {
            $this->reset(['batch_numbers']);
        }
    }

    public function deleteBatchNumber($key)
    {
        unset($this->batch_numbers[$key]);
        $this->batch_numbers = array_values($this->batch_numbers);
        $this->updatedBatchNumbers();
    }

    public function addBatchNumber()
    {
        $this->batch_numbers[] = [
            'expired_date' => date('Y-m-d', strtotime('+360 days')),
            'batch_number' => null,
            'stok' => null,
        ];
    }

    public function updatedBatchNumbers()
    {
        $quantity_arrival = $this->quantity_arrival ? intval(Str::replace('.', '', $this->quantity_arrival)) : 0;

        $groupedBatches = [];
        $totalStok = 0;

        // Step 1: Group batch numbers by 'expired_date' and 'batch_number', while summing up 'stok'
        foreach ($this->batch_numbers as $batch) {
            $expiredDate = $batch['expired_date'];
            $batchNumber = $batch['batch_number'];
            $stok = $batch['stok'] ? $batch['stok'] : 0;

            // Jika total stok sudah melebihi $quantity_arrival, hentikan proses penambahan stok
            if ($totalStok + $stok > $quantity_arrival) {
                $stok = max(0, $quantity_arrival - $totalStok);
            }

            // Jika stok setelah penghitungan lebih dari nol, tambahkan ke groupedBatches
            if ($stok > 0) {
                if (!isset($groupedBatches[$expiredDate][$batchNumber])) {
                    $groupedBatches[$expiredDate][$batchNumber] = [
                        'expired_date' => $expiredDate,
                        'batch_number' => $batchNumber,
                        'stok' => 0,
                    ];
                }

                // Tambahkan stok ke batch yang sesuai
                $groupedBatches[$expiredDate][$batchNumber]['stok'] += $stok;
                $totalStok += $stok;
            }

            // Jika stok sudah mencapai batas $quantity_arrival, hentikan iterasi
            if ($totalStok >= $quantity_arrival) {
                break;
            }
        }

        // Step 2: Flatten groupedBatches into a single array
        $flattenedBatches = [];
        foreach ($groupedBatches as $batchesByDate) {
            foreach ($batchesByDate as $batch) {
                $flattenedBatches[] = $batch;
            }
        }

        // Step 3: Update class properties
        $this->batch_numbers = $flattenedBatches;
        $this->getQuantityAccepted = $totalStok;
    }

    public function updatedHna()
    {
        $this->changePrice();
    }

    public function updatedHnaPpn()
    {
        $this->changePrice();
    }
    public function updatedPrice()
    {
        $this->changePrice();
    }
    public function changePrice()
    {
        $hna = $this->hna ? intval(Str::replace('.', '', $this->hna)) : 0;
        $hna_old = $this->hna_old ? intval(Str::replace('.', '', $this->hna_old)) : 0;
        $ppn = $this->ppn ? intval(Str::replace('.', '', $this->ppn)) : 0;
        $ppn_old = $this->ppn_old ? intval(Str::replace('.', '', $this->ppn_old)) : 0;
        $hna_ppn = $this->hna_ppn ? intval(Str::replace('.', '', $this->hna_ppn)) : 0;
        $hna_ppn_old = $this->hna_ppn_old ? intval(Str::replace('.', '', $this->hna_ppn_old)) : 0;
        $quantity_detail = $this->quantity_detail ? intval(Str::replace('.', '', $this->quantity_detail)) : 0;

        $price = 0;

        $ppn_percentage = SystemSetting::where('company_id', Auth::user()->company_id)->first()->tax ?? 11; // Default to 11 if no tax is set

        if ($hna != $hna_old) {
            $hna_ppn = ($hna * ($ppn_percentage / 100)) + $hna; // Calculate HNA including PPN
            $ppn = $hna * ($ppn_percentage / 100);             // Calculate PPN
            $price = $hna_ppn;                                 // Use HNA including PPN as the price
        } else {
            if ($hna_ppn != $hna_ppn_old) {
                $hna = $hna_ppn / (1 + $ppn_percentage / 100); // Calculate HNA from HNA including PPN
                $ppn = $hna * ($ppn_percentage / 100);         // Calculate PPN
                $price = $hna_ppn;                             // Use HNA including PPN as the price
            }
        }

        $this->hna = number_format($hna, 0, ',', '.');
        $this->hna_old = number_format($hna, 0, ',', '.');
        $this->hna_ppn = number_format($hna_ppn, 0, ',', '.');
        $this->hna_ppn_old = number_format($hna_ppn, 0, ',', '.');
        $this->ppn = number_format($ppn, 0, ',', '.');
        $this->ppn_old = number_format($ppn, 0, ',', '.');
        $this->price = number_format($price, 0, ',', '.');
        $this->price_old = number_format($price, 0, ',', '.');
        $this->sub_total = number_format($price * $quantity_detail, 0, ',', '.');
    }

    public function saveProduct()
    {
        $this->reset(['batch_numbers']);

        $validation = $this->validateInputPurchaseOrder();
        if ($validation !== true) {
            // Jika validasi gagal, return error dan hentikan eksekusi berikutnya
            return $validation;
        }

        $this->hna = $this->hna ? intval(Str::replace('.', '', $this->hna)) : 0;
        $this->hna_ppn = $this->hna_ppn ? intval(Str::replace('.', '', $this->hna_ppn)) : 0;
        $this->price = $this->price ? intval(Str::replace('.', '', $this->price)) : 0;
        $this->sub_total = $this->sub_total ? intval(Str::replace('.', '', $this->sub_total)) : 0;
        $this->quantity_arrival = $this->quantity_arrival ? intval(Str::replace('.', '', $this->quantity_arrival)) : 0;

        DB::beginTransaction();
        try {
            $this->createGoodCome();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product: ' . $e->getMessage());
            return AlertHelper::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        AlertHelper::success('Berhasil', 'Data berhasil disimpan');

        return $this->closeModal();
    }

    public function confirmSave()
    {
        return AlertHelper::confirmSave('save', 'Apakah Anda yakin ingin menyimpan data ini?');
    }

    public function save()
    {
        $purchaseOrder = $this->getPurchaseOrder($this->purchase_order_id);
        $purchaseOrder->status = 'success';
        $purchaseOrder->save();

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan');
    }

    public function render()
    {
        return view(
            'livewire.admin.logistic.good-come.detail.admin-logistic-good-come-detail-index',
            [
                'purchaseOrder' => $this->getPurchaseOrder($this->purchase_order_id)
            ]
        )
            ->extends('layout.app')
            ->section('content');
    }
}
