<?php

namespace App\Livewire\Admin\Master\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\Module;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AdminMasterTimetableIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    public $data_id;
    public $name;
    public $module_id;
    public $supervisors = [];
    public $start_time;
    public $end_time;
    public $description;
    public $getSupervisors = [];
    public $modules = [];

    public function mount()
    {
        $this->modules = Module::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal-timetable']);
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'name',
            'module_id',
            'supervisors',
            'start_time',
            'end_time',
            'description',
        ]);
        return $this->dispatch('close-modal', ['id' => 'modal-timetable']);
    }

    public function edit($id)
    {
        $data = Timetable::find($id);
        $this->data_id = $data->id;
        $this->name = $data->name;
        $this->module_id = $data->module_id;
        $this->supervisors = json_decode($data->supervisors);
        $this->start_time = $data->start_time;
        $this->end_time = $data->end_time;
        $this->description = $data->description;
        $this->openModal();
    }

    public function confirmGenerateToken($id)
    {
        return AlertHelper::confirmWarning('generateToken', 'Apakah Anda Yakin Membuat Token?', $id);
    }

    public function generateToken($id)
    {
        try {
            DB::beginTransaction();
            $token = '';
            $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $codeAlphabet .= 'abcdefghijklmnopqrstuvwxyz';
            $codeAlphabet .= '0123456789';
            $max = strlen($codeAlphabet); // edited
            for ($i = 0; $i < 5; $i++) {
                $token .= $codeAlphabet[random_int(0, $max - 1)];
            }

            Timetable::where('id', $id[0])->update([
                'code' => $token,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Token gagal dibuat!');
            return Log::info('Gagal Menghapus Token : ' . $th);
        }
        AlertHelper::success('Berhasil', 'Token berhasil dibuat!');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = Timetable::find($id[0]);
            $data->delete();
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal dihapus!');
            return Log::info('Gagal Menghapus Data Jadwal : ' . $th);
        }
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            // 'module_id' => 'required',
            'supervisors' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        try {
            DB::beginTransaction();
            Timetable::updateOrCreate([
                'id' => $this->data_id,
            ], [
                'name' => $this->name,
                'module_id' => $this->module_id,
                'supervisors' => json_encode($this->supervisors),
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'description' => $this->description,
            ]);
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!');
            return Log::info('Gagal Menyimpan Data Jadwal : ' . $th);
        }
    }

    public function render()
    {
        $timetable = Timetable::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('start_time', 'like', '%' . $search . '%')
                    ->orWhere('end_time', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.admin-master-timetable-index', [
            'timetables' => $timetable,
        ])
            ->extends('layout.app')
            ->section('content')
        ;
    }
}
