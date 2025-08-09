<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\User\UserDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class LecturerManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $facultyFilter = '';
    public $departmentFilter = '';
    public $positionFilter = '';
    public $showModal = false;
    public $editMode = false;
    public $selectedLecturerId = null;

    // Form properties
    public $name = '';
    public $email = '';
    public $lecturer_id = '';
    public $lecturer_nidn = '';
    public $lecturer_nip = '';
    public $lecturer_department = '';
    public $lecturer_faculty = '';
    public $lecturer_position = '';
    public $lecturer_functional_position = '';
    public $lecturer_education_level = '';
    public $lecturer_specialization = '';
    public $lecturer_expertise = '';
    public $lecturer_status = 'active';
    public $lecturer_type = 'full_time';
    public $birth_place = '';
    public $birth_date = '';
    public $gender = '';
    public $religion = '';
    public $nationality = 'Indonesian';
    public $marital_status = 'single';
    public $address = '';
    public $city = '';
    public $province = '';
    public $phone = '';
    public $mobile_phone = '';
    public $identity_type = 'KTP';
    public $identity_number = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'lecturer_id' => 'required|string|unique:user_details,lecturer_id',
        'lecturer_nidn' => 'required|string|unique:user_details,lecturer_nidn',
        'lecturer_department' => 'required|string|max:255',
        'lecturer_faculty' => 'required|string|max:255',
        'lecturer_position' => 'required|string|max:255',
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

    public function render()
    {
        $query = User::role('Lecturer')
            ->with(['userDetail'])
            ->whereHas('userDetail', function ($q) {
                if ($this->search) {
                    $q->where('lecturer_id', 'like', '%' . $this->search . '%')
                        ->orWhere('lecturer_nidn', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                }

                if ($this->facultyFilter) {
                    $q->where('lecturer_faculty', $this->facultyFilter);
                }

                if ($this->departmentFilter) {
                    $q->where('lecturer_department', $this->departmentFilter);
                }

                if ($this->positionFilter) {
                    $q->where('lecturer_position', $this->positionFilter);
                }
            });

        $lecturers = $query->paginate(10);

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

        return view('livewire.admin.lecturer-management', [
            'lecturers' => $lecturers,
            'faculties' => $faculties,
            'departments' => $departments,
            'positions' => $positions
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function editLecturer($id)
    {
        $user = User::with('userDetail')->findOrFail($id);
        $detail = $user->userDetail;

        $this->selectedLecturerId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->lecturer_id = $detail->lecturer_id ?? '';
        $this->lecturer_nidn = $detail->lecturer_nidn ?? '';
        $this->lecturer_nip = $detail->lecturer_nip ?? '';
        $this->lecturer_department = $detail->lecturer_department ?? '';
        $this->lecturer_faculty = $detail->lecturer_faculty ?? '';
        $this->lecturer_position = $detail->lecturer_position ?? '';
        $this->lecturer_functional_position = $detail->lecturer_functional_position ?? '';
        $this->lecturer_education_level = $detail->lecturer_education_level ?? '';
        $this->lecturer_specialization = $detail->lecturer_specialization ?? '';
        $this->lecturer_expertise = $detail->lecturer_expertise ?? '';
        $this->lecturer_status = $detail->lecturer_status ?? 'active';
        $this->lecturer_type = $detail->lecturer_type ?? 'full_time';
        $this->birth_place = $detail->birth_place ?? '';
        $this->birth_date = $detail->birth_date ?? '';
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

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->selectedLecturerId;
            $this->rules['lecturer_id'] = 'required|string|unique:user_details,lecturer_id,' . $this->selectedLecturerId . ',user_id';
            $this->rules['lecturer_nidn'] = 'required|string|unique:user_details,lecturer_nidn,' . $this->selectedLecturerId . ',user_id';
        }

        $this->validate();

        try {
            if ($this->editMode) {
                $user = User::findOrFail($this->selectedLecturerId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                $user->userDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'lecturer_id' => $this->lecturer_id,
                        'lecturer_nidn' => $this->lecturer_nidn,
                        'lecturer_nip' => $this->lecturer_nip,
                        'lecturer_department' => $this->lecturer_department,
                        'lecturer_faculty' => $this->lecturer_faculty,
                        'lecturer_position' => $this->lecturer_position,
                        'lecturer_functional_position' => $this->lecturer_functional_position,
                        'lecturer_education_level' => $this->lecturer_education_level,
                        'lecturer_specialization' => $this->lecturer_specialization,
                        'lecturer_expertise' => $this->lecturer_expertise,
                        'lecturer_status' => $this->lecturer_status,
                        'lecturer_type' => $this->lecturer_type,
                        'birth_place' => $this->birth_place,
                        'birth_date' => $this->birth_date,
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

                session()->flash('message', 'Data dosen berhasil diperbarui.');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now()
                ]);

                $user->assignRole('Lecturer');

                $user->userDetail()->create([
                    'lecturer_id' => $this->lecturer_id,
                    'lecturer_nidn' => $this->lecturer_nidn,
                    'lecturer_nip' => $this->lecturer_nip,
                    'lecturer_department' => $this->lecturer_department,
                    'lecturer_faculty' => $this->lecturer_faculty,
                    'lecturer_position' => $this->lecturer_position,
                    'lecturer_functional_position' => $this->lecturer_functional_position,
                    'lecturer_education_level' => $this->lecturer_education_level,
                    'lecturer_specialization' => $this->lecturer_specialization,
                    'lecturer_expertise' => $this->lecturer_expertise,
                    'lecturer_status' => $this->lecturer_status,
                    'lecturer_type' => $this->lecturer_type,
                    'birth_place' => $this->birth_place,
                    'birth_date' => $this->birth_date,
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

                session()->flash('message', 'Data dosen berhasil ditambahkan.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteLecturer($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->userDetail()->delete();
            $user->delete();

            session()->flash('message', 'Data dosen berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
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
            'lecturer_department',
            'lecturer_faculty',
            'lecturer_position',
            'lecturer_functional_position',
            'lecturer_education_level',
            'lecturer_specialization',
            'lecturer_expertise',
            'lecturer_status',
            'lecturer_type',
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
            'selectedLecturerId',
            'editMode'
        ]);
    }
}
