<?php

namespace App\Livewire\Admin\Master\Product\Detail;

use App\Helpers\AlertHelper;
use App\Models\Master\CodeSystem\Medication\MasterMedicationForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestValueQuantity;
use App\Models\Medication\Medication;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductFactory;
use App\Models\Product\ProductRack;
use App\Models\Product\ProductType;
use App\Models\Unit\Unit;
use App\service\apiservice;
use App\Traits\Product\ProductTrait;
use Auth;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AdminMasterProductDetailData extends Component
{
    use ProductTrait;
    public $data_id;
    public $registration_path;
    public $sku_number;
    public $name;
    public $unit_id;
    public $description;
    public $product_category_id;
    public $product_factory_id;
    public $product_rack_id;
    public $product_type_id;
    public $is_narcotics;
    public $medicine_dosage;
    public $dosage_unit;
    public $is_non_stock;
    public $minimun_stock;
    public $safety_stock;
    public $maximum_stock;
    public $normal;
    public $recipe;
    public $code_coding_code;
    public $form_coding_code;
    public $item_code;
    public $item_display;
    public $numerator_code;
    public $numerator_value;
    public $denominator_code;
    public $getProductType;

    public $productCategorys = [];
    public $productFactorys = [];
    public $productRacks = [];
    public $productTypes = [];
    public $units = [];
    public $master_medication_forms = [];
    public $master_medication_request_value_quantities = [];
    public $master_medication_request_orderable_drug_forms = [];

    public function mount()
    {
        $product_id = Session::get('product_id');
        if ($product_id) {
            $product = Product::find($product_id);
            $this->data_id = $product->id;
            $this->registration_path = $product->registration_path;
            $this->sku_number = $product->sku_number;
            $this->name = $product->name;
            $this->unit_id = $product->unit_id;
            $this->description = $product->description;
            $this->product_category_id = $product->product_category_id;
            $this->product_factory_id = $product->product_factory_id;
            $this->product_rack_id = $product->product_rack_id;
            $this->product_type_id = $product->product_type_id;
            $this->is_narcotics = $product->is_narcotics;
            $this->medicine_dosage = $product->medicine_dosage;
            $this->dosage_unit = $product->dosage_unit;
            $this->minimun_stock = $product->minimun_stock;
            $this->safety_stock = $product->safety_stock;
            $this->maximum_stock = $product->maximum_stock;
            $this->normal = $product->normal;
            $this->recipe = $product->recipe;
            $this->code_coding_code = $product->code_coding_code;
            $this->form_coding_code = $product->form_coding_code;
            $this->item_code = $product->item_code;
            $this->item_display = $product->item_display;
            $this->numerator_code = $product->numerator_code;
            $this->updatedProductTypeId();
        }

        $this->productCategorys = $this->getProductCategorys();
        $this->productFactorys = $this->getProductFactorys();
        $this->productRacks = $this->getProductRacks();
        $this->productTypes = $this->getProductTypeWithoutTindakans();
        $this->units = $this->getUnits();

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
    }

    private function validateOptional($datas)
    {
        $errors = [];

        foreach ($datas as $key => $label) {
            if (empty($this->{$key})) {
                $errors[$key] = "$label tidak boleh kosong.";
            }
        }

        if (!empty($errors)) {
            AlertHelper::error('Gagal', implode("\n", $errors));
            throw \Illuminate\Validation\ValidationException::withMessages($errors);
        }
    }

    public function confirmSubmit()
    {
        return AlertHelper::confirmSave('save', 'Apakah Anda Yakin menyimpan Data Ini?');
    }

    public function updatedProductTypeId()
    {
        $productType = ProductType::find($this->product_type_id);
        $this->getProductType = null;
        if ($productType?->name == 'Obat') {
            $this->getProductType = $productType->name;
        }
    }

    public function save()
    {
        $this->denominator_code = $this->unit_id ? Unit::find($this->unit_id)->code : null;
        $this->numerator_value = $this->medicine_dosage;
        $this->dosage_unit = $this->numerator_code ? MasterMedicationRequestValueQuantity::where('code', $this->numerator_code)->first()?->code : null;

        $productType = ProductType::find($this->product_type_id);
        $getProductType = null;
        if ($productType?->name == 'Obat') {
            $getProductType = $productType->name;
        }

        $this->validate([
            'sku_number' => 'required',
            'unit_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'product_category_id' => 'nullable',
            'product_factory_id' => 'nullable',
            'product_rack_id' => 'nullable',
            'product_type_id' => 'required',
            'is_narcotics' => 'nullable|boolean',
            'is_non_stock' => 'nullable|boolean',
            'medicine_dosage' => 'nullable|integer',
            'dosage_unit' => 'nullable|string|max:255',
            'minimun_stock' => 'required|integer',
            'safety_stock' => 'required|integer',
            'maximum_stock' => 'required|integer',
            'normal' => 'nullable|integer|min:0|max:100',
            // 'recipe' => 'nullable|integer|min:0|max:100',
        ]);

        if ($getProductType) {
            $datas = [
                'code_coding_code' => 'Produk Varian',
                'form_coding_code' => 'Bentuk Obat',
                'item_code' => 'Bahan Baku Kode',
                'item_display' => 'Bahan Baku Nama',
                'dosage_unit' => 'Satuan Dosis',
                'medicine_dosage' => 'Dosis Obat',
                'numerator_code' => 'Satuan Dosis'
            ];

            $this->validateOptional($datas);
        }

        DB::beginTransaction();

        try {
            $medication = Medication::where('product_id', $this->data_id)->first();

            $product = Product::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'sku_number' => $this->sku_number,
                    'unit_id' => $this->unit_id,
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_category_id' => $this->product_category_id,
                    'product_factory_id' => $this->product_factory_id,
                    'product_rack_id' => $this->product_rack_id,
                    'product_type_id' => $this->product_type_id,
                    'registration_path' => $this->registration_path ?? 'manual',
                    'is_narcotics' => $this->is_narcotics,
                    'is_non_stock' => $this->is_non_stock,
                    'medicine_dosage' => $this->medicine_dosage ?? 0,
                    'dosage_unit' => $this->dosage_unit,
                    'minimun_stock' => $this->minimun_stock,
                    'safety_stock' => $this->safety_stock,
                    'maximum_stock' => $this->maximum_stock,
                    'company_id' => auth()->user()->company_id,
                    'code_coding_code' => $this->code_coding_code,
                    'form_coding_code' => $this->form_coding_code,
                    'item_code' => $this->item_code,
                    'item_display' => $this->item_display,
                    'numerator_code' => $this->numerator_code,
                    'numerator_value' => $this->numerator_value,
                    'denominator_code' => $this->denominator_code,
                    'denominator_value' => 1,
                    'normal' => $this->normal ?? 0,
                ]
            );

            $datas = [
                "id" => $medication?->id ?? null,
                "product_id" => $product->id,
                "company_id" => Auth::user()->company_id,
                "code_coding_code" => $this->code_coding_code,
                "status" => "active",
                "manufacturer_reference" => "3e1a2508-04ef-43da-ac34-ff7a8ad6bc88",
                "form_coding_code" => $this->form_coding_code,
                "ingredients" => [
                    [
                        "product_id" => null,
                        "item_code" => $this->item_code,
                        "item_display" => $this->item_display,
                        "is_active" => true,
                        "numerator_value" => $this->numerator_value,
                        "numerator_code" => $this->numerator_code,
                        "denominator_value" => 1,
                        "denominator_code" => $this->denominator_code
                    ]
                ],
                "medication_type_code" => "NC"
            ];

            if ($getProductType) {
                app(apiservice::class)->createMedictation($datas);
            }

            Session::put('product_id', $product->id);

            DB::commit();

            AlertHelper::success('Berhasil', 'Data produk berhasil disimpan');

            return redirect()->route('user.master.product.detail');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Terjadi kesalahan saat menyimpan data produk: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data produk. Silakan coba lagi.');
            return redirect()->back();
        }
    }
    public function render()
    {
        return view('livewire.admin.master.product.detail.admin-master-product-detail-data')
            ->extends('layout.app')
            ->section('content');
    }
}
