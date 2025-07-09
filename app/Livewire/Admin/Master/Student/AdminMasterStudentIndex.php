<?php

namespace App\Livewire\Admin\Master\Student;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdminMasterStudentIndex extends Component
{
    use WithPagination, WithFileUploads;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;

    // User
    public $data_id;
    public $name;
    public $nim;
    public $email;
    public $password;
    public $profile;
    public $profile_old;
    public $phone;

    // User Detail
    public $address;
    public $identity_card;
    public $is_head = false;
    public $is_active = false;

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'name',
            'nim',
            'email',
            'password',
            'profile',
            'profile_old',
            'phone',
            'address',
            'identity_card',
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
        $this->nim = $user->nim;
        $this->email = $user->email;
        $this->profile_old = $user->profile;
        $this->phone = trim($user->phone ?? 0);

        if ($user->userDetail) {
            $this->address = $user->userDetail->address;
            $this->identity_card = $user->userDetail->identity_card ? Crypt::decryptString($user->userDetail->identity_card) : null;
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
        }

        $this->openModal();
    }

    public function submit()
    {
        $currentCompanyId = Auth::user()->company_id;

        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                'min:4',
                'regex:/^\S*$/u', // tidak boleh ada spasi
                Rule::unique('users', 'nim')
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
            'address' => 'required|string|max:500',
            'identity_card' => 'nullable|string|max:20',
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
                    'nim' => $this->nim,
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
            'nim' => $validatedData['nim'],
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
            'nim' => $validatedData['nim'],
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
            'address' => $validatedData['address'],
        ];

        // Handle identity card encryption
        if (!empty($validatedData['identity_card'])) {
            $detailData['identity_card'] = Crypt::encryptString($validatedData['identity_card']);
        }

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
        $isHead = $this->is_head ?? false;
        $isActive = $this->is_active ?? true;

        RoleHelper::assignRoleToUserInCompany(
            $user,
            'Mahasiswa',
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

    public function render()
    {
        $user = User::companyRole('Mahasiswa', Auth::user()->company_id)
            ->search($this->search)
            ->where('type_user', 'employee')
            ->orderBy('name', 'asc');

        return view('livewire.admin.master.student.admin-master-student-index', [
            'admins' => $user->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
