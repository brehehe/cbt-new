<?php

namespace App\Livewire\Admin\Pharmacy\Sale\Recipe;

use App\Helpers\AlertHelper;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Product\Product;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionPayment;
use Auth;
use Livewire\Component;
use Str;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\MedicineType\MedicineType;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionProduct;
use App\Models\Transaction\TransactionRecipe;
use App\Models\User;
use App\Traits\Product\ProductTrait;
use DB;
use Hash;
use Session;
use Log;
use Livewire\WithPagination;

class AdminPharmacySaleRecipeIndex extends Component
{
    use ProductTrait, WithPagination;
    protected $queryString = [
        'pageProduct' => ['except' => 1], // Ini akan menghapus ?pageProduct=1 dari URL
        'searchProduct' => ['except' => ''],
    ];
    public $searchProduct = '';
    public $perPageProduct = 5;
    public $transaction_id, $transaction;
    public $search_sku;
    public $transaction_details = [], $discount, $discount_type, $medicine_types = [], $supporting_products = [];
    public $payment_method_id, $payment_amount, $is_single_payment, $admin_fee, $description;
    public $transaction_recipe_id, $is_narcotic = false, $user_asign_narcotic_id, $product_id, $product_name, $barcode, $username_or_email, $password, $is_outside_pharmacy = false, $actions = [], $medicines = [];

    public function mount()
    {
        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }

        $transaction_id = Session::get('transaction_id');

