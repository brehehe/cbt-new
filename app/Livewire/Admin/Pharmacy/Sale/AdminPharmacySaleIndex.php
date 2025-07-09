<?php

namespace App\Livewire\Admin\Pharmacy\Sale;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\Doctor\Doctor;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender;
use App\Models\Poly\Poly;
use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Models\User\UserCompanyRole;
use App\Models\User\UserDetail;
use Auth;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use Session;

class AdminPharmacySaleIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public $status, $date, $type_transaction, $patient_company_role_transaction_id;
    public $type;
    public $type_customer, $patient_id, $patient_company_role_id,$patient_name, $patient_address, $patient_phone, $patient_email, $catatan, $patient_gender, $patients = [], $genders = [];
    public $doctor_id, $doctors = [], $name_doctor, $type_doctor, $specialization, $hospital, $number_recipe, $cashBank, $amount, $description;

    public function mount() {
        $this->patients = UserCompanyRole::companyRole('Pasien', Auth::user()->company_id)
            ->get();

        $this->genders = MasterPatientAdministrativeGender::select('code','display')->get()->toArray();
        $this->doctors = Doctor::where('company_id', Auth::user()->company_id)->select('id','name','type','specialization')->get()->toArray();
        Session::forget('transaction_id');

        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');
            return;
        }
    }

    public function hydrate() {
        $this->resetPage();
    }

    public function openModal() {
        $this->type = 'non-resep';
        return $this->dispatch('open-modal', ['id'=>'modal']);
    }

    public function confirmDetail($id)
    {
        return AlertHelper::confirmInfo('detail', 'Apakah Anda Yakin Melihat Transaksi?', $id);
    }

    public function detail($id)
    {
        $transaction = Transaction::find($id[0]);
        if ($transaction) {
            Session::put('transaction_id', $transaction->id);
            if (in_array($transaction->type, ['non-resep'])) {
                return redirect()->route('user.pharmacy.sale.detail');
            } else {
                return redirect()->route('user.pharmacy.sale.recipe');
            }
        } else {
            AlertHelper::error('Error', 'Transaksi tidak ditemukan.');
        }
    }

    public function confirmCancel($id)
    {
        return AlertHelper::confirmDelete('cancel', 'Apakah Anda Yakin Membatalkan Transaksi?', $id);
    }

    public function cancel($id)
    {
        $transaction = Transaction::find($id[0]);
        if ($transaction) {
            $transaction->update([
                'status' => 'canceled',
            ]);
            return AlertHelper::success('Berhasil', 'Transaksi berhasil dibatalkan.');
        } else {
            return AlertHelper::error('Error', 'Transaksi tidak ditemukan.');
        }
    }

    public function changeType($type) {
        $this->type = $type;
        $this->reset('type_customer', 'patient_id', 'patient_company_role_id', 'patient_name', 'patient_address', 'patient_phone', 'patient_email', 'catatan', 'patient_gender','type', 'doctor_id', 'name_doctor', 'type_doctor', 'specialization', 'hospital');
    }

    public function updatedTypeCustomer() {
        $type = $this->type_customer;
        if ($type == 'umum') {
            $this->patient_name = 'Umum';
        } else {
            $this->patient_id = null;
            $this->patient_company_role_id = null;
            $this->patient_name = null;
            $this->patient_address = null;
            $this->patient_phone = null;
            $this->patient_gender = null;
        }
    }

    public function updatedPatientCompanyRoleId() {
        $patientCompany = UserCompanyRole::find($this->patient_company_role_id);
        if ($patientCompany) {
            $this->patient_id = $patientCompany->user->id;
            $this->patient_name = $patientCompany->user->name;
            $this->patient_address = $patientCompany->user->userDetail->address;
            $this->patient_phone = trim($patientCompany->user->phone);
            $this->patient_gender = $patientCompany->user->userDetail->administrative_gender;
        } else {
            $this->patient_id = null;
            $this->patient_name = null;
            $this->patient_address = null;
            $this->patient_phone = null;
            $this->patient_gender = null;
        }
    }

    public function updatedTypeDoctor() {
        $this->reset(['doctor_id', 'name_doctor', 'specialization', 'hospital']);
    }

    public function updatedDoctorId() {
        $doctor = Doctor::find($this->doctor_id);
        $this->name_doctor = $doctor ? $doctor->name : null;
        $this->specialization = $doctor ? $doctor->specialization : null;
        $this->hospital = $doctor ? $doctor->hospital : null;
    }

    public function closeModal() {
        $this->resetValidation();
        $this->reset(['type_customer', 'patient_id', 'patient_company_role_id', 'patient_name', 'patient_address', 'patient_phone', 'patient_email', 'catatan', 'patient_gender','type', 'doctor_id', 'name_doctor', 'type_doctor', 'specialization', 'hospital']);
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function saveTransaction() {
        $this->validate([
            'type_customer' => 'required',
            'patient_company_role_id' => $this->type_customer == 'member' ? 'required' : 'nullable',
            'patient_name' => $this->type_customer == 'umum' ? 'nullable' : 'required',
            'patient_address' => $this->type_customer == 'umum' ? 'nullable' : 'required',
            'patient_phone' => $this->type_customer == 'umum' ? 'nullable' : 'required',
            'number_recipe' => $this->type == 'resep' ? 'required' : 'nullable',
            'doctor_id'=> $this->type == 'resep' ? ($this->type_doctor == 'old' ? 'required' : 'nullable') : 'nullable',
            'name_doctor' => $this->type == 'resep' ? 'required' : 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'code' => 'TRX' . date('ymd') . str_pad(Transaction::whereDate('created_at', Carbon::now())->count() + 1, 4, '0', STR_PAD_LEFT),
                'type_customer' => $this->type_customer,
                'patient_name' => $this->patient_name,
                'type' => $this->type,
                'catatan' => $this->catatan,
                'status'=>'sale_pharmacy',
                'pharmacy' => 'yes',
            ]);

            if ($this->type_customer == 'new') {
                $name = strtolower(str_replace(' ', '', $this->patient_name));
                $username = $name;
                $counter = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $name . $counter;
                    $counter++;
                }

                $this->patient_phone = trim($this->patient_phone);

                $user = User::where('phone', $this->patient_phone)->orWhere('name', $this->patient_name)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $this->patient_name,
                        'username' => $username,
                        'phone' => $this->patient_phone,
                        'email' => $this->patient_email,
                        'password' => bcrypt('12345678'),
                    ]);
                }

                $patient = UserDetail::where('user_id', $user->id)->first();

                if (!$patient) {
                    $patient = UserDetail::create([
                        'user_id' => $user->id,
                        'address' => $this->patient_address,
                        'administrative_gender' => $this->patient_gender,
                    ]);
                }

                RoleHelper::assignRoleToUserInCompany($user, 'Pasien', Auth::user()->company_id, 'PMR' . date('ymd') . str_pad(User::where('company_id', Auth::user()->company_id)->whereDate('created_at', Carbon::now())->count() + 1, 5, '0', STR_PAD_LEFT));

                $patient_company = UserCompanyRole::companyRole('Pasien', Auth::user()->company_id)->where('user_id', $user->id)->where('company_id', Auth::user()->company_id)->first();

            } else {
                $patient_company = UserCompanyRole::find($this->patient_company_role_id);
                $user = User::find($this->patient_id);
            }

            if ($this->type == 'resep') {
                if ($this->type_doctor == 'new') {
                    $doctor = Doctor::create([
                        'name' => $this->name_doctor,
                        'specialization' => $this->specialization,
                        'hospital' => $this->hospital,
                        'company_id' => Auth::user()->company_id,
                    ]);

                    $transaction->doctor_id = $doctor->id;
                    $transaction->doctor_name = $doctor->name;
                } else {
                    $transaction->doctor_id = $this->doctor_id;
                    $transaction->doctor_name = $this->name_doctor;
                }
                $transaction->number_recipe = $this->number_recipe;
            } else {
                $transaction->doctor_id = null;
                $transaction->doctor_name = null;
                $transaction->number_recipe = null;
            }

            Session::put('transaction_id', $transaction->id);

            $location = Location::where('name','Instalasi Farmasi')->where('company_id', Auth::user()->company_id)->first();

            $locationCode = strtoupper(implode('', array_map(fn($word) => $word[0], explode(' ', $location->name))));
            $todayCount = Transaction::where('location_id', $location->id)
                    ->whereDate('date', Carbon::now())
                    ->count() + 1;

            $codeConsultation = $locationCode . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            $transaction->location_id = $location->id;
            $transaction->location_name = $location->name;
            $transaction->code_consultation = $codeConsultation;

            $transaction->patient_name = $user ? $user->name : $this->patient_name;
            $transaction->patient_id = $user ? $user->id : $this->patient_id;
            $transaction->patient_company_role_id = $patient_company ? $patient_company->id : $this->patient_company_role_id;
            $transaction->save();

            DB::commit();

            session()->flash('saved', [
                'title' => 'Transaksi Berhasil!',
                'text' => 'Anda berhasil membuat transaksi baru!',
            ]);

            $this->closeModal();

            // return redirect()->route('user.sale.pos.detail');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal membuat transaksi: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat membuat transaksi.');
        }
    }

    public function openDetail($id) {
        Session::put('transaction_id', $id);
        $transaction = Transaction::find($id);

        if (in_array($transaction->type, ['resep', 'konsultasi'])) {
            return redirect()->route('user.sale.pos.recipe');
        } else {
            return redirect()->route('user.sale.pos.detail');
        }
    }

    public function render()
    {
        $transactions = Transaction::search($this->search)
            ->where('pharmacy', 'yes')
            ->whereIn('status', ['sale_pharmacy','draft', 'process','take_medicine','completed', 'canceled'])
            ->orderBy('created_at', 'desc')
            ->where('company_id', auth()->user()->company_id);

        return view('livewire.admin.pharmacy.sale.admin-pharmacy-sale-index',[
            'transactions' => $transactions->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
