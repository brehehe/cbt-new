<?php

namespace App\Livewire\Admin\Pharmacy\Sale\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\MedicineType\MedicineType;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionPayment;
use App\Models\Transaction\TransactionProduct;
use App\Models\Transaction\TransactionRecipe;
use App\Models\User;
use App\Services\Product\ProductService;
use App\Traits\Product\ProductTrait;
use Auth;
use DB;
use Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use Session;
use Str;

class AdminPharmacySaleDetailIndex extends Component
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
    public $transaction_details = [], $discount, $discount_type;
    public $payment_method_id, $payment_amount, $is_single_payment, $admin_fee, $description, $is_narcotic, $user_asign_narcotic_id, $product_id, $product_name, $barcode, $username_or_email, $password;

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
                if (in_array($transaction->type, ['resep','konsultasi'])) {
                    return redirect()->route('user.pharmacy.sale.recipe');
                }

                $this->transaction = $transaction;
                $this->transaction_id = $transaction_id;
                $this->discount_type = $transaction->discount_type ?? 'rupiah';
                $this->discount =$this->discount_type == 'rupiah' ? number_format($transaction->discount,0,',','.') : Str::replace(',','.', $transaction->discount);

                $this->details();
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

    public function choiceProductChange()
    {
        $product = Product::where('sku_number', $this->search_sku)->first();

        if ($product) {

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
                    'is_narcotic' => $this->is_narcotic ?? false,
                    'user_asign_narcotic_id' => $this->user_asign_narcotic_id,
                    'type_transaction' => 'medicine',
                ]);
            }

            $this->details();
            $this->updateTotal();
            $this->reset('search_sku', 'is_narcotic', 'user_asign_narcotic_id','product_id', 'product_name','username_or_email', 'password');
            $this->closeModal();
            return AlertHelper::success('Berhasil', 'Produk berhasil ditambahkan ke keranjang.');
        } else {
            $this->reset('search_sku');
            return AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
        }
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

    public function updatedTransactionDetails() {
        foreach ($this->transaction_details as $key => $value) {

            $productStock = ProductStock::where('product_id', $value['product_id'])
            ->where('company_id', auth()->user()->company_id)
            ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
            ->first();

            if (!$productStock || $productStock->quantity <= 0) {
                $value['quantity'] = $productStock->quantity;
                $this->transaction_details[$key]['quantity'] = $productStock->quantity;

                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            } elseif ($productStock->quantity < $value['quantity']) {
                $value['quantity'] = $productStock->quantity;
                $this->transaction_details[$key]['quantity'] = $productStock->quantity;

                AlertHelper::error('Gagal', 'Stok produk tidak mencukupi.');
            } elseif ($value['quantity'] <= 0) {
                $value['quantity'] = 1;
                $this->transaction_details[$key]['quantity'] = 1;

                AlertHelper::error('Gagal', 'Jumlah produk tidak boleh kurang dari 1.');
            }

            $transactionDetail = TransactionDetail::find($value['id']);

            if ($transactionDetail) {
                $transactionDetail->quantity = $value['quantity'];
                $transactionDetail->sub_total_price = $transactionDetail->price * $value['quantity'];
                $transactionDetail->save();
            }
        }

        $this->details();
        $this->updateTotal();
        $this->closeModal();

    }

    public function details() {
        $this->transaction_details = [];

        $transactionDetails = TransactionDetail::where('transaction_id', $this->transaction_id)
            ->orderBy('order', 'asc')
            ->get();

            foreach ($transactionDetails as $key => $transactionDetail) {
                $this->transaction_details[] = [
                    'id' => $transactionDetail->id,
                    'product_id' => $transactionDetail->product_id,
                    'product_name' => $transactionDetail->product->name,
                    'quantity' => $transactionDetail->quantity,
                    'price' => $transactionDetail->price,
                    'sub_total_price' => $transactionDetail->sub_total_price,
                ];
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

        if ($transaction->transactionDetails()->count() <= 0) {
            return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena tidak ada item yang ditambahkan.');
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
            AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
            return redirect()->route('user.pharmacy.sale');
        }
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

    public function saveSuccessTransaction()
    {
        try {
            // Validasi awal di luar transaksi
            $transaction = Transaction::find($this->transaction_id);

            if (!$transaction) {
                return AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
            }

            if ($transaction->transactionDetails()->count() <= 0) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena tidak ada item yang ditambahkan.');
            }

            if ((float) $transaction->remaining_bill > 0) {
                return AlertHelper::error('Gagal', 'Transaksi tidak dapat disimpan, karena masih ada sisa tagihan.');
            }

            // Mulai database transaction
            DB::beginTransaction();

            // $productService = new ProductService();
            // $companyId = Auth::user()->company_id;
            // $branchId = Branch::where('company_id', $companyId)->first()?->id;

            // if (!$branchId) {
            //     throw new \Exception('Branch tidak ditemukan untuk perusahaan ini.');
            // }

            // foreach ($this->transaction_details as $transactionDetailData) {
            //     // Ambil transaction detail dengan relasi
            //     $transactionDetail = TransactionDetail::with('product')
            //         ->find($transactionDetailData['id']);

            //     if (!$transactionDetail) {
            //         throw new \Exception("Transaction detail dengan ID {$transactionDetailData['id']} tidak ditemukan.");
            //     }

            //     $product = $transactionDetail->product;
            //     if (!$product) {
            //         throw new \Exception("Product dengan ID {$transactionDetail->product_id} tidak ditemukan.");
            //     }

            //     // Ambil product price recipe
            //     $productPriceRecipe = ProductPrice::where('product_id', $product->id)
            //         ->where('company_id', $companyId)
            //         ->where('branch_id', $branchId)
            //         ->first();

            //     $hppPrice = $productPriceRecipe ? $productPriceRecipe->hpp_average : 0;
            //     $quantity = $transactionDetailData['quantity'];
            //     $sellingPrice = $transactionDetailData['price'];

            //     // Update transaction detail
            //     $transactionDetail->update([
            //         'price_hpp' => $hppPrice,
            //         'sub_total_price_hpp' => $hppPrice * $quantity
            //     ]);

            //     // Hitung profit dan margin
            //     $profit = ($sellingPrice - $hppPrice) * $quantity;
            //     $margin = $sellingPrice > 0 ? (($sellingPrice - $hppPrice) / $sellingPrice) * 100 : 0;

            //     // Create transaction product
            //     TransactionProduct::create([
            //         'transaction_id' => $transaction->id,
            //         'transaction_detail_id' => $transactionDetail->id,
            //         'product_id' => $product->id,
            //         'product_name' => $product->name,
            //         'quantity' => $quantity,
            //         'price' => $sellingPrice,
            //         'total' => $transactionDetailData['sub_total_price'],
            //         'hpp_average' => $hppPrice,
            //         'hpp_total' => $hppPrice * $quantity,
            //         'profit' => $profit,
            //         'margin' => $margin,
            //     ]);

            //     // Create product decrement
            //     $productService->createProductDecrement(
            //         $product->id,
            //         $quantity,
            //         null,
            //         null,
            //         $sellingPrice,
            //         null,
            //         null,
            //         $transactionDetail->id,
            //         null,
            //         null
            //     );
            // }

            $this->processTransactionPayments($transaction);

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

            DB::commit();

            session()->flash('saved', [
                'title' => 'Transaksi Berhasil!',
                'text' => 'Anda berhasil menyimpan transaksi!',
            ]);

            return redirect()->route('user.pharmacy.sale');

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Model tidak ditemukan: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction_id,
                'user_id' => Auth::id()
            ]);
            return AlertHelper::error('Gagal', 'Data yang diperlukan tidak ditemukan.');

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction_id,
                'user_id' => Auth::id(),
                'sql' => $e->getSql()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan database. Silakan coba lagi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction error: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction_id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::critical('Critical error in saveSuccessTransaction: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction_id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan sistem. Silakan hubungi administrator.');
        }
    }

    public function updateTotal()
    {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction) {
            $total = TransactionDetail::where('transaction_id', $this->transaction_id)
                ->sum('sub_total_price');

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
        $this->reset('searchProduct');
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
            ->with('company:id,name','productStock:id,product_id,quantity','productPrice:id,product_id,price,recipe')
            ->where('company_id', Auth::user()->company_id);

        $paymentMethod = PaymentMethod::where('company_id', Auth::user()->company_id);

        if ($this->transaction->transactionPayments()->where('is_single_payment', false)->exists()) {
            $paymentMethod->where('is_single_payment', false);
        }

        return view('livewire.admin.pharmacy.sale.detail.admin-pharmacy-sale-detail-index', [
            'products'=>$products->orderBy('name', 'asc')->paginate($this->perPageProduct, ['*'], 'pageProduct'),
            'paymentMethods'=> $paymentMethod->orderBy('name', 'asc')->get(),
            'transactionPayments'=> TransactionPayment::where('transaction_id', $this->transaction_id)
                ->orderBy('created_at', 'desc')
                ->get(),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
