<?php

namespace App\Livewire\Admin\Master\Classmate;

use App\Models\Classmate\Classmate;
use Livewire\Component;
use App\Helpers\AlertHelper;
use App\Models\Classmate\ClassmateStudent;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Redirect;
use Throwable;

class AdminMasterClassmateIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $name, $description, $user_id, $users = [];
    public $type_study;

    public function mount()
    {
        $this->users = User::role(['Dosen'])->where('company_id', Auth::user()?->company?->id)->select('id', 'name')->get()->pluck('name', 'id')->toArray();
    }

    public function render()
    {
        $classmates = Classmate::withoutGlobalScope('user_scope')->search($this->search)->select('id', 'name', 'description','type_study')
            ->where('company_id', Auth::user()?->company?->id)
            ->orderBy('order', 'desc');
        return view('livewire.admin.master.classmate.admin-master-classmate-index', [
            'classmates' => $classmates->paginate($this->perPage)
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
        $this->reset(['data_id', 'name', 'description','type_study']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function setType($type) : void {
        $this->type_study = $type;
    }

    public function submit()
    {
        $this->validate(
            [
                'type_study'       => 'required|in:default,mahasiswa,general',
                'name'             => 'required',
                'user_id'         => $this->type_study == 'mahasiswa' ? 'required|exists:users,id' : 'nullable',
                'description'      => 'nullable',
            ],
            [
                'type_study.required'      => 'Tipe studi wajib diisi.',
                'user_id.required'        => 'Dosen wajib diisi.',
                'name.required'             => 'Nama Peserta wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();

            Classmate::updateOrCreate(
                [
                    'id' => $this->data_id ?? null
                ],
                [
                    'type_study'  => $this->type_study,
                    'user_id'     => $this->type_study == 'mahasiswa' ? $this->user_id ?? null : null,
                    'company_id'  => Auth::user()?->company_id,
                    'name'        => $this->name ?? null,
                    'description' => $this->description ?? null,
                ]
            );

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
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
                $module->random_question = !$module->random_question;
                $module->save();
            }
            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
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
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterClassmateIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
