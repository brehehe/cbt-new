<?php

namespace App\Livewire\Admin\Consultation\Consultation\Detail;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Encounter\Encounter;
use App\Models\HowToUse\HowToUse;
use App\Models\Icd\Icd10;
use App\Models\Icd\Icd9;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\Condition\MasterConditionBodySite;
use App\Models\Master\CodeSystem\Condition\MasterConditionCategory;
use App\Models\Master\CodeSystem\Condition\MasterConditionClinicalStatus;
use App\Models\Master\CodeSystem\Condition\MasterConditionCodeChiefComplaint;
use App\Models\Master\CodeSystem\Condition\MasterConditionSeverity;
use App\Models\Master\CodeSystem\Condition\MasterConditionVerificationStatus;
use App\Models\Master\CodeSystem\Consultation\MasterConsultationConditionVerStatus;
use App\Models\Master\CodeSystem\Consultation\MasterConsultationSnomedCT;
use App\Models\Master\CodeSystem\Consultation\MasterConsultationTerminology;
use App\Models\Master\CodeSystem\Location\MasterLocationMode;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute;
use App\Models\Medication\Medication;
use App\Models\MedicineType\MedicineType;
use App\Models\Patient\Patient;
use App\Models\Poly\Poly;
use App\Models\Practitiont\Practitioner;
use App\Models\Product\Product;
use App\Models\Product\ProductPackage;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Models\Product\ProductType;
use App\Models\Transaction\SupportingTransactionIcd10;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionAction;
use App\Models\Transaction\TransactionCondition;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionDetailPackage;
use App\Models\Transaction\TransactionDiagnosis;
use App\Models\Transaction\TransactionIcd10;
use App\Models\Transaction\TransactionIcd9;
use App\Models\Transaction\TransactionNurse;
use App\Models\Transaction\TransactionPrimary;
use App\Models\Transaction\TransactionProduct;
use App\Models\Transaction\TransactionProofOfAction;
use App\Models\Transaction\TransactionRecipe;
use App\Models\Transaction\TransactionRecipeReal;
use App\Models\Transaction\TransactionRecipeRealDetail;
use App\Models\Transaction\TransactionReference;
use App\Models\Transaction\TransactionSecondary;
use App\Models\User;
use App\Models\User\AllergyMedicine;
use App\Models\User\UserControlSchedule;
use App\service\apiservice;
use App\Services\Product\ProductService;
use Database\Seeders\Consultation\MasterConsultationConditionVerStatusSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Notification;

class AdminConsultationConsultationDetailIndex extends Component
{
    use WithPagination, WithFileUploads;
    public $search,
        $perPage = 5;
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $transaction_id, $transaction, $remaining_queue;

    public $get_tabs = ['diagnosa', 'tindakan', 'bukti-tindakan', 'resep', 'jadwal-kontrol', 'rujukan', 'odontogram'];

    public $tab;

    // Diagnosa
    public $subjective,
        $objective,
        $assessment,
        $plan,
        $allergy_name,
        $type,
        $return_recommendation,
        $transaction_nurses = [],
        $transaction_icd9s = [];

    // Diagnosa Keluhan Utama
    public $description_primary,
        $verification_status,
        $clinical_status,
        $snomed_code,
        $onset_datetime,
        $transaction_icd10s = [];

    // Diagnosa Keluhan Sekunder / Penyerta
    public $description_secondary,
        $supporting_verification_status,
        $supporting_clinical_status,
        $supporting_snomed_code,
        $supporting_onset_datetime,
        $supporting_transaction_icd10s = [];

    // Action
    public $transaction_actions = [];

    // Proof of Action
    public $proof_of_actions = [],
        $description,
        $type_before_photo,
        $before_photo,
        $type_after_photo,
        $after_photo;

    // Recipe
    public $recipes = [],
        $transaction_recipe_id;

    // Jadwal Kontrol
    public $date, $description_control, $doctor_id, $location_id;

    // Rujukan
    public $hospital_name, $doctor_name, $description_refer, $date_refer;

    // Array
    public $medicine_types = [],
        $supporting_products = [],
        $locations = [],
        $doctors = [],
        $product_types = [];

    // Aturan Pakai
    public $name_how_to_use,
        $description_how_to_use,
        $day_how_to_use,
        $time_how_to_use;

    public $is_outside_pharmacy;

