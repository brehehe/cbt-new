<?php

namespace App\Livewire\Admin\Pharmacy\Consultation\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\HowToUse\HowToUse;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute;
use App\Models\Medication\Medication;
use App\Models\MedicineType\MedicineType;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Models\Product\ProductType;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionRecipe;
use App\Models\Transaction\TransactionRecipeReal;
use App\Models\Transaction\TransactionRecipeRealDetail;
use App\Models\User;
use App\service\apiservice;
use Auth;
use Cache;
use DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Log;

class AdminPharmacyConsultationDetailIndex extends Component
{
    use WithPagination;
    public $search, $perPage = 5;
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $transaction_id;
    public $transaction;

    // Array
    public $recipes = [], $actions = [], $medicine_types = [], $supporting_products = [], $product_types = [], $medicines = [], $how_to_uses = [], $master_medication_request_dosage_routes = [];

    // Variables
    public $transaction_detail_id, $type, $is_narcotic = false, $user_asign_narcotic_id = null, $product_id, $product_name, $username_or_email, $password, $barcode = false, $discount, $discount_type = 'percentage', $discount_value = 0, $is_admin_fee = false, $admin_fee = 0, $admin_fee_type = 'percentage', $admin_fee_value = 0, $is_outside_pharmacy = false;

    public $transaction_recipe_id, $name_how_to_use, $description_how_to_use, $day_how_to_use, $time_how_to_use;

