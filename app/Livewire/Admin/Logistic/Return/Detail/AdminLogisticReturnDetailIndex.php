<?php

namespace App\Livewire\Admin\Logistic\Return\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Product\ProductUnit;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Models\PurchaseRequisition\PurchaseRequisitionItem;
use App\Models\PurchaseReturn\PurchaseReturn;
use App\Models\PurchaseReturn\PurchaseReturnIndex;
use App\Models\Supplier\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminLogisticReturnDetailIndex extends Component
{
    public $status;
    public $return_number;
    public $supplier_id;
    public $suppliers = [];
    public $purchase_order_id;
    public $purchaseOrders = [];
    public $date;
    public $type;
    public $purchase_order_items = [];
    public $description;
    public $purchase_return_id;

    public function mount()
    {
        $purchaseReturnId = Session::get('purchase_return_id');

        if ($purchaseReturnId) {
            $purchaseReturn = PurchaseReturn::find($purchaseReturnId);
            $this->purchase_return_id = $purchaseReturn->id;
            $this->return_number = $purchaseReturn->return_number;
            $this->supplier_id = $purchaseReturn->supplier_id;
            $this->purchase_order_id = $purchaseReturn->purchase_order_id;
            $this->date = $purchaseReturn->date;
            $this->type = $purchaseReturn->type;
            $this->description = $purchaseReturn->description;
            $this->status = $purchaseReturn->status;

            $this->updatedSupplierId();
            $this->updatedPurchaseOrderId();
        } else {
            $this->return_number = 'PR' . date('ymd') . str_pad(PurchaseReturn::whereDate('created_at', Carbon::now())->count() + 1, 4, '0', STR_PAD_LEFT);
            $this->date = date('Y-m-d');
        }
        $this->suppliers = Supplier::select('name','id')->where('company_id', auth()->user()->company_id)->orderBy('name', 'asc')->get()->toArray();
    }

    public function updatedSupplierId()
    {
        if ($this->purchase_return_id == null) {
            $this->purchase_order_id = null;
        }
        $this->purchaseOrders = $this->supplier_id ?
            PurchaseOrder::select('id', 'number')
            ->where('status','success')
            ->where('supplier_id', $this->supplier_id)
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('order', 'asc')
            ->get()
            ->toArray() : [];
    }

    public function updatedPurchaseOrderId()
    {
        $this->reset(['purchase_order_items']);

        $purchase_order_items = $this->purchase_order_id ?
        PurchaseOrderItem::where('purchase_order_id', $this->purchase_order_id)
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('order', 'asc')
            ->get() : [];

        foreach ($purchase_order_items as $key => $value) {
            $purchase_return_index = PurchaseReturnIndex::where('purchase_order_item_id', $value->id)
                ->where('purchase_return_id', $this->purchase_return_id)
                ->where('company_id', auth()->user()->company_id)
                ->first();

            $this->purchase_order_items[] = [
                'id' => $value->id,
                'product_id' => $value->product_id,
                'product_unit_id' => $value->product_unit_id,
                'product_unit_name' => $value->productUnit->unit->name,
                'quantity_real'=> $value->productUnit->quantity ?? 0,
                'quantity' => $value->quantity,
                'quantity_accepted' => $value->quantity_accepted,
                'quantity_real_detail' => $value->productUnit->quantity * $value->quantity_accepted ?? 0,
                'quantity_returned' => $value->quantity_return ?? 0,
                'quantity_return' => $purchase_return_index->quantity ?? 0,
                'product_name' => $value->product->name,
                'product_sku_number' => $value->product->sku_number,
                'price' =>$value->price,
                'sub_total' =>$purchase_return_index->sub_total ?? 0,
            ];
        }
    }

    public function updatedPurchaseOrderItems() {
        foreach ($this->purchase_order_items as $key => $value) {
            $quantity_accepted = $value['quantity_accepted'] ?? 0;
            $quantity_returned = $value['quantity_returned'] ?? 0;
            $quantity_temporary = $quantity_accepted - $quantity_returned;
            $quantity_return = intval($value['quantity_return']) ?? 0;
            $quantity_return = $quantity_temporary < $quantity_return ? $quantity_temporary : ($quantity_return < 0 ? 0 : $quantity_return);

            $price = intval(Str::replace(',', '', $value['price'] ?? 0));

            $this->purchase_order_items[$key]['quantity_return'] = $quantity_return;
            $this->purchase_order_items[$key]['sub_total'] = $quantity_return * $price;
        };
    }

    public function confirmSave($status) {
        return AlertHelper::confirmSave('save','Apakah Anda yakin ingin menyimpan data ini?', $status);
    }

    public function save($status) {
        $this->validate([
            'return_number' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'date' => 'required|date',
            'type' => 'required|string|in:return,exchange',
            'description' => 'required|string|max:1000',
            'purchase_order_items.*.quantity_return' => 'nullable|integer',
        ],[
            'purchase_order_items.*.quantity_return.required' => 'Kolom Jumlah Retur tidak boleh kosong.',
            'purchase_order_items.*.quantity_return.min' => 'Kolom Jumlah Retur harus lebih besar dari 0.',
        ]);

        try {
            DB::beginTransaction();

            $status = $status[0];

            $purchaseReturn = PurchaseReturn::updateOrCreate([
                'id' => $this->purchase_return_id,
            ],[
                'return_number' => $this->return_number,
                'supplier_id' => $this->supplier_id,
                'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()->id,
                'purchase_order_id' => $this->purchase_order_id,
                'date' => $this->date,
                'type' => $this->type,
                'status' => $status,
                'description' => $this->description,
                'company_id' => auth()->user()->company_id,
            ]);

            if($purchaseReturn->status == 'completed') {
                if ($purchaseReturn->type == 'exchange') {
                    $purchaseRequisition = PurchaseRequisition::create([
                        'user_id' => auth()->user()->id,
                        'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()->id,
                        'supplier_id' => $this->supplier_id,
                        'status' => 'draft',
                        'price' => 0,
                        'discount' => 0,
                        'grand_total' => 0,
                        'company_id' => auth()->user()->company_id,
                    ]);
                }
            }

            $total = 0;

            foreach ($this->purchase_order_items as $item) {
                $total += 0;
                if ($item['quantity_return'] > 0) {
                    $purchase_order_items = PurchaseOrderItem::find($item['id']);

                    PurchaseReturnIndex::updateOrCreate([
                        'purchase_return_id' => $purchaseReturn->id,
                        'purchase_order_item_id' => $item['id'],
                    ],[
                        'product_unit_id' => $purchase_order_items->product_unit_id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity_return'],
                        'price' => $item['price'],
                        'hna' => $purchase_order_items->hna,
                        'ppn' => $purchase_order_items->ppn,
                        'hna_ppn' => $purchase_order_items->hna_ppn,
                        'sub_total' => $item['sub_total'],
                        'company_id' => auth()->user()->company_id,
                    ]);

                    $total += $item['sub_total'];

                    if($purchaseReturn->status == 'completed') {
                        if ($purchaseReturn->type == 'exchange') {
                            $productUnit = ProductUnit::find($purchase_order_items->product_unit_id);

                            PurchaseRequisitionItem::create([
                                'purchase_requisition_id' => $purchaseRequisition->id,
                                'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()->id,
                                'company_id' => auth()->user()->company_id,
                                'product_id' => $item['product_id'],
                                'product_name'=> $item['product_name'],
                                'product_unit_id' => $purchase_order_items->product_unit_id,
                                'quantity'=>$productUnit->quantity * $item['quantity_return'],
                                'quantity_detail' => $item['quantity_return'],
                                'product_unit_quantity' => $productUnit->quantity * $item['quantity_return'],
                            ]);
                        }

                        $purchase_order_items->quantity_return = ($purchase_order_items->quantity_return ?? 0) + $item['quantity_return'];
                        $purchase_order_items->save();
                    }
                }
            }

            $purchaseReturn->grand_total = $total;
            $purchaseReturn->price = $total;
            $purchaseReturn->save();

            DB::commit();

            AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
            return redirect()->route('user.purchase.return');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan purchase return: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }

    }

    public function render()
    {
        return view('livewire.admin.logistic.return.detail.admin-logistic-return-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
