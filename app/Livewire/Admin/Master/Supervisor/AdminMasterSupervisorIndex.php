<?php

namespace App\Livewire\Admin\Master\Supervisor;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\User;
use App\Models\UsrSecKey;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Log;
use Hash;
use App\Exports\SupervisorExport;
use App\Imports\User\SupervisorImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminMasterSupervisorIndex extends Component
{
    use WithPagination, WithFileUploads;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
    ];

    public $search = '';
    public $statusFilter = '';
    public $departmentFilter = '';

    public $perPage = 5;

    // Import file
    public $importFile;

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
    public $supervisor_id;
    public $supervisor_nip;
    public $supervisor_department;
    public $supervisor_unit;
    public $supervisor_position;
    public $supervisor_level;
    public $supervisor_area;
    public $supervisor_specialization;
    public $supervisor_status;
    public $supervisor_type;
    public $supervisor_start_date;
    public $supervisor_experience_years;
    // public $identity_card;
    public $is_head = true;
    public $is_active = true;

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'name',
            'username',
            'email',
            'password',
            'profile',
            'profile_old',
            'phone',
            'address',
            'supervisor_id',
            'supervisor_id',
            'supervisor_nip',
            'supervisor_department',
            'supervisor_unit',
            'supervisor_position',
            'supervisor_level',
            'supervisor_area',
            'supervisor_specialization',
            'supervisor_status',
            'supervisor_type',
            'supervisor_start_date',
            'supervisor_experience_years',
            // 'identity_number',
            'is_head',
            'is_active',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->data_id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->profile_old = $user->profile;
        $this->phone = trim($user->phone ?? '');

        if ($user->userDetail) {
            $this->address = $user->userDetail->address;
            $this->supervisor_id = $user->userDetail->supervisor_id;
            $this->supervisor_id = $user->userDetail->supervisor_id;
            $this->supervisor_nip = $user->userDetail->supervisor_nip;
            $this->supervisor_department = $user->userDetail->supervisor_department;
            $this->supervisor_unit = $user->userDetail->supervisor_unit;
            $this->supervisor_position = $user->userDetail->supervisor_position;
            $this->supervisor_level = $user->userDetail->supervisor_level;
            $this->supervisor_area = $user->userDetail->supervisor_area;
            $this->supervisor_specialization = $user->userDetail->supervisor_specialization;
            $this->supervisor_status = $user->userDetail->supervisor_status;
            $this->supervisor_type = $user->userDetail->supervisor_type;
            $this->supervisor_start_date = $user->userDetail->supervisor_start_date;
            $this->supervisor_experience_years = $user->userDetail->supervisor_experience_years;

            $companyRole = $user->companyRoles()
                ->where('company_id', Auth::user()->company_id)
                ->first();

            $this->is_head = $companyRole ? $companyRole->is_head : true;
            $this->is_active = $companyRole ? $companyRole->is_active : true;
        }
        $this->openModal();
    }

    public function submit()
    {
        $currentCompanyId = Auth::user()->company_id;

        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'min:4',
                'regex:/^\S*$/u', // tidak boleh ada spasi
                Rule::unique('users', 'username')
                    ->where('type_user', 'employee')
                    ->where('company_id', $currentCompanyId)
                    ->ignore($this->data_id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->where('type_user', 'employee')
                    ->where('company_id', $currentCompanyId)
                    ->ignore($this->data_id),
            ],
            'password' => $this->data_id ? 'nullable|string|min:8' : 'required|string|min:8',
            'profile' => 'nullable|image|max:2048',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')
                    ->where('type_user', 'employee')
                    ->where('company_id', $currentCompanyId)
                    ->ignore($this->data_id),
            ],
            'address' => 'nullable|string|max:500',
            'supervisor_id' => 'required|string|max:50|unique:user_details,supervisor_id,' . ($this->data_id ? $this->data_id : 'NULL') . ',user_id',
        ]);

        try {
            // Mulai database transaction
            DB::beginTransaction();

            // Handle user creation/update
            $userResult = $this->handleUserIdentityResolution($currentCompanyId, $validatedData);

            if (!$userResult['success']) {
                DB::rollBack();
                $this->addError('general', $userResult['message']);
                return;
            }

            $user = $userResult['user'];

            // Update user detail
            $this->updateUserDetail($user, $validatedData);

            // Assign role (hanya untuk karyawan)
            $this->assignUserRole($user, $currentCompanyId);

            // Commit transaction jika semua berhasil
            DB::commit();

            // Reset form dan tutup modal
            $this->reset();
            $this->closeModal();

            AlertHelper::success('Berhasil', 'Pengguna berhasil disimpan.');
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollBack();
            $this->setErrorBag($e->validator->getMessageBag());
            return;
        } catch (\Exception $e) {
            // Handle general errors
            DB::rollBack();

            $errorMessage = 'Pengguna gagal disimpan.';

            // Log detailed error for debugging
            Log::error('Error saving user: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'company_id' => $currentCompanyId,
                'data' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'username' => $this->username,
                    'type_user' => 'employee',
                ],
                'trace' => $e->getTraceAsString(),
            ]);

            // Show user-friendly error message
            if (app()->environment('local')) {
                $errorMessage .= ' Error: ' . $e->getMessage();
            }

            AlertHelper::error('Gagal', $errorMessage);
            $this->addError('general', $errorMessage);
        } catch (\Throwable $th) {
            // Handle any other throwable errors
            DB::rollBack();

            Log::error('Critical error saving user: ' . $th->getMessage(), [
                'user_id' => Auth::id(),
                'company_id' => $currentCompanyId,
                'trace' => $th->getTraceAsString(),
            ]);

            AlertHelper::error('Gagal', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            $this->addError('general', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Handle user identity resolution with smart conflict handling
     */
    protected function handleUserIdentityResolution($companyId, $validatedData)
    {
        try {
            if ($this->data_id) {
                return $this->updateExistingUser($companyId, $validatedData);
            }

            // Untuk user baru, buat user baru
            $user = $this->createNewUser($companyId, $validatedData);

            UsrSecKey::create([
                'user_id' => $user->id,
                'company_id' => $companyId,
                'sec_val' => encrypt($validatedData['password']),
            ]);

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

    /**
     * Update existing user
     */
    protected function updateExistingUser($companyId, $validatedData)
    {
        $user = User::find($this->data_id);
        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }

        // Verify user belongs to the same company
        if ($user->company_id !== $companyId) {
            throw new \Exception('User tidak ditemukan dalam perusahaan ini');
        }

        // Prepare update data
        $updateData = [
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'phone' => trim($validatedData['phone']),
            'company_id' => $companyId,
            'type_user' => 'employee',
        ];

        // Handle password update
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        // Handle profile image
        if ($this->profile && $this->profile instanceof \Illuminate\Http\UploadedFile) {
            // Delete old profile if exists
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }
            $updateData['profile'] = $this->profile->store('profiles', 'public');
        }

        if (!empty($validatedData['password'])) {
            UsrSecKey::where('user_id', $user->id)->where('company_id', $companyId)->update([
                'sec_val' => encrypt($validatedData['password']),
            ]);
        }

        $user->update($updateData);

        return [
            'success' => true,
            'user' => $user->fresh(),
            'message' => 'User updated successfully',
        ];
    }

    /**
     * Create new user
     */
    protected function createNewUser($companyId, $validatedData)
    {
        // Prepare create data
        $createData = [
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => trim($validatedData['phone']),
            'company_id' => $companyId,
            'type_user' => 'employee',
        ];

        // Handle profile image
        if ($this->profile && $this->profile instanceof \Illuminate\Http\UploadedFile) {
            $createData['profile'] = $this->profile->store('profiles', 'public');
        }

        return User::create($createData);
    }

    /**
     * Update user detail
     */
    protected function updateUserDetail($user, $validatedData)
    {
        $detailData = [
            'address' => $validatedData['address'] ?? null,
            'supervisor_id' => $validatedData['supervisor_id'] ?? null,
            'supervisor_id' => $this->supervisor_id ?? $validatedData['supervisor_id'],
            'supervisor_nip' => $this->supervisor_nip ?? null,
            'supervisor_department' => $this->supervisor_department,
            'supervisor_unit' => $this->supervisor_unit,
            'supervisor_position' => $this->supervisor_position,
            'supervisor_level' => $this->supervisor_level,
            'supervisor_area' => $this->supervisor_area,
            'supervisor_specialization' => $this->supervisor_specialization,
            'supervisor_status' => $this->supervisor_status ?? 'active',
            'supervisor_type' => $this->supervisor_type ?? 'internal',
            'supervisor_start_date' => $this->supervisor_start_date,
            'supervisor_experience_years' => $this->supervisor_experience_years,
        ];

        // Handle identity card encryption
        // if (!empty($validatedData['identity_number'])) {
        //     $detailData['identity_number'] = Crypt::encryptString($validatedData['identity_number']);
        // }

        UserDetail::updateOrCreate(
            ['user_id' => $user->id],
            $detailData
        );
    }

    /**
     * Assign user role
     */
    protected function assignUserRole($user, $companyId)
    {
        // Check if role assignment properties exist
        $isHead = $this->is_head ?? true;
        $isActive = $this->is_active ?? true;

        RoleHelper::assignRoleToUserInCompany(
            $user,
            'Pengawas',
            $companyId,
            null,
            $isHead,
            $isActive
        );
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

    public function export()
    {
        try {
            $fileName = 'supervisor_export_' . date('YmdHis') . '.xlsx';
            return Excel::download(new SupervisorExport(), $fileName);
        } catch (\Exception $e) {
            Log::error('Supervisor Export Error: ' . $e->getMessage());
            AlertHelper::error('Gagal', 'Gagal mengekspor data pengawas.');
        }
    }

    public function import()
    {
        try {
            $this->validate([
                'importFile' => 'required|mimes:xlsx,xls|max:5120', // max 5MB
            ]);

            Excel::import(new SupervisorImport(), $this->importFile);

            $this->reset('importFile');
            AlertHelper::success('Berhasil', 'Data pengawas berhasil diimpor.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            AlertHelper::error('Gagal', 'File tidak valid. Pastikan format file adalah Excel (.xlsx atau .xls).');
        } catch (\Exception $e) {
            Log::error('Supervisor Import Error: ' . $e->getMessage());
            AlertHelper::error('Gagal', 'Gagal mengimpor data pengawas: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        try {
            // Create a simple template with headers
            $headers = [
                ['Name', 'Username', 'Email', 'Phone', 'Password', 'Position', 'Address']
            ];

            $fileName = 'supervisor_template.xlsx';
            return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            }, $fileName);
        } catch (\Exception $e) {
            Log::error('Supervisor Template Download Error: ' . $e->getMessage());
            AlertHelper::error('Gagal', 'Gagal mengunduh template.');
        }
    }

    public function render()
    {
        $query = User::companyRole('Pengawas', Auth::user()->company_id)
            ->search($this->search)
            ->where('type_user', 'employee');

        // Filter by status if selected
        if ($this->statusFilter) {
            $query->whereHas('companyRoles', function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('is_active', $this->statusFilter === 'active');
            });
        }

        // Filter by department if selected
        if (!empty($this->departmentFilter)) {
            $query->whereHas('userDetail', function ($q) {
                $q->where('supervisor_department', $this->departmentFilter);
            });
        }

        $user = $query->orderBy('name', 'asc');

        return view('livewire.admin.master.supervisor.admin-master-supervisor-index', [
            'admins' => $user->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