    public function mount()
    {
        $this->transaction_id = session('transaction_id');
        if (!$this->transaction_id) {
            return redirect()->route('user.pharmacy.consultation');
        }

        $this->transaction = Transaction::find($this->transaction_id);
        if (!$this->transaction) {
            return redirect()->route('user.pharmacy.consultation');
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

        $this->how_to_uses = HowToUse::select('id', 'name', 'description')
            ->where('company_id', Auth::user()->company_id)
            ->get()
            ->pluck('name_display', 'id')
            ->toArray();

        $this->master_medication_request_dosage_routes = MasterMedicationRequestDosageRoute::select('code', 'display')
            ->get()
            ->pluck('code_display', 'code')
            ->toArray();
        $this->getActions();
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
                'how_to_use_id' => $transactionDetail->how_to_use_id,
                'route_coding_code' => $transactionDetail->route_coding_code,
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
        $this->updateTotal();
    }

    public function getActions()
    {
        $this->reset(['actions']);

        $actions = TransactionDetail::where('transaction_id', $this->transaction_id)
            ->whereIn('type_transaction', ['action', 'other'])
            ->with('product:id,sku_number,name,description,company_id')
            ->orderBy('order', 'asc')
            ->get();

        foreach ($actions as $action) {
            $this->actions[] = [
                'id' => $action->id,
                'product_id' => $action->product_id,
                'product_name' => $action->product?->name ?? $action->name,
                'description' => $action->description,
                'quantity' => $action->quantity,
                'price' => $action->price,
                'sub_total_price' => $action->sub_total_price,
            ];
        }

        $this->updateTotal();
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
        $this->updateTotal();
    }

    public function updatedMedicines()
    {
        $companyId = auth()->user()->company_id;
        $branchId = Branch::where('company_id', $companyId)->first()->id;
        foreach ($this->medicines as $key => $value) {
            $quantity = intval(Str::replace('.', '', $value['quantity']));
            $transactionDetail = TransactionDetail::find($value['id']);

            if (!$transactionDetail) {
                return AlertHelper::error('Gagal', 'Obat tidak ditemukan.');
            }

            $productStock = ProductStock::where([
                'product_id' => $transactionDetail->product_id,
                'company_id' => $companyId,
                'branch_id' => $branchId
            ])->first();

            $productPrice = ProductPrice::where([
                'product_id' => $transactionDetail->product_id,
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'is_updated' => true
            ])->first();

            if (!$productStock) {
                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan.');
            }

            if (!$productPrice) {
                return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
            }

            if ($productStock->quantity < intval(Str::replace('.', '', $value['quantity']))) {
                return AlertHelper::error('Gagal', 'Stok produk tidak mencukupi.');
            }


            $transactionDetail->product_id = !empty($value['product_id']) ? $value['product_id'] : null;
            $transactionDetail->quantity = $quantity;
            $transactionDetail->price = $productPrice->price;
            $transactionDetail->sub_total_price = $productPrice->price * $quantity;
            $transactionDetail->save();
        }

        AlertHelper::success('Berhasil', 'Obat berhasil diperbarui.');
        $this->getMedicines();
    }

    public function updatedRecipes()
    {
        foreach ($this->recipes as $key => $value) {
            $transactionRecipe = TransactionRecipe::find($value['id']);

            if (!$transactionRecipe) {
                return AlertHelper::error('Gagal', 'Resep tidak ditemukan.');
            }

            $transactionRecipe->medicine_type_id = $value['medicine_type_id'];

            $transactionRecipe->price_service_one = $value['price_service_one'] ? Str::replace('.', '', $value['price_service_one']) : 0;
            $transactionRecipe->product_id = !empty($value['product_id']) ? $value['product_id'] : null;
            $transactionRecipe->numero_recipe = intval(Str::replace('.', '', $value['numero_recipe']));
            $transactionRecipe->quantity = $value['quantity'];
            $transactionRecipe->price = Str::replace('.', '', $value['price']);
            $transactionRecipe->sub_total_price = Str::replace('.', '', $value['sub_total_price']);
            $transactionRecipe->description = $value['description'] ?? null;
            $transactionRecipe->how_to_use_id = $value['how_to_use_id'] ?? null;
            $transactionRecipe->route_coding_code = $value['route_coding_code'] ?? null;
            $transactionRecipe->save();

            if (!empty($value['details'])) {
                foreach ($value['details'] as $detail) {
                    $transactionDetail = TransactionDetail::find($detail['id']);

                    if ($transactionDetail) {
                        $productRecipe = Product::find($detail['product_id']);
                        $transactionDetail->product_id = $detail['product_id'];
                        $transactionDetail->type = $detail['type'] ?? 'single';
                        $transactionDetail->dosage_doctor = $detail['dosage_doctor'] ?? null;
                        $transactionDetail->dosage_drug = $detail['dosage_drug'] ?? $productRecipe->medicine_dosage;
                        $transactionDetail->quantity_real = intval(Str::replace('.', '', $detail['quantity_real']));
                        $transactionDetail->quantity = intval(Str::replace('.', '', $detail['quantity']));
                        $transactionDetail->price = Str::replace(',', '.', $detail['price']);
                        $transactionDetail->sub_total_price = Str::replace(',', '.', $detail['sub_total_price']);
                        $transactionDetail->save();
                    }
                }
            }
        }

        $this->updateTransactionRecipe();
        $this->getRecipes();
    }

    public function updateTransactionRecipe()
    {
        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            $branchId = Branch::where('company_id', $companyId)->first()->id;

            $transactionRecipes = TransactionRecipe::where('transaction_id', $this->transaction_id)
                ->orderBy('order', 'asc')
                ->get();

            foreach ($transactionRecipes as $key => $transactionRecipe) {
                $medicineType = MedicineType::find($transactionRecipe->medicine_type_id);
                $numeroRecipe = intval(Str::replace('.', '', $transactionRecipe->numero_recipe));

                if (!$medicineType) {
                    return AlertHelper::error('Gagal', "Tipe Resep Pada /R" . ($key + 1) . " tidak ditemukan.");
                }

                $product = Product::find($transactionRecipe->product_id);

                $productStock = ProductStock::where([
                    'product_id' => $transactionRecipe->product_id,
                    'company_id' => $companyId,
                    'branch_id' => $branchId
                ])->first();

                $productPrice = ProductPrice::where([
                    'product_id' => $transactionRecipe->product_id,
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'is_updated' => true
                ])->first();

                $quantity = 0;
                $price = 0;

                if ($numeroRecipe) {
                    if ($medicineType->is_single) {
                        $transactionRecipe->product_id = null;
                    } else {
                        // Pastikan product tidak null sebelum mengakses propertinya
                        if ($product && $product->is_non_stock) {
                            $quantity = $numeroRecipe;
                        } else {
                            if (!$productStock) {
                                $quantity = 0;
                            } elseif ($productStock->quantity < $numeroRecipe) {
                                AlertHelper::error('Gagal', "Stok produk {$transactionRecipe->product->name} tidak mencukupi.");
                                $quantity = 0;
                            } else {
                                $quantity = $numeroRecipe;
                            }
                        }

                        $price = $productPrice?->price ?? 0;
                    }

                    $transactionRecipe->fill([
                        'medicine_type_id' => $transactionRecipe->medicine_type_id,
                        'price_service_one' => $medicineType->service_price ?? 0,
                        'numero_recipe' => $numeroRecipe,
                        'quantity' => $quantity,
                        'price' => $price,
                        'sub_total_price' => $price * $quantity,
                        'description' => $transactionRecipe->description ?? null,
                    ])->save();

                    foreach ($transactionRecipe->transactionDetail as $detail) {
                        $productRecipe = Product::find($detail->product_id);

                        // Validasi productRecipe tidak null
                        if (!$productRecipe) {
                            AlertHelper::error('Gagal', "Produk dengan ID {$detail->product_id} tidak ditemukan.");
                            continue; // Skip iterasi ini
                        }

                        $productStockRecipe = ProductStock::where([
                            'product_id' => $detail->product_id,
                            'company_id' => $companyId,
                            'branch_id' => $branchId
                        ])->first();

                        $productPriceRecipe = ProductPrice::where([
                            'product_id' => $detail->product_id,
                            'company_id' => $companyId,
                            'branch_id' => $branchId,
                            'is_updated' => true
                        ])->first();

                        $priceRecipe = $productPriceRecipe?->price ?? 0;

                        if ($medicineType->is_single) {
                            if ($productRecipe->is_non_stock) {
                                $quantityRecipe = $numeroRecipe;
                            } else {
                                if (!$productStockRecipe) {
                                    $quantityRecipe = 0;
                                } elseif ($productStockRecipe->quantity < $numeroRecipe) {
                                    AlertHelper::error('Gagal', "Stok produk {$detail->product->name} tidak mencukupi.");
                                    $quantityRecipe = 0;
                                } else {
                                    $quantityRecipe = $numeroRecipe;
                                }
                            }

                            $detail->fill([
                                'type' => 'single',
                                'dosage_doctor' => 0,
                                'dosage_drug' => 0,
                                'quantity_real' => $quantityRecipe,
                                'quantity' => $quantityRecipe,
                                'price' => $priceRecipe,
                                'sub_total_price' => $priceRecipe * $quantityRecipe
                            ])->save();
                        } else {
                            // Partial / Gramasi handling
                            if ($detail->type == 'partial') {
                                $detail->doctor_dosage_gram = ($detail->dosage_doctor && $detail->dosage_drug)
                                    ? $detail->dosage_doctor * $detail->dosage_drug * $numeroRecipe : 0;

                                $detail->quantity_real = ($detail->dosage_drug && $detail->doctor_dosage_gram)
                                    ? $detail->doctor_dosage_gram / $detail->dosage_drug : 0;
                            } elseif ($detail->type == 'gramasi') {
                                $detail->doctor_dosage_gram = ($detail->dosage_doctor)
                                    ? ($detail->dosage_doctor != 0 ? $detail->dosage_drug / $detail->dosage_doctor * $numeroRecipe : 0)
                                    : 0;

                                $detail->quantity_real = ($detail->doctor_dosage_gram)
                                    ? $detail->dosage_drug / $detail->doctor_dosage_gram : 0;
                            } else {
                                $detail->type = 'single';
                                $detail->doctor_dosage_gram = 0;
                                $detail->quantity_real = 0;
                            }

                            $detail->quantity = $numeroRecipe ? ($detail->quantity_real ? ceil($detail->quantity_real) : 0) : 0;

                            if ($productRecipe->is_non_stock) {
                                $detail->quantity_real = $detail->quantity;
                            } else {
                                if (!$productStockRecipe || $productStockRecipe->quantity < $detail->quantity) {
                                    AlertHelper::error('Gagal', "Stok produk {$detail->product->name} tidak mencukupi.");
                                    $detail->quantity = 0;
                                }
                            }

                            $detail->fill([
                                'type' => $detail->type ?? 'single',
                                'price' => $priceRecipe,
                                'sub_total_price' => $priceRecipe * $detail->quantity
                            ])->save();
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat memperbarui resep: ' . $e->getMessage());
            Log::info('Error updating transaction recipe: ' . $e->getMessage());
        }
    }

    public function changeProduct($detailId = null)
    {
        $this->transaction_detail_id = $detailId;
        $this->type = 'medicine';
        $this->dispatch('open-modal', ['id' => 'modalProduct']);
    }

    public function choiceProduct($id)
    {
        if (!$this->transaction_detail_id) {
            $product = Product::find($id);

            $productStock = ProductStock::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->first();

            if (!$productStock || $productStock->quantity <= 0) {
                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            }

            $productPrice = ProductPrice::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->where('is_updated', true)
                ->first();

            if (!$productPrice) {
                return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
            }

            $transactionItem = TransactionDetail::where('transaction_id', $this->transaction_id)
                ->where('product_id', $product->id)
                ->first();

            if ($transactionItem) {
                $transactionItem->increment('quantity', 1);
                $transactionItem->price = $productPrice->price;
                $transactionItem->sub_total_price = $productPrice->price * $transactionItem->quantity;

                $transactionItem->save();
            } else {

                if ($product->is_narcotic) {
                    if (!$this->user_asign_narcotic_id) {
                        $this->is_narcotic = true;
                        $this->product_id = $product->id;
                        $this->product_name = $product->name;
                        $this->barcode = true;

                        $this->dispatch('close-modal', ['id' => 'modalProduct']);
                        return $this->dispatch('open-modal', ['id' => 'modalNarcotic']);
                    }
                }

                TransactionDetail::create([
                    'transaction_id' => $this->transaction_id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $productPrice->price,
                    'sub_total_price' => $productPrice->price,
                    'is_narcotic' => $this->is_narcotic,
                    'user_asign_narcotic_id' => $this->user_asign_narcotic_id,
                ]);
            }

            $this->closeModalProduct();
            $this->getMedicines();
            return AlertHelper::success('Berhasil', 'Obat berhasil ditambahkan.');
        } else {
            // Get authenticated user's company and branch once
            $companyId = auth()->user()->company_id;
            $branchId = Branch::where('company_id', $companyId)->value('id');

            // Find product with related data in one query
            $product = Product::with(['productStock', 'productPrice'])
                ->where('id', $id)
                // ->whereHas('productType', fn($query) => $query->where('name', 'Obat'))
                ->first();

            if (!$product) {
                // $this->reset('search_sku');
                return AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
            }

            // Check stock
            if ($product->is_non_stock == false) {
                $productStock = $product->productStock()
                    ->where('company_id', $companyId)
                    ->where('branch_id', $branchId)
                    ->first();

                if (!$productStock || $productStock->quantity <= 0) {
                    return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
                }
            }


            $productPrice = $product->productPrice()
                ->where('company_id', $companyId)
                ->where('branch_id', $branchId)
                ->where('is_updated', true)
                ->first();

            if (!$productPrice) {
                return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
            }

            $transactionDetail = TransactionDetail::find($this->transaction_detail_id);

            if (!$transactionDetail) {
                return AlertHelper::error('Gagal', 'Detail transaksi tidak ditemukan.');
            }

            $transactionDetail->product_id = $product->id;
            $transactionDetail->price = $productPrice->price;
            $transactionDetail->sub_total_price = $productPrice->price * $transactionDetail->quantity;
            $transactionDetail->save();

            $this->closeModalProduct();
            $this->getRecipes();
        }
        return AlertHelper::success('Berhasil', 'Obat berhasil ditambahkan ke resep.');
    }

    public function closeModalProduct()
    {
        $this->dispatch('close-modal', ['id' => 'modalProduct']);
        $this->reset(['transaction_detail_id', 'type']);
    }

    public function render()
    {
        return view('livewire.admin.pharmacy.consultation.detail.admin-pharmacy-consultation-detail-index', [
            'products' => $this->getProductRenders(),
        ])
            ->extends('layout.app')
            ->section('content');
    }

    private function getProductRenders()
    {
        if ($this->type !== 'medicine') {
            return [];
        }

        return $this->getProducts(false);
    }

    private function getProducts($isAction = true)
    {
        $query = Product::search($this->search)
            ->select('id', 'sku_number', 'name', 'description', 'company_id')
            ->where('company_id', Auth::user()->company_id);

        // Optimasi: constraint dengan branch_id untuk relations
        $branchId = $this->getBranchId();

        $query->with([
            'company:id,name',
            'productStock' => function ($q) use ($branchId) {
                $q->select('id', 'product_id', 'quantity')
                    ->where('branch_id', $branchId);
            },
            'productPrice' => function ($q) use ($branchId) {
                $q->select('id', 'product_id', 'price', 'recipe')
                    ->where('branch_id', $branchId);
            }
        ]);

        if ($isAction) {
            $query->whereIn('product_type_id', $this->product_types);
        } else {
            $query->whereNotIn('product_type_id', $this->product_types);
        }

        return $query->paginate($this->perPage);
    }

    public function closeModalNarcotic()
    {
        $this->reset('is_narcotic', 'user_asign_narcotic_id', 'product_id', 'product_name', 'search_sku', 'username_or_email', 'password');
        $this->dispatch('close-modal', ['id' => 'modalNarcotic']);
        if ($this->barcode) {
            $this->changeProduct();
            $this->reset('barcode');
        }
    }

    public function submitNarcotic()
    {
        $this->validate([
            'username_or_email' => 'required',
            'password' => 'required',
        ]);

        $company = Company::find(Auth::user()->company_id);

        if (!$company) {
            return AlertHelper::error('Error', 'Perusahaan tidak ditemukan.');
        }

        // Find user with smart identity resolution
        $userResult = $this->findHeadUserWithIdentityResolution($company->id);

        if (!$userResult['success']) {
            return AlertHelper::error('Akses Ditolak', $userResult['message']);
        }

        $user = $userResult['user'];
        $loginMethod = $userResult['login_method'];

        // Check password
        if (!Hash::check($this->password, $user->password)) {
            return AlertHelper::error('Akses Ditolak', 'Password salah. Silakan periksa kembali atau hubungi administrator perusahaan.');
        }

        // Check if user is head in this company
        $isHead = $user->companyRoles()
            ->where('company_id', $company->id)
            ->where('is_head', true)
            ->where('is_active', true)
            ->exists();

        if (!$isHead) {
            return AlertHelper::error('Akses Ditolak', 'Anda bukan supervisor di perusahaan ini.');
        }

        // Success - user is authenticated and is head
        $this->user_asign_narcotic_id = $user->id;
        $this->choiceProduct($this->product_id);
        $this->closeModalNarcotic();

        // Log head verification activity
        $this->logHeadVerificationActivity($user, $company, $loginMethod);
    }

    /**
     * Find user with smart identity resolution and head validation
     */
    protected function findHeadUserWithIdentityResolution($companyId)
    {
        $identifier = $this->username_or_email;

        // Strategy 1: Find by main fields (email, username, phone) - Employee only
        $mainUser = $this->findHeadByMainFields($identifier, $companyId);
        if ($mainUser) {
            return [
                'success' => true,
                'user' => $mainUser['user'],
                'login_method' => $mainUser['method'],
                'message' => 'Found via main fields'
            ];
        }

        // Strategy 2: Find by alternative contacts - Employee only
        $altUser = $this->findHeadByAlternativeContacts($identifier, $companyId);
        if ($altUser) {
            return [
                'success' => true,
                'user' => $altUser['user'],
                'login_method' => $altUser['method'],
                'message' => 'Found via alternative contacts'
            ];
        }

        // Strategy 3: Handle email sama tapi beda phone case - Employee only
        $conflictUser = $this->handleHeadEmailPhoneConflict($identifier, $companyId);
        if ($conflictUser) {
            return [
                'success' => true,
                'user' => $conflictUser['user'],
                'login_method' => $conflictUser['method'],
                'message' => 'Resolved identity conflict'
            ];
        }

        return [
            'success' => false,
            'user' => null,
            'login_method' => null,
            'message' => 'Username atau email tidak ditemukan, atau Anda bukan supervisor di perusahaan ini.'
        ];
    }

    /**
     * Find user by main fields (email, username, phone) - Employee only with head check
     */
    protected function findHeadByMainFields($identifier, $companyId)
    {
        // Cari user berdasarkan email, username, atau phone (hanya employee)
        $users = User::where('type_user', 'employee')
            ->where(function ($query) use ($identifier) {
                $query->where('username', $identifier)
                    ->orWhere('email', $identifier)
                    ->orWhere('phone', $identifier);
            })->get();

        // Filter users yang punya akses ke company ini dan is_head
        foreach ($users as $user) {
            if ($user->companyRoles()
                ->where('company_id', $companyId)
                ->where('is_active', true)
                ->where('is_head', true)
                ->exists()
            ) {

                // Determine which field matched
                $method = $this->determineMatchedField($user, $identifier);

                return [
                    'user' => $user,
                    'method' => $method
                ];
            }
        }

        return null;
    }

    /**
     * Find user by alternative contacts - Employee only with head check
     */
    protected function findHeadByAlternativeContacts($identifier, $companyId)
    {
        // Cari di alternative contacts dengan context company ini (hanya employee)
        $users = User::where('type_user', 'employee')
            ->whereJsonContains('alternative_contacts', function ($contact) use ($identifier, $companyId) {
                return ($contact['value'] === $identifier && $contact['context'] == $companyId);
            })->get();

        foreach ($users as $user) {
            if ($user->companyRoles()
                ->where('company_id', $companyId)
                ->where('is_active', true)
                ->where('is_head', true)
                ->exists()
            ) {

                // Get contact type from alternative contacts
                $contacts = $user->alternative_contacts ?? [];
                $contactType = null;

                foreach ($contacts as $contact) {
                    if ($contact['value'] === $identifier && $contact['context'] == $companyId) {
                        $contactType = $contact['type'];
                        break;
                    }
                }

                return [
                    'user' => $user,
                    'method' => 'alternative_' . $contactType
                ];
            }
        }

        return null;
    }

    /**
     * Handle case email sama tapi phone beda - Employee only with head check
     */
    protected function handleHeadEmailPhoneConflict($identifier, $companyId)
    {
        // Check if identifier is email
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        // Find users with same email but may not have access to this company (hanya employee)
        $usersWithSameEmail = User::where('type_user', 'employee')
            ->where('email', $identifier)
            ->get();

        foreach ($usersWithSameEmail as $user) {
            // Check if user has head role in this company
            if ($user->companyRoles()
                ->where('company_id', $companyId)
                ->where('is_active', true)
                ->where('is_head', true)
                ->exists()
            ) {

                return [
                    'user' => $user,
                    'method' => 'email'
                ];
            }
        }

        return null;
    }

    /**
     * Log head verification activity
     */
    protected function logHeadVerificationActivity($user, $company, $loginMethod)
    {
        \Log::info('Head verification for narcotic', [
            'user_id' => $user->id,
            'user_type' => $user->type_user,
            'company_id' => $company->id,
            'verification_method' => $loginMethod,
            'identifier_used' => $this->username_or_email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
    }

    private function getBranchId()
    {
        return Cache::remember('branch_id_' . Auth::user()->company_id, 3600, function () {
            return Branch::where('company_id', Auth::user()->company_id)->value('id');
        });
    }

    public function confirmDeleteMedicine($id)
    {
        return AlertHelper::confirmDelete('deleteMedicine', 'Apakah Anda yakin ingin menghapus obat ini?', $id);
    }

    public function deleteMedicine($id)
    {
        $transactionDetail = TransactionDetail::find($id[0]);

        if (!$transactionDetail) {
            return AlertHelper::error('Gagal', 'Obat tidak ditemukan.');
        }

        // Hapus detail transaksi
        $transactionDetail->delete();

        // Refresh data
        $this->getMedicines();

        return AlertHelper::success('Berhasil', 'Obat berhasil dihapus.');
    }

    public function updateTotal()
    {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction) {
            $first_service_price = $this->is_outside_pharmacy ? 0 : TransactionRecipe::where('transaction_id', $this->transaction_id)->sum('price_service_one');
            $price_product_price = $this->is_outside_pharmacy ? 0 : TransactionRecipe::where('transaction_id', $this->transaction_id)->sum('sub_total_price');
            $product_price = $this->is_outside_pharmacy ? TransactionDetail::whereIn('type_transaction', ['action', 'other'])->where('transaction_id', $this->transaction_id)
                ->sum('sub_total_price') : TransactionDetail::where('transaction_id', $this->transaction_id)
                ->sum('sub_total_price');

            $transaction->sub_total_price_embalage = $first_service_price + $price_product_price + $product_price;

            if ($transaction->sub_total_price_embalage < 25000) {
                $transaction->second_service_price = 0;
            } elseif ($transaction->sub_total_price_embalage >= 25000 && $transaction->sub_total_price_embalage <= 50000) {
                $transaction->second_service_price = 500;
            } elseif ($transaction->sub_total_price_embalage >= 50001 && $transaction->sub_total_price_embalage <= 100000) {
                $transaction->second_service_price = 1000;
            } elseif ($transaction->sub_total_price_embalage >= 100001 && $transaction->sub_total_price_embalage <= 1000000) {
                $transaction->second_service_price = 1500;
            } elseif ($transaction->sub_total_price_embalage >= 1000001) {
                $transaction->second_service_price = 2000;
            }

            $transaction->first_service_price = $first_service_price;
            $transaction->price_product_price = $price_product_price;
            $transaction->product_price = $product_price;
            $transaction->embalage = $this->is_outside_pharmacy ? 0 : $transaction->second_service_price + $first_service_price + $price_product_price;
            $total = $transaction->embalage + $product_price;

            $transaction->sub_total_price = $total;

            // Hitung diskon
            if ($total >= 1) {
                if ($this->discount_type == 'percentage') {
                    // ✅ Konversi ke float sebelum disimpan dan digunakan dalam perhitungan
                    $discountValue = (float) str_replace(',', '.', $this->discount);
                    $transaction->discount = $discountValue;
                    $transaction->discount_value = ($total * $discountValue) / 100;
                } else {
                    $discount = intval(str_replace('.', '', $this->discount));
                    $discount = $total < $discount ? $total : $discount;
                    $transaction->discount = $discount;
                    $transaction->discount_value = $discount;
                }
            } else {
                $transaction->discount = 0;
                $transaction->discount_type = 'rupiah';
                $transaction->discount_value = 0;
            }

            // ✅ Update format display untuk discount
            $this->discount = $this->discount_type == 'rupiah'
                ? number_format($transaction->discount, 0, ',', '.')
                : number_format($transaction->discount, 2, ',', '.');

            $transaction->discount_type = $this->discount_type;

            // Set sub_total_price_before_rounding
            $total = $transaction->sub_total_price_before_rounding = $total;

            // Hitung grand total sebelum pembulatan
            $grandTotal = $total - $transaction->discount_value;

            // Pembulatan
            $rounding = 0;
            $roundedTotal = 0;
            $remainder = 0;

            if ($grandTotal <= 0) {
                $roundedTotal = 0;
                $rounding = -$grandTotal;
                $remainder = 0;
            } else {
                $remainder = $grandTotal % 1000;

                if ($remainder < 250) {
                    $roundedTotal = $grandTotal - $remainder;
                    $rounding = -$remainder;
                } elseif ($remainder < 750) {
                    $roundedTotal = $grandTotal - $remainder + 500;
                    $rounding = 500 - $remainder;
                } else {
                    $roundedTotal = $grandTotal - $remainder + 1000;
                    $rounding = 1000 - $remainder;
                }
            }

            $transaction->rounding = $rounding;
            $transaction->grand_total_price = $roundedTotal;
            $transaction->rounding_remainder = $remainder;
            $transaction->payment_amount = $transaction->transactionPayments()->sum('payment_amount');
            $transaction->payment_change = $transaction->payment_amount < $transaction->grand_total_price ? 0 : $transaction->payment_amount - $transaction->grand_total_price;
            $transaction->remaining_bill = $transaction->grand_total_price - $transaction->payment_amount;
            $transaction->remaining_bill = $transaction->remaining_bill < 0 ? 0 : $transaction->remaining_bill;
            $transaction->grand_total_price_admin_fee = $transaction->grand_total_price + $transaction->single_payment_admin_fee;
            $transaction->save();

            $this->reset('transaction');
            $this->transaction = Transaction::find($this->transaction_id);
        }
    }

    public function updatedIsOutsidePharmacy()
    {
        $transaction = Transaction::find($this->transaction_id);
        if ($transaction) {
            $transaction->is_outside_pharmacy = $this->is_outside_pharmacy;
            $transaction->save();
            $this->getActions();
            $this->getRecipes();
            $this->getMedicines();
        }
    }

    public function confirmSave()
    {
        return AlertHelper::confirm('save', 'Apakah Anda yakin ingin menyimpan transaksi ini?');
    }

    public function save()
    {
        $transaction = Transaction::find($this->transaction_id);
        if (!$transaction) {
            return AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
        }

        // $this->updateServiceTransactionRecipe($transaction);

        // Lakukan penyimpanan data transaksi
        $transaction->update([
            'status' => 'process',
            'pharmacy_id' => auth()->user()->id,
            'pharmacy_name' => auth()->user()->name,
        ]);

        session()->flash('saved', [
            'title' => 'Simpan Berhasil!',
            'text' => 'Data transaksi berhasil disimpan!',
        ]);

        return redirect()->route('user.pharmacy.consultation');
    }

    private function updateServiceTransactionRecipe($transaction)
    {
        $transactionRecipes = TransactionRecipe::where('transaction_id', $transaction->id)->get();

        $encounter = Encounter::where('transaction_id', $transaction->id)->first();
        $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
        $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();

        foreach ($transactionRecipes as $transactionRecipe) {
            $transactionDetails = $transactionRecipe->transactionDetail;

            foreach ($transactionDetails as $index => $transactionDetail) {
                $validity = $this->getValidatyRequest($transactionDetail, $transactionRecipe);
                $medication = Medication::where('product_id', $transactionDetail->product_id)->first();

                if (!$medication) {
                    continue;
                }

                $data = [
                    'id' => null,
                    'transaction_detail_id' => $transactionDetail->id,
                    'company_id' => $transaction->company_id,
                    'patient_id' => $patient->id ?? null,
                    'encounter_id' => $encounter->id ?? null,
                    'medication_id' => $medication->id ?? null,
                    'requester_id' => $doctor->id ?? null,
                    'status' => 'active',
                    'intent' => 'order',
                    'category' => 'outpatient',
                    'priority' => 'routine',
                    'course_of_therapy' => 'continuous',
                    'dosage_instructions' => [
                        [
                            'sequence' => $index + 1,
                            'text' => $transactionRecipe->howToUse->name ?? '',
                            'additional_text' => $transactionRecipe->howToUse->description ?? '',
                            'patient_instruction' => $transactionRecipe->description ?? '',
                            'timing_repeat_frequency' => $transactionRecipe->howToUse->time ?? 1,
                            'timing_repeat_period' => $transactionRecipe->howToUse->day ?? 1,
                            'timing_repeat_period_unit' => 'd',
                            'route_coding_code' => $transactionRecipe->route_coding_code ?? null,
                            'dose_rate_type_coding_code' => 'ordered',
                            'dose_rate_quantity_value' => $transactionDetail->quantity ?? 0,
                            'dose_rate_quantity_code' => $transactionDetail->product->denominator_code ?? null,
                        ]
                    ],
                    'dispense_request' => [
                        'interval_value' => 1,
                        'interval_code' => 'd',
                        'validity_start' => $validity['validity_start'] ?? null,
                        'validity_end' => $validity['validity_end'] ?? null,
                        'number_repeat' => 0,
                        'quantity_value' => $transactionDetail->quantity ?? 0,
                        'quantity_code' => $transactionDetail->product->denominator_code ?? null,
                        'expect_value' => intval(Str::replace('.', '', number_format($validity['expect_value'] ?? 0, 0, ',', '.'))),
                        'expect_code' => 'd',
                    ]
                ];

                app(apiservice::class)->createMedicationRequest($data);
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

    public function openModalHowToUse($transactionRecipeId)
    {
        $this->transaction_recipe_id = $transactionRecipeId;
        $this->dispatch('open-modal', ['id' => 'modalHowToUse']);
    }

    public function closeModalHowToUse()
    {
        $this->reset(['transaction_recipe_id', 'name_how_to_use', 'description_how_to_use', 'day_how_to_use', 'time_how_to_use']);
        $this->dispatch('close-modal', ['id' => 'modalHowToUse']);
    }

    public function saveHowToUse()
    {
        $this->validate([
            'name_how_to_use' => 'required|string|max:255',
            'description_how_to_use' => 'required|string|max:500',
            'day_how_to_use' => 'required|integer|min:1|max:30',
            'time_how_to_use' => 'required|integer|min:1|max:24',
        ]);

        $transactionRecipe = TransactionRecipe::find($this->transaction_recipe_id);
        if (!$transactionRecipe) {
            return AlertHelper::error('Gagal', 'Resep tidak ditemukan.');
        }

        $transactionRecipe->how_to_use_id = HowToUse::create([
            'name' => $this->name_how_to_use,
            'description' => $this->description_how_to_use,
            'day' => $this->day_how_to_use,
            'time' => $this->time_how_to_use,
        ])->id;

        $transactionRecipe->save();

        $this->closeModalHowToUse();
        $this->detailMedicine();
        return AlertHelper::success('Berhasil', 'Cara penggunaan berhasil disimpan.');
    }
}
