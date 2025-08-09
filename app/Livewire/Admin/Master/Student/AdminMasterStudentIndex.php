<?php

namespace App\Livewire\Admin\Master\Student;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

    // User Detail - Basic Information
    public $address;
    public $is_head = false;
    public $is_active = false;

    // Student Specific Fields
    public $student_id;
    public $student_program;
    public $student_faculty;
    public $student_department;
    public $student_class;
    public $student_semester;
    public $student_academic_year;
    public $student_status = 'active';
    public $student_gpa;
    public $student_advisor_id;
    public $student_entry_date;
    public $student_graduation_date;

    // Personal Information
    public $birth_date;
    public $birth_place;
    public $gender;
    public $religion;
    public $nationality = 'Indonesian';
    public $marital_status = 'single';
    public $postal_code;
    public $city;
    public $province;
    public $country = 'ID';
    public $mobile_phone;
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relation;
    public $identity_type = 'KTP';
    public $identity_number;
    public $blood_group;

    // Academic Performance
    public $total_exams_taken = 0;
    public $average_score;
    public $exam_preference;
    public $special_needs = false;
    public $special_needs_description;

    // System Settings
    public $preferred_language = 'id';
    public $verification_status = 'pending';
    public $notes;

    public function openModal()
    {
        $this->dispatch('open-modal', ['id' => 'modal']);
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
            'identity_number',
            'is_head',
            'is_active',
            'student_id',
            'student_program',
            'student_faculty',
            'student_department',
            'student_class',
            'student_semester',
            'student_academic_year',
            'student_status',
            'student_gpa',
            'student_advisor_id',
            'student_entry_date',
            'student_graduation_date',
            'birth_date',
            'birth_place',
            'gender',
            'religion',
            'nationality',
            'marital_status',
            'postal_code',
            'city',
            'province',
            'country',
            'mobile_phone',
            'emergency_contact_name',
            'emergency_contact_phone',
            'emergency_contact_relation',
            'identity_type',
            'identity_number',
            'blood_group',
            'total_exams_taken',
            'average_score',
            'exam_preference',
            'special_needs',
            'special_needs_description',
            'preferred_language',
            'verification_status',
            'notes',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('close-modal', ['id' => 'modal']);
    }


    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            $this->data_id = $user->id;
            $this->name = $user->name;
            $this->nim = $user->nim;
            $this->email = $user->email;
            $this->profile_old = $user->profile;
            $this->phone = trim($user->phone ?? '');

            if ($user->userDetail) {
                $detail = $user->userDetail;

                // Basic Information
                $this->address = $detail->address;
                
                // Handle identity_number decryption safely
                if ($detail->identity_number) {
                    try {
                        $this->identity_number = Crypt::decryptString($detail->identity_number);
                    } catch (\Exception $e) {
                        // If decryption fails, assume it's already plain text
                        $this->identity_number = $detail->identity_number;
                        Log::info('Identity number appears to be stored as plain text for user: ' . $user->id);
                    }
                } else {
                    $this->identity_number = null;
                }

                // Student Specific Fields
                $this->student_id = $detail->student_id;
                $this->student_program = $detail->student_program;
                $this->student_faculty = $detail->student_faculty;
                $this->student_department = $detail->student_department;
                $this->student_class = $detail->student_class;
                $this->student_semester = $detail->student_semester;
                $this->student_academic_year = $detail->student_academic_year;
                $this->student_status = $detail->student_status ?? 'active';
                $this->student_gpa = $detail->student_gpa;
                $this->student_advisor_id = $detail->student_advisor_id;
                $this->student_entry_date = $detail->student_entry_date;
                $this->student_graduation_date = $detail->student_graduation_date;

                // Personal Information
                $this->birth_date = $detail->birth_date;
                $this->birth_place = $detail->birth_place;
                $this->gender = $detail->gender;
                $this->religion = $detail->religion;
                $this->nationality = $detail->nationality ?? 'Indonesian';
                $this->marital_status = $detail->marital_status ?? 'single';
                $this->postal_code = $detail->postal_code;
                $this->city = $detail->city;
                $this->province = $detail->province;
                $this->country = $detail->country ?? 'ID';
                $this->mobile_phone = $detail->mobile_phone;
                $this->emergency_contact_name = $detail->emergency_contact_name;
                $this->emergency_contact_phone = $detail->emergency_contact_phone;
                $this->emergency_contact_relation = $detail->emergency_contact_relation;
                $this->identity_type = $detail->identity_type ?? 'KTP';
                $this->blood_group = $detail->blood_group;

                // Academic Performance
                $this->total_exams_taken = $detail->total_exams_taken ?? 0;
                $this->average_score = $detail->average_score;
                $this->exam_preference = $detail->exam_preference;
                $this->special_needs = $detail->special_needs ?? false;
                $this->special_needs_description = $detail->special_needs_description;

                // System Settings
                $this->preferred_language = $detail->preferred_language ?? 'id';
                $this->verification_status = $detail->verification_status ?? 'pending';
                $this->notes = $detail->notes;

                // Company roles
                $companyRole = $user
                    ->companyRoles()
                    ->where('company_id', Auth::user()->company_id)
                    ->first();
                
                $this->is_head = $companyRole->is_head ?? false;
                $this->is_active = $companyRole->is_active ?? false;
            }

            $this->openModal();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('User not found for edit: ' . $id);
            AlertHelper::error('Error', 'Data mahasiswa tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in edit method: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            AlertHelper::error('Error', 'Gagal membuka data mahasiswa. Silakan coba lagi.');
        }
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
                'max:15',
                Rule::unique('users', 'phone')
                    ->where('type_user', 'employee')
                    ->where('company_id', $currentCompanyId)
                    ->ignore($this->data_id),
            ],
            'address' => 'required|string|max:500',
            'identity_number' => 'nullable|string|max:20',

            // Student Specific Validation
            'student_id' => 'nullable|string|max:20',
            'student_program' => 'nullable|string|max:100',
            'student_faculty' => 'nullable|string|max:100',
            'student_department' => 'nullable|string|max:100',
            'student_class' => 'nullable|string|max:10',
            'student_semester' => 'nullable|string|max:10',
            'student_academic_year' => 'nullable|string|max:20',
            'student_status' => 'nullable|in:active,graduate,dropout,transfer,leave',
            'student_gpa' => 'nullable|numeric|min:0|max:4',
            'student_advisor_id' => 'nullable|string|max:50',
            'student_entry_date' => 'nullable|date',
            'student_graduation_date' => 'nullable|date|after_or_equal:student_entry_date',

            // Personal Information Validation
            'birth_date' => 'nullable|date|before:today',
            'birth_place' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:3',
            'mobile_phone' => 'nullable|string|max:15',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:15',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'identity_type' => 'nullable|string|max:20',
            'blood_group' => 'nullable|in:A,B,AB,O',

            // Academic Performance Validation
            'total_exams_taken' => 'nullable|integer|min:0',
            'average_score' => 'nullable|numeric|min:0|max:100',
            'exam_preference' => 'nullable|string|max:100',
            'special_needs' => 'nullable|boolean',
            'special_needs_description' => 'nullable|string|max:500',

            // System Settings Validation
            'preferred_language' => 'nullable|string|max:10',
            'verification_status' => 'nullable|in:pending,verified,rejected',
            'notes' => 'nullable|string|max:1000',
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
            if (in_array(App::environment(), ['local', 'development'])) {
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

            // Student Specific Fields
            'student_id' => $validatedData['student_id'] ?? null,
            'student_program' => $validatedData['student_program'] ?? null,
            'student_faculty' => $validatedData['student_faculty'] ?? null,
            'student_department' => $validatedData['student_department'] ?? null,
            'student_class' => $validatedData['student_class'] ?? null,
            'student_semester' => $validatedData['student_semester'] ?? null,
            'student_academic_year' => $validatedData['student_academic_year'] ?? null,
            'student_status' => $validatedData['student_status'] ?? 'active',
            'student_gpa' => $validatedData['student_gpa'] ?? null,
            'student_advisor_id' => $validatedData['student_advisor_id'] ?? null,
            'student_entry_date' => $validatedData['student_entry_date'] ?? null,
            'student_graduation_date' => $validatedData['student_graduation_date'] ?? null,

            // Personal Information
            'birth_date' => $validatedData['birth_date'] ?? null,
            'birth_place' => $validatedData['birth_place'] ?? null,
            'gender' => $validatedData['gender'] ?? null,
            'religion' => $validatedData['religion'] ?? null,
            'nationality' => $validatedData['nationality'] ?? 'Indonesian',
            'marital_status' => $validatedData['marital_status'] ?? 'single',
            'postal_code' => $validatedData['postal_code'] ?? null,
            'city' => $validatedData['city'] ?? null,
            'province' => $validatedData['province'] ?? null,
            'country' => $validatedData['country'] ?? 'ID',
            'mobile_phone' => $validatedData['mobile_phone'] ?? null,
            'emergency_contact_name' => $validatedData['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validatedData['emergency_contact_phone'] ?? null,
            'emergency_contact_relation' => $validatedData['emergency_contact_relation'] ?? null,
            'identity_type' => $validatedData['identity_type'] ?? 'KTP',
            'blood_group' => $validatedData['blood_group'] ?? null,

            // Academic Performance
            'total_exams_taken' => $validatedData['total_exams_taken'] ?? 0,
            'average_score' => $validatedData['average_score'] ?? null,
            'exam_preference' => $validatedData['exam_preference'] ?? null,
            'special_needs' => $validatedData['special_needs'] ?? false,
            'special_needs_description' => $validatedData['special_needs_description'] ?? null,

            // System Settings
            'preferred_language' => $validatedData['preferred_language'] ?? 'id',
            'verification_status' => $validatedData['verification_status'] ?? 'pending',
            'notes' => $validatedData['notes'] ?? null,
        ];

        // Handle identity card encryption safely
        if (!empty($validatedData['identity_number'])) {
            try {
                $detailData['identity_number'] = Crypt::encryptString($validatedData['identity_number']);
            } catch (\Exception $e) {
                // If encryption fails, store as plain text for now
                $detailData['identity_number'] = $validatedData['identity_number'];
                Log::warning('Failed to encrypt identity_number, storing as plain text: ' . $e->getMessage());
            }
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
