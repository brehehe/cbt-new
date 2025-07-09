<?php

namespace App\Livewire\Admin\Master\Doctor;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Http\Controllers\API\TestingController;
use App\Models\Doctor\Doctor;
use App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender;
use App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus;
use App\Models\Practitiont\Practitioner;
use App\Models\Role\RoleCompany;
use App\Models\User;
use App\Models\User\UserDetail;
use App\Models\User\UserPrice;
use App\service\apiservice;
use App\Traits\Region\RegionTrait;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Log;
use Str;

class AdminMasterDoctorIndex extends Component
{
    use WithPagination, WithFileUploads, RegionTrait;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;


    // User
    public $data_id;
    public $name;
    public $nik;
    public $username;
    public $email;
    public $password;
    public $profile;
    public $profile_old;
    public $phone;

    // User Detail
    public $address;
    public $identity_card;
    public $blood_group;
    public $administrative_gender;
    public $birth_date;
    public $deceased_date;
    public $marital_status;
    public $role_id;
    public $is_head = false;
    public $is_active = false;
    public $province_code;
    public $city_code;
    public $district_code;
    public $sub_district_code;
    public $doctor_id;
    public $country;
    public $rt_code;
    public $rw_code;
    public $longitude;
    public $latitude;
    public $altitude;

    public $maritalStatusDetails = [];
    public $administrativeGenderDetails = [];
    public $roles = [];
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $subDistricts = [];
    // Doctor
    public $sip_number;
    public $specialization;
    public $doctor_type = 'general';
    public $type = 'in';
    public $role_name;

    // Price
    public $incentive_doctor = 0;
    public $incentive_pharmacy = 0;
    public $incentive_nurse = 0;
    public $incentive_cashier = 0;
    public $price_doctor = 0;

    // Type Incentive
    public $type_incentive_doctor = 'rupiah';
    public $type_incentive_nurse = 'rupiah';
    public $type_incentive_pharmacy = 'rupiah';
    public $type_incentive_cashier = 'rupiah';

    // Other
    public $practitioner_id;

    public function mount()
    {
        $this->administrativeGenderDetails = MasterPatientAdministrativeGender::select('code', 'display')->get()->toArray();
        $this->maritalStatusDetails = MasterPatientMaritalStatus::select('code', 'display')->get()->map(function ($item) {
            return [
                'code' => $item->code,
                'display' => $item->display,             // versi asli
                'display_ind' => $item->display_ind,     // otomatis dari accessor
            ];
        });
        $this->provinces = $this->getProvinceTrait();
    }

