<?php

namespace App\Livewire\Admin\Master\Classmate\Detail;

use App\Helpers\AlertHelper;
use App\Models\Classmate\Classmate;
use App\Models\Classmate\ClassmateStudent;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Auth;

class AdminMasterClassmateDetailIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 8, $search;
    public $classmate_id;
    public $name;
    public $description;
    public $selectedStudents = [];
    public $openStudentModal = false;
    public $users = [];
    public $user_id;

    public function mount($id)
    {
        $this->users = User::role(['Dosen'])->where('company_id', Auth::user()?->company?->id)->select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->classmate_id = $id;

        // Load classmate details from the database
        $classmate = Classmate::findOrFail($this->classmate_id);
        if ($classmate) {
            $this->name = $classmate->name;
            $this->user_id = $classmate->user_id;
            $this->description = $classmate->description;
        }
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $classmate = Classmate::findOrFail($this->classmate_id);
        if ($classmate) {
            $classmate->name = $this->name;
            $classmate->user_id = $this->user_id;
            $classmate->description = $this->description;
            $classmate->save();
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

    public function choiceQuestion($user_id)
    {
        // Kalau id sudah ada → hapus (uncheck)
        if (isset($this->selectedStudents[$user_id]) && $this->selectedStudents[$user_id]) {
            unset($this->selectedStudents[$user_id]);
        }
        // Kalau id belum ada → tambahkan (check)
        else {
            $this->selectedStudents[$user_id] = true;
        }
    }

    public function submitModuleStudent()
    {
        if (count($this->selectedStudents) > 0) {
            // Simpan data mahasiswa yang dipilih
            foreach ($this->selectedStudents as $user_id => $selected) {
                if ($selected) {
                    ClassmateStudent::create([
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

    public function render()
    {
        $classmateStudents = ClassmateStudent::search($this->search)->where('classmate_id', $this->classmate_id)->select('id', 'user_id', 'classmate_id')->with(['user:id,name,email', 'user.userDetail'])->get();

        return view('livewire.admin.master.classmate.detail.admin-master-classmate-detail-index', [
            'mahasiswas' => $this->openStudentModal ? User::role(['Mahasiswa'])
                ->search($this->search)
                ->whereNotIn('id', ClassmateStudent::select('user_id')->get()->pluck('user_id')->toArray() ?? [])
                ->paginate($this->perPage) : [],
            'classmateStudents' => $classmateStudents,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
