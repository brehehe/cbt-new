<?php

namespace App\Livewire\Admin\Master\Recipe\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Master\CodeSystem\Medication\MasterMedicationForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestValueQuantity;
use App\Models\Medication\Medication;
use App\Models\Product\Product;
use App\Models\Product\ProductPackage;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductType;
use App\Models\Unit\Unit;
use App\service\apiservice;
use App\Traits\Product\ProductTrait;
use Auth;
use DB;
use Livewire\Component;
use Log;
use Str;

class AdminMasterRecipeDetailIndex extends Component
{
    use ProductTrait;
    public $data_id;
    public $registration_path;
    public $sku_number;
    public $name;
    public $unit_id;
    public $description;
    public $product_type_id;
    public $is_narcotics;
    public $minimun_stock;
    public $safety_stock;
    public $maximum_stock;
    public $normal;
    public $recipe;
    public $code_coding_code;
    public $form_coding_code;
    public $details = [];
    public $master_medication_forms = [];
    public $master_medication_request_value_quantities = [];
    public $master_medication_request_orderable_drug_forms = [];
    public $units = [];
    public $productTypes = [];
    public $hpp_average;
    public $hpp_average_total;
    public $price_generate;
    public $price;
    public $sub_total;
    public $sub_total_final;
    public $products = [];
    public $is_stock_ingredient = [];
    public $denominator_code;

