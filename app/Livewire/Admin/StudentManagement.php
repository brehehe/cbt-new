<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\User\UserDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class StudentManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $facultyFilter = '';
    public $programFilter = '';
    public $statusFilter = '';
    public $showModal = false;
    public $editMode = false;
    public $selectedStudentId = null;

    // Form properties
    public $name = '';
    public $email = '';
    public $student_id = '';
    public $student_program = '';
    public $student_faculty = '';
    public $student_department = '';
    public $student_class = '';
    public $student_semester = '';
    public $student_academic_year = '';
    public $student_status = 'active';
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
        'student_id' => 'required|string|unique:user_details,student_id',
        'student_program' => 'required|string|max:255',
        'student_faculty' => 'required|string|max:255',
        'student_department' => 'required|string|max:255',
        'student_semester' => 'required|integer|min:1|max:14',
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

    public function updatingProgramFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::role('Student')
            ->with(['userDetail'])
            ->whereHas('userDetail', function ($q) {
                if ($this->search) {
                    $q->where('student_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                }

                if ($this->facultyFilter) {
                    $q->where('student_faculty', $this->facultyFilter);
                }

                if ($this->programFilter) {
                    $q->where('student_program', $this->programFilter);
                }

                if ($this->statusFilter) {
                    $q->where('student_status', $this->statusFilter);
                }
            });

        $students = $query->paginate(10);

        $faculties = UserDetail::whereNotNull('student_faculty')
            ->distinct()
            ->pluck('student_faculty')
            ->filter()
            ->sort();

        $programs = UserDetail::whereNotNull('student_program')
            ->distinct()
            ->pluck('student_program')
            ->filter()
            ->sort();

        return view('livewire.admin.student-management', [
            'students' => $students,
            'faculties' => $faculties,
            'programs' => $programs
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function editStudent($id)
    {
        $user = User::with('userDetail')->findOrFail($id);
        $detail = $user->userDetail;

        $this->selectedStudentId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->student_id = $detail->student_id ?? '';
        $this->student_program = $detail->student_program ?? '';
        $this->student_faculty = $detail->student_faculty ?? '';
        $this->student_department = $detail->student_department ?? '';
        $this->student_class = $detail->student_class ?? '';
        $this->student_semester = $detail->student_semester ?? '';
        $this->student_academic_year = $detail->student_academic_year ?? '';
        $this->student_status = $detail->student_status ?? 'active';
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
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->selectedStudentId;
            $this->rules['student_id'] = 'required|string|unique:user_details,student_id,' . $this->selectedStudentId . ',user_id';
        }

        $this->validate();

        try {
            if ($this->editMode) {
                $user = User::findOrFail($this->selectedStudentId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                $user->userDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'student_id' => $this->student_id,
                        'student_program' => $this->student_program,
                        'student_faculty' => $this->student_faculty,
                        'student_department' => $this->student_department,
                        'student_class' => $this->student_class,
                        'student_semester' => $this->student_semester,
                        'student_academic_year' => $this->student_academic_year,
                        'student_status' => $this->student_status,
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

                session()->flash('message', 'Data mahasiswa berhasil diperbarui.');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now()
                ]);

                $user->assignRole('Student');

                $user->userDetail()->create([
                    'student_id' => $this->student_id,
                    'student_program' => $this->student_program,
                    'student_faculty' => $this->student_faculty,
                    'student_department' => $this->student_department,
                    'student_class' => $this->student_class,
                    'student_semester' => $this->student_semester,
                    'student_academic_year' => $this->student_academic_year,
                    'student_status' => $this->student_status,
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

                session()->flash('message', 'Data mahasiswa berhasil ditambahkan.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteStudent($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->userDetail()->delete();
            $user->delete();

            session()->flash('message', 'Data mahasiswa berhasil dihapus.');
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
            'student_id',
            'student_program',
            'student_faculty',
            'student_department',
            'student_class',
            'student_semester',
            'student_academic_year',
            'student_status',
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
            'selectedStudentId',
            'editMode'
        ]);
    }
}
