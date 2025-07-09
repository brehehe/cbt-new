<?php

namespace App\Models\Transaction;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientCompany;
use App\Models\Poly\Poly;
use App\Models\User;
use App\Models\User\ControlDoctor;
use App\Models\User\UserCompanyRole;
use App\Models\User\UserDetail;
use App\Models\User\UserIncentive;
use App\Models\User\UserPrice;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class Transaction extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function patientCompanyRole()
    {
        return $this->belongsTo(UserCompanyRole::class, 'patient_company_role_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function transactionRecipes()
    {
        return $this->hasMany(TransactionRecipe::class);
    }

    public function transactionPayments()
    {
        return $this->hasMany(TransactionPayment::class);
    }

    public function transactionPrimary()
    {
        return $this->belongsTo(TransactionPrimary::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function controlDoctor()
    {
        return $this->belongsTo(ControlDoctor::class, 'control_doctor_id');
    }

    // public function poly() {
    //     return $this->belongsTo(Poly::class,'poly_id');
    // }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'ilike', "%{$search}%")
                    ->orWhere('code_consultation', 'ilike', "%{$search}%")
                    ->orWhereHas('patient', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('patientCompanyRole', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    })->orWhereHas('doctor', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('poly', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    });
            });
        }
        return $query;
    }

    public function transactionNurses()
    {
        return $this->hasMany(TransactionNurse::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
            $modelCreate->branch_id = Branch::where('company_id', auth()->user()->company_id)->first()->id;
            $modelCreate->date = $modelCreate->date ?? now();
            $modelCreate->created_by = auth()->user()->id;
            $modelCreate->date = $modelCreate->date ?? date('Y-m-d');
        });

        static::updating(function ($modelUpdate) {
            try {
                // Force commit any pending transactions before proceeding
                $initialTransactionLevel = \DB::transactionLevel();
                if ($initialTransactionLevel > 0) {
                    while (\DB::transactionLevel() > 0) {
                        \DB::commit();
                    }
                }

                // Debug: Log status dan ID yang tersedia
                \Log::info('Transaction Update Debug', [
                    'transaction_id' => $modelUpdate->id,
                    'status' => $modelUpdate->status,
                    'doctor_id' => $modelUpdate->doctor_id,
                    'pharmacy_id' => $modelUpdate->pharmacy_id,
                    'cashier_id' => $modelUpdate->cashier_id,
                    'company_id' => $modelUpdate->company_id,
                    'grand_total_price' => $modelUpdate->grand_total_price,
                ]);

                if ($modelUpdate->status === 'completed') {
                    \Log::info('Status is completed, processing incentives...');

                    // if ($modelUpdate->doctor_id) {
                    //     \Log::info('Processing doctor incentive for doctor_id: ' . $modelUpdate->doctor_id);
                    //     $modelUpdate->updateDoctorIncentive($modelUpdate, $modelUpdate->doctor_id, $modelUpdate->company_id);
                    // } else {
                    //     \Log::warning('doctor_id is NULL or empty');
                    // }

                    // if ($modelUpdate->pharmacy_id) {
                    //     \Log::info('Processing pharmacy incentive for pharmacy_id: ' . $modelUpdate->pharmacy_id);
                    //     $modelUpdate->updatePharmacyIncentive($modelUpdate, $modelUpdate->pharmacy_id, $modelUpdate->company_id);
                    // } else {
                    //     \Log::warning('pharmacy_id is NULL or empty');
                    // }

                    // if ($modelUpdate->cashier_id) {
                    //     \Log::info('Processing cashier incentive for cashier_id: ' . $modelUpdate->cashier_id);
                    //     $modelUpdate->updateCashierIncentive($modelUpdate, $modelUpdate->cashier_id, $modelUpdate->company_id);
                    // } else {
                    //     \Log::warning('cashier_id is NULL or empty');
                    // }

                    // if ($modelUpdate->transactionNurses && $modelUpdate->transactionNurses->count() > 0) {
                    //     \Log::info('Processing nurse incentives, count: ' . $modelUpdate->transactionNurses->count());
                    //     foreach ($modelUpdate->transactionNurses as $nurse) {
                    //         $modelUpdate->updateNurseIncentive($modelUpdate, $nurse->nurse_id, $modelUpdate->company_id);
                    //     }
                    // } else {
                    //     \Log::warning('No nurses found for this transaction');
                    // }
                } else {
                    \Log::info('Transaction status is not completed: ' . $modelUpdate->status);
                }
            } catch (\Exception | \Throwable $th) {
                \DB::rollBack();
                $error = [
                    'message' => $th->getMessage(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                    'trace' => $th->getTraceAsString(),
                ];
                \Log::error('Ada kesalahan saat boot Transaction sync', $error);

                // Re-throw exception untuk debugging
                throw $th;
            }
        });
    }

    function updateDoctorIncentive($modelUpdate, $doctorId, $companyId)
    {
        \Log::info('updateDoctorIncentive START', [
            'doctorId' => $doctorId,
            'companyId' => $companyId,
            'transaction_id' => $modelUpdate->id
        ]);

        $userPrice = UserPrice::where('user_id', $doctorId)
            ->where('company_id', $companyId)
            ->first();

        \Log::info('updateDoctorIncentive userPrice query result', [
            'userPrice_found' => $userPrice ? 'YES' : 'NO',
            'userPrice_data' => $userPrice ? $userPrice->toArray() : null
        ]);

        $grandTotalPrice = $modelUpdate->grand_total_price ?? 0;

        if ($userPrice) {
            $tipeInsentifDokter = $userPrice->type_incentive_doctor ?? 'rupiah';

            if ($tipeInsentifDokter === 'persen') {
                $persentase = min($userPrice->incentive_doctor, 100);
                $jumlahInsentif = ($grandTotalPrice * $persentase) / 100;
            } else {
                $jumlahInsentif = $userPrice->incentive_doctor;
            }

            \Log::info('updateDoctorIncentive calculation', [
                'type' => $tipeInsentifDokter,
                'incentive_value' => $userPrice->incentive_doctor,
                'grand_total' => $grandTotalPrice,
                'calculated_amount' => $jumlahInsentif
            ]);

            try {
                $result = UserIncentive::updateOrCreate(
                    [
                        'user_id' => $doctorId,
                        'transaction_id' => $modelUpdate->id,
                        'status' => 'dokter',
                    ],
                    [
                        'amount' => $jumlahInsentif,
                        'month' => date('m'),
                        'year' => date('Y'),
                    ]
                );

                \Log::info('updateDoctorIncentive SUCCESS', [
                    'user_incentive_id' => $result->id,
                    'was_recently_created' => $result->wasRecentlyCreated
                ]);
            } catch (\Exception $e) {
                \Log::error('updateDoctorIncentive FAILED', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('updateDoctorIncentive - No userPrice found', [
                'doctorId' => $doctorId,
                'companyId' => $companyId
            ]);
        }
    }

    function updatePharmacyIncentive($modelUpdate, $pharmacyId, $companyId)
    {
        \Log::info('updatePharmacyIncentive START', [
            'pharmacyId' => $pharmacyId,
            'companyId' => $companyId,
            'transaction_id' => $modelUpdate->id
        ]);

        $userPrice = UserPrice::where('user_id', $pharmacyId)
            ->where('company_id', $companyId)
            ->first();

        \Log::info('updatePharmacyIncentive userPrice query result', [
            'userPrice_found' => $userPrice ? 'YES' : 'NO',
            'userPrice_data' => $userPrice ? $userPrice->toArray() : null
        ]);

        $grandTotalPrice = $modelUpdate->grand_total_price ?? 0;

        if ($userPrice) {
            // Cek apakah field incentive_pharmacy ada dan tidak null
            if (is_null($userPrice->incentive_pharmacy) || $userPrice->incentive_pharmacy == 0) {
                \Log::warning('updatePharmacyIncentive - incentive_pharmacy is null or zero', [
                    'incentive_pharmacy' => $userPrice->incentive_pharmacy,
                    'userPrice_data' => $userPrice->toArray()
                ]);
                return;
            }

            $tipeInsentifApotek = $userPrice->type_incentive_pharmacy ?? 'rupiah';

            if ($tipeInsentifApotek === 'persen') {
                $persentase = min($userPrice->incentive_pharmacy, 100);
                $jumlahInsentif = ($grandTotalPrice * $persentase) / 100;
            } else {
                $jumlahInsentif = $userPrice->incentive_pharmacy;
            }

            \Log::info('updatePharmacyIncentive calculation', [
                'type' => $tipeInsentifApotek,
                'incentive_value' => $userPrice->incentive_pharmacy,
                'grand_total' => $grandTotalPrice,
                'calculated_amount' => $jumlahInsentif
            ]);

            try {
                $result = UserIncentive::updateOrCreate(
                    [
                        'user_id' => $pharmacyId,
                        'transaction_id' => $modelUpdate->id,
                        'status' => 'apoteker',
                    ],
                    [
                        'amount' => $jumlahInsentif,
                        'month' => date('m'),
                        'year' => date('Y'),
                    ]
                );

                \Log::info('updatePharmacyIncentive SUCCESS', [
                    'user_incentive_id' => $result->id,
                    'was_recently_created' => $result->wasRecentlyCreated
                ]);
            } catch (\Exception $e) {
                \Log::error('updatePharmacyIncentive FAILED', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('updatePharmacyIncentive - No userPrice found', [
                'pharmacyId' => $pharmacyId,
                'companyId' => $companyId
            ]);
        }

        // Ganti Log::check dengan Log::info
        \Log::info('updatePharmacyIncentive DEBUG INFO', [
            'modelUpdate_id' => $modelUpdate->id,
            'modelUpdate_status' => $modelUpdate->status,
            'pharmacyId' => $pharmacyId,
            'companyId' => $companyId,
            'userPrice' => $userPrice ? $userPrice->toArray() : null,
            'grandTotalPrice' => $grandTotalPrice,
        ]);
    }

    function updateCashierIncentive($modelUpdate, $cashierId, $companyId)
    {
        \Log::info('updateCashierIncentive START', [
            'cashierId' => $cashierId,
            'companyId' => $companyId,
            'transaction_id' => $modelUpdate->id
        ]);

        $userPrice = UserPrice::where('user_id', $cashierId)
            ->where('company_id', $companyId)
            ->first();

        \Log::info('updateCashierIncentive userPrice query result', [
            'userPrice_found' => $userPrice ? 'YES' : 'NO',
            'userPrice_data' => $userPrice ? $userPrice->toArray() : null
        ]);

        $grandTotalPrice = $modelUpdate->grand_total_price ?? 0;

        if ($userPrice) {
            // Cek apakah field incentive_cashier ada dan tidak null
            if (is_null($userPrice->incentive_cashier) || $userPrice->incentive_cashier == 0) {
                \Log::warning('updateCashierIncentive - incentive_cashier is null or zero', [
                    'incentive_cashier' => $userPrice->incentive_cashier,
                    'userPrice_data' => $userPrice->toArray()
                ]);
                return;
            }

            $tipeInsentifKasir = $userPrice->type_incentive_cashier ?? 'rupiah';

            if ($tipeInsentifKasir === 'persen') {
                $persentase = min($userPrice->incentive_cashier, 100);
                $jumlahInsentif = ($grandTotalPrice * $persentase) / 100;
            } else {
                $jumlahInsentif = $userPrice->incentive_cashier;
            }

            \Log::info('updateCashierIncentive calculation', [
                'type' => $tipeInsentifKasir,
                'incentive_value' => $userPrice->incentive_cashier,
                'grand_total' => $grandTotalPrice,
                'calculated_amount' => $jumlahInsentif
            ]);

            try {
                $result = UserIncentive::updateOrCreate(
                    [
                        'user_id' => $cashierId,
                        'transaction_id' => $modelUpdate->id,
                        'status' => 'kasir',
                    ],
                    [
                        'amount' => $jumlahInsentif,
                        'month' => date('m'),
                        'year' => date('Y'),
                    ]
                );

                \Log::info('updateCashierIncentive SUCCESS', [
                    'user_incentive_id' => $result->id,
                    'was_recently_created' => $result->wasRecentlyCreated
                ]);
            } catch (\Exception $e) {
                \Log::error('updateCashierIncentive FAILED', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('updateCashierIncentive - No userPrice found', [
                'cashierId' => $cashierId,
                'companyId' => $companyId
            ]);
        }

        // Tambahkan debug info untuk cashier juga
        \Log::info('updateCashierIncentive DEBUG INFO', [
            'modelUpdate_id' => $modelUpdate->id,
            'modelUpdate_status' => $modelUpdate->status,
            'cashierId' => $cashierId,
            'companyId' => $companyId,
            'userPrice' => $userPrice ? $userPrice->toArray() : null,
            'grandTotalPrice' => $grandTotalPrice,
        ]);
    }

    function updateNurseIncentive($modelUpdate, $nurseId, $companyId)
    {
        \Log::info('updateNurseIncentive START', [
            'nurseId' => $nurseId,
            'companyId' => $companyId,
            'transaction_id' => $modelUpdate->id
        ]);

        $userPrice = UserPrice::where('user_id', $nurseId)
            ->where('company_id', $companyId)
            ->first();

        \Log::info('updateNurseIncentive userPrice query result', [
            'userPrice_found' => $userPrice ? 'YES' : 'NO',
            'userPrice_data' => $userPrice ? $userPrice->toArray() : null
        ]);

        $grandTotalPrice = $modelUpdate->grand_total_price ?? 0;

        if ($userPrice) {
            $tipeInsentifPerawat = $userPrice->type_incentive_nurse ?? 'rupiah';

            if ($tipeInsentifPerawat === 'persen') {
                $persentase = min($userPrice->incentive_nurse, 100);
                $jumlahInsentif = ($grandTotalPrice * $persentase) / 100;
            } else {
                $jumlahInsentif = $userPrice->incentive_nurse;
            }

            \Log::info('updateNurseIncentive calculation', [
                'type' => $tipeInsentifPerawat,
                'incentive_value' => $userPrice->incentive_nurse,
                'grand_total' => $grandTotalPrice,
                'calculated_amount' => $jumlahInsentif
            ]);

            try {
                $result = UserIncentive::updateOrCreate(
                    [
                        'user_id' => $nurseId,
                        'transaction_id' => $modelUpdate->id,
                        'status' => 'perawat',
                    ],
                    [
                        'amount' => $jumlahInsentif,
                        'month' => date('m'),
                        'year' => date('Y'),
                    ]
                );

                \Log::info('updateNurseIncentive SUCCESS', [
                    'user_incentive_id' => $result->id,
                    'was_recently_created' => $result->wasRecentlyCreated
                ]);
            } catch (\Exception $e) {
                \Log::error('updateNurseIncentive FAILED', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('updateNurseIncentive - No userPrice found', [
                'nurseId' => $nurseId,
                'companyId' => $companyId
            ]);
        }
    }

    public function transactionPhysicalExamination()
    {
        return $this->hasOne(TransactionPhysicalExamination::class);
    }

    public function transactionCondition()
    {
        return $this->hasOne(TransactionCondition::class);
    }
}
