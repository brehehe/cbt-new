<?php

namespace App\Livewire\Admin\Purchase\Draft\MailOrder;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Master\CodeSystem\Medication\MasterMedicationForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestValueQuantity;
use App\Models\Medication\Medication;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductFactory;
use App\Models\Product\ProductRack;
use App\Models\Product\ProductType;
use App\Models\Product\ProductUnit;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Models\PurchaseRequisition\PurchaseRequisitionItem;
use App\Models\Unit\Unit;
use App\service\apiservice;
use App\Traits\Product\ProductTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPurchaseDraftMailOrderIndex extends Component
{
    use ProductTrait;
    use WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
        // 'pageProduct' => ['except' => 1],
        'searchProduct' => ['except' => ''],
    ];

    public $search = '';

    public $searchProduct = '';

    public $perPage = 5;

    public $perPageProduct = 5;

    public $purchase_requisition_item_id;

    public $product_id;
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
    public $stock;

    // Product Old
    public $productOld;

    // Array Get
    public $productCategorys = [];

    public $productFactorys = [];

    public $productRacks = [];

    public $productTypes = [];

    public $units = [];

    public $master_medication_forms = [];

    public $master_medication_request_value_quantities = [];

    public $master_medication_request_orderable_drug_forms = [];

    // Product Category

    public $name_category;

    public $description_category;

    public $normal_category;

    public $recipe_category;

    public $price_category;

    // Product Rack
    public $name_rack;

    public $description_rack;

    // Product Factory
    public $name_factory;

    public $description_factory;

    // ProductRequisitionItems
    public array $selectedUnitIds = [];

    // ProductUnit
    public $product_product_unit_id;

    public $unit_product_unit_id;

    public $quantity_product_unit;

    // Select

    public $selectAll = false;

    public $selected = [];

    public $product_unit_name;

    public function mount()
    {
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

        // Update ProductUnit
        $this->generateUpdatePurchaseUnit();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearchProduct()
    {
        $this->resetPage('pageProduct');
    }

    public function updatedPerPageProduct()
    {
        $this->resetPage('pageProduct');
    }

    public function hydrate()
    {
        $this->resetValidation();
    }

    public function openModal($modalId)
    {
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function closeModal($modalId)
    {
        $this->reset([
            'product_id',
            'sku_number',
            'name',
            'description',
            'product_category_id',
            'product_factory_id',
            'product_rack_id',
            'unit_id',
            'product_type_id',
            'is_narcotics',
            'medicine_dosage',
            'dosage_unit',
            'minimun_stock',
            'safety_stock',
            'maximum_stock',
            'productOld',
            'code_coding_code',
            'form_coding_code',
            'item_code',
            'item_display',
            'numerator_code',
            'numerator_value',
            'denominator_code',
            'stock',
        ]);

        $this->dispatch('close-modal', ['id' => $modalId]);
    }

    public function openModalProduct($modalId)
    {
        $this->reset([
            'product_id',
            'sku_number',
            'name',
            'description',
            'product_category_id',
            'product_factory_id',
            'product_rack_id',
            'unit_id',
            'product_type_id',
            'is_narcotics',
            'medicine_dosage',
            'dosage_unit',
            'minimun_stock',
            'safety_stock',
            'maximum_stock',
            'productOld',
            'code_coding_code',
            'form_coding_code',
            'item_code',
            'item_display',
            'numerator_code',
            'numerator_value',
            'denominator_code',
            'stock',
        ]);

        if ($modalId == 'modalProductOld') {
            $this->productOld = true;
        }

        $this->dispatch('close-modal', ['id' => 'modal']);
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function closeModalProduct($modalId)
    {
        $this->reset([
            'product_id',
            'sku_number',
            'name',
            'description',
            'product_category_id',
            'product_factory_id',
            'product_rack_id',
            'unit_id',
            'product_type_id',
            'is_narcotics',
            'medicine_dosage',
            'dosage_unit',
            'minimun_stock',
            'safety_stock',
            'maximum_stock',
            'productOld',
            'code_coding_code',
            'form_coding_code',
            'item_code',
            'item_display',
            'numerator_code',
            'numerator_value',
            'denominator_code',
            'stock',
        ]);

        $this->dispatch('close-modal', ['id' => $modalId]);
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function openModalCategory($modalId)
    {
        $this->reset(['productCategorys']);
        $this->productCategorys = $this->getProductCategorys();
        $this->dispatch('close-modal', ['id' => 'modalProductNew']);
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function closeModalCategory($modalId)
    {
        $this->reset(['productCategorys']);
        $this->productCategorys = $this->getProductCategorys();
        $this->dispatch('close-modal', ['id' => $modalId]);
        $this->dispatch('open-modal', ['id' => 'modalProductNew']);
    }

    public function openModalRack($modalId)
    {
        $this->reset(['productRacks']);
        $this->productRacks = $this->getProductRacks();
        $this->dispatch('close-modal', ['id' => 'modalProductNew']);
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function closeModalRack($modalId)
    {
        $this->reset(['productRacks']);
        $this->productRacks = $this->getProductRacks();
        $this->dispatch('close-modal', ['id' => $modalId]);
        $this->dispatch('open-modal', ['id' => 'modalProductNew']);
    }

    public function openModalFactory($modalId)
    {
        $this->reset(['productFactorys']);
        $this->productFactorys = $this->getProductFactorys();
        $this->dispatch('close-modal', ['id' => 'modalProductNew']);
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function closeModalFactory($modalId)
    {
        $this->reset(['productFactorys']);
        $this->productFactorys = $this->getProductFactorys();
        $this->dispatch('close-modal', ['id' => $modalId]);
        $this->dispatch('open-modal', ['id' => 'modalProductNew']);
    }

    public function generateSkuNumber()
    {
        do {
            $this->sku_number = random_int(00000000000001, 99999999999999);
        } while (strlen(strval($this->sku_number)) < 14 || Product::where('sku_number', $this->sku_number)->first());
    }

    public function closeModalProductChoice()
    {
        $this->reset([
            'product_id',
            'sku_number',
            'name',
            'description',
            'product_category_id',
            'product_factory_id',
            'product_rack_id',
            'unit_id',
            'product_type_id',
            'is_narcotics',
            'medicine_dosage',
            'dosage_unit',
            'minimun_stock',
            'safety_stock',
            'maximum_stock',
            'productOld',
            'code_coding_code',
            'form_coding_code',
            'item_code',
            'item_display',
            'numerator_code',
            'numerator_value',
            'denominator_code',
            'stock',
        ]);

        $this->productOld = true;

        $this->dispatch('close-modal', ['id' => 'modalProductChoice']);
        $this->dispatch('open-modal', ['id' => 'modalProductOld']);
    }

    public function closeModalProductChoiceNew()
    {
        $this->reset([
            'product_id',
            'sku_number',
            'name',
            'description',
            'product_category_id',
            'product_factory_id',
            'product_rack_id',
            'unit_id',
            'product_type_id',
            'is_narcotics',
            'medicine_dosage',
            'dosage_unit',
            'minimun_stock',
            'safety_stock',
            'maximum_stock',
            'productOld',
            'code_coding_code',
            'form_coding_code',
            'item_code',
            'item_display',
            'numerator_code',
            'numerator_value',
            'denominator_code',
            'stock',
        ]);

        $this->dispatch('close-modal', ['id' => 'modalProductChoice']);
    }

    public function closeModalProductUnit($modalId)
    {
        $this->resetValidation();
        $this->reset(['purchase_requisition_item_id', 'product_product_unit_id', 'unit_product_unit_id', 'quantity_product_unit', 'name', 'product_unit_name']);
        $this->dispatch('close-modal', ['id' => $modalId]);
    }

    public function openModalProductUnit($modalId)
    {
        $this->purchase_requisition_item_id = $modalId;
        $purchaseRequisitionItem = PurchaseRequisitionItem::find($modalId);
        $this->product_product_unit_id = $purchaseRequisitionItem->product_id;
        $this->name = $purchaseRequisitionItem?->product?->name;
        $this->product_unit_name = $purchaseRequisitionItem?->product?->unit?->name;

        $this->dispatch('open-modal', ['id' => 'modalProductUnit']);
    }

    public function submitCategory()
    {
        $this->validate([
            'name_category' => 'required',
            'description_category' => 'nullable',
            'normal_category' => 'required|numeric|min:1|max:100',
            // 'recipe_category' => 'required|numeric|min:1|max:100',
            'price_category' => 'nullable',
        ], [
            'name_category.required' => 'Nama wajib diisi',
            'normal_category.required' => 'Normal wajib diisi',
            'recipe_category.required' => 'Recipe wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            $price_category = intval(Str::replace('.', '', $this->price_category));
            $normal_category = intval(Str::replace('.', '', $this->normal_category));
            $recipe_category = intval(Str::replace('.', '', $this->recipe_category));

            $productCategory = ProductCategory::create(
                [
                    'name' => $this->name_category,
                    'description' => $this->description_category,
                    'normal' => $normal_category ?? 0,
                    'recipe' => $recipe_category ?? 0,
                    'price' => $price_category,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->reset(['name_category', 'description_category', 'normal_category', 'recipe_category', 'price_category']);
            $this->closeModalCategory('modalCategory');

            $this->product_category_id = $productCategory->id;

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function submitRack()
    {
        $this->validate([
            'name_rack' => 'required',
            'description_rack' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $productRack = ProductRack::create(
                [
                    'name' => $this->name_rack,
                    'description' => $this->description_rack,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->closeModalRack('modalRack');
            $this->reset(['name_rack', 'description_rack']);
            $this->product_rack_id = $productRack->id;

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product rack', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function choiceProduct($product_id)
    {
        $this->reset(['productOld']);

        $product = Product::find($product_id);

        $purchaseRequisitionItemStocks = PurchaseRequisitionItem::where('product_id', $product_id)->where('company_id', Auth::user()->company_id)->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id)->where('status', 'draft')->sum('quantity');

        $this->product_id = $product->id;
        $this->sku_number = $product->sku_number;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->product_category_id = $product->product_category_id;
        $this->product_factory_id = $product->product_factory_id;
        $this->product_rack_id = $product->product_rack_id;
        $this->product_type_id = $product->product_type_id;
        $this->unit_id = $product->unit_id;
        $this->is_narcotics = $product->is_narcotics;
        $this->medicine_dosage = $product->medicine_dosage;
        $this->dosage_unit = $product->dosage_unit;
        $this->minimun_stock = $product->minimun_stock;
        $this->safety_stock = $product->safety_stock;
        $this->maximum_stock = $product->maximum_stock;
        $this->code_coding_code = $product->code_coding_code;
        $this->form_coding_code = $product->form_coding_code;
        $this->item_code = $product->item_code;
        $this->item_display = $product->item_display;
        $this->numerator_code = $product->numerator_code;
        $this->numerator_value = $product->numerator_value;
        $this->denominator_code = $product->denominator_code;
        $this->stock = $purchaseRequisitionItemStocks;

        $this->dispatch('close-modal', ['id' => 'modalProductOld']);
        $this->dispatch('open-modal', ['id' => 'modalProductChoice']);
    }

    public function submitFactory()
    {
        $this->validate([
            'name_factory' => 'required',
            'description_factory' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $productFactory = ProductFactory::create(
                [
                    'name' => $this->name_factory,
                    'description' => $this->description_factory,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->closeModalFactory('modalFactory');
            $this->reset(['name_factory', 'description_factory']);
            $this->product_factory_id = $productFactory->id;

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product factory', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
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

    public function saveProduct()
    {
        $this->denominator_code = $this->unit_id ? Unit::find($this->unit_id)->code : null;
        $this->numerator_value = $this->medicine_dosage;
        $this->dosage_unit = $this->numerator_code ? MasterMedicationRequestValueQuantity::where('code', $this->numerator_code)->first()?->code : null;

        $productType = ProductType::find($this->product_type_id);
        $getProductType = null;
        if (in_array($productType?->name, ['Obat'])) {
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
            'medicine_dosage' => 'nullable|integer',
            'dosage_unit' => 'nullable|string|max:255',
            'minimun_stock' => 'required|integer',
            'safety_stock' => 'required|integer',
            'maximum_stock' => 'required|integer',
            'normal' => 'required|integer|min:0|max:100',
            'stock' => 'required|integer|min:1',
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

            $product = Product::updateOrCreate(
                ['id' => $this->product_id],
                [
                    'sku_number' => $this->sku_number,
                    'unit_id' => $this->unit_id,
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_category_id' => $this->product_category_id,
                    'product_factory_id' => $this->product_factory_id,
                    'product_rack_id' => $this->product_rack_id,
                    'product_type_id' => $this->product_type_id,
                    'registration_path' => 'purchase',
                    'is_narcotics' => $this->is_narcotics,
                    'is_non_stock' => false,
                    'medicine_dosage' => $this->medicine_dosage,
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
                ]
            );

            $medication = Medication::where('product_id', $product->id)
                ->first();

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

            app(apiservice::class)->createMedictation($datas);

            PurchaseRequisitionItem::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $this->stock,
                'branch_id' => Branch::where('company_id', Auth::user()->company_id)->first()->id,
                'company_id' => Auth::user()->company_id,
                'status' => 'draft',
            ]);

            DB::commit();

            AlertHelper::success('Berhasil', 'Data berhasil disimpan');

            return $this->closeModal('modalProductNew');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function saveChoiceProduct()
    {
        $this->validate([
            'stock' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            $companyId = Auth::user()->company_id;
            $branchId = Branch::where('company_id', $companyId)->value('id');

            $product = Product::find($this->product_id);

            $purchaseRequisitionItem = PurchaseRequisitionItem::where([
                'product_id' => $product->id,
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'status' => 'draft',
            ])->first();

            $quantity = $this->stock;
            $quantity_detail = 0;
            $quantity_real = 0;

            if ($purchaseRequisitionItem) {
                if ($purchaseRequisitionItem->product_unit_id) {
                    $productUnit = ProductUnit::find($purchaseRequisitionItem->product_unit_id);

                    $quantityProdukUnit = $productUnit->quantity;
                    $quantityProdukPurchaseRequisitionItem = $quantity;

                    $quantity_detail = ceil($quantityProdukPurchaseRequisitionItem / $quantityProdukUnit);
                    $quantity_real = $quantity_detail * $quantityProdukUnit;
                }
            }

            PurchaseRequisitionItem::updateOrCreate([
                'id' => optional($purchaseRequisitionItem)->id,
            ], [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $this->stock,
                'branch_id' => $branchId,
                'company_id' => $companyId,
                'quantity_detail' => $quantity_detail,
                'quantity_real' => $quantity_real,
                'status' => 'draft',
            ]);

            DB::commit();

            AlertHelper::success('Berhasil', 'Data berhasil disimpan');

            return $this->closeModalProductChoiceNew();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function updateSelectedUnit(string $itemId, $unitId)
    {
        try {
            $purchaseRequisitionItem = PurchaseRequisitionItem::findOrFail($itemId);
            $productUnit = ProductUnit::findOrFail($unitId);

            $quantityProdukUnit = $productUnit->quantity;
            $quantityProdukPurchaseRequisitionItem = $purchaseRequisitionItem->quantity;

            $quantity_detail = ceil($quantityProdukPurchaseRequisitionItem / $quantityProdukUnit);
            $quantity_real = $quantity_detail * $quantityProdukUnit;

            $purchaseRequisitionItem->product_unit_id = $productUnit->id;
            $purchaseRequisitionItem->quantity_detail = $quantity_detail;
            $purchaseRequisitionItem->quantity_real = $quantity_real;
            $purchaseRequisitionItem->save();

            $this->selectedUnitIds[$itemId] = $unitId;

            Log::info("Berhasil update item ID: $itemId dengan unit ID: $unitId");
        } catch (\Throwable $e) {
            Log::error("Gagal update item ID: $itemId dengan unit ID: $unitId. Error: " . $e->getMessage());
        }
    }

    public function submitProductUnit()
    {
        $this->validate([
            'product_product_unit_id' => 'required',
            'unit_product_unit_id' => 'required',
            'quantity_product_unit' => 'required|numeric',
            // 'stock' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            $productUnit = ProductUnit::create(
                [
                    'product_id' => $this->product_product_unit_id,
                    'unit_id' => $this->unit_product_unit_id,
                    'quantity' => $this->quantity_product_unit,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            $purchaseRequisitionItem = PurchaseRequisitionItem::findOrFail($this->purchase_requisition_item_id);
            $productUnit = ProductUnit::findOrFail($productUnit->id);

            $quantityProdukUnit = $productUnit->quantity;
            $quantityProdukPurchaseRequisitionItem = $purchaseRequisitionItem->quantity;

            $quantity_detail = ceil($quantityProdukPurchaseRequisitionItem / $quantityProdukUnit);
            $quantity_real = $quantity_detail * $quantityProdukUnit;

            $purchaseRequisitionItem->product_unit_id = $productUnit->id;
            $purchaseRequisitionItem->quantity_detail = $quantity_detail;
            $purchaseRequisitionItem->quantity_real = $quantity_real;
            $purchaseRequisitionItem->save();

            $this->selectedUnitIds[$this->purchase_requisition_item_id] = $productUnit->id;

            DB::commit();
            $this->dispatch('close-modal', ['id' => 'modal']);
            $this->reset(['purchase_requisition_item_id', 'product_product_unit_id', 'unit_product_unit_id', 'quantity_product_unit', 'name', 'product_unit_name']);
            $this->closeModalProductUnit('modalProductUnit');

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving product unit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function getPurchaseRequisitionItemsProperty()
    {
        $company = Company::find(Auth::user()->company_id);

        $query = PurchaseRequisitionItem::with([
            'product:id,name,unit_id',
            'product.unit:id,name',
            'product.productUnits:id,product_id,unit_id,quantity',
            'product.productUnits.unit:id,name',
            'productUnit:id,quantity,unit_id',
            'productUnit.unit:id,name',
            'company:id,name'
        ])->where('status', 'draft')
            ->where('company_id', Auth::user()->company_id);

        return $query->orderBy('order', 'desc')->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $company = Company::find(Auth::user()->company_id);

            $query = PurchaseRequisitionItem::query()
                ->where('company_id', Auth::user()->company_id)
                ->select('id')
                ->where('status', 'draft');

            $this->selected = $query->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = count($this->selected) === $this->purchaseRequisitionItems->total();
    }

    public function save()
    {
        if (count($this->selected) > 0) {
            return $this->confirmSelected();
        }

        return AlertHelper::info('Info', 'Checklist terlebih dahulu!');
    }

    public function confirmSelected()
    {
        return LivewireAlert::title('Buat Surat Pesanan?')
            ->text('Apakah Anda yakin ingin membuat Surat Pesanan dari item yang dipilih?')
            ->withConfirmButton('Ya, Buat', '#1E3A8A')
            ->withCancelButton('Batal')
            ->confirmButtonColor('#1E3A8A')
            ->denyButtonColor('#dc3545')
            ->withOptions([
                'toast' => false,
                'customClass' => [
                    'title' => 'text-lg font-bold text-start',
                    'content' => 'text-start text-sm',
                    'popup' => 'text-left',
                ],
                'backdrop' => true,
            ])
            ->onConfirm('createPurchaseRequisition')
            ->show();
    }

    public function createPurchaseRequisition()
    {
        $items = PurchaseRequisitionItem::whereIn('id', $this->selected)->get();

        // Cek jika ada item yang belum memiliki product_unit_id
        if ($items->contains(fn($item) => ! $item->product_unit_id)) {
            return AlertHelper::info('Info', 'Satuan Order belum terpilih!');
        }

        // Buat Purchase Requisition baru
        $purchaseRequisition = PurchaseRequisition::create([
            'company_id' => auth()->user()->company_id,
            'user_id'    => auth()->user()->id,
            'branch_id'  => Branch::where('company_id', auth()->user()->company_id)->value('id'),
            'status'     => 'draft',
        ]);

        // Update semua item terpilih
        foreach ($items as $item) {
            $item->update([
                'purchase_requisition_id' => $purchaseRequisition->id,
                'status'                  => 'success',
            ]);
        }

        // Reset pilihan
        $this->reset(['selected', 'selectAll']);

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        LivewireAlert::title('Delete?')
            ->text('Apakah Anda yakin ingin menghapus data ini?')
            ->withConfirmButton('Delete', '#dc3545')
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

    public function delete($data)
    {
        $itemId = $data['id'];

        try {
            DB::beginTransaction();

            $purchaseRequisitionItem = PurchaseRequisitionItem::findOrFail($itemId);
            if ($purchaseRequisitionItem) {
                $purchaseRequisitionItem->delete();

                DB::commit();
                return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
            }

            DB::rollBack();
            Log::error('Purchase Requisition not found for deletion', ['id' => $itemId]);
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

    public function render()
    {
        $items = $this->purchaseRequisitionItems;
        // Set selected unit ID dari database
        $this->selectedUnitIds = $items->pluck('product_unit_id', 'id')->toArray();

        return view('livewire.admin.purchase.draft.mail-order.admin-purchase-draft-mail-order-index', [
            'purchaseRequisitionItems' => $items,
            'products' => $this->productOld ? $this->getProductPaginates() : [],
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
