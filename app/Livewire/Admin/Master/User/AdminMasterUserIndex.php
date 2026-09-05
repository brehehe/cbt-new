<?php

namespace App\Livewire\Admin\Master\User;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\Doctor\Doctor;
use App\Models\Role\RoleCompany;
use App\Models\User;
use App\Models\User\UserDetail;
use App\Traits\Region\RegionTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdminMasterUserIndex extends Component
{
    use RegionTrait, WithFileUploads, WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;

    // User
    public $data_id;

    public $name;

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

    public $rt_code;

    public $rw_code;

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

    public function mount()
    {
        $this->roles = RoleCompany::with([
            'role' => function ($query) {
                $query->where('name', 'not like', '%Pasien%')
                    ->where('name', 'not like', '%Dokter%');
            },
        ])
            ->select('id', 'role_id', 'company_id')
            ->where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'not like', '%Pasien%')
                    ->where('name', 'not like', '%Dokter%');
            })
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->role->name,
                ];
            })
            ->toArray();

        $this->provinces = $this->getProvinceTrait();
    }

    public function openModal()
    {
        $this->type_incentive_doctor = 'rupiah';
        $this->type_incentive_nurse = 'rupiah';
        $this->type_incentive_pharmacy = 'rupiah';
        $this->type_incentive_cashier = 'rupiah';
        $this->provinces = $this->getProvinceTrait();

        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->reset(['data_id', 'name', 'username', 'email', 'password', 'profile', 'profile_old', 'phone', 'address', 'identity_number', 'blood_group', 'administrative_gender', 'birth_date', 'deceased_date', 'marital_status', 'role_id', 'is_head', 'is_active', 'sip_number', 'specialization', 'doctor_type', 'type', 'incentive_doctor', 'incentive_pharmacy', 'incentive_nurse', 'incentive_cashier', 'type_incentive_doctor', 'type_incentive_nurse', 'type_incentive_pharmacy', 'type_incentive_cashier', 'price_doctor', 'role_name', 'province_code', 'city_code', 'district_code', 'sub_district_code', 'provinces', 'cities', 'districts', 'subDistricts', 'rt_code', 'rw_code']);
        $this->resetErrorBag();
        $this->resetValidation();

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function updatedRoleId()
    {
        $role = RoleCompany::find($this->role_id);
        if ($role) {
            $this->role_name = $role->role->name;
            if ($role->role->name == 'Dokter') {
                $user = User::find($this->data_id);
                if ($user) {
                    $this->sip_number = $user->userDetail->sip_number ?? null;
                    $this->specialization = $user->userDetail->specialization ?? null;
                    $this->doctor_type = $user->userDetail->doctor_type ?? 'general';
                    $this->type = $user->userDetail->type ?? 'in';
                }
            } elseif ($role->role->name == 'Sales') {
                $this->sip_number = null;
                $this->specialization = null;
                $this->doctor_type = null;
            } elseif ($role->role->name == 'Kasir') {
                $this->sip_number = null;
                $this->specialization = null;
                $this->doctor_type = null;
            }
        } else {
            $this->role_name = null;
            $this->sip_number = null;
            $this->specialization = null;
            $this->doctor_type = 'general';
            $this->type = 'in';
        }
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

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->data_id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->profile_old = $user->profile;
        $this->phone = $user->phone;

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
            $this->birth_date = $user->userDetail->birth_date ? $user->userDetail->birth_date->format('Y-m-d') : null;
            $this->deceased_date = $user->userDetail->deceased_date;
            $this->marital_status = $user->userDetail->marital_status;
            $this->province_code = $user->userDetail->province_code;
            $this->updatedProvinceCode();
            $this->city_code = $user->userDetail->city_code;
            $this->updatedCityCode();
            $this->district_code = $user->userDetail->district_code;
            $this->updatedDistrictCode();
            $this->sub_district_code = $user->userDetail->sub_district_code;
            $this->rt_code = $user->userDetail->rt;
            $this->rw_code = $user->userDetail->rw;
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
            if ($this->role_name == 'Dokter') {
                $this->sip_number = $user->userDetail->sip_number;
                $this->specialization = $user->userDetail->specialization;
                $this->doctor_type = $user->userDetail->doctor_type ?? 'general';
                $this->incentive_doctor = number_format($user->userDetail->incentive_doctor ?? 0, 0, ',', '.');
                $this->type = $user->userDetail->type ?? 'in';
            }
        }

        $this->openModal();
    }

    public function updatedTypeIncentiveDoctor()
    {
        $this->incentive_doctor = 0; // Reset to 0 if switching to percentage
    }

    public function updatedTypeIncentiveNurse()
    {
        $this->incentive_nurse = 0; // Reset to 0 if switching to percentage
    }

    public function updatedTypeIncentivePharmacy()
    {
        $this->incentive_pharmacy = 0; // Reset to 0 if switching to percentage
    }

    public function updatedTypeIncentiveCashier()
    {
        $this->incentive_cashier = 0; // Reset to 0 if switching to percentage
    }

    public function updatedIncentiveDoctor()
    {
        $incentive_doctor = intval(Str::replace('.', '', $this->incentive_doctor));
        // Jika tipe insentif adalah persen, pastikan nilainya tidak lebih dari 100
        if ($this->type_incentive_doctor == 'persen' && $incentive_doctor > 100) {
            $this->incentive_doctor = 100;
        } else {
            $this->incentive_doctor = number_format($incentive_doctor, 0, ',', '.');
        }
    }

    public function updatedIncentiveNurse()
    {
        $incentive_nurse = intval(Str::replace('.', '', $this->incentive_nurse));
        // Jika tipe insentif adalah persen, pastikan nilainya tidak lebih dari 100
        if ($this->type_incentive_nurse == 'persen' && $incentive_nurse > 100) {
            $this->incentive_nurse = 100;
        } else {
            $this->incentive_nurse = number_format($incentive_nurse, 0, ',', '.');
        }
    }

    public function updatedIncentivePharmacy()
    {
        $incentive_pharmacy = intval(Str::replace('.', '', $this->incentive_pharmacy));
        // Jika tipe insentif adalah persen, pastikan nilainya tidak lebih dari 100
        if ($this->type_incentive_pharmacy == 'persen' && $incentive_pharmacy > 100) {
            $this->incentive_pharmacy = 100;
        } else {
            $this->incentive_pharmacy = number_format($incentive_pharmacy, 0, ',', '.');
        }
    }

    public function updatedIncentiveCashier()
    {
        $incentive_cashier = intval(Str::replace('.', '', $this->incentive_cashier));
        // Jika tipe insentif adalah persen, pastikan nilainya tidak lebih dari 100
        if ($this->type_incentive_cashier == 'persen' && $incentive_cashier > 100) {
            $this->incentive_cashier = 100;
        } else {
            $this->incentive_cashier = number_format($incentive_cashier, 0, ',', '.');
        }
    }

    public function submit()
    {
        $currentCompanyId = Auth::user()->company_id;

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
            'identity_number' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:10',
            'administrative_gender' => 'required',
            'birth_date' => 'required|date',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'sub_district_code' => 'required',
            'deceased_date' => 'nullable|date',
            'marital_status' => 'nullable',
            'sip_number' => $this->role_name == 'Dokter' ? 'required|string|max:20' : 'nullable',
            'specialization' => $this->role_name == 'Dokter' ? 'required|string|max:100' : 'nullable',
            'doctor_type' => $this->role_name == 'Dokter' ? 'required|in:general,specialist' : 'nullable',
            'type' => $this->role_name == 'Dokter' ? 'required|in:in,out' : 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // Handle user creation/update with smart identity resolution
            $userResult = $this->handleUserIdentityResolution($currentCompanyId);

            if (! $userResult['success']) {
                DB::rollBack();

                return AlertHelper::error('Gagal', $userResult['message']);
            }

            $user = $userResult['user'];

            // Update user detail
            $this->updateUserDetail($user);

            if ($this->role_name == 'Dokter') {
                $this->updateUserDoctor($user);
            }

            // Assign role (hanya untuk karyawan)
            $this->assignUserRole($user, $currentCompanyId);

            DB::commit();

            $this->closeModal();
            AlertHelper::success('Berhasil', 'Pengguna berhasil disimpan.');

            return;
        } catch (\Throwable $th) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Pengguna gagal disimpan. '.$th->getMessage());
            Log::error('Error saving user: '.$th->getMessage(), [
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
        if (! $user) {
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
                'identity_number' => $this->identity_card,
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
                'rt' => $this->rt_code,
                'rw' => $this->rw_code,
            ],
        );
    }

    /**
     * Assign user role
     */
    protected function assignUserRole($user, $companyId)
    {
        $getRole = RoleCompany::find($this->role_id);
        if (! $getRole) {
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

    public function closeModalPrice()
    {
        $this->reset(['data_id', 'incentive_doctor', 'incentive_pharmacy', 'incentive_nurse', 'incentive_cashier', 'type_incentive_doctor', 'type_incentive_nurse', 'type_incentive_pharmacy', 'type_incentive_cashier']);
        $this->resetErrorBag();
        $this->resetValidation();

        return $this->dispatch('close-modal', ['id' => 'modal-price']);
    }

    public function render()
    {
        $user = User::companyWithoutRolePasienAndDokter(Auth::user()->company_id)
            ->with(['companyRoles' => function ($q) {
                $q->where('company_id', Auth::user()->company_id)->with('role');
            }])
            ->search($this->search)
            ->where('type_user', 'employee')
            ->orderBy('name', 'asc');

        return view('livewire.admin.master.user.admin-master-user-index', [
            'users' => $user->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