        if ($transaction_id) {
            $transaction = Transaction::find($transaction_id);

            if ($transaction) {
                if ($transaction->type == 'non-resep') {
                    return redirect()->route('user.pharmacy.sale.detail');
                }

                $this->transaction = $transaction;
                $this->transaction_id = $transaction_id;
                $this->discount_type = $transaction->discount_type ?? 'rupiah';
                $this->discount =$this->discount_type == 'rupiah' ? number_format($transaction->discount,0,',','.') : Str::replace(',','.', $transaction->discount);

                $this->medicine_types = MedicineType::where('company_id', Auth::user()->company_id)
                    ->select('id', 'name')
                    ->orderBy('name', 'asc')
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

                $this->is_outside_pharmacy = $transaction->is_outside_pharmacy ?? false;

                $this->details();
                $this->getActions();
                $this->getMedicines();
            } else {
                return redirect()->route('user.pharmacy.sale');
            }
        } else {
            return redirect()->route('user.pharmacy.sale');
        }
    }

    public function updatedDiscountType() {
        $this->discount = 0;
        $this->updateTotal();
    }

    public function updatedDiscount() {
        if ($this->discount_type == 'percentage') {
            $discount = Str::replace(',','.', $this->discount);
            $this->discount = $discount <= 0 ? 0 : ($discount > 100 ? 100 : $discount);
        } else {
            $this->discount = intval(str_replace('.', '', $this->discount));
        }

        $this->updateTotal();
    }

    public function updatedSearchSku()
    {
        $this->search_sku = ltrim($this->search_sku);

        $this->choiceProductChange();
    }

    public function getActions() {
        $this->reset(['actions']);

        $actions = TransactionDetail::where('transaction_id', $this->transaction_id)
            ->where('type_transaction', 'action')
            ->with('product:id,sku_number,name,description,company_id')
            ->orderBy('order', 'asc')
            ->get();

        foreach ($actions as $action) {
            $this->actions[] = [
                'id' => $action->id,
                'product_id' => $action->product_id,
                'product_name' => $action->product?->name,
                'description' => $action->description,
                'quantity' => $action->quantity,
                'price' => $action->price,
                'sub_total_price' => $action->sub_total_price,
            ];
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

   public function choiceProductChange()
    {
        // Get authenticated user's company and branch once
        $companyId = auth()->user()->company_id;
        $branchId = Branch::where('company_id', $companyId)->value('id');

        // Find product with related data in one query
        $product = Product::with(['productStock', 'productPrice'])
            ->where('sku_number', $this->search_sku)
            ->whereHas('productType', fn($query) => $query->where('name', 'Obat'))
            ->first();

        if (!$product) {
            $this->reset('search_sku');
            return AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
        }

        // Check stock
        $productStock = $product->productStock()
            ->where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->first();

        if (!$productStock || $productStock->quantity <= 0) {
            return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
        }

        // Check price
        $productPrice = $product->productPrice()
            ->where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->where('is_updated', true)
            ->first();

        if (!$productPrice) {
            return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
        }

        if ($product->is_narcotic) {
            if (!$this->user_asign_narcotic_id) {
                $this->is_narcotic = true;
                $this->product_id = $product->id;
                $this->product_name = $product->name;

                $this->dispatch('close-modal', ['id' => 'modalProduct']);
                return $this->dispatch('open-modal', ['id' => 'modalNarcotic']);
            }
        }

        // Get or create transaction recipe
        $transactionRecipe = $this->transaction_recipe_id
            ? TransactionRecipe::find($this->transaction_recipe_id)
            : TransactionRecipe::create([
                'transaction_id' => $this->transaction_id,
                'company_id' => $companyId,
                'branch_id' => $branchId,
            ]);

        // Create transaction detail
        TransactionDetail::create([
            'transaction_recipe_id' => $transactionRecipe->id,
            'transaction_id' => $this->transaction_id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $productPrice->price,
            'sub_total_price' => $productPrice->price,
            'type_transaction' => 'recipe',
        ]);

        // Clean up and return success
        $this->details();
        $this->updateTotal();
        $this->reset('search_sku');
        $this->closeModal();

        return AlertHelper::success('Berhasil', 'Produk berhasil ditambahkan ke keranjang.');
    }

     public function closeModalNarcotic()
    {
        $this->reset('is_narcotic', 'user_asign_narcotic_id', 'product_id', 'product_name','search_sku','username_or_email', 'password');
        $this->dispatch('close-modal', ['id' => 'modalNarcotic']);
        if ($this->barcode) {
            $this->openModal();
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
        $this->choiceProductChange();

        $this->reset('barcode');

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
                ->exists()) {

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
                    ->whereJsonContains('alternative_contacts', function($contact) use ($identifier, $companyId) {
                        return ($contact['value'] === $identifier && $contact['context'] == $companyId);
                    })->get();

        foreach ($users as $user) {
            if ($user->companyRoles()
                ->where('company_id', $companyId)
                ->where('is_active', true)
                ->where('is_head', true)
                ->exists()) {

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
                ->exists()) {

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

    public function details() {
        $this->transaction_details = [];

        $transactionDetails = $this->is_outside_pharmacy ? [] : TransactionRecipe::where('transaction_id', $this->transaction_id)
        ->orderBy('order', 'asc')
        ->get();

        foreach ($transactionDetails as $key => $transactionDetail) {
            $medicine_type = MedicineType::find($transactionDetail->medicine_type_id);
            $this->transaction_details[] = [
                'id' => $transactionDetail->id,
                'medicine_type_id' => $transactionDetail->medicine_type_id,
                'medicine_type_name' => $medicine_type ? $medicine_type->name : null,
                'is_single'=> $medicine_type ? $medicine_type->is_single : false,
                'numero_recipe' => $transactionDetail->numero_recipe,
                'price_service_one'=> number_format($medicine_type ? $medicine_type->service_price : 0, 0, ',', '.'),
                'product_id' => $transactionDetail->product_id,
                'product_name' => $transactionDetail->product->name ?? '',
                'quantity'=> $transactionDetail->quantity,
                'price' => number_format($transactionDetail->price, 0, ',', '.'),
                'sub_total_price' => number_format($transactionDetail->sub_total_price, 0, ',', '.'),
                'description' => $transactionDetail->description,
            ];

            foreach ($transactionDetail->transactionDetail as $detail) {
                $this->transaction_details[$key]['details'][] = [
                    'id' => $detail->id,
                    'product_id' => $detail->product_id,
                    'product_name' => $detail->product->name,
                    'type'=>$detail->type,
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
        $this->updateTransactionRecipe();
    }

    public function updatedTransactionDetails() {
        foreach ($this->transaction_details as $key => $value) {
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
        $this->details();
        $this->updateTotal();
        $this->closeModal();
    }

    public function updateTransactionRecipe() {
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
                        if (!$productStock) {
                            $quantity = 0;
                        } elseif ($productStock->quantity < $numeroRecipe) {
                            AlertHelper::error('Gagal', "Stok produk {$transactionRecipe->product->name} tidak mencukupi.");
                            $quantity = 0;
                        } else {
                            $quantity = $numeroRecipe;
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
                            if (!$productStockRecipe) {
                                $quantityRecipe = 0;
                            } elseif ($productStockRecipe->quantity < $numeroRecipe) {
                                AlertHelper::error('Gagal', "Stok produk {$detail->product->name} tidak mencukupi.");
                                $quantityRecipe = 0;
                            } else {
                                $quantityRecipe = $numeroRecipe;
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

                            if (!$productStockRecipe || $productStockRecipe->quantity < $detail->quantity) {
                                AlertHelper::error('Gagal', "Stok produk {$detail->product->name} tidak mencukupi.");
                                $detail->quantity = 0;
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

    public function updateQuantity($transactionDetailId, $quantity)
    {
        $transactionDetail = TransactionDetail::find($transactionDetailId);

        if (!$transactionDetail) {
            return AlertHelper::error('Gagal', 'Detail transaksi tidak ditemukan.');
        }

        $productStock = ProductStock::where('product_id', $transactionDetail->product_id)
            ->where('company_id', auth()->user()->company_id)
            ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
            ->first();

        if ($quantity === 'decrement') {

            if (!$productStock || $productStock->quantity <= 0) {
                $transactionDetail->quantity = 1;
                $transactionDetail->save();

                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            }

            if ($transactionDetail->quantity <= 1) {
                $transactionDetail->quantity = 1;
                $transactionDetail->save();

                return AlertHelper::error('Gagal', 'Jumlah produk tidak boleh kurang dari 1.');
            }

            $transactionDetail->decrement('quantity');
        }


        if ($quantity === 'increment') {
            if ($productStock->quantity <= $transactionDetail->quantity) {
                $transactionDetail->quantity = $productStock->quantity;
            } else {
                $transactionDetail->increment('quantity');
            }
        }

        $transactionDetail->sub_total_price = $transactionDetail->price * $transactionDetail->quantity;
        $transactionDetail->save();

        $this->details();
        $this->updateTotal();
    }

    public function confirmSaveTransaction($type) {
        $saveFunction = $type == 'draft' ? 'saveDraft' : ($type == 'process' ? 'saveTransaction' : 'saveSuccessTransaction');

        return AlertHelper::confirmSave($saveFunction,"Apakah Anda yakin ingin menyimpan transaksi ".Str::title($type)." ini?");
    }

    public function saveDraft() {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction) {
            $transaction->status = 'draft';
            $transaction->save();

            AlertHelper::success('Berhasil', 'Transaksi berhasil disimpan sebagai draft.');
            return redirect()->route('user.pharmacy.sale');
        } else {
            AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
            return redirect()->route('user.pharmacy.sale');
        }
    }

    public function saveTransaction() {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction->transactionRecipes()->count() <= 0) {
            return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena tidak ada item yang ditambahkan.');
        }

        $recipes = $transaction->transactionRecipes;

        foreach ($recipes as $key_recipe => $recipe) {
            if ($recipe->medicine_type_id == null || $recipe->medicine_type_id == 0) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena tipe resep pada /R' . ($key_recipe + 1) . ' belum dipilih.');
            }

            if ($recipe->numero_recipe <= 0) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena Quantity Resep pada /R' . ($key_recipe + 1) . ' belum diisi.');
            }

            $medicine_type = MedicineType::find($recipe->medicine_type_id);

            if (!$medicine_type->is_single && $recipe->product_id == null) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena produk pendukung pada /R' . ($key_recipe + 1) . ' belum dipilih.');
            }

            if ($recipe->transactionDetail()->count() <= 0) {
                return AlertHelper::error('Gagal', 'Detail Obat /R' . ($key_recipe + 1) . ' belum diisi.');
            }

            foreach ($recipe->transactionDetail as $detail) {
                if ($detail->product_id == null) {
                    return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena produk pada /R' . ($key_recipe + 1) . ' belum dipilih.');
                }

                if(!$medicine_type->is_single && ($detail->type != null || $detail->type == 'single') && ($detail->dosage_doctor <= 0 || $detail->dosage_drug <= 0)) {
                    return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena dosis dokter atau dosis obat pada /R' . ($key_recipe + 1) . ' belum diisi.');
                }

                if ($detail->quantity <= 0) {
                    return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena Quantity pada /R' . ($key_recipe + 1) . ' belum diisi.');
                }
            }
        }

        // if ((float) $transaction->remaining_bill > 0) {
        //     return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena masih ada sisa tagihan.');
        // }

        if ($transaction) {
            $transaction->status = 'process';
            $transaction->pharmacy_id = Auth::user()->user_id;
            $transaction->pharmacy_name = Auth::user()->name;
            $transaction->save();

            AlertHelper::success('Berhasil', 'Transaksi berhasil disimpan sebagai proses.');
            return redirect()->route('user.pharmacy.sale');
        } else {
            return AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
            // return redirect()->route('user.pharmacy.sale');
        }
    }

    public function confirmDeleteTransaction(){
        return AlertHelper::confirmDelete('deleteTransaction','Apakah Anda yakin ingin menghapus transaksi ini?', $this->transaction_id);
    }

    public function deleteTransaction($id) {
        try {
            DB::beginTransaction();

            $transaction = Transaction::find($id[0]);
            if ($transaction) {
                $transaction->status = 'canceled';
                $transaction->save();
                DB::commit();
                AlertHelper::success('Berhasil', 'Transaksi berhasil dihapus.');
            } else {
                AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menghapus transaksi: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus transaksi.');
        }

        return redirect()->route('user.pharmacy.sale');
    }

    public function saveSuccessTransaction()
        {
            $transaction = Transaction::find($this->transaction_id);

            if ($transaction->transactionRecipes()->count() <= 0) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena tidak ada item yang ditambahkan.');
            }

            $recipes = $transaction->transactionRecipes;

            foreach ($recipes as $key_recipe => $recipe) {
                if ($recipe->medicine_type_id == null || $recipe->medicine_type_id == 0) {
                    return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena tipe resep pada /R' . ($key_recipe + 1) . ' belum dipilih.');
                }

                if ($recipe->numero_recipe <= 0) {
                    return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena Quantity Resep pada /R' . ($key_recipe + 1) . ' belum diisi.');
                }

                $medicine_type = MedicineType::find($recipe->medicine_type_id);

                if (!$medicine_type->is_single && $recipe->product_id == null) {
                    return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena produk pendukung pada /R' . ($key_recipe + 1) . ' belum dipilih.');
                }

                if ($recipe->transactionDetail()->count() <= 0) {
                    return AlertHelper::error('Gagal', 'Detail Obat /R' . ($key_recipe + 1) . ' belum diisi.');
                }

                foreach ($recipe->transactionDetail as $detail) {
                    if ($detail->product_id == null) {
                        return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena produk pada /R' . ($key_recipe + 1) . ' belum dipilih.');
                    }

                    if(!$medicine_type->is_single && ($detail->type != null || $detail->type == 'single') && ($detail->dosage_doctor <= 0 || $detail->dosage_drug <= 0)) {
                        return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena dosis dokter atau dosis obat pada /R' . ($key_recipe + 1) . ' belum diisi.');
                    }

                    if ($detail->quantity <= 0) {
                        return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena Quantity pada /R' . ($key_recipe + 1) . ' belum diisi.');
                    }
                }
            }

            if ((float) $transaction->remaining_bill > 0) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena masih ada sisa tagihan.');
            }

            try {
                // Validasi awal
                $transaction = Transaction::with('transactionDetails','transactionRecipes','transactionPayments')->find($this->transaction_id);

                if (!$transaction) {
                    return $this->errorResponse('Transaksi tidak ditemukan.');
                }

                if ($transaction->transactionDetails->count() <= 0) {
                    return $this->errorResponse('Transaksi tidak dapat disimpan, karena tidak ada item yang ditambahkan.');
                }

                if ((float) $transaction->remaining_bill > 0) {
                    return $this->errorResponse('Transaksi tidak dapat disimpan, karena masih ada sisa tagihan.');
                }

                DB::beginTransaction();

                // $this->processTransactionDetails($transaction);

                $this->processTransactionPayments($transaction);

                if ($this->is_outside_pharmacy) {
                    $transaction->update(['status' => 'completed']);
                } else {
                    $transactionDetails = TransactionDetail::where('transaction_id', $this->transaction_id)
                        ->whereIn('type_transaction', ['medicine', 'recipe'])
                        ->count();

                    if ($transactionDetails <= 0) {
                        $transaction->update(['status' => 'completed']);
                    } else {
                        $transaction->update([
                            'status' => 'take_medicine',
                            'is_take_medicine' => true,
                        ]);
                    }
                }

                DB::commit();

                session()->flash('saved', [
                    'title' => 'Transaksi Berhasil!',
                    'text' => 'Transaksi berhasil disimpan sebagai pengambilan obat.',
                ]);

                return redirect()->route('user.pharmacy.sale');

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->handleError($e);
            }
        }

        private function processTransactionDetails($transaction)
        {
            $productService = new ProductService();
            $companyId = Auth::user()->company_id;
            $branch = Branch::where('company_id', $companyId)->firstOrFail();

            foreach ($this->transaction_details as $transactionDetailData) {
                $this->processRecipeLevel($transaction, $transactionDetailData, $productService, $companyId, $branch->id);
                $this->processDetailLevel($transaction, $transactionDetailData, $productService, $companyId, $branch->id);
            }
        }

        private function processRecipeLevel($transaction, $data, $productService, $companyId, $branchId)
        {
            $transactionRecipe = TransactionRecipe::findOrFail($data['id']);
            $product = Product::findOrFail($data['product_id']);

            $productPrice = ProductPrice::where('product_id', $product->id)
                ->where('company_id', $companyId)
                ->where('branch_id', $branchId)
                ->first();

            $hppPrice = $productPrice ? intval(Str::replace('.', '', number_format($productPrice->hpp_average,0,',','.'))) : 0;
            $quantity = $data['quantity'];
            $sellingPrice = intval(Str::replace('.', '', $data['price']));
            $data['sub_total_price'] = intval(Str::replace('.', '', $data['sub_total_price']));
            $transactionRecipe->update([
                'price_hpp' => $hppPrice,
                'sub_total_price_hpp' => $hppPrice * $quantity
            ]);

            $this->createTransactionProduct($transaction, $data, $product, $hppPrice, $quantity, $sellingPrice);
            $productService->createProductDecrement($product->id, $quantity, null, null, $sellingPrice, null, null, null, $data['id'], null);
        }

        private function processDetailLevel($transaction, $data, $productService, $companyId, $branchId)
        {
            if (!isset($data['details']) || !is_array($data['details'])) {
                return;
            }

            foreach ($data['details'] as $detailData) {
                $transactionDetail = TransactionDetail::findOrFail($detailData['id']);
                $productRecipe = Product::findOrFail($detailData['product_id']);

                $productPriceRecipe = ProductPrice::where('product_id', $productRecipe->id)
                    ->where('company_id', $companyId)
                    ->where('branch_id', $branchId)
                    ->first();

                $hppPrice = $productPriceRecipe ? intval(Str::replace('.', '', number_format($productPriceRecipe->hpp_average,0,',','.'))) : 0;
                $quantity = $detailData['quantity'];
                $sellingPrice = intval(Str::replace('.', '', number_format($detailData['price'],0,',','.')));
                $data['sub_total_price'] = intval(Str::replace('.', '', number_format($data['sub_total_price'],0,',','.')));

                $transactionDetail->update([
                    'price_hpp' => $hppPrice,
                    'sub_total_price_hpp' => $hppPrice * $quantity
                ]);

                $this->createTransactionProduct($transaction, $detailData, $productRecipe, $hppPrice, $quantity, $sellingPrice);

                $productService->createProductDecrement($productRecipe->id, $quantity, null, null, $sellingPrice, null, null, $detailData['id'], null, null);
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

        private function errorResponse($message)
        {
            return AlertHelper::error('Gagal', $message);
        }

        public function processTransactionPayments($transaction)
        {
            $payments = $transaction->transactionPayments;

            $lastPayment = $payments->last();

            foreach ($payments as $payment) {
                if ($payment->is($lastPayment)) {
                    $payment->payment_real = $payment->payment_amount - $transaction->payment_change;
                } else {
                    $payment->payment_real = $payment->payment_amount;
                }

                $payment->save();
            }
        }

        private function handleError(\Exception $e)
        {
            Log::error('Error dalam saveSuccessTransaction: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction_id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
            // return redirect()->route('user.pharmacy.sale');

            return;
        }

    public function updateTotal()
    {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction) {
            $first_service_price = TransactionRecipe::where('transaction_id', $this->transaction_id)->sum('price_service_one');
            $price_product_price = TransactionRecipe::where('transaction_id', $this->transaction_id)->sum('sub_total_price');
            $product_price = TransactionDetail::where('transaction_id', $this->transaction_id)
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
            $transaction->embalage = $transaction->second_service_price + $first_service_price + $price_product_price;
            $total = $transaction->embalage + $product_price;

            $transaction->sub_total_price = $total;

            // Hitung diskon
            if ($total >= 1) {
                if ($this->discount_type == 'percentage') {
                    $transaction->discount = Str::replace(',', '.', $this->discount);
                    $transaction->discount_value = ($total * $transaction->discount) / 100;
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

            $this->discount = $this->discount_type == 'rupiah'
                ? number_format($transaction->discount, 0, ',', '.')
                : Str::replace(',', '.', $this->discount);

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
            $transaction->rounding_remainder = $remainder; // ✅ disimpan di field baru
            $transaction->payment_amount = $transaction->transactionPayments()->sum('payment_amount');
            $transaction->payment_change = $transaction->payment_amount < $transaction->grand_total_price ? 0 : $transaction->payment_amount - $transaction->grand_total_price;
            $transaction->remaining_bill = $transaction->grand_total_price - $transaction->payment_amount;
            $transaction->remaining_bill = $transaction->remaining_bill < 0 ? 0 : $transaction->remaining_bill;
            $transaction->grand_total_price_admin_fee = $transaction->grand_total_price + $transaction->single_payment_admin_fee;
            $transaction->save();
            $this->reset('transaction');
            $this->transaction = $transaction;
        }
    }

    public function addDetail($transaction_recipe) {
        $this->transaction_recipe_id = $transaction_recipe;
        $this->dispatch('open-modal',['id'=>'modalProduct']);
    }

    public function confirmDeleteTransactionRecipe($transactionRecipeId)
    {
        return AlertHelper::confirmDelete('deleteTransactionRecipe','Apakah Anda yakin ingin menghapus item ini?', $transactionRecipeId);
    }

    public function confirmDeleteTransactionDetail($transactionDetailId)
    {
        return AlertHelper::confirmDelete('deleteTransactionDetail','Apakah Anda yakin ingin menghapus item ini?', $transactionDetailId);
    }

    public function deleteTransactionDetail($transactionDetailId)
    {
        $transactionDetail = TransactionDetail::find($transactionDetailId[0]);

        if ($transactionDetail) {
            $transactionDetail->delete();
            $this->details();
            $this->updateTotal();
            AlertHelper::success('Berhasil', 'Item berhasil dihapus dari keranjang.');
        } else {
            AlertHelper::error('Gagal', 'Item tidak ditemukan.');
        }
    }

    public function deleteTransactionRecipe($transactionRecipeId)
    {
        $transactionRecipe = TransactionRecipe::find($transactionRecipeId[0]);

        if ($transactionRecipe) {
            TransactionDetail::where('transaction_recipe_id', $transactionRecipe->id)
                ->where('transaction_id', $this->transaction_id)
                ->delete();

            $transactionRecipe->delete();
            $this->details();
            $this->updateTotal();
            AlertHelper::success('Berhasil', 'Item berhasil dihapus dari keranjang.');
        } else {
            AlertHelper::error('Gagal', 'Item tidak ditemukan.');
        }
    }

    public function confirmResetTransaction()
    {
        return AlertHelper::confirmDelete('resetTransaction','Apakah Anda yakin ingin mereset transaksi ini?', $this->transaction_id);
    }

    public function resetTransaction()
    {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction) {
            $transaction->sub_total_price = 0;
            $transaction->discount = 0;
            $transaction->discount_value = 0;
            $transaction->discount_type = 'rupiah';
            $transaction->grand_total_price = 0;
            $transaction->payment_amount = 0;
            $transaction->payment_change = 0;
            $transaction->remaining_bill = 0;
            $transaction->rounding = 0;
            $transaction->rounding_remainder = 0; // ✅ disimpan di field baru
            $transaction->save();
            $transaction->transactionPayments()->delete();
            $transaction->transactionDetails()->delete();
            $this->details();
            $this->updateTotal();
            AlertHelper::success('Berhasil', 'Transaksi berhasil direset.');
        } else {
            AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
        }
    }

    public function openModal()
    {
        $this->dispatch('open-modal',['id'=>'modalProduct']);
    }

    public function closeModal()
    {
        $this->reset('searchProduct', 'transaction_recipe_id');
        $this->resetPage('pageProduct');
        $this->dispatch('close-modal',['id'=>'modalProduct']);
    }

    public function getProduct($product_id) {
        $product = Product::find($product_id);
        $this->search_sku = $product->sku_number;
        $this->barcode = true;
        $this->choiceProductChange();
    }

    public function updatedSearchProduct()
    {
        $this->resetPage('pageProduct');
    }

    public function updatedPerPageProduct()
    {
        $this->resetPage('pageProduct');
    }

    public function openModalPayment()
    {
        $this->dispatch('open-modal',['id'=>'modalPayment']);
    }

    public function closeModalPayment()
    {
        $this->reset('payment_method_id', 'payment_amount', 'description','admin_fee','is_single_payment');
        $this->dispatch('close-modal',['id'=>'modalPayment']);
    }

    public function submitPayment()
    {
        $this->validate([
            'payment_method_id' => 'required',
            'payment_amount' => 'required',
        ]);

        $payment_amount = intval(Str::replace('.', '', $this->payment_amount));

        if ($payment_amount <= 0) {
            return AlertHelper::error('Gagal', 'Jumlah pembayaran tidak boleh kurang dari 1.');
        }

        $admin_fee = intval(Str::replace('.', '', $this->admin_fee));

        TransactionPayment::create([
            'user_id' => $this->transaction->patient_id,
            'transaction_id' => $this->transaction_id,
            'payment_method_id' => $this->payment_method_id,
            'description' => $this->description,
            'payment_amount' => $payment_amount,
            'admin_fee' => $admin_fee,
            'payment_real'=> $payment_amount + $admin_fee,
            'company_id' => Auth::user()->company_id,
        ]);

        $transaction = Transaction::find($this->transaction_id);
        if ($this->is_single_payment) {
            $transaction->payment_method_single_payment_id = $this->payment_method_id;
            $transaction->single_payment_admin_fee = $admin_fee;
            $transaction->single_payment_payment_amount = $payment_amount;
            $transaction->single_payment_payment_real = $payment_amount + $admin_fee;
            $transaction->is_single_payment = true;
        } else {
            $transaction->payment_method_single_payment_id = null;
            $transaction->single_payment_admin_fee = 0;
            $transaction->single_payment_payment_amount = 0;
            $transaction->single_payment_payment_real = 0;
            $transaction->is_single_payment = false;
        }
        $transaction->save();

        $this->closeModalPayment();
        $this->updateTotal();
        return AlertHelper::success('Berhasil', 'Pembayaran berhasil ditambahkan.');
    }

    public function confirmDeleteTransactionPayment($transactionPaymentId)
    {
        return AlertHelper::confirmDelete('deleteTransactionPayment','Apakah Anda yakin ingin menghapus pembayaran ini?', $transactionPaymentId);
    }

    public function updatedPaymentMethodId()
    {
        $paymentMethod = PaymentMethod::find($this->payment_method_id);
        if ($paymentMethod->is_single_payment) {
            $this->is_single_payment = true;
            $this->payment_amount = number_format($this->transaction->remaining_bill,0,',','.');
            $this->updatePaymentSinglePayment();
        } else {
            $this->payment_amount = 0;
            $this->is_single_payment = false;
        }
    }

    public function deleteTransactionPayment($transactionPaymentId)
    {
        $transactionPayment = TransactionPayment::find($transactionPaymentId[0]);

        if ($transactionPayment) {
            $transaction = Transaction::find($this->transaction_id);
            if ($transaction->is_single_payment) {
                $transaction->payment_method_single_payment_id = null;
                $transaction->single_payment_admin_fee = 0;
                $transaction->single_payment_payment_amount = 0;
                $transaction->single_payment_payment_real = 0;
                $transaction->is_single_payment = false;
                $transaction->save();
            }

            $transactionPayment->delete();
            $this->updateTotal();
            AlertHelper::success('Berhasil', 'Pembayaran berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Pembayaran tidak ditemukan.');
        }
    }

    public function updatedPaymentAmount() {
       $this->updatePaymentSinglePayment();
    }

    public function updatePaymentSinglePayment()
    {
        $payment_amount = intval(Str::replace('.', '', $this->payment_amount));
        $this->payment_amount = number_format($payment_amount,0,',','.');
        if ($this->is_single_payment) {
            $paymentMethod = PaymentMethod::find($this->payment_method_id);
            if ($paymentMethod->type_admin_fee == 'percentage') {
                $this->admin_fee = number_format($payment_amount? $payment_amount * ($paymentMethod->percentage / 100) : 0,0,',','.');
            } else {
                $this->admin_fee = number_format($payment_amount ? $paymentMethod->value_admin_fee : 0,0,',','.');
            }
        } else {
            $this->admin_fee = 0;
        }
    }

    public function render()
    {
        $products = Product::search($this->searchProduct)
            ->select('id', 'sku_number', 'name', 'description', 'company_id')
            ->whereHas('productType', function ($query) {
                    $query->where('name', 'Obat'); // atau 'Supporting Product' sesuai isi database
            })
            ->with('company:id,name','productStock:id,product_id,quantity','productPrice:id,product_id,price,recipe', 'productType:id,name')
            ->where('company_id', Auth::user()->company_id);

        return view('livewire.admin.pharmacy.sale.recipe.admin-pharmacy-sale-recipe-index',[
            'products'=>$products->orderBy('name', 'asc')->paginate($this->perPageProduct, ['*'], 'pageProduct'),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