    public function mount()
    {
        $this->product_type_id = ProductType::select('id')->where('name', 'Resep')->first()->toArray()['id'];

        $this->productTypes = ProductType::whereIn('name', ['Resep'])->select('id', 'name')->orderBy('name', 'asc')->get()->toArray();
        $this->units = Unit::select('id', 'name')
            // ->where('company_id', auth()->user()->company_id)
            ->orderBy('name', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $this->products = Product::select('id', 'name', 'sku_number') // tambahkan 'sku_number'
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get()
            ->pluck('name_sku', 'id')
            ->toArray();

        $this->master_medication_forms = MasterMedicationForm::orderBy('code')
            ->select('code', 'display')
            ->orderBy('code')
            ->get()
            ->pluck('code_display', 'code')->toArray();

        $this->master_medication_request_value_quantities = MasterMedicationRequestValueQuantity::orderBy('code')
            ->select('code', 'display')
            ->orderBy('code')
            ->get()
            ->pluck('code_display', 'code')->toArray();

        $this->master_medication_request_orderable_drug_forms = MasterMedicationRequestOrderableDrugForm::orderBy('code')
            ->select('code', 'display')
            ->orderBy('code')
            ->get()
            ->pluck('code_display', 'code')->toArray();

        $product_id = session('product_id', null);

        if ($product_id) {
            $this->data_id = $product_id;
            $this->registration_path = route('user.master.recipe.detail', ['id' => $this->data_id]);
            $this->getProductPackages();
            $product = Product::find($this->data_id);
            if ($product) {
                $this->sku_number = $product->sku_number;
                $this->name = $product->name;
                $this->description = $product->description;
                $this->product_type_id = $product->product_type_id;
                $this->code_coding_code = $product->code_coding_code;
                $this->form_coding_code = $product->form_coding_code;
                $this->sub_total_final = number_format($product->productPrice->price ?? 0, 0, ',', '.');
                $this->is_stock_ingredient = $product->is_stock_ingredient;
                $this->minimun_stock = $product->minimun_stock ?? 0;
                $this->safety_stock = $product->safety_stock ?? 0;
                $this->maximum_stock = $product->maximum_stock ?? 0;
                $this->denominator_code = $product->denominator_code;
            }
        } else {
            $this->createProductPackage();
        }
    }


    public function getProductPackages()
    {
        $this->reset(['details']);

        $details = ProductPackage::where('product_id', $this->data_id)
            ->where('company_id', auth()->user()->company_id)
            ->select('id', 'product_id', 'product_child_id', 'name', 'quantity')
            ->with(['productChild:id,name'])
            ->get();

        foreach ($details as $key => $detail) {
            $this->details[] = [
                'product_package_id' => $detail->id,
                'product_id' => $detail->product_child_id,
                'product_name' => $detail->productChild->name ?? null,
                'quantity' => $detail->quantity,
                'hpp_average' => 0, // Will be updated later
                'hpp_average_total' => 0, // Will be updated later
                'price' => 0, // Will be updated later
                'sub_total_price' => 0, // Will be updated later
            ];
        }

        $this->updatedDetails();
    }

    public function createProductPackage()
    {
        $this->details[] = [
            'product_package_id' => null,
            'product_id' => null,
            'product_name' => null,
            'quantity' => 1,
            'hpp_average' => 0,
            'hpp_average_total' => 0,
            'price' => 0,
            'sub_total_price' => 0,
        ];
    }

    public function updatedDetails()
    {
        foreach ($this->details as $key => $detail) {
            $product = Product::find($detail['product_id']);

            $productPrice = ProductPrice::where('product_id', $detail['product_id'])
                ->where('company_id', auth()->user()->company_id)
                ->first();

            $quantity = intval(Str::replace('.', '', $detail['quantity'] ?? 1));
            $quantity = $quantity > 0 ? $quantity : 1;

            $this->details[$key] = [
                'product_package_id' => $detail['product_package_id'] ?? null,
                'product_id' => $detail['product_id'],
                'product_name' => $product ? $product->name : null,
                'quantity' => $quantity,
                'hpp_average' => $productPrice ? ($productPrice->hpp_average) : 0,
                'hpp_average_total' => $productPrice ? ($productPrice->hpp_average * $quantity) : 0,
                'price' => $productPrice ? ($productPrice->price) : 0,
                'sub_total_price' => $productPrice ? ($productPrice->price * $quantity) : 0,
            ];
        };
        $this->updateTotalPrice();
    }

    public function getSelectedProductIds()
    {
        return collect($this->details)->pluck('product_id')->filter()->toArray();
    }

    public function confirmDelete($key)
    {
        return AlertHelper::confirmDelete('deleteProductPackage', 'Anda yakin ingin menghapus paket produk ini?', $key);
    }

    public function deleteProductPackage($key)
    {
        if ($this->details[$key[0]]['product_package_id']) {
            ProductPackage::where('id', $this->details[$key[0]]['product_package_id'])
                ->where('company_id', auth()->user()->company_id)
                ->delete();
        }

        unset($this->details[$key[0]]);
        $this->details = array_values($this->details);
        AlertHelper::success('Berhasil', 'Paket produk berhasil dihapus.');

        if ($this->data_id) {
            $this->getProductPackages();
        }

        $this->updateTotalPrice();
    }

    public function updateTotalPrice()
    {
        $hpp_average = 0;
        $hpp_average_total = 0;
        $price = 0;
        $sub_total = 0;
        foreach ($this->details as $detail) {
            $price += intval(Str::replace('.', '', number_format($detail['price'], 0, '.', '')));
            $hpp_average_total += intval(Str::replace('.', '', number_format($detail['hpp_average_total'], 0, '.', '')));
            $hpp_average += intval(Str::replace('.', '', number_format($detail['hpp_average'], 0, '.', '')));
            $sub_total += intval(Str::replace('.', '', number_format($detail['sub_total_price'], 0, '.', '')));
        }

        $this->price = number_format($price, 0, ',', '.');
        $this->hpp_average = number_format($hpp_average, 0, ',', '.');
        $this->hpp_average_total = number_format($hpp_average_total, 0, ',', '.');
        $this->sub_total = number_format($sub_total, 0, ',', '.');
    }

    public function Konfirmasi()
    {
        try {
            DB::beginTransaction();

            $product = Product::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_type_id' => $this->product_type_id,
                    'is_non_stock' => false,
                    'company_id' => auth()->user()->company_id,
                    'is_stock_ingredient' => $this->is_stock_ingredient,
                ]
            );

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'company_id' => auth()->user()->company_id],
                [
                    'hpp_average' => intval(Str::replace('.', '', $this->hpp_average_total)),
                    'price' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'recipe' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'is_updated' => true,
                    'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()?->id,
                ]
            );

            foreach ($this->details as $key => $detail) {
                ProductPackage::updateOrCreate(
                    [
                        'id' => $detail['product_package_id'] ?? null,
                        'product_id' => $product->id,
                        'company_id' => auth()->user()->company_id,
                    ],
                    [
                        'name' => $detail['product_name'],
                        'product_child_id' => $detail['product_id'],
                        'quantity' => intval(Str::replace('.', '', $detail['quantity'])),
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan paket produk: ' . $e->getMessage());
            Log::info('Error saving product package', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'details' => $this->details,
                ],
            ]);
            return;
        }

        AlertHelper::success('Berhasil', 'Paket produk berhasil disimpan.');
        return redirect()->route('user.master.product.package');
    }

    public function confirmSubmit()
    {
        return AlertHelper::confirmSave('save', 'Apakah Anda Yakin Menyimpan Ini?');
    }

    public function save()
    {
        $this->validate(
            [
                'sku_number' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'code_coding_code' => 'required',
                'form_coding_code' => 'required',
                'description' => 'required|string|max:1000',
                'sub_total_final' => 'required',
                'minimun_stock' => $this->is_stock_ingredient ? 'nullable|integer|min:0' : 'required|integer|min:0',
                'safety_stock' => $this->is_stock_ingredient ? 'nullable|integer|min:0' : 'required|integer|min:0',
                'maximum_stock' => $this->is_stock_ingredient ? 'nullable|integer|min:0' : 'required|integer|min:0',
                'denominator_code' => 'required',
                'details' => 'required|array|min:1',
                'details.*.product_id' => 'required|exists:products,id',
            ],
            [
                'details.*.product_id.required' => 'Harap Masukan Produk'
            ]
        );

        $sub_total = intval(Str::replace('.', '', $this->sub_total));

        $sub_total_final = intval(Str::replace('.', '', $this->sub_total_final));

        if ($sub_total_final <= $sub_total) {
            return AlertHelper::confirmPublish('Konfirmasi', 'Total harga paket produk tidak boleh kurang dari atau sama dengan harga sub total. Silakan periksa kembali harga produk yang ditambahkan.');
        }

        try {
            DB::beginTransaction();

            $product = Product::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'sku_number' => $this->sku_number,
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_type_id' => $this->product_type_id,
                    'is_non_stock' => true,
                    'company_id' => auth()->user()->company_id,
                    'code_coding_code' => $this->code_coding_code,
                    'form_coding_code' => $this->form_coding_code,
                    'is_stock_ingredient' => $this->is_stock_ingredient ? true : false,
                    'minimun_stock' => $this->minimun_stock,
                    'safety_stock' => $this->safety_stock,
                    'maximum_stock' => $this->maximum_stock,
                    'denominator_code' => $this->denominator_code,
                ]
            );

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'company_id' => auth()->user()->company_id],
                [
                    'hpp_average' => intval(Str::replace('.', '', $this->hpp_average_total)),
                    'price' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'recipe' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'is_updated' => true,
                    'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()?->id,
                ]
            );

            foreach ($this->details as $key => $product_package) {
                ProductPackage::updateOrCreate(
                    [
                        'id' => $product_package['product_package_id'] ?? null,
                        'product_id' => $product->id,
                        'company_id' => auth()->user()->company_id,
                    ],
                    [
                        'product_child_id' => $product_package['product_id'],
                        'name' => $product_package['product_name'],
                        'quantity' => intval(Str::replace('.', '', $product_package['quantity'])),
                    ]
                );
            }

            $this->updateApiServiceRecipe($product);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan paket produk: ' . $e->getMessage());
            Log::info('Error saving product package', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'details' => $this->details,
                ],
            ]);
            return;
        }

        AlertHelper::success('Berhasil', 'Paket produk berhasil disimpan.');
        return redirect()->route('user.master.recipe');
    }

    public function updateApiServiceRecipe($product)
    {
        $medication = Medication::where('product_id', $product->id)->first();

        $datas = [
            "id" => $medication?->id ?? null,
            "product_id" => $product->id,
            "company_id" => Auth::user()->company_id,
            "code_coding_code" => $this->code_coding_code,
            "status" => "active",
            "manufacturer_reference" => "3e1a2508-04ef-43da-ac34-ff7a8ad6bc88",
            "form_coding_code" => $this->form_coding_code,
            "ingredients" => $this->productDetails($product),
            "medication_type_code" => "EP"
        ];

        app(apiservice::class)->createMedictation($datas);
    }

    public function productDetails($product)
    {
        $details = [];

        $productPackages = ProductPackage::where('product_id', $product->id)->get();
        foreach ($productPackages as $product_package) {
            $details[] = [
                "product_id" => $product_package->product_child_id,
                "item_code" => $product_package->productChild->item_code,
                "item_display" => $product_package->productChild->item_display,
                "is_active" => true,
                "numerator_value" => $product_package->productChild->numerator_value,
                "numerator_code" => $product_package->productChild->numerator_code,
                "denominator_value" => 1,
                "denominator_code" => $product_package->productChild->denominator_code
            ];
        }

        return $details;
    }

    public function updatedIsStockIngredient()
    {
        $this->reset(['minimun_stock', 'safety_stock', 'maximum_stock']);
    }

    public function render()
    {
        return view('livewire.admin.master.recipe.detail.admin-master-recipe-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
