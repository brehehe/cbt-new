<?php

namespace App\Livewire\Admin\Master\Classmate;

use App\Helpers\AlertHelper;
use App\Models\Classmate\Classmate;
use App\Models\Classmate\ClassmateStudent;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Redirect;
use Throwable;

class AdminMasterClassmateIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search;

    public $data_id;

    public $name;

    public $description;

    public $user_id;

    public $users = [];

    public $type_study;

    public $exam_sessions = [];
    public $exam_rooms = [];
    public $exam_session_id;
    public $exam_room_id;
    public $exam_date;

    public function mount()
    {
        $this->users = User::role(['Dosen'])->where('company_id', Auth::user()?->company?->id)->select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $companyId = Auth::user()?->company?->id;
        $this->exam_sessions = \App\Models\Master\Exam\ExamSession::where('company_id', $companyId)->get();
        $this->exam_rooms = \App\Models\Master\Exam\ExamRoom::where('company_id', $companyId)->get();
    }

    public function render()
    {
        $classmates = Classmate::withoutGlobalScope('user_scope')->search($this->search)->select('id', 'name', 'description', 'type_study')
            ->where('company_id', Auth::user()?->company?->id)
            ->orderBy('order', 'desc');

        return view('livewire.admin.master.classmate.admin-master-classmate-index', [
            'classmates' => $classmates->paginate($this->perPage),
        ])->extends('layout.app')->section('content');
    }

    // public function hydrate()
    // {
    //     $this->resetPage();
    // }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['data_id', 'name', 'description', 'type_study', 'exam_session_id', 'exam_room_id', 'exam_date']);

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function setType($type): void
    {
        $this->type_study = $type;
    }

    public function submit()
    {
        $this->validate(
            [
                'type_study' => 'required|in:default,mahasiswa,general',
                'name' => 'required',
                'user_id' => $this->type_study == 'mahasiswa' ? 'required|exists:users,id' : 'nullable',
                'description' => 'nullable',
                'exam_session_id' => 'nullable|exists:exam_sessions,id',
                'exam_room_id' => 'nullable|exists:exam_rooms,id',
                'exam_date' => 'nullable|date',
            ],
            [
                'type_study.required' => 'Tipe studi wajib diisi.',
                'user_id.required' => 'Dosen wajib diisi.',
                'name.required' => 'Nama Peserta wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();

            $classmate = Classmate::updateOrCreate(
                [
                    'id' => $this->data_id ?? null,
                ],
                [
                    'type_study' => $this->type_study,
                    'user_id' => $this->type_study == 'mahasiswa' ? $this->user_id ?? null : null,
                    'company_id' => Auth::user()?->company_id,
                    'name' => $this->name ?? null,
                    'description' => $this->description ?? null,
                    'exam_session_id' => $this->exam_session_id ?: null,
                    'exam_room_id' => $this->exam_room_id ?: null,
                    'exam_date' => $this->exam_date ?: null,
                ]
            );

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

                ClassmateStudent::where('classmate_id', $classmate->id)->delete();
                foreach ($students as $studentId) {
                    ClassmateStudent::create([
                        'classmate_id' => $classmate->id,
                        'user_id' => $studentId,
                    ]);
                }
            }

            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterClassmateIndex => submit', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        return Redirect::route('admin.master.classmate.detail', ['id' => $id]);
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function toggleRandomQuestion($id)
    {
        try {
            DB::beginTransaction();
            $module = Classmate::find($id);
            if ($module) {
                $module->random_question = ! $module->random_question;
                $module->save();
            }
            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterClassmateIndex => toggleRandomQuestion', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat mengubah data');
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Classmate::findOrFail($id[0]);
            ClassmateStudent::where('classmate_id', $result->id)->delete();
            $result->delete();
            DB::commit();
        } catch (Exception|Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterClassmateIndex => delete', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
