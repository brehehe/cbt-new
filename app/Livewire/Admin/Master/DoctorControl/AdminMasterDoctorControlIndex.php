<?php

namespace App\Livewire\Admin\Master\DoctorControl;

use App\Helpers\AlertHelper;
use App\Models\Location\Location;
use App\Models\Poly\Poly;
use App\Models\User;
use App\Models\User\ControlDoctor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterDoctorControlIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public $data_id;
    public $user_id;
    public $location_id;
    public $locations = [];
    public $getDays = [];
    public $getTimes = [];
    public $users = [];
    public $day;
    public $start_time;
    public $end_time;
    public $max_patients = 0;

    public function mount()
    {
        $this->getDays = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];
        $this->locations = Location::select('id', 'name')
            ->where('company_id', Auth::user()->company_id)
            ->get()
            ->toArray();
        $this->users = User::select('id', 'name')->companyRole('Dokter', Auth::user()->company_id)->get()->toArray();
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function openModal($modal)
    {
        $this->dispatch('open-modal', ['id' => $modal]);
    }

    public function closeModal($modal)
    {
        $this->dispatch('close-modal', ['id' => $modal]);
        return $this->reset([
            'data_id',
            'user_id',
            'day',
            'start_time',
            'end_time',
            'max_patients',
            'location_id'
        ]);
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda Yakin Menghapus Data Ini?', $id);
    }

    public function delete($id)
    {
        $controlDoctor = ControlDoctor::findOrFail($id[0]);
        $controlDoctor->delete();
        AlertHelper::success('Kontrol Dokter Berhasil Dihapus');
    }

    public function edit($id)
    {
        $controlDoctor = ControlDoctor::findOrFail($id);
        $this->data_id = $controlDoctor->id;
        $this->user_id = $controlDoctor->user_id;
        $this->day = $controlDoctor->day;
        $this->start_time = $controlDoctor->start_time;
        $this->end_time = $controlDoctor->end_time;
        $this->max_patients = $controlDoctor->max_patients;
        $this->location_id = $controlDoctor->location_id;

        return $this->openModal('modal');
    }

    public function submit()
    {
        $this->validate([
            'user_id' => 'required',
            'location_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_patients' => 'nullable|integer|min:0',
        ]);

        ControlDoctor::updateOrCreate(
            ['id' => $this->data_id],
            [
                'location_id' => $this->location_id,
                'user_id' => $this->user_id,
                'day' => $this->day,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'max_patients' => $this->max_patients,
                'company_id' => Auth::user()->company_id,
            ]
        );

        AlertHelper::success('Kontrol Dokter Berhasil Disimpan');
        return $this->closeModal('modal');
    }

    public function render()
    {
        $controlDoctors = ControlDoctor::search($this->search)
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.doctor-control.admin-master-doctor-control-index', [
            'controlDoctors' => $controlDoctors
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
