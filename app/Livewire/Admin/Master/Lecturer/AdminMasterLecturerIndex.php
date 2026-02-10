<?php

namespace App\Livewire\Admin\Master\Lecturer;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\Study\Study;
use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Log;
use Hash;
use App\Exports\LecturerExport;
use App\Imports\User\LecturerImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminMasterLecturerIndex extends Component
{
    use WithPagination, WithFileUploads;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';
    public $facultyFilter = '';
    public $departmentFilter = '';
    public $positionFilter = '';

    public $perPage = 5;

    // Import file
    public $importFile;
    public $showModal = false;
    public $editMode = false;

    // User
    public $data_id;
    public $name;
    public $email;
    public $password;

    // Lecturer Detail
    public $lecturer_id;
    public $lecturer_nidn;
    public $lecturer_nip;
    public $lecturer_department;
    public $lecturer_faculty;
    public $lecturer_position;
    public $lecturer_functional_position;
    public $lecturer_education_level;
    public $lecturer_specialization;
    public $lecturer_expertise;
    public $lecturer_status = 'active';
    public $lecturer_type = 'full_time';
    public $lecturer_start_date;

    // Personal Info
    public $birth_place;
    public $birth_date;
    public $gender;
    public $religion;
    public $nationality = 'Indonesian';
    public $marital_status = 'single';
    public $address;
    public $city;
    public $province;
    public $phone;
    public $mobile_phone;
    public $identity_type = 'KTP';
    public $identity_number;
    public $studys = [];
    public $getStudys = [];
    public $filterStudy;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'lecturer_id' => 'required|string|unique:user_details,lecturer_id',
        'lecturer_nidn' => 'required|string|unique:user_details,lecturer_nidn',
        'studys' => 'required',
        'lecturer_faculty' => 'nullable|string|max:255',
        'lecturer_position' => 'nullable|string|max:255',
        'lecturer_education_level' => 'required|string|max:255',
        'lecturer_specialization' => 'required|string|max:255',
        'birth_place' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'gender' => 'required|in:male,female',
        'phone' => 'nullable|string|max:20',
        'mobile_phone' => 'nullable|string|max:20',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:255',
        'province' => 'required|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFacultyFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatingPositionFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', ['id' => 'modal']);
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'lecturer_id',
            'lecturer_nidn',
            'lecturer_nip',
            'studys',
            'lecturer_faculty',
            'lecturer_position',
            'lecturer_functional_position',
            'lecturer_education_level',
            'lecturer_specialization',
            'lecturer_expertise',
            'lecturer_status',
            'lecturer_type',
            'lecturer_start_date',
            'birth_place',
            'birth_date',
            'gender',
            'religion',
            'nationality',
            'marital_status',
            'address',
            'city',
            'province',
            'phone',
            'mobile_phone',
            'identity_type',
            'identity_number',
            'data_id',
            'editMode',
            'password'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->getStudys = Study::select('id', 'name')->orderBy('name')->get()->pluck('name', 'id')->toArray();
    }

    public function edit($id)
    {
        $user = User::with('userDetail')->findOrFail($id);
        $detail = $user->userDetail;

        $this->data_id = $id;
        $this->editMode = true; // Set edit mode to true
        $this->name = $user->name;
        $this->email = $user->email;
        $this->studys = $user->studys ?? '';

        if ($detail) {
            $this->lecturer_id = $detail->lecturer_id ?? '';
            $this->lecturer_nidn = $detail->lecturer_nidn ?? '';
            $this->lecturer_nip = $detail->lecturer_nip ?? '';
            $this->lecturer_faculty = $detail->lecturer_faculty ?? '';
            $this->lecturer_position = $detail->lecturer_position ?? '';
            $this->lecturer_functional_position = $detail->lecturer_functional_position ?? '';
            $this->lecturer_education_level = $detail->lecturer_education_level ?? '';
            $this->lecturer_specialization = $detail->lecturer_specialization ?? '';
            $this->lecturer_expertise = $detail->lecturer_expertise ?? '';
            $this->lecturer_status = $detail->lecturer_status ?? 'active';
            $this->lecturer_type = $detail->lecturer_type ?? 'full_time';
            $this->lecturer_start_date = $detail->lecturer_start_date?->format('Y-m-d') ?? '';
            $this->birth_place = $detail->birth_place ?? '';
            $this->birth_date = $detail->birth_date?->format('Y-m-d') ?? '';
            $this->gender = $detail->gender ?? '';
            $this->religion = $detail->religion ?? '';
            $this->nationality = $detail->nationality ?? 'Indonesian';
            $this->marital_status = $detail->marital_status ?? 'single';
            $this->address = $detail->address ?? '';
            $this->city = $detail->city ?? '';
            $this->province = $detail->province ?? '';
            $this->phone = $detail->phone ?? '';
            $this->mobile_phone = $detail->mobile_phone ?? '';
            $this->identity_type = $detail->identity_type ?? 'KTP';
            $this->identity_number = $detail->identity_number ?? '';
        }

        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        // Dynamic validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'studys' => 'required',
            'lecturer_faculty' => 'nullable|string|max:255',
            'lecturer_position' => 'nullable|string|max:255',
            'lecturer_education_level' => 'required|string|max:255',
            'lecturer_specialization' => 'nullable|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:20',
            'mobile_phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'studys' => 'required|array|min:1',
        ];

        $messages = [
            'studys.required' => 'Prodi wajib diisi.',
            'studys.array' => 'Prodi harus berupa array.',
            'studys.min' => 'Pilih minimal satu Prodi.',
        ];

        if ($this->editMode) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->data_id . ',id';
            $rules['lecturer_id'] = 'required|string|unique:user_details,lecturer_id,' . $this->data_id . ',user_id';
            $rules['lecturer_nidn'] = 'required|string|unique:user_details,lecturer_nidn,' . $this->data_id . ',user_id';
        } else {
            // For create mode: strict unique validation + password required
            $rules['email'] = 'required|email|unique:users,email';
            $rules['lecturer_id'] = 'required|string|unique:user_details,lecturer_id';
            $rules['lecturer_nidn'] = 'required|string|unique:user_details,lecturer_nidn';
            $rules['password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        try {
            DB::beginTransaction();

            if ($this->editMode) {
                $user = User::findOrFail($this->data_id);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'studys' => $this->studys,
                ]);

                $user->userDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'lecturer_id' => $this->lecturer_id,
                        'lecturer_nidn' => $this->lecturer_nidn,
                        'lecturer_nip' => $this->lecturer_nip,
                        'lecturer_faculty' => $this->lecturer_faculty,
                        'lecturer_position' => $this->lecturer_position,
                        'lecturer_functional_position' => $this->lecturer_functional_position,
                        'lecturer_education_level' => $this->lecturer_education_level,
                        'lecturer_specialization' => $this->lecturer_specialization,
                        'lecturer_expertise' => $this->lecturer_expertise,
                        'lecturer_status' => $this->lecturer_status,
                        'lecturer_type' => $this->lecturer_type,
                        'lecturer_start_date' => $this->lecturer_start_date ?: null,
                        'birth_place' => $this->birth_place,
                        'birth_date' => $this->birth_date ?: null,
                        'gender' => $this->gender,
                        'religion' => $this->religion,
                        'nationality' => $this->nationality,
                        'marital_status' => $this->marital_status,
                        'address' => $this->address,
                        'city' => $this->city,
                        'province' => $this->province,
                        'phone' => $this->phone,
                        'mobile_phone' => $this->mobile_phone,
                        'identity_type' => $this->identity_type,
                        'identity_number' => $this->identity_number,
                    ]
                );

                RoleHelper::assignRoleToUserInCompany($user, 'Dosen', Auth::user()->company_id);

                AlertHelper::success('Berhasil', 'Data dosen berhasil diperbarui.');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password ?: 'password123'),
                    'email_verified_at' => now(),
                    'studys' => $this->studys,
                ]);

                $user->userDetail()->create([
                    'lecturer_id' => $this->lecturer_id,
                    'lecturer_nidn' => $this->lecturer_nidn,
                    'lecturer_nip' => $this->lecturer_nip,
                    'lecturer_faculty' => $this->lecturer_faculty,
                    'lecturer_position' => $this->lecturer_position,
                    'lecturer_functional_position' => $this->lecturer_functional_position,
                    'lecturer_education_level' => $this->lecturer_education_level,
                    'lecturer_specialization' => $this->lecturer_specialization,
                    'lecturer_expertise' => $this->lecturer_expertise,
                    'lecturer_status' => $this->lecturer_status,
                    'lecturer_type' => $this->lecturer_type,
                    'lecturer_start_date' => $this->lecturer_start_date ?: null,
                    'birth_place' => $this->birth_place,
                    'birth_date' => $this->birth_date ?: null,
                    'gender' => $this->gender,
                    'religion' => $this->religion,
                    'nationality' => $this->nationality,
                    'marital_status' => $this->marital_status,
                    'address' => $this->address,
                    'city' => $this->city,
                    'province' => $this->province,
                    'phone' => $this->phone,
                    'mobile_phone' => $this->mobile_phone,
                    'identity_type' => $this->identity_type,
                    'identity_number' => $this->identity_number,
                    'verification_status' => 'pending',
                    'status' => 'active'
                ]);

                RoleHelper::assignRoleToUserInCompany($user, 'Dosen', Auth::user()->company_id);

                AlertHelper::success('Berhasil', 'Data dosen berhasil ditambahkan.');
            }

            DB::commit();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id[0]);

            if ($user->id == Auth::id()) {
                return AlertHelper::error('Gagal', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $user->userDetail()->delete();
            $user->delete();

            AlertHelper::success('Berhasil', 'Data dosen berhasil dihapus.');
        } catch (\Exception $e) {
            AlertHelper::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus data dosen ini?', $id);
    }

    public function export()
    {
        try {
            $fileName = 'lecturer_export_' . date('YmdHis') . '.xlsx';
            return Excel::download(new LecturerExport(), $fileName);
        } catch (\Exception $e) {
            Log::error('Lecturer Export Error: ' . $e->getMessage());
            AlertHelper::error('Gagal', 'Gagal mengekspor data dosen.');
        }
    }

    public function import()
    {
        try {
            $this->validate([
                'importFile' => 'required|mimes:xlsx,xls|max:5120', // max 5MB
            ]);

            Excel::import(new LecturerImport(), $this->importFile);

            $this->reset('importFile');
            AlertHelper::success('Berhasil', 'Data dosen berhasil diimpor.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            AlertHelper::error('Gagal', 'File tidak valid. Pastikan format file adalah Excel (.xlsx atau .xls).');
        } catch (\Exception $e) {
            Log::error('Lecturer Import Error: ' . $e->getMessage());
            AlertHelper::error('Gagal', 'Gagal mengimpor data dosen: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        try {
            // Create a simple template with headers
            $headers = [
                ['Name', 'Username', 'Email', 'Phone', 'Password', 'NIDN/NIP', 'Faculty', 'Department', 'Position', 'Address']
            ];

            $fileName = 'lecturer_template.xlsx';
            return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            }, $fileName);
        } catch (\Exception $e) {
            Log::error('Lecturer Template Download Error: ' . $e->getMessage());
            AlertHelper::error('Gagal', 'Gagal mengunduh template.');
        }
    }

    public function render()
    {
        $query = User::role('Dosen')
            ->orderBy('name', 'asc')
            ->with(['userDetail'])
            ->whereHas('userDetail', function ($q) {
                if ($this->search) {
                    $q->where('lecturer_id', 'ilike', '%' . $this->search . '%')
                        ->orWhere('lecturer_nidn', 'ilike', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'ilike', '%' . $this->search . '%')
                                ->orWhere('email', 'ilike', '%' . $this->search . '%');
                        });
                }

                if ($this->facultyFilter) {
                    $q->where('lecturer_faculty', $this->facultyFilter);
                }

                if ($this->departmentFilter) {
                    $q->where('studys', $this->departmentFilter);
                }

                if ($this->positionFilter) {
                    $q->where('lecturer_position', $this->positionFilter);
                }
            });

        if ($this->filterStudy) {
            $query->whereJsonContains('studys', $this->filterStudy);
        }

        $lecturers = $query->paginate($this->perPage);

        $faculties = UserDetail::whereNotNull('lecturer_faculty')
            ->distinct()
            ->pluck('lecturer_faculty')
            ->filter()
            ->sort();

        $departments = UserDetail::whereNotNull('lecturer_department')
            ->distinct()
            ->pluck('lecturer_department')
            ->filter()
            ->sort();

        $positions = UserDetail::whereNotNull('lecturer_position')
            ->distinct()
            ->pluck('lecturer_position')
            ->filter()
            ->sort();

        return view('livewire.admin.master.lecturer.admin-master-lecturer-index', [
            'lecturers' => $lecturers,
            'faculties' => $faculties,
            'departments' => $departments,
            'positions' => $positions,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
