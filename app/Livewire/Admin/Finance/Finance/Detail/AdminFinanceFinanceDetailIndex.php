<?php

namespace App\Livewire\Admin\Finance\Finance\Detail;

use App\Helpers\AlertHelper;
use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinancePayment;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Auth;
use Livewire\Component;
use Redirect;
use Session;
use Str;
use Carbon\Carbon;
use DB;

class AdminFinanceFinanceDetailIndex extends Component
{
    public $financeId;
    public $details = [];
    public $account_cashs = [];
    public $accounts = [];
    public $get_types = [];
    public $code, $description, $date, $type, $grand_total, $account_id;
    public $finance_payment_id;

    public function  mount()
    {
        $this->financeId = Session::get('finance_finance_id');

        if ($this->financeId === null) {
            $this->createDetail();
            $this->code = 'FIN' . date('ymd') . str_pad(Finance::whereDate('created_at', Carbon::now())->count() + 1, 4, '0', STR_PAD_LEFT);
            $this->date = Carbon::now()->format('Y-m-d');
        } else {
            $finance = Finance::find($this->financeId);
            if ($finance === null) {
                return redirect()->route('user.finance.finance');
            }

            $this->code = $finance->code;
            $this->description = $finance->description;
            $this->date = $finance->date ? Carbon::parse($finance->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $this->type = $finance->type;
            $this->grand_total = number_format($finance->grand_total, 0, ',', '.');
            $firstPayment = $finance->payments()->orderBy('order', 'asc')->first();

            $this->account_id = $firstPayment?->account_payment_id;
            $this->finance_payment_id = $firstPayment?->id;

            $this->getDetails();
        }

        $this->account_cashs = Account::where('company_id', auth()->user()->company_id)
            ->where('is_cash', true)
            ->orderBy('code', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $this->accounts = Account::where('company_id', auth()->user()->company_id)
            ->where('is_cash', false)
            ->orderBy('code', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $this->get_types = [
            'expenditure' => 'Pengeluaran',
            'reception' => 'Penerimaan',
            'transfer' => 'Pemindahaan Dana',
        ];
    }

    public function createDetail()
    {
        $this->details[] = [
            'finance_item_id' => null,
            'account_id' => null,
            'description' => null,
            'sub_total' => 0,
        ];
    }

    public function getDetails()
    {
        $financeItems = FinanceItem::where('finance_id', $this->financeId)
            ->get();

        foreach ($financeItems as $item) {
            $this->details[] = [
                'finance_item_id' => $item->id,
                'account_id' => $item->account_id,
                'description' => $item->description,
                'sub_total' => number_format($item->sub_total, 0, ',', '.'),
            ];
        }
    }

    public function confirmDelete($index)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus data ini?', $index);
    }

    public function delete($index)
    {
        $checkFinanceItem = $this->details[$index[0]]['finance_item_id'];

        if ($checkFinanceItem) {
            $financeItem = FinanceItem::find($checkFinanceItem);
            if ($financeItem) {
                $accountTransaction = AccountTransaction::where('finance_item_id', $financeItem->id)->get();
                foreach ($accountTransaction as $transaction) {
                    $transaction->delete();
                }
                $financeItem->delete();
            }
        }

        unset($this->details[$index[0]]);
        $this->details = array_values($this->details);

        $this->updateTotal();
    }

    public function updateTotal(): void
    {
        $this->grand_total = 0;
        foreach ($this->details as $detail) {
            if (isset($detail['sub_total'])) {
                $this->grand_total += intval(Str::replace('.', '', $detail['sub_total']));
            }
        }

        if ($this->financeId) {
            $finance = Finance::find($this->financeId);
            if ($finance) {
                if ($finance->type === 'reception') {
                    $deskripsi = "Penerimaan {$finance->code} - {$finance->description}";

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        null,
                        null,
                        Auth::user()->company_id,
                        $finance->account_id,
                        $deskripsi,
                        $finance->grand_total,
                        $finance->date
                    );
                } else {
                    $type = $this->type === 'expenditure' ? 'Pengeluaran' : 'Pemindahaan Dana';
                    $deskripsi = "{$type} {$finance->code} - {$this->description}";

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        null,
                        Auth::user()->company_id,
                        $finance->account_id,
                        $deskripsi,
                        $finance->grand_total,
                        $finance->date
                    );
                }

                $finance->grand_total = $this->grand_total;
                $finance->save();
            }
        }
        $this->grand_total = number_format($this->grand_total, 0, ',', '.');
    }

