<?php

namespace App\Livewire\Admin\Master\Classmate\Detail;

use App\Helpers\AlertHelper;
use App\Models\Classmate\Classmate;
use App\Models\Classmate\ClassmateStudent;
use App\Models\User;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterClassmateDetailIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $perPage = 8;

    public $search;

    public $classmate_id;

    public $name;

    public $description;

    public $selectedStudents = [];

    public $openStudentModal = false;

    public $users = [];

    public $user_id;

    public $type_study = 'general';

    public $exam_sessions = [];
    public $exam_rooms = [];
    public $exam_session_id;
    public $exam_room_id;
    public $exam_date;

    public function mount($id)
    {
        $this->users = User::role(['Dosen'])->where('company_id', Auth::user()?->company?->id)->select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->classmate_id = $id;

        $companyId = Auth::user()?->company?->id;
        $this->exam_sessions = \App\Models\Master\Exam\ExamSession::where('company_id', $companyId)->get();
        $this->exam_rooms = \App\Models\Master\Exam\ExamRoom::where('company_id', $companyId)->get();

        // Load classmate details from the database
        $classmate = Classmate::findOrFail($this->classmate_id);
        if ($classmate) {
            $this->name = $classmate->name;
            $this->user_id = $classmate->user_id;
            $this->description = $classmate->description;
            $this->type_study = $classmate->type_study;
            $this->exam_session_id = $classmate->exam_session_id;
            $this->exam_room_id = $classmate->exam_room_id;
            $this->exam_date = $classmate->exam_date ? $classmate->exam_date->format('Y-m-d') : null;
        }
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'user_id' => $this->type_study == 'general' ? 'nullable' : 'required|exists:users,id',
            'description' => 'nullable|string',
            'exam_session_id' => 'nullable|exists:exam_sessions,id',
            'exam_room_id' => 'nullable|exists:exam_rooms,id',
            'exam_date' => 'nullable|date',
        ]);

        $classmate = Classmate::findOrFail($this->classmate_id);
        if ($classmate) {
            $classmate->name = $this->name;
            $classmate->user_id = $this->user_id;
            $classmate->description = $this->description;
            $classmate->type_study = $this->type_study;
            $classmate->exam_session_id = $this->exam_session_id ?: null;
            $classmate->exam_room_id = $this->exam_room_id ?: null;
            $classmate->exam_date = $this->exam_date ?: null;
            $classmate->save();

            if ($this->exam_session_id || $this->exam_room_id || $this->exam_date) {
                $studentsQuery = User::whereHas('userDetail', function ($query) {
                    if ($this->exam_session_id) {
                        $query->where('exam_session_id', $this->exam_session_id);
                    }
                    if ($this->exam_room_id) {
                        $query->where('exam_room_id', $this->exam_room_id);
                    }
                    if ($this->exam_date) {
                        $query->where('exam_date', $this->exam_date);
                    }
                })
                ->where('company_id', Auth::user()->company_id);

                $students = $studentsQuery->pluck('id');

                ClassmateStudent::where('classmate_id', $this->classmate_id)->delete();
                foreach ($students as $studentId) {
                    ClassmateStudent::create([
                        'classmate_id' => $this->classmate_id,
                        'user_id' => $studentId,
                    ]);
                }
            }
        }

        return AlertHelper::success('Berhasil', 'Data berhasil diperbarui.');
    }

    public function openModalStudent()
    {
        $this->selectedStudents = [];
        $this->openStudentModal = true;
        $this->reset('search');
        $this->dispatch('open-modal', ['id' => 'modalStudent']);
    }

    public function closeModalStudent()
    {
        $this->openStudentModal = false;
        $this->reset('search', 'selectedStudents');
        $this->dispatch('close-modal', ['id' => 'modalStudent']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetPage('modalPage');
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->resetPage('modalPage');
    }

    public function choiceQuestion($user_id)
    {
        $user_id = (string) $user_id;
        if (in_array($user_id, $this->selectedStudents)) {
            $this->selectedStudents = array_values(array_diff($this->selectedStudents, [$user_id]));
        } else {
            $this->selectedStudents[] = $user_id;
        }
    }

    public function toggleSelectAllOnPage($selectAll = true)
    {
        $query = User::role(['Mahasiswa'])
            ->search($this->search)
            ->orderBy('name', 'asc');

        $pageResults = $query->paginate($this->perPage, ['*'], 'modalPage');
        $pageIds = $pageResults->pluck('id')->map(fn ($id) => (string) $id)->toArray();

        if ($selectAll) {
            $this->selectedStudents = array_values(array_unique(array_merge($this->selectedStudents, $pageIds)));
        } else {
            $this->selectedStudents = array_values(array_diff($this->selectedStudents, $pageIds));
        }
    }

    public function toggleSelectAllAllPages($selectAll = true)
    {
        if ($selectAll) {
            $this->selectedStudents = User::role(['Mahasiswa'])
                ->search($this->search)
                ->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedStudents = [];
        }
    }

    public function submitModuleStudent()
    {
        if (count($this->selectedStudents) > 0) {
            // Simpan data mahasiswa yang dipilih
            foreach ($this->selectedStudents as $user_id) {
                if ($user_id) {
                    ClassmateStudent::firstOrCreate([
                        'classmate_id' => $this->classmate_id,
                        'user_id' => $user_id,
                    ]);
                }
            }
            $this->closeModalStudent();

            return AlertHelper::success('Berhasil', 'Data mahasiswa berhasil disimpan.');
        }

        return AlertHelper::error('Gagal', 'Tidak ada mahasiswa yang dipilih.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        $classmateStudent = ClassmateStudent::find($id[0]);
        if ($classmateStudent) {
            $classmateStudent->delete();

            return AlertHelper::success('Berhasil', 'Data mahasiswa berhasil dihapus.');
        }

        return AlertHelper::error('Gagal', 'Data mahasiswa tidak ditemukan.');
    }

    public $selectedDeleteStudents = [];

    public $selectAllDelete = false;

    public function updatedSelectAllDelete($value)
    {
        if ($value) {
            $this->selectedDeleteStudents = ClassmateStudent::search($this->search)
                ->where('classmate_id', $this->classmate_id)
                ->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedDeleteStudents = [];
        }
    }

    public function confirmDeleteSelected()
    {
        if (empty($this->selectedDeleteStudents)) {
            return AlertHelper::error('Gagal', 'Tidak ada data mahasiswa yang dipilih.');
        }

        return AlertHelper::confirmDelete('deleteSelected', 'Apakah Anda yakin ingin menghapus data mahasiswa yang dipilih?', 'selected');
    }

    public function deleteSelected()
    {
        if (empty($this->selectedDeleteStudents)) {
            return AlertHelper::error('Gagal', 'Tidak ada data mahasiswa yang dipilih.');
        }
        ClassmateStudent::whereIn('id', $this->selectedDeleteStudents)->delete();
        $this->selectedDeleteStudents = [];
        $this->selectAllDelete = false;

        return AlertHelper::success('Berhasil', 'Data mahasiswa terpilih berhasil dihapus.');
    }

    public function render()
    {
        $classmateStudents = ClassmateStudent::search($this->search)
            ->where('classmate_id', $this->classmate_id)
            ->select('id', 'user_id', 'classmate_id', 'created_at')
            ->with(['user:id,name,email', 'user.userDetail'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.classmate.detail.admin-master-classmate-detail-index', [
            'mahasiswas' => $this->openStudentModal ? User::role(['Mahasiswa'])
                ->with('userDetail')
                ->search($this->search)
                ->orderBy('name', 'asc')
                ->paginate($this->perPage, ['*'], 'modalPage') : [],
            'classmateStudents' => $classmateStudents,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