    public function mount()
    {
        $this->transaction_id = session('transaction_id', null);

        if ($this->transaction_id === null) {
            return redirect()->route('user.consultation.consultation');
        }

        $this->transaction = Transaction::find($this->transaction_id);
        $this->product_types = Cache::remember('product_types_tindakan_paket', 3600, function () {
            return ProductType::whereIn('name', ['Tindakan', 'Paket'])
                ->pluck('id')
                ->toArray();
        });

        $this->changeTab('diagnosa');
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function createTransactionIcd10()
    {
        $this->type = 'icd10';
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function createSupportingTransactionIcd10()
    {
        $this->type = 'supporting_icd10';
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function createTransactionIcd9()
    {
        $this->type = 'icd9';
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->reset(['type', 'search']);
        $this->perPage = 5;
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function choiceICD($id)
    {
        if ($this->type == 'icd10') {
            $icd10 = Icd10::find($id);

            if ($icd10) {
                TransactionIcd10::create([
                    'transaction_id' => $this->transaction_id,
                    'icd10_id' => $id,
                    'user_id' => $this->transaction->patient_id,
                ]);

                $this->detailTransactionIcd10();
                AlertHelper::success('Berhasil', 'Diagnosa ICD-10 berhasil ditambahkan.');
            } else {
                AlertHelper::error('Gagal', 'Diagnosa ICD-10 tidak ditemukan.');
            }
        } elseif ($this->type == 'icd9') {
            $icd9 = Icd9::find($id);

            if ($icd9) {
                TransactionIcd9::create([
                    'transaction_id' => $this->transaction_id,
                    'icd9_id' => $id,
                    'user_id' => $this->transaction->patient_id,
                ]);

                $this->detailTransactionIcd9();
                AlertHelper::success('Berhasil', 'Diagnosa ICD-9 berhasil ditambahkan.');
            } else {
                AlertHelper::error('Gagal', 'Diagnosa ICD-9 tidak ditemukan.');
            }
        }
        $this->closeModal();
    }

    public function choiceSupportingICD($id)
    {
        $icd10 = Icd10::find($id);

        if ($icd10) {
            SupportingTransactionIcd10::create([
                'transaction_id' => $this->transaction_id,
                'icd10_id' => $id,
                'user_id' => $this->transaction->patient_id,
            ]);

            $this->detailSupportingTransactionIcd10();
            AlertHelper::success('Berhasil', 'Diagnosa ICD-10 berhasil ditambahkan.');
        } else {
            AlertHelper::error('Gagal', 'Diagnosa ICD-10 tidak ditemukan.');
        }
        $this->closeModal();
    }

    public function changeTab($tab)
    {
        $this->reset(['remaining_queue']);
        $this->remaining_queue = Transaction::select('id')
            ->where([['doctor_id', '=', $this->transaction->doctor_id], ['location_id', '=', $this->transaction->location_id], ['control_doctor_id', '=', $this->transaction->control_doctor_id], ['id', '!=', $this->transaction_id]])
            ->whereIn('status', ['draft_consultation', 'call_consultation', 'confirmation_call'])
            ->whereDate('date', $this->transaction->date)
            ->count();

        if (in_array($tab, $this->get_tabs)) {
            $this->tab = $tab;

            if ($tab == 'tindakan') {
                $this->detailAction();
            } elseif ($tab == 'diagnosa') {
                $this->detail();
                $this->detailPrimary();
                $this->detailSecondary();
                $this->detailTransactionIcd10();
                $this->detailSupportingTransactionIcd10();
            } elseif ($tab == 'bukti-tindakan') {
                $this->detailProofOfAction();
            } elseif ($tab == 'resep') {
                $this->getMedicineTypes();
                $this->getSupportingProducts();
                $this->detailMedicine();
            } elseif ($tab == 'jadwal-kontrol') {
                $this->getPolys();
                $this->getDoctors();
                $this->detailSchedule();
            } elseif ($tab == 'rujukan') {
                $this->detailReference();
            } else {
                $this->reset(['transaction_actions', 'subjective', 'return_recommendation', 'objective', 'assessment', 'plan', 'proof_of_actions', 'recipes', 'date', 'description_control', 'doctor_id', 'location_id', 'hospital_name', 'doctor_name', 'description_refer', 'date_refer', 'supporting_products', 'medicine_types', 'locations', 'doctors', 'transaction_icd10s', 'transaction_icd9s', 'allergy_name', 'transaction_nurses', 'type', 'transaction_recipe_id', 'description_primary', 'verification_status', 'clinical_status', 'snomed_code', 'onset_datetime', 'description_secondary', 'supporting_verification_status', 'supporting_clinical_status', 'supporting_snomed_code', 'supporting_onset_datetime', 'supporting_transaction_icd10s']);
            }
        } else {
            $this->tab = 'diagnosa';
        }

        $this->updateTotal();
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

    public function detailPrimary()
    {
        $this->description_primary = '';
        $this->verification_status = '';
        $this->clinical_status = '';
        $this->snomed_code = '';
        $this->onset_datetime = '';

        $transactionPrimary = TransactionPrimary::where('transaction_id', $this->transaction_id)->first();

        if ($transactionPrimary) {
            $this->description_primary = $transactionPrimary->description_primary ?? '';
            $this->verification_status = $transactionPrimary->verification_status ?? '';
            $this->clinical_status = $transactionPrimary->clinical_status ?? '';
            $this->snomed_code = $transactionPrimary->snomed_code ?? '';
            $this->onset_datetime = $transactionPrimary->onset_datetime ?? '';
        }
    }

    public function detailSecondary()
    {
        $this->description_secondary = '';
        $this->supporting_verification_status = '';
        $this->supporting_clinical_status = '';
        $this->supporting_snomed_code = '';
        $this->supporting_onset_datetime = '';

        $transactionSecondary = TransactionSecondary::where('transaction_id', $this->transaction_id)
            ->first();

        if ($transactionSecondary) {
            $this->description_secondary = $transactionSecondary->description_secondary ?? '';
            $this->supporting_verification_status = $transactionSecondary->supporting_verification_status ?? '';
            $this->supporting_clinical_status = $transactionSecondary->supporting_clinical_status ?? '';
            $this->supporting_snomed_code = $transactionSecondary->supporting_snomed_code ?? '';
            $this->supporting_onset_datetime = $transactionSecondary->supporting_onset_datetime ?? '';
        }
    }

    public function detailTransactionIcd10()
    {
        $this->transaction_icd10s = [];

        $transaction_icd10s = TransactionIcd10::where('transaction_id', $this->transaction_id)->get();
        foreach ($transaction_icd10s as $key => $value) {
            $this->transaction_icd10s[] = [
                'id' => $value->id,
                'icd10_id' => $value->icd10_id,
                'icd10_code' => $value->icd10->code ?? '',
                'icd10_display' => $value->icd10->display ?? '',
            ];
        }
    }

    public function detailSupportingTransactionIcd10(): void
    {
        $this->supporting_transaction_icd10s = [];

        $transaction_icd10s = SupportingTransactionIcd10::where('transaction_id', $this->transaction_id)
            ->get();

        foreach ($transaction_icd10s as $key => $value) {
            $this->supporting_transaction_icd10s[] = [
                'id' => $value->id,
                'icd10_id' => $value->icd10_id,
                'icd10_code' => $value->icd10->code ?? '',
                'icd10_display' => $value->icd10->display ?? '',
            ];
        }
    }

    public function detailTransactionIcd9()
    {
        $this->transaction_icd9s = [];

        $transaction_icd9s = TransactionIcd9::where('transaction_id', $this->transaction_id)->get();
        foreach ($transaction_icd9s as $key => $value) {
            $this->transaction_icd9s[] = [
                'id' => $value->id,
                'icd9_id' => $value->icd9_id,
                'icd9_code' => $value->icd9->code ?? '',
                'icd9_display' => $value->icd9->display ?? '',
            ];
        }
    }

    public function getPolys()
    {
        $this->reset(['locations']);
        $this->locations = Location::select('id', 'name')
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();
    }

    public function getDoctors()
    {
        $this->reset(['doctors']);
        $this->doctors = User::select('id', 'name')
            ->companyRole('Dokter', Auth::user()->company_id)
            ->get()
            ->toArray();
    }

    public function getMedicineTypes()
    {
        $this->reset(['medicine_types']);
        $this->medicine_types = MedicineType::select('id', 'name')
            ->where('company_id', Auth::user()->company_id)
            ->get()
            ->toArray();
    }

    public function getSupportingProducts()
    {
        $this->reset(['supporting_products']);

        $this->supporting_products = Product::where('company_id', Auth::user()->company_id)
            ->whereHas('productType', function ($query) {
                $query->where('name', 'Produk Pendukung'); // atau 'Supporting Product' sesuai isi database
            })
            ->whereHas('productPrice', function ($query) {
                $query->where('price', '>', 0)->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id);
            })
            ->whereHas('productStock', function ($query) {
                $query->where('quantity', '>', 0)->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id); // atau 'Supporting Product' sesuai isi database
            })
            ->select('id', 'name')
            ->with('productPrice:id,product_id,price,recipe', 'productStock:id,product_id,quantity')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'subjective' || $propertyName === 'objective' || $propertyName === 'assessment' || $propertyName === 'plan' || $propertyName === 'return_recommendation') {
            TransactionDiagnosis::updateOrCreate(
                [
                    'transaction_id' => $this->transaction_id,
                    'user_id' => $this->transaction->patient_id,
                ],
                [
                    'subjective' => empty($this->subjective) ? null : $this->subjective,
                    'objective' => empty($this->objective) ? null : $this->objective,
                    'assessment' => empty($this->assessment) ? null : $this->assessment,
                    'plan' => empty($this->plan) ? null : $this->plan,
                    'return_recommendation' => empty($this->return_recommendation) ? null : $this->return_recommendation,
                ],
            );
        } elseif ($propertyName === 'date' || $propertyName === 'description_control' || $propertyName === 'doctor_id' || $propertyName === 'location_id') {
            UserControlSchedule::updateOrCreate(
                [
                    'transaction_id' => $this->transaction_id,
                    'user_id' => $this->transaction->patient_id,
                ],
                [
                    'date' => empty($this->date) ? null : $this->date, // Fix di sini
                    'description' => empty($this->description_control) ? null : $this->description_control,
                    'doctor_id' => empty($this->doctor_id) ? null : $this->doctor_id,
                    'location_id' => empty($this->location_id) ? null : $this->location_id,
                ],
            );
        } elseif ($propertyName === 'hospital_name' || $propertyName === 'doctor_name' || $propertyName === 'description_refer' || $propertyName === 'date_refer') {
            TransactionReference::updateOrCreate(
                [
                    'transaction_id' => $this->transaction_id,
                    'user_id' => $this->transaction->patient_id,
                ],
                [
                    'hospital' => empty($this->hospital_name) ? null : $this->hospital_name,
                    'doctor_name' => empty($this->doctor_name) ? null : $this->doctor_name,
                    'description' => empty($this->description_refer) ? null : $this->description_refer,
                    'date' => empty($this->date_refer) ? null : $this->date_refer,
                ],
            );
        } elseif ($propertyName === 'allergy_name') {
            $allergyMedicine = AllergyMedicine::where('user_id', $this->transaction->patient_id)
                ->where('transaction_id', $this->transaction_id)
                ->where('company_id', Auth::user()->company_id)
                ->first();

            if ($this->allergy_name) {
                AllergyMedicine::updateOrCreate(
                    [
                        'user_id' => $this->transaction->patient_id,
                        'transaction_id' => $this->transaction_id,
                        'company_id' => Auth::user()->company_id,
                    ],
                    [
                        'description' => $this->allergy_name,
                    ],
                );
            } else {
                if ($allergyMedicine) {
                    $allergyMedicine->delete();
                }
            }
        } elseif ($propertyName === 'transaction_nurses') {
            foreach ($this->transaction_nurses as $nurseId) {
                $user = User::find($nurseId);
                TransactionNurse::updateOrCreate(
                    [
                        'nurse_id' => $nurseId,
                        'transaction_id' => $this->transaction_id,
                    ],
                    [
                        'nurse_name' => $user ? $user->name : null,
                        'company_id' => Auth::user()->company_id,
                    ]
                );
            }

            // Hapus yang tidak diinginkan
            TransactionNurse::where('transaction_id', $this->transaction_id)
                ->whereNotIn('nurse_id', $this->transaction_nurses)
                ->delete();
        } elseif ($propertyName === 'description_primary' || $propertyName === 'verification_status' || $propertyName === 'clinical_status' || $propertyName === 'snomed_code' || $propertyName === 'onset_datetime') {
            TransactionPrimary::updateOrCreate(
                [
                    'transaction_id' => $this->transaction_id,
                ],
                [
                    'description_primary' => empty($this->description_primary) ? null : $this->description_primary,
                    'verification_status' => empty($this->verification_status) ? null : $this->verification_status,
                    'clinical_status' => empty($this->clinical_status) ? null : $this->clinical_status,
                    'snomed_code' => empty($this->snomed_code) ? null : $this->snomed_code,
                    'onset_datetime' => empty($this->onset_datetime) ? null : $this->onset_datetime,
                ],
            );

            TransactionCondition::updateOrCreate(
                [
                    'transaction_id' => $this->transaction_id,
                    'type' => 'keluhan-utama'
                ],
                [
                    'description' => empty($this->description_primary) ? null : $this->description_primary,
                    'verification_status' => empty($this->verification_status) ? null : $this->verification_status,
                    'clinical_status' => empty($this->clinical_status) ? null : $this->clinical_status,
                    'snomed_code' => empty($this->snomed_code) ? null : $this->snomed_code,
                    'onset_datetime' => empty($this->onset_datetime) ? null : $this->onset_datetime,
                ],
            );
        } elseif ($propertyName === 'description_secondary' || $propertyName === 'supporting_verification_status' || $propertyName === 'supporting_clinical_status' || $propertyName === 'supporting_snomed_code' || $propertyName === 'supporting_onset_datetime') {
            TransactionSecondary::updateOrCreate(
                [
                    'transaction_id' => $this->transaction_id,
                ],
                [
                    'description_secondary' => empty($this->description_secondary) ? null : $this->description_secondary,
                    'supporting_verification_status' => empty($this->supporting_verification_status) ? null : $this->supporting_verification_status,
                    'supporting_clinical_status' => empty($this->supporting_clinical_status) ? null : $this->supporting_clinical_status,
                    'supporting_snomed_code' => empty($this->supporting_snomed_code) ? null : $this->supporting_snomed_code,
                    'supporting_onset_datetime' => empty($this->supporting_onset_datetime) ? null : $this->supporting_onset_datetime,
                ],
            );
        }
    }

    public function confirmDeleteTransactionIcd10($id)
    {
        return AlertHelper::confirmDelete('deleteTransactionIcd10', 'Apakah Anda yakin ingin menghapus Diagnosa ICD-10 ini?', $id);
    }

    public function deleteTransactionIcd10($id)
    {
        $transaction_icd10 = TransactionIcd10::find($id[0]);

        if ($transaction_icd10) {
            $transaction_icd10->delete();
            $this->detailTransactionIcd10();
            AlertHelper::success('Berhasil', 'Diagnosa ICD-10 berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Diagnosa ICD-10 tidak ditemukan.');
        }
    }

    public function confirmDeleteSupportingTransactionIcd10($id)
    {
        return AlertHelper::confirmDelete('deleteSupportingTransactionIcd10', 'Apakah Anda yakin ingin menghapus Diagnosa Supporting ICD-10 ini?', $id);
    }

    public function deleteSupportingTransactionIcd10($id)
    {
        $transaction_icd10 = SupportingTransactionIcd10::find($id[0]);

        if ($transaction_icd10) {
            $transaction_icd10->delete();
            $this->detailSupportingTransactionIcd10();
            AlertHelper::success('Berhasil', 'Diagnosa Supporting ICD-10 berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Diagnosa Supporting ICD-10 tidak ditemukan.');
        }
    }

    public function confirmDeleteTransactionIcd9($id)
    {
        return AlertHelper::confirmDelete('deleteTransactionIcd9', 'Apakah Anda yakin ingin menghapus diagnosa ini?', $id);
    }

    public function deleteTransactionIcd9($id)
    {
        $transaction_icd9 = TransactionIcd9::find($id[0]);

        if ($transaction_icd9) {
            $transaction_icd9->delete();
            $this->detailTransactionIcd9();
            AlertHelper::success('Berhasil', 'Diagnosa ICD-9 berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Diagnosa ICD-9 tidak ditemukan.');
        }
    }

    public function createActions()
    {
        $this->type = 'action';
        return $this->dispatch('open-modal', ['id' => 'modalAction']);
    }

    public function choiceAction($id)
    {
        $productPrice = ProductPrice::where('product_id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->where('is_updated', true)
            ->first();

        TransactionDetail::create([
            'transaction_id' => $this->transaction_id,
            'product_id' => $id,
            'quantity' => 1,
            'price' => $productPrice ? $productPrice->price : 0,
            'sub_total_price' => $productPrice ? $productPrice->price : 0,
            'type_transaction' => 'action',
        ]);

        AlertHelper::success('Berhasil', 'Tindakan berhasil ditambahkan.');
        $this->closeModalAction();
        $this->detailAction();
    }

    public function closeModalAction()
    {
        $this->reset(['type', 'search']);
        $this->perPage = 5;
        $this->dispatch('close-modal', ['id' => 'modalAction']);
    }

    public function detailAction()
    {
        $this->reset(['transaction_actions']);

        $transaction_actions = TransactionDetail::where('transaction_id', $this->transaction_id)->where('type_transaction', 'action')->with('product:id,sku_number,name,description,company_id')->orderBy('order', 'asc')->get();

        foreach ($transaction_actions as $action) {
            $this->transaction_actions[] = [
                'id' => $action->id,
                'product_id' => $action->product_id,
                'name' => $action->product?->name,
                'description' => $action->description,
                'quantity' => $action->quantity,
                'price' => number_format($action->price, 0, ',', '.'),
                'sub_total_price' => number_format($action->sub_total_price, 0, ',', '.'),
            ];
        }
    }

    public function detailProofOfAction()
    {
        $this->reset(['proof_of_actions']);
        $proof_of_actions = TransactionProofOfAction::where('transaction_id', $this->transaction_id)->orderBy('created_at', 'desc')->get();

        foreach ($proof_of_actions as $key => $value) {
            $this->proof_of_actions[] = [
                'id' => $value->id,
                'transaction_id' => $value->transaction_id,
                'description' => $value->description,
                'before_photo' => $value->before_photo,
                'after_photo' => $value->after_photo,
            ];
        }
    }

    public function updatedTransactionActions()
    {
        $productType = ProductType::where('name', 'Paket')->first();

        foreach ($this->transaction_actions as $key => $action) {
            $price = intval(Str::replace('.', '', $action['price'] ?? 0));
            $quantity = intval(Str::replace(',', '', $action['quantity'])) ?? 1;
            $quantity = $quantity < 1 ? 1 : $quantity;

            $product = Product::find($action['product_id']);

            if ($product && $product->is_non_stock) {
                if ($product->product_type_id == $productType->id) {
                    $productPackages = ProductPackage::where('product_id', $action['product_id'])
                        ->where('company_id', Auth::user()->company_id)
                        ->get();

                    foreach ($productPackages as $key => $productPackages) {
                        $productStock = ProductStock::where('product_id', $productPackages->product_child_id)
                            ->where('company_id', Auth::user()->company_id)
                            ->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id)
                            ->first();

                        $getProduct = Product::find($productPackages->product_child_id);

                        $getQuantity = $productPackages->quantity * $quantity;

                        if ($productStock && $productStock->quantity < $getQuantity) {
                            AlertHelper::error('Gagal', "Stok Produk Dari Paket {$action['name']} dari produk {$getProduct->name} tidak mencukupi.");
                            $getQuantity = $productStock->quantity; // Set quantity ke 1 jika stok tidak mencukupi
                            $quantity = 1; // Set quantity ke 1 jika stok tidak mencukupi
                        }

                        TransactionDetailPackage::create([
                            'transaction_detail_id' => $action['id'],
                            'transaction_id' => $this->transaction_id,
                            'product_package_id' => $productPackages->id,
                            'product_id' => $productPackages->product_child_id,
                            'quantity_real' => $productPackages->quantity,
                            'quantity' => $getQuantity,
                        ]);
                    }
                } else {
                    $quantity = $quantity; // Jika produk tidak memerlukan stok, set quantity ke 1
                }
            } else {
                $productStock = ProductStock::where('product_id', $action['product_id'])
                    ->where('company_id', Auth::user()->company_id)
                    ->where('branch_id', Branch::where('company_id', Auth::user()->company_id)->first()->id)
                    ->first();

                if ($productStock && $productStock->quantity < $quantity) {
                    AlertHelper::error('Gagal', "Stok produk {$action['name']} tidak mencukupi.");
                    $quantity = 1; // Set quantity ke 1 jika stok tidak mencukupi
                }
            }

            $this->transaction_actions[$key]['quantity'] = number_format($quantity, 0, ',', '.');
            $this->transaction_actions[$key]['sub_total_price'] = number_format($price * $quantity, 0, ',', '.');

            $transactionAction = TransactionDetail::find($action['id']);
            if ($transactionAction) {
                $transactionAction->update([
                    'quantity' => $quantity,
                    'price' => $price,
                    'sub_total_price' => intval(Str::replace('.', '', $this->transaction_actions[$key]['sub_total_price'] ?? 0)),
                    'description' => $action['description'] ?? '',
                ]);
            }
        }
    }

    public function confirmDeleteAction($id)
    {
        return AlertHelper::confirmDelete('deleteAction', 'Apakah Anda yakin ingin menghapus tindakan ini?', $id);
    }

    public function deleteAction($id)
    {
        $transaction_action = TransactionDetail::find($id[0]);

        if ($transaction_action) {
            $transaction_action->delete();
            AlertHelper::success('Berhasil', 'Tindakan berhasil dihapus.');
            $this->detailAction();
        } else {
            AlertHelper::error('Gagal', 'Tindakan tidak ditemukan.');
        }
    }

    public function detail()
    {
        $transaction = TransactionDiagnosis::where('transaction_id', $this->transaction_id)->first();
        if ($transaction) {
            $this->subjective = $transaction->subjective;
            $this->objective = $transaction->objective;
            $this->assessment = $transaction->assessment;
            $this->plan = $transaction->plan;
            $this->return_recommendation = $transaction->return_recommendation;
        } else {
            $this->subjective = '';
            $this->objective = '';
            $this->assessment = '';
            $this->plan = '';
            $this->return_recommendation = '';
        }

        $allergyMedicine = AllergyMedicine::where('user_id', $this->transaction->patient_id)
            ->where('transaction_id', $this->transaction_id)
            ->where('company_id', Auth::user()->company_id)
            ->first();
        if ($allergyMedicine) {
            $this->allergy_name = $allergyMedicine->description;
        } else {
            $this->allergy_name = '';
        }
    }

    public function detailSchedule()
    {
        $schedule = UserControlSchedule::where('transaction_id', $this->transaction_id)->where('user_id', $this->transaction->patient_id)->first();

        if ($schedule) {
            $this->date = $schedule->date;
            $this->description_control = $schedule->description;
            $this->doctor_id = $schedule->doctor_id;
            $this->location_id = $schedule->location_id;
        } else {
            $this->date = null;
            $this->description_control = '';
            $this->doctor_id = null;
            $this->location_id = null;
        }
    }

    public function detailReference()
    {
        $reference = TransactionReference::where('transaction_id', $this->transaction_id)->first();

        if ($reference) {
            $this->hospital_name = $reference->hospital;
            $this->doctor_name = $reference->doctor_name;
            $this->description_refer = $reference->description;
            $this->date_refer = $reference->date;
        } else {
            $this->hospital_name = '';
            $this->doctor_name = '';
            $this->description_refer = '';
            $this->date_refer = null;
        }
    }

    public function createProofOfAction()
    {
        return $this->dispatch('open-modal', ['id' => 'modalProofOfAction']);
    }

    public function closeModalProofOfAction()
    {
        $this->reset(['description', 'type_before_photo', 'before_photo', 'type_after_photo', 'after_photo']);
        $this->dispatch('close-modal', ['id' => 'modalProofOfAction']);
    }

    public function saveAction()
    {
        $this->validate([
            'description' => 'required',
            'before_photo' => 'nullable|image|max:2048',
            'after_photo' => 'nullable|image|max:2048',
        ]);

        $before_photo = null;
        if ($this->before_photo) {
            $before_photo = $this->before_photo->store('proof_of_action/before', 'public');
        }
        $after_photo = null;
        if ($this->after_photo) {
            $after_photo = $this->after_photo->store('proof_of_action/after', 'public');
        }
        TransactionProofOfAction::create([
            'transaction_id' => $this->transaction_id,
            'description' => $this->description,
            'before_photo' => $before_photo,
            'after_photo' => $after_photo,
            'date' => now(),
            'user_id' => $this->transaction->patient_id,
        ]);
        AlertHelper::success('Berhasil', 'Bukti tindakan berhasil ditambahkan.');
        $this->closeModalProofOfAction();
        $this->detailProofOfAction();
    }

    public function confirmDeleteProofOfAction($id)
    {
        return AlertHelper::confirmDelete('deleteProofOfAction', 'Apakah Anda yakin ingin menghapus bukti tindakan ini?', $id);
    }

    public function deleteProofOfAction($id)
    {
        $proof_of_action = TransactionProofOfAction::find($id[0]);

        if ($proof_of_action) {
            if ($proof_of_action->before_photo && Storage::disk('public')->exists($proof_of_action->before_photo)) {
                Storage::disk('public')->delete($proof_of_action->before_photo);
            }
            if ($proof_of_action->after_photo && Storage::disk('public')->exists($proof_of_action->after_photo)) {
                Storage::disk('public')->delete($proof_of_action->after_photo);
            }

            $proof_of_action->delete();
            AlertHelper::success('Berhasil', 'Bukti tindakan berhasil dihapus.');
            $this->detailProofOfAction();
        } else {
            AlertHelper::error('Gagal', 'Bukti tindakan tidak ditemukan.');
        }
    }

    public function createMedicine()
    {
        $this->transaction_recipe_id = null; // Reset transaction_recipe_id
        $this->type = 'medicine';
        return $this->dispatch('open-modal', ['id' => 'modalProduct']);
    }

    public function closeModalProduct()
    {
        $this->reset(['type']);
        $this->dispatch('close-modal', ['id' => 'modalProduct']);
    }

    public function choiceProduct($id)
    {
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
            $productStock = $product->productStock()->where('company_id', $companyId)->where('branch_id', $branchId)->first();

            if (!$productStock || $productStock->quantity <= 0) {
                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            }
        }

        $productPrice = $product->productPrice()->where('company_id', $companyId)->where('branch_id', $branchId)->where('is_updated', true)->first();

        if (!$productPrice) {
            return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
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

        $this->closeModalProduct();
        $this->detailMedicine();
        return AlertHelper::success('Berhasil', 'Obat berhasil ditambahkan ke resep.');
    }

    public function detailMedicine()
    {
        $this->recipes = [];

        $transactionDetails = TransactionRecipe::where('transaction_id', $this->transaction_id)->orderBy('order', 'asc')->get();

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
                    'dosage_drug' => $detail->product->medicine_dosage,
                    'quantity_real' => $detail->quantity_real,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'sub_total_price' => $detail->sub_total_price,
                ];
            }
        }
        // $this->updateTransactionRecipe();
    }

    public function confirmDeleteTransactionRecipe($transactionRecipeId)
    {
        return AlertHelper::confirmDelete('deleteTransactionRecipe', 'Apakah Anda yakin ingin menghapus item ini?', $transactionRecipeId);
    }

    public function confirmDeleteTransactionDetail($transactionDetailId)
    {
        return AlertHelper::confirmDelete('deleteTransactionDetail', 'Apakah Anda yakin ingin menghapus item ini?', $transactionDetailId);
    }

    public function deleteTransactionDetail($transactionDetailId)
    {
        $transactionDetail = TransactionDetail::find($transactionDetailId[0]);

        if ($transactionDetail) {
            $transactionDetail->delete();
            $this->detailMedicine();
            AlertHelper::success('Berhasil', 'Item berhasil dihapus dari keranjang.');
        } else {
            AlertHelper::error('Gagal', 'Item tidak ditemukan.');
        }
    }

    public function deleteTransactionRecipe($transactionRecipeId)
    {
        $transactionRecipe = TransactionRecipe::find($transactionRecipeId[0]);

        if ($transactionRecipe) {
            TransactionDetail::where('transaction_recipe_id', $transactionRecipe->id)->where('transaction_id', $this->transaction_id)->delete();

            $transactionRecipe->delete();
            $this->detailMedicine();
            AlertHelper::success('Berhasil', 'Item berhasil dihapus dari keranjang.');
        } else {
            AlertHelper::error('Gagal', 'Item tidak ditemukan.');
        }
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
        $this->detailMedicine();
    }

    public function updateTransactionRecipe()
    {
        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            $branchId = Branch::where('company_id', $companyId)->first()->id;

            $transactionRecipes = TransactionRecipe::where('transaction_id', $this->transaction_id)->orderBy('order', 'asc')->get();

            foreach ($transactionRecipes as $key => $transactionRecipe) {
                $medicineType = MedicineType::find($transactionRecipe->medicine_type_id);
                $numeroRecipe = intval(Str::replace('.', '', $transactionRecipe->numero_recipe));

                if (!$medicineType) {
                    return AlertHelper::error('Gagal', 'Tipe Resep Pada /R' . ($key + 1) . ' tidak ditemukan.');
                }

                $product = Product::find($transactionRecipe->product_id);

                $productStock = ProductStock::where([
                    'product_id' => $transactionRecipe->product_id,
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                ])->first();

                $productPrice = ProductPrice::where([
                    'product_id' => $transactionRecipe->product_id,
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'is_updated' => true,
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

                    $transactionRecipe
                        ->fill([
                            'medicine_type_id' => $transactionRecipe->medicine_type_id,
                            'price_service_one' => $medicineType->service_price ?? 0,
                            'numero_recipe' => $numeroRecipe,
                            'quantity' => $quantity,
                            'price' => $price,
                            'sub_total_price' => $price * $quantity,
                            'description' => $transactionRecipe->description ?? null,
                        ])
                        ->save();

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
                            'branch_id' => $branchId,
                        ])->first();

                        $productPriceRecipe = ProductPrice::where([
                            'product_id' => $detail->product_id,
                            'company_id' => $companyId,
                            'branch_id' => $branchId,
                            'is_updated' => true,
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

                            $detail
                                ->fill([
                                    'type' => 'single',
                                    'dosage_doctor' => 0,
                                    'dosage_drug' => 0,
                                    'quantity_real' => $quantityRecipe,
                                    'quantity' => $quantityRecipe,
                                    'price' => $priceRecipe,
                                    'sub_total_price' => $priceRecipe * $quantityRecipe,
                                ])
                                ->save();
                        } else {
                            // Partial / Gramasi handling
                            if ($detail->type == 'partial') {
                                $detail->doctor_dosage_gram = $detail->dosage_doctor && $detail->dosage_drug ? $detail->dosage_doctor * $detail->dosage_drug * $numeroRecipe : 0;

                                $detail->quantity_real = $detail->dosage_drug && $detail->doctor_dosage_gram ? $detail->doctor_dosage_gram / $detail->dosage_drug : 0;
                            } elseif ($detail->type == 'gramasi') {
                                $detail->doctor_dosage_gram = $detail->dosage_doctor ? ($detail->dosage_doctor != 0 ? ($detail->dosage_drug / $detail->dosage_doctor) * $numeroRecipe : 0) : 0;

                                $detail->quantity_real = $detail->doctor_dosage_gram ? $detail->dosage_drug / $detail->doctor_dosage_gram : 0;
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

                            $detail
                                ->fill([
                                    'type' => $detail->type ?? 'single',
                                    'price' => $priceRecipe,
                                    'sub_total_price' => $priceRecipe * $detail->quantity,
                                ])
                                ->save();
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

    public function addDetail($transaction_recipe)
    {
        $this->transaction_recipe_id = $transaction_recipe;
        $this->type = 'medicine';
        $this->dispatch('open-modal', ['id' => 'modalProduct']);
    }

    public function render()
    {
        return view('livewire.admin.consultation.consultation.detail.admin-consultation-consultation-detail-index', [
            'icds' => $this->getIcds(),
            'actions' => $this->getActions(),
            'master_consultation_body_sites' => $this->MasterConsultationBodySite(),
            'master_consultation_categories' => $this->MasterConsultationCategory(),
            'master_consultation_clinic_statuses' => $this->MasterConsultationClinicStatus(),
            'master_consultation_severities' => $this->MasterConsultationSeverity(),
            'master_consultation_verification_statuses' => $this->MasterConsultationVerificationStatus(),
            'master_consultation_snomed_cts' => $this->MasterConsultationSnomedCT(),
            'master_consultation_terminologies' => $this->MasterConsultationTerminology(),
            'medicines' => $this->getMedicines(),
            'nurses' => $this->getNurses(),
            'master_medication_request_dosage_routes' => $this->masterMedicationRequestDosageRoute(),
            'how_to_uses' => $this->howToUses(),
        ])
            ->extends('layout.app')
            ->section('content');
    }

    private function MasterConsultationBodySite()
    {
        return $this->tab == 'diagnosa' ? MasterConditionBodySite::select('code', 'display')->get() : [];
    }

    private function MasterConsultationCategory()
    {
        return $this->tab == 'diagnosa' ? MasterConditionCategory::select('code', 'display')->get() : [];
    }

    private function MasterConsultationClinicStatus()
    {
        return $this->tab == 'diagnosa' ? MasterConditionClinicalStatus::select('code', 'display')->get() : [];
    }

    private function MasterConsultationSeverity()
    {
        return $this->tab == 'diagnosa' ? MasterConditionSeverity::select('code', 'display')->get() : [];
    }

    private function masterMedicationRequestDosageRoute()
    {
        return $this->tab == 'resep' ? MasterMedicationRequestDosageRoute::select('code', 'display')->get()->pluck('code_display', 'code')->toArray() : [];
    }

    private function howToUses()
    {
        return $this->tab == 'resep' ? HowToUse::select('id', 'name', 'description', 'day', 'time')->get()->pluck('name_display', 'id')->toArray() : [];
    }

    private function MasterConsultationVerificationStatus()
    {
        return $this->tab == 'diagnosa' ? MasterConditionVerificationStatus::select('code', 'display')->get() : [];
    }

    private function MasterConsultationSnomedCT()
    {
        return $this->tab == 'diagnosa' ? MasterConditionCodeChiefComplaint::select('code', 'display')->get() : [];
    }

    private function MasterConsultationTerminology()
    {
        return $this->tab == 'diagnosa' ? MasterConsultationTerminology::select('code', 'display')->get() : [];
    }

    private function getIcds()
    {
        if ($this->type !== 'icd10' && $this->type !== 'icd9' && $this->type !== 'supporting_icd10') {
            return [];
        }

        $model = in_array($this->type, ['icd10', 'supporting_icd10']) ? Icd10::class : Icd9::class;

        return $model::select('id', 'code', 'display')->search($this->search)->paginate($this->perPage);
    }

    private function getActions()
    {
        if ($this->type !== 'action') {
            return [];
        }

        return $this->getProducts(true);
    }

    private function getMedicines()
    {
        if ($this->type !== 'medicine') {
            return [];
        }

        return $this->getProducts(false);
    }

    private function getNurses()
    {
        if ($this->tab !== 'diagnosa') {
            return [];
        }

        return User::where('company_id', Auth::user()->company_id)
            ->select('id', 'name')
            ->where('type_user', 'employee')
            ->get()
            ->toArray();
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
                $q->select('id', 'product_id', 'quantity')->where('branch_id', $branchId);
            },
            'productPrice' => function ($q) use ($branchId) {
                $q->select('id', 'product_id', 'price', 'recipe')->where('branch_id', $branchId);
            },
        ]);

        if ($isAction) {
            $query->whereIn('product_type_id', $this->product_types);
        } else {
            $query->whereNotIn('product_type_id', $this->product_types);
        }

        return $query->paginate($this->perPage);
    }

    private function getBranchId()
    {
        return Cache::remember('branch_id_' . Auth::user()->company_id, 3600, function () {
            return Branch::where('company_id', Auth::user()->company_id)->value('id');
        });
    }

    public function confirmSave()
    {
        return AlertHelper::confirmSave('simpan', 'Apakah Anda yakin ingin menyimpan perubahan ini?');
    }

    public function simpan()
    {
        // Validasi Diagnosa
        $error = $this->validateDiagnosis();
        if ($error !== null) {
            return $error;
        }

        $error = $this->validateTransactionPrimary();
        if ($error !== null) {
            return $error;
        }

        $error = $this->validateTransactionSecondary();
        if ($error !== null) {
            return $error;
        }

        // Validasi Jadwal Kontrol
        $error = $this->validateScheduleControl();
        if ($error !== null) {
            return $error;
        }

        // Validasi Rujukan
        $error = $this->validateReferral();
        if ($error !== null) {
            return $error;
        }

        // Validasi Resep
        $error = $this->validateRecipes();
        if ($error !== null) {
            return $error;
        }

        // Simpan transaksi
        return $this->saveTransaction();
    }

    private function validateDiagnosis()
    {
        $fields = [
            'subjective' => 'Subjective',
            'objective' => 'Objective',
            'assessment' => 'Assessment',
            'plan' => 'Plan',
        ];

        $emptyFields = [];

        foreach ($fields as $fieldKey => $fieldName) {
            if (empty(trim($this->$fieldKey))) {
                $emptyFields[] = $fieldName;
            }
        }

        if (!empty($emptyFields)) {
            $this->changeTab('diagnosa');
            AlertHelper::error('Gagal', 'Field diagnosa wajib diisi: ' . implode(', ', $emptyFields));
            return false; // Return boolean false untuk error
        }


        return null;
    }

    public function validateTransactionPrimary()
    {
        $fields = [
            'description_primary' => 'Deskripsi Utama',
            'verification_status' => 'Status Verifikasi',
            'clinical_status' => 'Status Klinik',
            'snomed_code' => 'Snomed Code',
            'onset_datetime' => 'Onset Datetime'
        ];

        $isAnyFieldFilled = false;
        $emptyFields = [];

        // Cek apakah ada salah satu field yang terisi
        foreach ($fields as $fieldKey => $fieldName) {
            if (!empty(trim($this->$fieldKey))) {
                $isAnyFieldFilled = true;
                break;
            }
        }

        // Jika ada salah satu yang terisi, validasi semua
        if ($isAnyFieldFilled) {
            foreach ($fields as $fieldKey => $fieldName) {
                if (empty(trim($this->$fieldKey))) {
                    $emptyFields[] = $fieldName;
                }
            }

            if (!empty($emptyFields)) {
                $this->changeTab('diagnosa');
                AlertHelper::error('Gagal', 'Field Keluhan wajib diisi: ' . implode(', ', $emptyFields));
                return false;
            }
        }

        return null;
    }

    public function validateTransactionSecondary()
    {
        $fields = [
            'description_secondary' => 'Deskripsi Sekunder',
            'supporting_verification_status' => 'Status Verifikasi',
            'supporting_clinical_status' => 'Status Klinik',
            'supporting_snomed_code' => 'Snomed Code',
            'supporting_onset_datetime' => 'Onset Datetime'
        ];

        $isAnyFieldFilled = false;
        $emptyFields = [];

        // Cek apakah ada salah satu field yang terisi
        foreach ($fields as $fieldKey => $fieldName) {
            if (!empty(trim($this->$fieldKey))) {
                $isAnyFieldFilled = true;
                break;
            }
        }

        // Jika ada salah satu yang terisi, validasi semua
        if ($isAnyFieldFilled) {
            foreach ($fields as $fieldKey => $fieldName) {
                if (empty(trim($this->$fieldKey))) {
                    $emptyFields[] = $fieldName;
                }
            }

            if (!empty($emptyFields)) {
                $this->changeTab('diagnosa');
                AlertHelper::error('Gagal', 'Field Keluhan wajib diisi: ' . implode(', ', $emptyFields));
                return false;
            }
        }

        return null;
    }

    private function validateScheduleControl()
    {
        return $this->validateFieldGroup(
            [
                'date' => 'Tanggal',
                'doctor_id' => 'Dokter',
                'location_id' => 'Poliklinik',
                'description_control' => 'Deskripsi Kontrol',
            ],
            'jadwal-kontrol',
            'jadwal kontrol',
        );
    }

    private function validateReferral()
    {
        return $this->validateFieldGroup(
            [
                'hospital_name' => 'Nama Rumah Sakit',
                'doctor_name' => 'Nama Dokter',
                'description_refer' => 'Deskripsi Rujukan',
                'date_refer' => 'Tanggal Rujukan',
            ],
            'rujukan',
            'rujukan',
        );
    }

    private function validateFieldGroup($fields, $tab, $groupName)
    {
        $filledFields = array_filter($fields, fn($field) => !empty($this->$field), ARRAY_FILTER_USE_KEY);
        $filledCount = count($filledFields);
        $totalFields = count($fields);

        if ($filledCount > 0 && $filledCount < $totalFields) {
            $emptyFields = array_diff_key($fields, $filledFields);
            $this->changeTab($tab);
            AlertHelper::error('Gagal', 'Lengkapi field ' . $groupName . ' yang kosong: ' . implode(', ', $emptyFields) . ', atau kosongkan semua field ' . $groupName . '.');
            return false; // Return boolean false untuk error
        }

        return null;
    }

    private function validateRecipes()
    {
        // if ($this->transaction->transactionRecipes()->count() <= 0) {
        //     return AlertHelper::error('Gagal', 'Tidak ada item resep yang ditambahkan.');
        // }

        $recipes = $this->transaction
            ->transactionRecipes()
            ->with(['transactionDetail', 'medicineType'])
            ->get();

        foreach ($recipes as $key => $recipe) {
            $index = $key + 1;

            // Validasi basic recipe
            if (!$recipe->medicine_type_id) {
                return AlertHelper::error('Gagal', "Tipe resep /R {$index} belum dipilih.");
            }

            if ($recipe->numero_recipe <= 0) {
                return AlertHelper::error('Gagal', "Quantity resep /R {$index} belum diisi.");
            }

            if (!$recipe->medicineType->is_single && !$recipe->product_id) {
                return AlertHelper::error('Gagal', "Produk pendukung /R {$index} belum dipilih.");
            }

            if (!$recipe->how_to_use_id && !$recipe->route_coding_code && !$recipe->description) {
                return AlertHelper::error('Gagal', "Cara penggunaan & rute pemberian & Deskripsi Aturan Pakai /R {$index} belum diisi.");
            }

            if ($recipe->how_to_use_id && !HowToUse::find($recipe->how_to_use_id)) {
                return AlertHelper::error('Gagal', "Cara penggunaan /R {$index} tidak ditemukan.");
            }

            if ($recipe->route_coding_code && !MasterMedicationRequestDosageRoute::where('code', $recipe->route_coding_code)->exists()) {
                return AlertHelper::error('Gagal', "Rute pemberian /R {$index} tidak ditemukan.");
            }

            if (!$recipe->description) {
                return AlertHelper::error('Gagal', "Deskripsi resep /R {$index} belum diisi.");
            }

            if ($recipe->transactionDetail->isEmpty()) {
                return AlertHelper::error('Gagal', "Detail obat /R {$index} belum diisi.");
            }

            // Validasi detail
            foreach ($recipe->transactionDetail as $detail) {
                if ($error = $this->validateRecipeDetail($detail, $index, $recipe->medicineType)) {
                    return $error;
                }
            }
        }

        return null;
    }

    private function validateRecipeDetail($detail, $recipeIndex, $medicineType)
    {
        if (!$detail->product_id) {
            AlertHelper::error('Gagal', "Produk /R {$recipeIndex} belum dipilih.");
            return false; // Return false to indicate error
        }

        $isSingleType = in_array($detail->type, ['single', null]);
        if ($isSingleType) {
            AlertHelper::error('Gagal', "Opsi dosis /R {$recipeIndex} belum dipilih.");
            return false; // Return false to indicate error
        }

        if (!$medicineType->is_single && ($detail->dosage_doctor <= 0 || $detail->dosage_drug <= 0)) {
            AlertHelper::error('Gagal', "Dosis dokter/obat /R {$recipeIndex} belum diisi.");
            return false; // Return false to indicate error
        }

        if ($detail->quantity <= 0) {
            AlertHelper::error('Gagal', "Quantity /R {$recipeIndex} belum diisi.");
            return false; // Return false to indicate error
        }

        return null;
    }

    private function saveTransaction()
    {
        $productService = new ProductService();
        $companyId = Auth::user()->company_id;
        $branch = Branch::where('company_id', $companyId)->firstOrFail();

        $encounter = Encounter::where('transaction_id', $this->transaction_id)->first();
        try {
            DB::beginTransaction();

            $transaction = Transaction::find($this->transaction_id);
            if (!$transaction) {
                return AlertHelper::error('Gagal', 'Transaksi tidak ditemukan.');
            }

            $data = [
                'id' => null,
                'company_id' => $companyId,
                'transaction_id' => $this->transaction_id,
                'encounter_id' => $encounter ? $encounter->id : null,
            ];

            $transactionCondition = TransactionCondition::where('transaction_id', $this->transaction_id)->where('type', 'keluhan-utama')->first();
            $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();

            $dataPrimary = [
                "id" => "",
                "transaction_condition_id" => $transactionCondition->id,
                "company_id" => $companyId,
                "patient_id" => $patient->id,
                "encounter_id" => $encounter->id,
                "clinical_status" => $transactionCondition->clinical_status,
                "category" => "chief-complaint",
                "code" => $transactionCondition->snomed_code,
                "onset_date_time" => $transactionCondition->onset_date_time,
                "notes" => [$transactionCondition->description]
            ];

            app(apiservice::class)->createConditionPrimary($data);

            app(apiservice::class)->createCondition($dataPrimary);

            $data = [];

            $transactionActions = TransactionDetail::where('transaction_id', $this->transaction_id)->where('type_transaction', 'action')->get();

            foreach ($transactionActions as $action) {
                $this->processDetailLevel($transaction, $action, $productService, $companyId, $branch->id);
            }

            $transactionDetails = TransactionRecipe::where('transaction_id', $this->transaction_id)->orderBy('order', 'asc')->get();

            if ($transactionDetails->count() > 0) {
                foreach ($transactionDetails as $transactionDetail) {
                    $medicine_type = MedicineType::find($transactionDetail->medicine_type_id);
                    $transaction_recipe_real = TransactionRecipeReal::create([
                        'transaction_id' => $this->transaction_id,
                        'transaction_recipe_id' => $transactionDetail->id,
                        'product_id' => $transactionDetail->product_id,
                        'product_name' => $transactionDetail->product->name ?? '',
                        'medicine_type_id' => $transactionDetail->medicine_type_id,
                        'medicine_type_name' => $medicine_type ? $medicine_type->name : null,
                        'numero_recipe' => $transactionDetail->numero_recipe ?? 0,
                    ]);

                    foreach ($transactionDetail->transactionDetail as $detail) {
                        TransactionRecipeRealDetail::create([
                            'transaction_recipe_real_id' => $transaction_recipe_real->id,
                            'transaction_id' => $this->transaction_id,
                            'transaction_detail_id' => $detail->id,
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product->name ?? '',
                        ]);
                    }
                }
            }

            $encounter = Encounter::where('transaction_id', $transaction->id)->first();

            $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
            $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();

            $dataEncounter = [
                'id' => $encounter->id ?? null,
                'transaction_id' => $transaction->id,
                'company_id' => $transaction->company_id,
                'location_id' => $transaction->location_id,
                'patient_id' => $patient->id ?? null,
                'practitioner_id' => $doctor->id ?? null,
                'type' => 'outpatient',
                'status' => 'in-progress',
                'class_code' => 'AMB',
                "hospital_discharge_text" => $this->return_recommendation,
            ];

            app(apiservice::class)->createTransaction($dataEncounter);

            if ($transaction->transactionRecipes()->count() <= 0) {
                $transaction->update(['status' => 'process']);

                // Notification::create([
                //     'user_id' => $transaction->patient_id,
                //     'title' => 'Transaksi Baru',
                //     'description' => 'Anda memiliki transaksi baru.',
                //     'url' => route('user.sale.pos'),
                // ]);
            } else {

                $this->updateServiceTransactionRecipe($transaction);

                $transaction->update(['status' => 'pharmacy']);
                // Notification::create([
                //     'user_id' => $transaction->patient_id,
                //     'title' => 'Resep Baru',
                //     'description' => 'Anda memiliki resep baru.',
                //     'url' => route('user.sale.pos'),
                // ]);
            }

            DB::commit();

            session()->flash('saved', [
                'title' => 'Transaksi Berhasil!',
                'text' => 'Transaksi berhasil disimpan!',
            ]);

            return redirect()->route('user.consultation.consultation');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving transaction: ' . $e->getMessage());
            return AlertHelper::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function processDetailLevel($transaction, $data, $productService, $companyId, $branchId)
    {
        $transactionDetail = TransactionDetail::findOrFail($data['id']);
        $product = Product::findOrFail($data['product_id']);

        $productPackage = ProductPackage::where('product_id', $product->id)->where('company_id', $companyId)->get();

        // Get product price once for efficiency
        $productPrice = ProductPrice::where('product_id', $product->id)->where('company_id', $companyId)->where('branch_id', $branchId)->first();

        $hppPrice = $productPrice ? intval(Str::replace('.', '', number_format($productPrice->hpp_average, 0, ',', '.'))) : 0;
        $quantity = $data['quantity'];
        $sellingPrice = $data['price'];
        $subTotalPrice = $data['price'] * $quantity;

        // Update transaction detail for main product
        $transactionDetail->update([
            'price_hpp' => $hppPrice,
            'sub_total_price_hpp' => $hppPrice * $quantity,
            'sub_total_price' => $subTotalPrice,
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
                        'sub_total_price' => 0,
                    ]);

                    // $this->createTransactionProduct($transaction, $childData, $childProduct, $childHppPrice, $childQuantity, $childSellingPrice);
                    $productService->createProductDecrement($childProduct->id, $childQuantity, null, null, $childSellingPrice, null, null, null, null, null);
                }
            }
        }
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
            'user_id' => $transaction->patient_id,
            'user_name' => $transaction->patient_name ?? '',
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
                        'quantity_code' => trim($transactionDetail->product->denominator_code ?? null),
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
}