    public function updatedDetails()
    {
        foreach ($this->details as $index => $detail) {
            $sub_total = intval(Str::replace('.', '', $detail['sub_total']));
            $this->details[$index]['sub_total'] = number_format($sub_total, 0, ',', '.');
        }

        $this->updateTotal();
    }

    public function confirmSubmit()
    {
        return AlertHelper::confirmSave('submit', 'Apakah Anda yakin ingin menyimpan data ini?');
    }

    public function submit()
    {
        foreach ($this->details as $key => $detail) {
            $this->details[$key]['sub_total'] = intval(Str::replace('.', '', $detail['sub_total']));
        }

        $grand_total = intval(Str::replace('.', '', $this->grand_total));

        $this->validate([
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:expenditure,reception,transfer',
            'grand_total' => 'required',
            'details' => 'required|array',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.description' => 'required|string|max:255',
            'details.*.sub_total' => 'required|numeric|min:0',
        ], [
            'code.required' => 'Kode wajib diisi.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
            'date.required' => 'Tanggal wajib diisi.',
            'type.required' => 'Jenis wajib dipilih.',
            'grand_total.required' => 'Total wajib diisi.',
            'details.required' => 'Detail wajib diisi.',
            'details.*.account_id.required' => 'Akun wajib dipilih.',
            'details.*.description.required' => 'Deskripsi detail wajib diisi.',
            'details.*.sub_total.required' => 'Subtotal wajib diisi.',
            'details.*.sub_total.numeric' => 'Subtotal harus berupa angka.',
            'details.*.sub_total.min' => 'Subtotal tidak boleh kurang dari 0.',
        ]);

        try {
            DB::beginTransaction();

            if ($this->type === 'reception') {
                $finance = Finance::updateOrCreate([
                    'id' => $this->financeId,
                ], [
                    'code' => $this->code,
                    'description' => $this->description,
                    'date' => $this->date,
                    'type' => $this->type,
                    'sub_total' => 0,
                    'discount' => 0,
                    'tax' => 0,
                    'grand_total' => $grand_total,
                    'company_id' => auth()->user()->company_id,
                ]);

                $financePayment = FinancePayment::updateOrCreate([
                    'finance_id' => $finance->id,
                    'id' => $this->finance_payment_id ?? null,
                ], [
                    'account_payment_id' => $this->account_id,
                    'description' => $this->description,
                    'amount' => $grand_total,
                    'date' => $this->date,
                    'company_id' => auth()->user()->company_id,
                ]);

                $journal = Journal::updateOrCreate(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => Auth::user()->company_id,
                    ],
                    [
                        'date' => $this->date,
                        'description' => 'Jurnal for Dead Stock Processing for company ID: ' . Auth::user()->company_id . ' - Finance Code: ' . $finance->code,
                    ]
                );

                $journalPayment = JournalItem::updateOrCreate(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => Auth::user()->company_id,
                        'journal_id' => $journal->id,
                        'finance_payment_id' => $financePayment->id,
                        'type' => 'debit',
                    ],
                    [
                        'account_id' => $this->account_id,
                    ]
                );

                $deskripsi = "Penerimaan {$finance->code} - {$this->description}";

                app(FinanceAccountTransactionService::class)->AccountTransactionDebit($finance, null, $financePayment->id, Auth::user()->company_id, $this->account_id, $deskripsi, $grand_total, $this->date, $journal, $journalPayment);

                foreach ($this->details as $detail) {
                    $financeItem = FinanceItem::updateOrCreate([
                        'finance_id' => $finance->id,
                        'id' => $detail['finance_item_id'] ?? null,
                    ], [
                        'account_id' => $detail['account_id'],
                        'description' => $detail['description'],
                        'sub_total' => $detail['sub_total'],
                        'company_id' => auth()->user()->company_id,
                    ]);

                    $journalItem = JournalItem::updateOrCreate(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => Auth::user()->company_id,
                            'journal_id' => $journal->id,
                            'finance_item_id' => $financeItem->id,
                            'type' => 'credit',
                        ],
                        [
                            'account_id' => $detail['account_id'],
                        ]
                    );

                    $deskripsiDetail = "Penerimaan {$finance->code} - {$detail['description']}";

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit($finance, $financeItem->id, null, Auth::user()->company_id, $detail['account_id'], $deskripsiDetail, $detail['sub_total'], $this->date, $journal, $journalItem);
                }
            } else {
                $finance = Finance::updateOrCreate([
                    'id' => $this->financeId,
                ], [
                    'code' => $this->code,
                    'description' => $this->description,
                    'date' => $this->date,
                    'type' => $this->type,
                    'sub_total' => 0,
                    'discount' => 0,
                    'tax' => 0,
                    'grand_total' => $grand_total,
                    'company_id' => auth()->user()->company_id,
                ]);

                $financePayment = FinancePayment::updateOrCreate([
                    'finance_id' => $finance->id,
                    'id' => $this->finance_payment_id ?? null,
                ], [
                    'account_payment_id' => $this->account_id,
                    'description' => $this->description,
                    'amount' => $grand_total,
                    'date' => $this->date,
                    'company_id' => auth()->user()->company_id,
                ]);

                $journal = Journal::updateOrCreate(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => Auth::user()->company_id,
                    ],
                    [
                        'date' => $this->date,
                        'description' => 'Jurnal for Dead Stock Processing for company ID: ' . Auth::user()->company_id . ' - Finance Code: ' . $finance->code,
                    ]
                );

                $journalPayment = JournalItem::updateOrCreate(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => Auth::user()->company_id,
                        'journal_id' => $journal->id,
                        'finance_payment_id' => $financePayment->id,
                        'type' => 'credit',
                    ],
                    [
                        'account_id' => $this->account_id,
                    ]
                );

                $type = $this->type === 'expenditure' ? 'Pengeluaran' : 'Pemindahaan Dana';
                $deskripsi = "{$type} {$finance->code} - {$this->description}";

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit($finance, null, $financePayment->id, Auth::user()->company_id, $this->account_id, $deskripsi, $grand_total, $this->date, $journal, $journalPayment);

                foreach ($this->details as $detail) {
                    $financeItem = FinanceItem::updateOrCreate([
                        'id' => $detail['finance_item_id'] ?? null,
                        'finance_id' => $finance->id,
                    ], [
                        'account_id' => $detail['account_id'],
                        'description' => $detail['description'],
                        'sub_total' => $detail['sub_total'],
                        'company_id' => auth()->user()->company_id,
                    ]);

                    $journalItem = JournalItem::updateOrCreate(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => Auth::user()->company_id,
                            'journal_id' => $journal->id,
                            'finance_item_id' => $financeItem->id,
                            'type' => 'debit',
                        ],
                        [
                            'account_id' => $detail['account_id'],
                        ]
                    );

                    $type = $this->type === 'expenditure' ? 'Pengeluaran' : 'Pemindahaan Dana';

                    $deskripsiDetail = "{$type} {$finance->code} - {$detail['description']}";

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit($finance, $financeItem->id, null, Auth::user()->company_id, $detail['account_id'], $deskripsiDetail, $detail['sub_total'], $this->date, $journal, $journalItem);
                }
            }

            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal menyimpan data. ' . $e->getMessage());
            return redirect()->route('user.finance.finance.detail');
        }
        Session::forget('finance_finance_id');
        return redirect()->route('user.finance.finance');
    }

    public function render()
    {
        return view('livewire.admin.finance.finance.detail.admin-finance-finance-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
