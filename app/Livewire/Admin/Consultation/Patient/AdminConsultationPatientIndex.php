<?php

namespace App\Livewire\Admin\Consultation\Patient;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\Branch\Branch;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender;
use App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Patient\Patient;
use App\Models\Poly\Poly;
use App\Models\Practitiont\Practitioner;
use App\Models\Role\RoleCompany;
use App\Models\Spatie\Role;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\User;
use App\Models\User\ControlDoctor;
use App\Models\User\UserCompanyRole;
use App\Models\User\UserDetail;
use App\Models\User\UserPrice;
use App\service\apiservice;
use App\Traits\OneHealth\AuthenticateTrait;
use App\Traits\Region\RegionTrait;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Session;

class AdminConsultationPatientIndex extends Component
{
    use WithPagination, RegionTrait, AuthenticateTrait;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'searchUser' => ['except' => ''],
    ];
    public $search = '';
    public $searchUser = '';
    public $perPage = 5;
    public $perPagePatient = 5;

    // User
    public $data_id;
    public $name;
    public $username;
    public $email;
    public $phone;
    public $user_id;
    public $user_detail;

    // User Detail
    public $address;
    public $ihs_number;
    public $identity_card;
    public $blood_group;
    public $administrative_gender;
    public $birth_date;
    public $marital_status;
    public $province_code;
    public $city_code;
    public $district_code;
    public $sub_district_code;
    public $rt_code;
    public $rw_code;
    public $postal_code;

    // Konsultasi
    public $doctor_id;
    public $location_id;
    public $control_doctor_id;
    public $day;
    public $date;

    // Array
    public $maritalStatusDetails = [];
    public $administrativeGenderDetails = [];
    public $doctors = [];
    public $locations = [];
    public $controlDoctors = [];
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $subDistricts = [];

    // public $getDays = [
    //     'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
    // ];

    public function mount()
    {
        Session::forget('patient_id');

        $this->maritalStatusDetails = MasterPatientMaritalStatus::select('code', 'display')->get()->map(function ($item) {
            return [
                'code' => $item->code,
                'display' => $item->display,             // versi asli
                'display_ind' => $item->display_ind,     // otomatis dari accessor
            ];
        });

        $this->administrativeGenderDetails = MasterPatientAdministrativeGender::select('code', 'display')->get()->toArray();
        $this->doctors = User::select('id', 'name')->companyRole('Dokter', Auth::user()->company_id)->get()->toArray();
        $this->locations = Location::where('company_id', Auth::user()->company_id)->select('id', 'name')->get()->toArray();
        $this->date = now()->format('Y-m-d');

        $this->provinces = [];
        $this->provinces = $this->getProvinceTrait();
    }

    public function updatedProvinceCode()
    {
        $this->cities = $this->getCityTrait($this->province_code);
        $this->reset(['city_code', 'district_code', 'sub_district_code']);
    }

    public function updatedCityCode()
    {
        $this->districts = $this->getDistrictTrait($this->city_code);
        $this->reset(['district_code', 'sub_district_code']);
    }

    public function updatedDistrictCode()
    {
        $this->subDistricts = $this->getSubDistrictTrait($this->district_code);
        $this->reset('sub_district_code');
    }

    public function updatedLocationId()
    {
        $this->reset('day', 'control_doctor_id');
        $this->getControlDoctors();
    }

    public function openModal()
    {
        // $this->identity_card = intval(rand(1000000000000000, 9999999999999999));
        $this->provinces = $this->getProvinceTrait();
        $this->date = now()->format('Y-m-d');
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'name',
            // 'username',
            'email',
            'phone',
            'address',
            'ihs_number',
            'identity_card',
            'blood_group',
            'administrative_gender',
            'birth_date',
            'marital_status',
            'doctor_id',
            'location_id',
            'control_doctor_id',
            'day',
            'date',
            'provinces',
            'cities',
            'districts',
            'subDistricts',
            'controlDoctors',
            'province_code',
            'city_code',
            'district_code',
            'sub_district_code',
            'user_id',
            'user_detail',
            'rt_code',
            'rw_code',
            'postal_code',
        ]);
        $this->resetValidation();
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function hydrate()
    {
        $this->resetPage();
        $this->resetPage('pagePatient');
    }

    public function updatedDoctorId()
    {
        $this->reset('day', 'control_doctor_id');
        $this->getControlDoctors();
    }

    public function updatedDate()
    {
        // $this->mount();
        $this->reset('day', 'control_doctor_id');
        $this->getControlDoctors();
    }

    public function getControlDoctors()
    {

        if ($this->location_id && $this->doctor_id && $this->date) {
            $days = [
                'Sunday'    => 'Minggu',
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
            ];

            $englishDay = date('l', strtotime($this->date));
            $this->day = $days[$englishDay];


            $this->controlDoctors = ControlDoctor::where('location_id', $this->location_id)
                ->where('user_id', $this->doctor_id)
                ->where('day', $this->day)
                ->get()
                ->filter(function ($item) {
                    $count = Transaction::where('control_doctor_id', $item->id)
                        ->where('location_id', $this->location_id) // pastikan transaksi sesuai dengan poli yang dipilih
                        ->where('doctor_id', $this->doctor_id) // pastikan transaksi sesuai dengan dokter yang dipilih
                        ->whereDate('date', $this->date)
                        ->count();
                    return $count < $item->max_patients;
                })
                ->map(function ($item) {
                    // Hitung jumlah pasien terdaftar pada tanggal yang dipilih untuk dokter kontrol ini
                    $countTransactions = Transaction::where('control_doctor_id', $item->id)
                        ->where('location_id', $this->location_id) // pastikan transaksi sesuai dengan poli yang dipilih
                        ->where('doctor_id', $this->doctor_id) // pastikan transaksi sesuai dengan dokter yang dipilih
                        ->whereDate('date', $this->date) // gunakan tanggal yang sedang dipilih user
                        ->count();

                    return [
                        'id' => $item->id,
                        'day' => $item->day,
                        'start_time' => $item->start_time,
                        'end_time' => $item->end_time,
                        'max_patients' => $item->max_patients,
                        'current_patients' => $countTransactions,
                        'remaining_quota' => $item->max_patients - $countTransactions,
                        'is_full' => $countTransactions >= $item->max_patients,
                    ];
                })
                ->toArray();
        } else {
            $this->controlDoctors = [];
        }
    }

    public function edit($id)
    {
        $this->provinces = $this->getProvinceTrait();
        $user = User::findOrFail($id);
        $this->data_id = $user->id;
        $this->name = $user->name;
        $this->user_id = $user->user_id;
        $this->user_detail = $user->user ? $user?->user?->name . ' (' . ($user?->user?->userDetail ? $user?->user?->userDetail?->address : '-') . ')' : '-';
        // $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = trim($user->phone);

        if ($user->userDetail) {

            $this->address = $user->userDetail->address;
            $this->identity_card = $user->userDetail->identity_card;
            $this->blood_group = $user->userDetail->blood_group;
            $this->administrative_gender = $user->userDetail->administrative_gender;
            $this->birth_date = $user->userDetail->birth_date ? $user->userDetail->birth_date->format('Y-m-d') : null;
            $this->marital_status = $user->userDetail->marital_status;
            $this->province_code = $user->userDetail->province_code;
            $this->city_code = $user->userDetail->city_code;
            $this->district_code = $user->userDetail->district_code;
            $this->sub_district_code = $user->userDetail->sub_district_code;
            $this->postal_code = $user->userDetail->postal_code;
            $this->rt_code = $user->userDetail->rt;
            $this->rw_code = $user->userDetail->rw;
            $this->cities = $this->getCityTrait($this->province_code);
            $this->districts = $this->getDistrictTrait($this->city_code);
            $this->subDistricts = $this->getSubDistrictTrait($this->district_code);
        }
        $this->openModal();
    }

    protected function rules()
    {
        $currentCompanyId = Auth::user()->company_id;

        return [
            'name' => 'required|string|max:255',
            'identity_card' => [
                'required',
                'string',
                'digits:16',
                'regex:/^[0-9]{16}$/',
                // function ($attribute, $value, $fail) use ($currentCompanyId) {
                //     $this->validateUniquePatientInCompany('identity_card', $value, $currentCompanyId, $fail);
                // },
            ],
            // 'username' => [
            //     'required',
            //     'string',
            //     'min:4',
            //     'max:50',
            //     'regex:/^\S*$/u', // tidak boleh ada spasi
            //     function ($attribute, $value, $fail) use ($currentCompanyId) {
            //         $this->validateUniquePatientInCompany('username', $value, $currentCompanyId, $fail);
            //     },
            // ],
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($currentCompanyId) {
                    $this->validateUniquePatientInCompany('email', $value, $currentCompanyId, $fail);
                },
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s\(\)]*$/', // hanya angka, +, -, spasi, dan kurung
                function ($attribute, $value, $fail) use ($currentCompanyId) {
                    $this->validateUniquePatientInCompany('phone', $value, $currentCompanyId, $fail);
                },
            ],
            'address' => 'required|string|max:500',
            'postal_code' => 'required|string|max:20',
            'blood_group' => 'nullable|string|max:10',
            'administrative_gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date|before:today',
            'marital_status' => 'required',
            'rt_code' => 'required',
            'rw_code' => 'required',
            'location_id' => 'required|exists:locations,id',
            'doctor_id' => 'required|exists:users,id',
            'control_doctor_id' => 'required|exists:control_doctors,id',
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'identity_card.required' => 'NIK wajib diisi',
            'identity_card.digits' => 'NIK harus terdiri dari 16 digit',
            'identity_card.regex' => 'NIK hanya boleh berisi angka',
            // 'username.required' => 'Username wajib diisi',
            // 'username.min' => 'Username minimal 4 karakter',
            // 'username.regex' => 'Username tidak boleh mengandung spasi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'address.required' => 'Alamat wajib diisi',
            'administrative_gender.required' => 'Jenis kelamin wajib dipilih',
            'administrative_gender.in' => 'Jenis kelamin harus male atau female',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'postal_code.required' => 'Kode pos wajib diisi',
            'marital_status.required' => 'Status pernikahan wajib dipilih',
            'location_id.required' => 'Poli wajib dipilih',
            'doctor_id.required' => 'Dokter wajib dipilih',
            'control_doctor_id.required' => 'Control doctor wajib dipilih',
            'rt_code' => 'RT wajib diisi',
            'rw_code' => 'RW wajib diisi',
        ];
    }

    /**
     * Main submit method
     */
    public function submit()
    {
        Log::info('Patient submit method called', [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            // 'username' => $this->username,
            'data_id' => $this->data_id,
            'user_id' => Auth::id()
        ]);

        $currentCompanyId = Auth::user()->company_id;
        Log::info('Current company ID: ' . $currentCompanyId);

        try {
            DB::beginTransaction();

            // Validate input
            $this->validate();
            Log::info('Validation passed successfully');

            // Get required models
            $location = Location::findOrFail($this->location_id);
            $doctor = User::findOrFail($this->doctor_id);

            // Handle user creation/update
            $userResult = $this->handlePatientIdentityResolution($currentCompanyId);

            if (!$userResult['success']) {
                DB::rollBack();
                Log::error('Failed to handle patient identity: ' . $userResult['message']);
                return AlertHelper::error('Gagal', $userResult['message']);
            }

            $user = $userResult['user'];
            Log::info('Patient handled successfully', ['user_id' => $user->id]);


            // Update user detail
            $this->updateUserDetail($user);

            $patient = Patient::where('user_id', $user->id)->first();
            $oneHealthPatient = OneHealthPatient::where('patient_id', $patient?->id)->first();
            if (!$patient || !$oneHealthPatient || !$oneHealthPatient->id_patient) {
                app(apiservice::class)->createUser($user);
            }

            // Assign patient role
            $this->assignPatientRole($user, $currentCompanyId);

            // $auth = $this->accessToken($user->company);
            // dd($auth);
            // Create transaction if required
            if ($this->shouldCreateTransaction()) {
                $this->createPatientTransaction($location, $doctor, $user, $currentCompanyId);
            }

            DB::commit();

            // $this->resetForm();
            $this->closeModal();

            AlertHelper::success('Berhasil', $userResult['is_update'] ? 'Patient berhasil diperbarui.' : 'Patient berhasil ditambahkan.');

            // $this->emit('patientSaved');
            Log::info('Patient successfully saved', ['user_id' => $user->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $errorMessages = collect($e->errors())->flatten()->implode(' ');
            AlertHelper::error('Validasi Gagal', $errorMessages);

            Log::error('Validation error saving patient', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'data' => $this->getPatientData()
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Patient gagal disimpan: ' . $th->getMessage());

            Log::error('Error saving patient', [
                'user_id' => Auth::id(),
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'data' => $this->getPatientData()
            ]);
        }
    }

    /**
     * Validasi khusus untuk patient: email, phone, dan username harus unik
     * dalam company yang sama untuk type_user = patient
     */
    protected function validateUniquePatientInCompany($field, $value, $companyId, $fail)
    {
        if (empty($value)) {
            return;
        }

        Log::info("Validating patient uniqueness", [
            'field' => $field,
            'value' => $value,
            'company_id' => $companyId,
            'exclude_user_id' => $this->data_id
        ]);

        // Cek duplikasi untuk patient dalam company yang sama
        $query = User::where($field, $value)
            ->where('type_user', 'patient')
            ->where('company_id', $companyId);

        // Exclude current user jika sedang update
        if ($this->data_id) {
            $query->where('id', '!=', $this->data_id);
        }

        $existingPatient = $query->first();

        if ($existingPatient) {
            $fieldName = $this->getFieldDisplayName($field);
            $errorMessage = "{$fieldName} '{$value}' sudah digunakan oleh pasien lain: {$existingPatient->name}";

            Log::warning("Patient validation failed", [
                'field' => $field,
                'value' => $value,
                'existing_patient_id' => $existingPatient->id,
                'existing_patient_name' => $existingPatient->name
            ]);

            $fail($errorMessage);
            return;
        }

        Log::info("Patient validation passed for field: {$field}");
    }

    /**
     * Get display name for field
     */
    protected function getFieldDisplayName($field)
    {
        $displayNames = [
            'email' => 'Email',
            'phone' => 'Nomor Telepon',
            'identity_card' => 'NIK',
            // 'username' => 'Username'
        ];

        return $displayNames[$field] ?? ucfirst($field);
    }

    /**
     * Handle patient identity resolution
     */
    protected function handlePatientIdentityResolution($companyId)
    {
        try {
            if ($this->data_id) {
                // Update existing patient
                return $this->updateExistingPatient($companyId);
            } else {
                // Create new patient
                return $this->createNewPatient($companyId);
            }
        } catch (\Exception $e) {
            Log::error('Error in handlePatientIdentityResolution', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'user' => null,
                'message' => $e->getMessage(),
                'is_update' => false
            ];
        }
    }

    /**
     * Update existing patient
     */
    protected function updateExistingPatient($companyId)
    {
        $user = User::find($this->data_id);

        if (!$user) {
            throw new \Exception('Patient tidak ditemukan');
        }

        // Validasi additional checks
        if ($user->type_user !== 'patient') {
            throw new \Exception('User yang dipilih bukan patient');
        }

        if ($user->company_id !== $companyId) {
            throw new \Exception('Patient tidak dalam company yang sama');
        }

        // Update patient data
        $user->update([
            'name' => $this->name,
            // 'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_id' => $companyId,
            'type_user' => 'patient',
            'updated_at' => now()
        ]);

        Log::info('Patient updated successfully', ['user_id' => $user->id]);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'Patient updated successfully',
            'is_update' => true
        ];
    }

    /**
     * Create new patient
     */
    protected function createNewPatient($companyId)
    {
        $userData = [
            'name' => $this->name,
            'user_id' => $this->user_id,
            // 'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make('12345678'), // Default password
            'phone' => $this->phone,
            'company_id' => $companyId,
            'type_user' => 'patient',
            'profile' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ];


        $user = User::create($userData);

        Log::info('New patient created successfully', ['user_id' => $user->id]);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'New patient created successfully',
            'is_update' => false
        ];
    }

    /**
     * Update user detail
     */
    protected function updateUserDetail($user)
    {
        $detailData = [
            'address' => $this->address,
            'identity_card' => $this->identity_card,
            'blood_group' => $this->blood_group,
            'administrative_gender' => $this->administrative_gender,
            'birth_date' => $this->birth_date,
            'marital_status' => $this->marital_status,
            'province_code' => $this->province_code,
            'city_code' => $this->city_code,
            'district_code' => $this->district_code,
            'sub_district_code' => $this->sub_district_code,
            'rt' => $this->rt_code,
            'rw' => $this->rw_code,
            'postal_code' => $this->postal_code,
            'updated_at' => now()
        ];

        UserDetail::updateOrCreate(
            ['user_id' => $user->id],
            $detailData
        );

        Log::info('Patient detail updated', ['user_id' => $user->id]);
    }

    /**
     * Assign patient role
     */
    protected function assignPatientRole($user, $companyId)
    {
        try {
            $role = Role::where('name', 'Pasien')->first();

            if (!$role) {
                throw new \Exception('Role Pasien tidak ditemukan');
            }

            $roleCompany = RoleCompany::where('company_id', $companyId)
                ->where('role_id', $role->uuid)
                ->first();

            if (!$roleCompany) {
                throw new \Exception('Role Pasien tidak tersedia untuk company ini');
            }

            // Check if role already assigned
            $existingRole = UserCompanyRole::where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->where('role_id', $role->uuid)
                ->first();

            if (!$existingRole) {
                RoleHelper::assignRoleToUserInCompany(
                    $user,
                    $role->name,
                    $companyId,
                    null,
                    false,
                    true
                );

                Log::info('Patient role assigned', [
                    'user_id' => $user->id,
                    'role' => $role->name,
                    'company_id' => $companyId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error assigning patient role', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check if should create transaction
     */
    protected function shouldCreateTransaction()
    {
        return !empty($this->location_id) && !empty($this->doctor_id) && !empty($this->control_doctor_id) && !empty($this->date);
    }

    /**
     * Create patient transaction
     */
    protected function createPatientTransaction($location, $doctor, $user, $companyId)
    {
        try {
            $branch = Branch::where('company_id', $companyId)->first();

            $patientRole = Role::where('name', 'Pasien')->first();
            $patientCompanyRole = UserCompanyRole::where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->where('role_id', $patientRole->uuid)
                ->first();

            // Generate transaction code
            $transactionCode = 'TRX' . date('ymd') . str_pad(
                Transaction::whereDate('created_at', Carbon::now())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Generate consultation code
            $locationCode = strtoupper(implode('', array_map(fn($word) => $word[0], explode(' ', $location->name))));
            $todayCount = Transaction::where('location_id', $this->location_id)
                ->where('doctor_id', $this->doctor_id)
                ->where('control_doctor_id', $this->control_doctor_id)
                ->whereDate('date', $this->date)
                ->count() + 1;

            $codeConsultation = $locationCode . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            // Create transaction
            $transactionData = [
                'code' => $transactionCode,
                'code_consultation' => $codeConsultation,
                'doctor_id' => $this->doctor_id,
                'doctor_name' => $doctor->name,
                'location_id' => $this->location_id,
                'location_name' => $location->name,
                'control_doctor_id' => $this->control_doctor_id,
                'patient_id' => $user->id,
                'patient_name' => $user->name,
                'patient_company_role_id' => $patientCompanyRole?->id,
                'date' => $this->date,
                'day' => $this->day ?? date('l', strtotime($this->date)),
                'branch_id' => $branch?->id,
                'type_customer' => $this->data_id ? 'member' : 'new',
                'type_doctor' => $this->data_id ? 'old' : 'new',
                'type' => 'konsultasi',
                'status' => 'draft_consultation',
                'consultation' => 'yes',
                'created_at' => now(),
                'updated_at' => now()
            ];

            $transaction = Transaction::create($transactionData);

            $userPrice = UserPrice::where('user_id', $this->doctor_id)
                ->where('company_id', $companyId)
                ->first();

            if ($userPrice) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => $this->doctor_id,
                    'quantity' => 1,
                    'name' => 'Biaya Konsultasi',
                    'price' => $userPrice->price_doctor,
                    'price_hpp' => 0,
                    'sub_total_price' => $userPrice->price_doctor,
                    'sub_total_price_hpp' => 0,
                    'type_transaction' => 'other',
                ]);
            }

            $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
            $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();

            $data = [
                'id' => null,
                'transaction_id' => $transaction->id,
                'company_id' => $transaction->company_id,
                'location_id' => $transaction->location_id,
                'patient_id' => $patient->id ?? null,
                'practitioner_id' => $doctor->id ?? null,
                'type' => 'outpatient',
                'status' => 'planned',
                'class_code' => 'AMB'
            ];

            app(apiservice::class)->createTransaction($data);

            Log::info('Patient transaction created', [
                'transaction_id' => $transaction->id,
                'transaction_code' => $transactionCode,
                'patient_id' => $user->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating patient transaction', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get patient data for logging
     */
    protected function getPatientData()
    {
        return [
            'name' => $this->name,
            // 'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'type_user' => 'patient',
            'company_id' => Auth::user()->company_id
        ];
    }

    public function confirmDetail($user_id)
    {
        Session::put('patient_id', $user_id);
        return redirect()->route('user.consultation.patient.detail');
    }

    public function debugPatientValidation()
    {
        $currentCompanyId = Auth::user()->company_id;

        $conflicts = [
            'email' => $this->findPatientConflictsInCompany('email', $this->email, $currentCompanyId, $this->data_id),
            'phone' => $this->findPatientConflictsInCompany('phone', $this->phone, $currentCompanyId, $this->data_id),
            // 'username' => $this->findPatientConflictsInCompany('username', $this->username, $currentCompanyId, $this->data_id)
        ];

        Log::info('Patient validation debug', [
            'conflicts' => $conflicts,
            'current_data' => $this->getPatientData()
        ]);

        return $conflicts;
    }

    /**
     * Find conflicts specifically for patients in company
     */
    protected function findPatientConflictsInCompany($field, $value, $companyId, $excludeUserId = null)
    {
        $conflicts = [];

        $query = User::where($field, $value)
            ->where('type_user', 'patient')
            ->where('company_id', $companyId);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        $patients = $query->get();

        foreach ($patients as $patient) {
            $conflicts[] = [
                'type' => 'patient_conflict',
                'user_id' => $patient->id,
                'user_name' => $patient->name,
                'field' => $field,
                'value' => $value,
                'context' => 'Patient dalam company yang sama'
            ];
        }

        return $conflicts;
    }

    public function render()
    {
        $patients = User::query()->search($this->search)->companyRole('Pasien', Auth::user()->company_id);

        return view('livewire.admin.consultation.patient.admin-consultation-patient-index', [
            'patients' => $patients->paginate($this->perPage),
            'users' => User::query()->where('id', '!=', $this->data_id)->search($this->searchUser)->companyRole('Pasien', Auth::user()->company_id)->paginate($this->perPagePatient, ['*'], 'pagePatient')
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function openModalUser()
    {
        $this->dispatch('close-modal', ['id' => 'modal']);
        $this->dispatch('open-modal', ['id' => 'modal-user']);
    }

    public function closeModalUser()
    {
        $this->dispatch('close-modal', ['id' => 'modal-user']);
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->user_detail = $user->name . ' (' . $user->userDetail->address . ')';
        $this->closeModalUser();
    }
}