    public function openModal()
    {
        $this->role_id = RoleCompany::where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'Dokter');
            })
            ->value('id');
        $this->role_name = RoleCompany::where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'Dokter');
            })
            ->first()->role->name;
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetInputFields();
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function resetInputFields()
    {
        $this->data_id = '';
        $this->name = '';
        $this->nik = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->profile = null;
        $this->profile_old = null;
        $this->phone = '';

        // User Detail
        $this->address = '';
        $this->identity_card = '';
        $this->blood_group = '';
        $this->administrative_gender = '';
        $this->birth_date = '';
        $this->deceased_date = '';
        $this->marital_status = '';
        $this->role_id = '';
        $this->is_head = false;
        $this->is_active = false;
        $this->province_code = '';
        $this->city_code = '';
        $this->district_code = '';
        $this->sub_district_code = '';
        $this->doctor_id = '';
        $this->country = 'ID';
        $this->rt_code = '';
        $this->rw_code = '';
        $this->longitude = '0';
        $this->latitude = '0';
        $this->altitude = '0';

        // Doctor
        $this->sip_number = '';
        $this->specialization = '';
        $this->doctor_type = 'general';
        $this->type = 'in';
        $this->role_name = '';

        // Price
        $this->incentive_doctor = 0;
        $this->incentive_pharmacy = 0;
        $this->incentive_nurse = 0;
        $this->incentive_cashier = 0;
        $this->price_doctor = 0;

        // Default
        $this->practitioner_id = '';
    }

    public function searchNik()
    {
        if ($this->name == '' && $this->nik == '') {
            AlertHelper::error('Gagal', 'Nama dan NIK tidak boleh kosong');
            return;
        }

        if (!is_numeric($this->nik)) {
            AlertHelper::error('Gagal', 'NIK harus berupa angka');
            return;
        }

        if (strlen($this->nik) < 16) {
            AlertHelper::error('Gagal', 'NIK harus 16 digit');
            return;
        }

        $this->reset(['address', 'province_code', 'city_code', 'district_code', 'sub_district_code', 'birth_date', 'administrative_gender', 'doctor_id', 'marital_status', 'identity_card', 'blood_group', 'deceased_date', 'sip_number', 'specialization', 'doctor_type', 'type', 'role_name', 'incentive_doctor', 'incentive_pharmacy', 'incentive_nurse', 'incentive_cashier', 'price_doctor', 'type_incentive_doctor', 'type_incentive_nurse', 'type_incentive_pharmacy', 'type_incentive_cashier', 'is_active', 'is_head', 'role_id', 'username', 'email', 'password', 'profile', 'profile_old', 'phone', 'doctor_id', 'rt_code', 'rw_code', 'longitude', 'latitude', 'altitude', 'country']);

        try {
            DB::beginTransaction();
            $data = [
                'company_id' => auth()->user()->company_id,
                'nik' => $this->nik,
                'name' => $this->name,
            ];

            $response = app(apiservice::class)->getPratition($data);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat mengambil data dokter');
            Log::error('Error fetching doctor data', ['data' => $data, 'error' => $e->getMessage()]);
            return;
        }

        if ($this->data_id) {
            $this->name = '';
            $this->nik = '';
            $this->username = '';
            $this->email = '';
            $this->password = '';
            $this->profile = null;
            $this->profile_old = null;
            $this->phone = '';

            // User Detail
            $this->address = '';
            $this->identity_card = '';
            $this->blood_group = '';
            $this->administrative_gender = '';
            $this->birth_date = '';
            $this->deceased_date = '';
            $this->marital_status = '';
            $this->role_id = '';
            $this->is_head = false;
            $this->is_active = false;
            $this->province_code = '';
            $this->city_code = '';
            $this->district_code = '';
            $this->sub_district_code = '';
            $this->doctor_id = '';
            $this->country = 'ID';
            $this->rt_code = '';
            $this->rw_code = '';
            $this->longitude = '0';
            $this->latitude = '0';
            $this->altitude = '0';

            // Doctor
            $this->sip_number = '';
            $this->specialization = '';
            $this->doctor_type = 'general';
            $this->type = 'in';
            $this->role_name = '';

            // Price
            $this->incentive_doctor = 0;
            $this->incentive_pharmacy = 0;
            $this->incentive_nurse = 0;
            $this->incentive_cashier = 0;
            $this->price_doctor = 0;

            // Default
            $this->practitioner_id = '';
            $this->edit($this->data_id);
        }

        $data = $response['data'] ?? null;
        $this->practitioner_id = $data['practitioner_id'] ?? '';

        $this->address = $data['address']['address'] ?? '';
        $this->doctor_id = $data['id_practitioner'] ?? '';
        $this->province_code = $data['address']['province_code'] ?? '';
        $this->updatedProvinceCode();
        $this->city_code = $data['address']['city_code'] ?? '';
        $this->updatedCityCode();
        $this->district_code = $data['address']['district_code'] ?? '';
        $this->updatedDistrictCode();
        $this->sub_district_code = $data['address']['village_code'] ?? '';
        $this->birth_date = $data['birth_date'] ?? '';
        $this->administrative_gender = $data['gender'] ? MasterPatientAdministrativeGender::where('code', $data['gender'])->first()->code : null;
        $this->country = $data['address']['country'] ?? 'ID';
        $this->rt_code = $data['address']['rt_code'] ?? '';
        $this->rw_code = $data['address']['rw_code'] ?? '';
        $this->longitude = $data['address']['longitude'] ?? '0';
        $this->latitude = $data['address']['latitude'] ?? '0';
        $this->altitude = $data['address']['altitude'] ?? '0';
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

    public function getPratitionerId()
    {
        if ($this->nik == '') {
            AlertHelper::error('Gagal', 'NIK tidak boleh kosong');
            return;
        }

        if (!is_numeric($this->nik)) {
            AlertHelper::error('Gagal', 'NIK harus berupa angka');
            return;
        }

        if (strlen($this->nik) < 16) {
            AlertHelper::error('Gagal', 'NIK harus 16 digit');
            return;
        }

        $data = [
            'company_id' => auth()->user()->company_id,
            'nik' => $this->nik,
            'name' => $this->name,
        ];

        $response = app(apiservice::class)->getPratition($data);

        if ($response['success']) {
            $data = $response['data'] ?? null;
            $this->practitioner_id = $data['practitioner_id'] ?? '';
            AlertHelper::success('Berhasil', 'ID Praktisi berhasil ditemukan.');
        } else {
            AlertHelper::error('Gagal', $response['message']);
        }
    }

    public function submit()
    {
        $currentCompanyId = Auth::user()->company_id;

        $this->reset(['role_id', 'role_name']);

        $this->role_id = RoleCompany::where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'Dokter');
            })
            ->value('id');
        $this->role_name = RoleCompany::where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'Dokter');
            })
            ->first()->role->name;

        $this->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'min:4',
                'regex:/^\S*$/u', // tidak boleh ada spasi
                function ($attribute, $value, $fail) use ($currentCompanyId) {
                    $this->validateUniqueInCompany('username', $value, $currentCompanyId, $fail);
                },
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($currentCompanyId) {
                    $this->validateUniqueInCompany('email', $value, $currentCompanyId, $fail);
                },
            ],
            'password' => $this->data_id ? 'nullable|string|min:8' : 'required|string|min:8',
            'profile' => 'nullable|image|max:2048',
            'phone' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) use ($currentCompanyId) {
                    $this->validateUniqueInCompany('phone', $value, $currentCompanyId, $fail);
                },
            ],
            'address' => 'required|string|max:500',
            'identity_card' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:10',
            'administrative_gender' => 'required',
            'birth_date' => 'nullable|date',
            'deceased_date' => 'nullable|date',
            'marital_status' => 'nullable',
            'sip_number' => $this->role_name == 'Dokter' ? 'required' : 'nullable',
            'specialization' => $this->role_name == 'Dokter' ? 'required|string|max:100' : 'nullable',
            'doctor_type' => $this->role_name == 'Dokter' ? 'required' : 'nullable',
        ]);

        if (!$this->practitioner_id) {
            $this->getPratitionerId();
            return AlertHelper::error('Gagal', 'ID Praktisi tidak ditemukan. Silakan cari NIK terlebih dahulu.');
        }

        try {
            DB::beginTransaction();

            // Handle user creation/update with smart identity resolution
            $userResult = $this->handleUserIdentityResolution($currentCompanyId);

            if (!$userResult['success']) {
                DB::rollBack();
                return AlertHelper::error('Gagal', $userResult['message']);
            }

            $user = $userResult['user'];

            // Update user detail
            $this->updateUserDetail($user);

            if ($this->role_name == 'Dokter') {
                $this->updateUserDoctor($user);
            }

            $practitioner = Practitioner::find($this->practitioner_id);
            $practitioner->user_id = $user->id;
            $practitioner->save();

            // Update user prices
            $this->updateUserPrices($user, $currentCompanyId);

            // Assign role (hanya untuk karyawan)
            $this->assignUserRole($user, $currentCompanyId);

            DB::commit();

            $this->closeModal();
            AlertHelper::success('Berhasil', 'Pengguna berhasil disimpan.');

            return;
        } catch (\Throwable $th) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Pengguna gagal disimpan. ' . $th->getMessage());
            Log::error('Error saving user: ' . $th->getMessage(), [
                'user_id' => Auth::id(),
                'data' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'role_id' => $this->role_id,
                    'type_user' => 'employee',
                ],
            ]);

            return;
        }
    }

    /**
     * Validate unique dalam company context untuk employee
     */
    protected function validateUniqueInCompany($field, $value, $companyId, $fail)
    {
        if (empty($value)) {
            return;
        }

        // Query untuk mencari konflik dalam company yang sama
        $query = User::where($field, $value)->where('type_user', 'employee')->where('company_id', $companyId);

        // Exclude user yang sedang di-update
        if ($this->data_id) {
            $query->where('id', '!=', $this->data_id);
        }

        $existingUser = $query->first();

        if ($existingUser) {
            $fieldLabel = $this->getFieldLabel($field);
            $fail("{$fieldLabel} '{$value}' sudah digunakan oleh karyawan lain dalam perusahaan ini ({$existingUser->name}).");
        }
    }

    /**
     * Get field label for error message
     */
    protected function getFieldLabel($field)
    {
        $labels = [
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'No. Telepon',
        ];

        return $labels[$field] ?? ucfirst($field);
    }

    /**
     * Handle user identity resolution with smart conflict handling
     */
    protected function handleUserIdentityResolution($companyId)
    {
        try {
            if ($this->data_id) {
                return $this->updateExistingUser($companyId);
            }

            // Untuk user baru, langsung buat tanpa mencari existing user
            // karena validasi uniqueness sudah dilakukan di atas
            $user = $this->createNewUser($companyId);

            return [
                'success' => true,
                'user' => $user,
                'message' => 'New user created successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'user' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function updateUserDoctor($user)
    {
        Doctor::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'specialization' => $this->specialization,
                'type' => 'internal',
                'company_id' => Auth::user()->company_id,
            ]
        );
    }

    /**
     * Update existing user
     */
    protected function updateExistingUser($companyId)
    {
        $user = User::find($this->data_id);
        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }

        $password = $this->password ? Hash::make($this->password) : $user->password;

        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $password,
            'profile' => $this->profile ? $this->profile->store('profiles', 'public') : $user->profile,
            'phone' => trim($this->phone),
            'company_id' => $companyId,
            'type_user' => 'employee',
        ]);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'User updated successfully',
        ];
    }

    /**
     * Create new user
     */
    protected function createNewUser($companyId)
    {
        return User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'profile' => $this->profile ? $this->profile->store('profiles', 'public') : null,
            'phone' => trim($this->phone),
            'company_id' => $companyId,
            'type_user' => 'employee',
        ]);
    }

    /**
     * Update user detail
     */
    protected function updateUserDetail($user)
    {
        UserDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $this->address,
                'identity_card' => $this->nik,
                'blood_group' => $this->blood_group,
                'administrative_gender' => $this->administrative_gender,
                'birth_date' => $this->birth_date,
                'deceased_date' => $this->deceased_date,
                'marital_status' => $this->marital_status,
                'sip_number' => $this->sip_number,
                'specialization' => $this->specialization,
                'doctor_type' => $this->doctor_type,
                'type' => $this->type ?? 'in',
                'province_code' => $this->province_code,
                'city_code' => $this->city_code,
                'district_code' => $this->district_code,
                'sub_district_code' => $this->sub_district_code,
                'doctor_id' => $this->doctor_id,
                'country' => $this->country,
                'rt' => $this->rt_code,
                'rw' => $this->rw_code,
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
                'altitude' => $this->altitude,
            ],
        );
    }

    /**
     * Update user prices
     */
    protected function updateUserPrices($user, $companyId)
    {
        UserPrice::updateOrCreate(
            [
                'user_id' => $user->id,
                'company_id' => $companyId,
            ],
            [
                'price_doctor' => $this->price_doctor ? intval(Str::replace('.', '', $this->price_doctor)) : 0,
                'type_incentive_doctor' => $this->type_incentive_doctor,
                'type_incentive_nurse' => $this->type_incentive_nurse,
                'type_incentive_pharmacy' => $this->type_incentive_pharmacy,
                'type_incentive_cashier' => $this->type_incentive_cashier,
                'incentive_doctor' => $this->incentive_doctor ? intval(Str::replace('.', '', $this->incentive_doctor)) : 0,
                'incentive_pharmacy' => $this->incentive_pharmacy ? intval(Str::replace('.', '', $this->incentive_pharmacy)) : 0,
                'incentive_nurse' => $this->incentive_nurse ? intval(Str::replace('.', '', $this->incentive_nurse)) : 0,
                'incentive_cashier' => $this->incentive_cashier ? intval(Str::replace('.', '', $this->incentive_cashier)) : 0,
            ],
        );
    }

    /**
     * Assign user role
     */
    protected function assignUserRole($user, $companyId)
    {
        $getRole = RoleCompany::find($this->role_id);
        if (!$getRole) {
            throw new \Exception('Role tidak ditemukan.');
        }

        $role = $getRole->role->name;
        RoleHelper::assignRoleToUserInCompany($user, $role, $companyId, null, $this->is_head, $this->is_active);
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus pengguna ini?', $id);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id[0]);
        if ($user->id == Auth::id()) {
            return AlertHelper::error('Gagal', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        AlertHelper::success('Pengguna Berhasil Dihapus');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->data_id = $user->id;
        $this->name = $user->name;
        $this->nik = $user->userDetail ? $user->userDetail->identity_card : '';
        $this->username = $user->username;
        $this->email = $user->email;
        $this->profile_old = $user->profile;
        $this->phone = trim($user->phone);

        $this->role_id =
            $user
            ->companyRoles()
            ->where('company_id', Auth::user()->company_id)
            ->first()->role_company_id ?? null;
        $this->role_name =
            $user
            ->companyRoles()
            ->where('company_id', Auth::user()->company_id)
            ->first()->role->name ?? null;

        if ($user->userDetail) {
            $this->address = $user->userDetail->address;
            $this->identity_card = $user->userDetail->identity_card;
            $this->blood_group = $user->userDetail->blood_group;
            $this->administrative_gender = $user->userDetail->administrative_gender;
            $this->birth_date = $user->userDetail->birth_date->format('Y-m-d') ?? '';
            $this->deceased_date = $user->userDetail->deceased_date;
            $this->marital_status = $user->userDetail->marital_status;
            $this->is_head =
                $user
                ->companyRoles()
                ->where('company_id', Auth::user()->company_id)
                ->first()->is_head ?? false;
            $this->is_active =
                $user
                ->companyRoles()
                ->where('company_id', Auth::user()->company_id)
                ->first()->is_active ?? false;
            $this->province_code = $user->userDetail->province_code;
            $this->updatedProvinceCode();
            $this->city_code = $user->userDetail->city_code;
            $this->updatedCityCode();
            $this->district_code = $user->userDetail->district_code;
            $this->updatedDistrictCode();
            $this->sub_district_code = $user->userDetail->sub_district_code;
            $this->country = $user->userDetail->country ?? 'ID';
            $this->rt_code = $user->userDetail->rt;
            $this->rw_code = $user->userDetail->rw;
            $this->longitude = $user->userDetail->longitude ?? '0';
            $this->latitude = $user->userDetail->latitude ?? '0';
            $this->altitude = $user->userDetail->altitude ?? '0';
            if ($this->role_name == 'Dokter') {
                $this->sip_number = $user->userDetail->sip_number;
                $this->specialization = $user->userDetail->specialization;
                $this->doctor_type = $user->userDetail->doctor_type ?? 'general';
                $this->incentive_doctor = number_format($user->userDetail->incentive_doctor ?? 0, 0, ',', '.');
                $this->type = $user->userDetail->type ?? 'in';
            }
        }

        if ($user->userPrice) {
            $this->price_doctor = number_format($user->userPrice->price_doctor ?? 0, 0, ',', '.');
            $this->incentive_doctor = number_format($user->userPrice->incentive_doctor ?? 0, 0, ',', '.');
            $this->incentive_pharmacy = number_format($user->userPrice->incentive_pharmacy ?? 0, 0, ',', '.');
            $this->incentive_nurse = number_format($user->userPrice->incentive_nurse ?? 0, 0, ',', '.');
            $this->incentive_cashier = number_format($user->userPrice->incentive_cashier ?? 0, 0, ',', '.');
            $this->type_incentive_doctor = $user->userPrice->type_incentive_doctor ?? 'rupiah';
            $this->type_incentive_nurse = $user->userPrice->type_incentive_nurse ?? 'rupiah';
            $this->type_incentive_pharmacy = $user->userPrice->type_incentive_pharmacy ?? 'rupiah';
            $this->type_incentive_cashier = $user->userPrice->type_incentive_cashier ?? 'rupiah';
        } else {
            $this->type_incentive_doctor = 'rupiah';
            $this->type_incentive_nurse = 'rupiah';
            $this->type_incentive_pharmacy = 'rupiah';
            $this->type_incentive_cashier = 'rupiah';
            $this->price_doctor = 0;
            $this->incentive_doctor = 0;
            $this->incentive_pharmacy = 0;
            $this->incentive_nurse = 0;
            $this->incentive_cashier = 0;
        }

        $this->openModal();
    }

    public function render()
    {
        $user = User::CompanyRole('Dokter', Auth::user()->company_id)
            ->search($this->search)
            ->where('type_user', 'employee')
            ->orderBy('name', 'asc');
        $users = $user->paginate($this->perPage);
        return view('livewire.admin.master.doctor.admin-master-doctor-index', [
            'users' => $users,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
