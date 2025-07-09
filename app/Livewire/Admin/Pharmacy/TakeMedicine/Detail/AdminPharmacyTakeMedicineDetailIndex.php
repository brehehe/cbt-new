<?php

namespace App\Livewire\Admin\Pharmacy\TakeMedicine\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Encounter\Encounter;
use App\Models\Location\Location;
use App\Models\Medication\Medication;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\MedicineType\MedicineType;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;
use App\Models\Product\Product;
use App\Models\Product\ProductPackage;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductType;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionProduct;
use App\Models\Transaction\TransactionRecipe;
use App\service\apiservice;
use App\Services\Product\ProductService;
use Auth;
use Cache;
use DB;
use Illuminate\Console\View\Components\Alert;
use Livewire\Component;
use Log;
use Str;

class AdminPharmacyTakeMedicineDetailIndex extends Component
{
    public $transaction_id;
    public $transaction;
    public $recipes = [];
    public $medicines = [];
    public $is_outside_pharmacy = false;
    public $medicine_types = [];
    public $supporting_products = [];
    public $product_types = [];

    public function mount()
    {
        $this->transaction_id = session('transaction_id');
        if (!$this->transaction_id) {
            return redirect()->route('user.pharmacy.take-medicine.index');
        }

        $this->transaction = Transaction::find($this->transaction_id);

        if (!$this->transaction) {
            return redirect()->route('user.pharmacy.take-medicine.index');
        }

        $this->is_outside_pharmacy = $this->transaction->is_outside_pharmacy;

        $this->product_types = Cache::remember('product_types_tindakan_paket', 3600, function () {
            return ProductType::whereIn('name', ['Tindakan', 'Paket'])->pluck('id')->toArray();
        });

        $this->medicine_types = MedicineType::select('id', 'name')
            ->where('company_id', Auth::user()->company_id)
            ->get()
            ->toArray();

        $this->supporting_products = Product::where('company_id', Auth::user()->company_id)
            ->whereHas('productType', function ($query) {
                $query->where('name', 'Produk Pendukung'); // atau 'Supporting Product' sesuai isi database
            })
            ->whereHas('productPrice', function ($query) {
                $query->where('price', '>', 0)
                    ->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id);
            })
            ->whereHas('productStock', function ($query) {
                $query->where('quantity', '>', 0)
                    ->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id); // atau 'Supporting Product' sesuai isi database
            })
            ->select('id', 'name')
            ->with('productPrice:id,product_id,price,recipe', 'productStock:id,product_id,quantity')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        $this->getRecipes();
        $this->getMedicines();
    }

    public function getRecipes()
    {
        $this->recipes = [];

        $transactionDetails = $this->is_outside_pharmacy ? [] : TransactionRecipe::where('transaction_id', $this->transaction_id)
            ->orderBy('order', 'asc')
            ->get();

        foreach ($transactionDetails as $key => $transactionDetail) {
            $medicine_type = MedicineType::find($transactionDetail->medicine_type_id);
            $this->recipes[] = [
                'id' => $transactionDetail->id,
                'medicine_type_id' => $transactionDetail->medicine_type_id,
                'medicine_type_name' => $medicine_type ? $medicine_type->name : null,
                'is_single' => $medicine_type ? $medicine_type->is_single : false,
                'numero_recipe' => $transactionDetail->numero_recipe ?? null,
                'price_service_one' => number_format($medicine_type ? $medicine_type->service_price : 0, 0, ',', '.'),
                'product_id' => $transactionDetail->product_id,
                'product_name' => $transactionDetail->product->name ?? '',
                'quantity' => $transactionDetail->quantity,
                'price' => number_format($transactionDetail->price, 0, ',', '.'),
                'sub_total_price' => number_format($transactionDetail->sub_total_price, 0, ',', '.'),
                'description' => $transactionDetail->description,
            ];

            foreach ($transactionDetail->transactionDetail as $detail) {
                $this->recipes[$key]['details'][] = [
                    'id' => $detail->id,
                    'product_id' => $detail->product_id,
                    'product_name' => $detail->product->name,
                    'type' => $detail->type,
                    'dosage_doctor' => $detail->dosage_doctor,
                    'doctor_dosage_gram' => $detail->doctor_dosage_gram,
                    'dosage_drug' => $detail->dosage_drug ?? $detail->product->medicine_dosage,
                    'quantity_real' => $detail->quantity_real,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'sub_total_price' => $detail->sub_total_price,
                ];
            }
        }
    }

    public function getMedicines()
    {
        $this->reset(['medicines']);

        $medicines = $this->is_outside_pharmacy ? [] : TransactionDetail::where('transaction_id', $this->transaction_id)
            ->where('type_transaction', 'medicine')
            ->with('product:id,sku_number,name,description,company_id')
            ->orderBy('order', 'asc')
            ->get();

        foreach ($medicines as $medicine) {
            $this->medicines[] = [
                'id' => $medicine->id,
                'product_id' => $medicine->product_id,
                'product_name' => $medicine->product?->name,
                'quantity' => $medicine->quantity,
                'price' => $medicine->price,
                'sub_total_price' => $medicine->sub_total_price,
            ];
        }
    }

    public function confirmSave()
    {
        return AlertHelper::confirmSave('save', 'Apakah Anda Yakin Mengkonfirmasi Pengambilan Obat?');
    }

    public function save()
    {
        $transaction = Transaction::find($this->transaction_id);
        if (!$transaction) {
            return;
        }

        $productService = new ProductService();
        $companyId = Auth::user()->company_id;
        $branch = Branch::where('company_id', $companyId)->firstOrFail();

        try {
            DB::beginTransaction();

            $transactionRecipes = TransactionRecipe::where('transaction_id', $this->transaction_id)->get();
            foreach ($transactionRecipes as $recipe) {
                $this->processRecipeLevel($transaction, $recipe, $productService, $companyId, $branch->id);
            }

            $transactionDetailRecipes = TransactionDetail::where('transaction_id', $this->transaction_id)->where('type_transaction', 'recipe')->get();
            foreach ($transactionDetailRecipes as $transactionDetailRecipe) {
                $this->processDetailLevel($transaction, $transactionDetailRecipe, $productService, $companyId, $branch->id);
            }

            $transactionDetails = TransactionDetail::where('transaction_id', $this->transaction_id)
                ->where('type_transaction', 'medicine')
                ->get();

            foreach ($transactionDetails as $detail) {
                $this->processDetailLevel($transaction, $detail, $productService, $companyId, $branch->id);
            }

            if ($transaction->consultation == 'yes') {
                // $encounter = Encounter::where('transaction_id', $transaction->id)->first();

                // $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
                // $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();

                // $data = [
                //     'id' => $encounter->id ?? null,
                //     'transaction_id' => $transaction->id,
                //     'company_id' => $transaction->company_id,
                //     'location_id' => $transaction->location_id,
                //     'patient_id' => $patient->id ?? null,
                //     'practitioner_id' => $doctor->id ?? null,
                //     'type' => 'outpatient',
                //     'status' => 'finished',
                //     'class_code' => 'AMB'
                // ];

                // app(apiservice::class)->createTransaction($data);
                $this->updateServiceTransactionRecipe($transaction);
            }


            $transaction->update([
                'status' => 'completed',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error saving take medicine: ' . $e->getMessage());
            return AlertHelper::error('Error', 'Gagal menyimpan pengambilan obat. Silakan coba lagi.');
        }

        // session()->flash('saved', [
        //     'title' => 'Berhasil',
        //     'text' => 'Pengambilan obat berhasil disimpan.',
        // ]);

        // return redirect()->route('user.pharmacy.take-medicine');
    }

    private function processRecipeLevel($transaction, $data, $productService, $companyId, $branchId)
    {
        $transactionRecipe = TransactionRecipe::findOrFail($data['id']);
        $medicineType = MedicineType::findOrFail($data['medicine_type_id']);
        if (!$medicineType->is_single) {
            $product = Product::findOrFail($data['product_id']);

            $productPrice = ProductPrice::where('product_id', $product->id)
                ->where('company_id', $companyId)
                ->where('branch_id', $branchId)
                ->first();

            $hppPrice = $productPrice ? intval(Str::replace('.', '', number_format($productPrice->hpp_average, 0, ',', '.'))) : 0;
            $quantity = $data->quantity;
            $sellingPrice = $data->price;
            $data['sub_total_price'] = $data->price * $quantity;
            $transactionRecipe->update([
                'price_hpp' => $hppPrice,
                'sub_total_price_hpp' => $hppPrice * $quantity
            ]);

            $this->createTransactionProduct($transaction, $data, $product, $hppPrice, $quantity, $sellingPrice);
            $productService->createProductDecrement($product->id, $quantity, null, null, $sellingPrice, null, null, null, $data['id'], null);
        }
    }

    private function processDetailLevel($transaction, $data, $productService, $companyId, $branchId)
    {
        $transactionDetail = TransactionDetail::findOrFail($data['id']);
        $product = Product::findOrFail($data['product_id']);

        $productPackage = ProductPackage::where('product_id', $product->id)
            ->where('company_id', $companyId)
            ->get();

        // Get product price once for efficiency
        $productPrice = ProductPrice::where('product_id', $product->id)
            ->where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->first();

        $hppPrice = $productPrice ? intval(Str::replace('.', '', number_format($productPrice->hpp_average, 0, ',', '.'))) : 0;
        $quantity = $data['quantity'];
        $sellingPrice = $data['price'];
        $subTotalPrice = $data['price'] * $quantity;

        // Update transaction detail for main product
        $transactionDetail->update([
            'price_hpp' => $hppPrice,
            'sub_total_price_hpp' => $hppPrice * $quantity,
            'sub_total_price' => $subTotalPrice
        ]);

        // Create transaction product for main product
        $this->createTransactionProduct($transaction, $data, $product, $hppPrice, $quantity, $sellingPrice);
        $productService->createProductDecrement($product->id, $quantity, null, null, $sellingPrice, null, null, null, null, null);

        // Process package products if exists
        if ($productPackage->count() > 0) {
            foreach ($productPackage as $package) {
                $childProduct = Product::find($package->product_child_id);
                if ($childProduct) {
                    $childHppPrice = 0;
                    $childQuantity = $data['quantity'] * $package->quantity;
                    $childSellingPrice = 0; // Package child products typically have 0 selling price

                    // Create separate data array for child product
                    $childData = array_merge($data, [
                        'product_id' => $childProduct->id,
                        'quantity' => $childQuantity,
                        'price' => $childSellingPrice,
                        'sub_total_price' => 0
                    ]);

                    // $this->createTransactionProduct($transaction, $childData, $childProduct, $childHppPrice, $childQuantity, $childSellingPrice);
                    $productService->createProductDecrement($childProduct->id, $childQuantity, null, null, $childSellingPrice, null, null, null, null, null);
                }
            }
        }
    }

    private function createTransactionProduct($transaction, $data, $product, $hppPrice, $quantity, $sellingPrice)
    {
        $profit = ($sellingPrice - $hppPrice) * $quantity;

        if ($sellingPrice > 0 && $quantity > 0) {
            $margin = ($profit / ($sellingPrice * $quantity)) * 100;
        } else {
            $margin = 0;
        }

        // Batasi margin ke rentang -100 s/d 100, lalu bulatkan
        $margin = max(min($margin, 100), -100);
        $margin = round($margin);

        TransactionProduct::create([
            'transaction_id' => $transaction->id,
            'user_id' => $transaction->patient_id, // Menyimpan user_id dari transaksi
            'user_name' => $transaction->patient_name, // Menyimpan nama user dari transaksi
            'transaction_detail_id' => $data['id'],
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'price' => $sellingPrice,
            'total' => $data['sub_total_price'],
            'hpp_average' => $hppPrice,
            'hpp_total' => $hppPrice * $quantity,
            'profit' => $profit,
            'margin' => $margin, // Sekarang integer
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pharmacy.take-medicine.detail.admin-pharmacy-take-medicine-detail-index')
            ->extends('layout.app')
            ->section('content');
    }

    private function updateServiceTransactionRecipe($transaction)
    {
        $transactionRecipes = TransactionRecipe::where('transaction_id', $transaction->id)->get();

        $encounter = Encounter::where('transaction_id', $transaction->id)->first();
        $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
        $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();
        $location = Location::where('name', 'Instalasi Farmasi')
            ->where('company_id', $transaction->company_id)
            ->first();

        foreach ($transactionRecipes as $transactionRecipe) {
            $transactionDetails = $transactionRecipe->transactionDetail;

            foreach ($transactionDetails as $index => $transactionDetail) {
                $validity = $this->getValidatyRequest($transactionDetail, $transactionRecipe);
                $medication = Medication::where('product_id', $transactionDetail->product_id)->first();

                $medicationRequest = MedicationRequest::where('transaction_detail_id', $transactionDetail->id)->first();

                if (!$medication || !$medicationRequest) {
                    continue;
                }

                $data = [
                    'id' => null,
                    'transaction_detail_id' => $transactionDetail->id,
                    'company_id' => $transaction->company_id,
                    'location_id' => $location->id ?? null,
                    'practitioner_id' => $doctor->id ?? null,
                    'patient_id' => $patient->id ?? null,
                    'encounter_id' => $encounter->id ?? null,
                    'medication_id' => $medication->id ?? null,
                    'medication_request_id' => $medicationRequest->id ?? null,
                    'performer_id' => $transaction->company_id,
                    'status' => 'completed',
                    'category' => 'outpatient',
                    'quantity_value' => $transactionDetail->quantity ?? 0,
                    'quantity_code' => trim($transactionDetail->product->denominator_code ?? ''),
                    'day_value' => (int) ($validity['expect_value'] ?? 0),
                    'day_code' => 'd',
                    'when_prepare' => $transaction->date_prepare ?? now()->format('Y-m-d'),
                    'when_hand_over' => now()->format('Y-m-d'),
                    'dosage_instructions' => [
                        [
                            'sequence' => $index + 1,
                            'text' => $transactionRecipe->howToUse->name ?? '',
                            'timing_repeat_frequency' => $transactionRecipe->howToUse->time ?? 1,
                            'timing_repeat_period' => $transactionRecipe->howToUse->day ?? 1,
                            'timing_repeat_period_unit' => 'd',
                            'route_coding_code' => $transactionRecipe->route_coding_code ?? null,
                            'dose_rate_type_coding_code' => 'ordered',
                            'dose_rate_quantity_value' => $transactionDetail->quantity ?? 0,
                            'dose_rate_quantity_code' => trim($transactionDetail->product->denominator_code ?? ''),
                        ]
                    ]
                ];

                app(apiservice::class)->createMedicationDispense($data);
            }
        }
    }

    private function getValidatyRequest($transactionDetail, $transactionRecipe): array
    {
        $total_obat = $transactionDetail->quantity ?? 0;
        $frekuensi_per_hari = $transactionRecipe->howToUse->time ?? 1;
        $interval_hari = $transactionRecipe->howToUse->day ?? 1;
        $tanggal_mulai = $transactionDetail->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');

        $tanggal_mulai_obj = new \DateTime($tanggal_mulai);

        if ($interval_hari == 1) {
            // Harian
            $jumlah_hari = ceil($total_obat / $frekuensi_per_hari);
            $tanggal_habis = clone $tanggal_mulai_obj;
            $tanggal_habis->modify('+' . ($jumlah_hari - 1) . ' days');
        } else {
            // Interval hari
            $jumlah_hari = ($total_obat - 1) * $interval_hari;
            $tanggal_habis = clone $tanggal_mulai_obj;
            $tanggal_habis->modify("+$jumlah_hari days");
        }

        return [
            'validity_start' => $tanggal_mulai_obj->format('Y-m-d'),
            'validity_end' => $tanggal_habis->format('Y-m-d'),
            'expect_value' => $jumlah_hari,
        ];
    }
}
