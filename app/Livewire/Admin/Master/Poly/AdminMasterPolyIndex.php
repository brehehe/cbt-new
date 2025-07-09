<?php

namespace App\Livewire\Admin\Master\Poly;

use App\Helpers\AlertHelper;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\Location\MasterLocationMode;
use App\Models\Master\CodeSystem\Location\MasterLocationStatus;
use App\Models\Master\CodeSystem\Location\MasterLocationType;
use App\Models\Poly\Poly;
use App\service\apiservice;
use DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use LaravelLang\Lang\Plugins\Breeze\Master;
use Log;

class AdminMasterPolyIndex extends Component
{
    use WithPagination, WithFileUploads;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $perPage = 5;
    public $search = '';
    public $data_id;
    public $name;
    public $description;
    public $status;
    public $mode;
    public $physical_type;
    public $image;
    public $image_old;

    // Array
    public $getStatuss = [];
    public $getModes = [];
    public $getPhysicalTypes = [];

    public function mount() {
        $this->getStatuss = MasterLocationStatus::select('code', 'display')->get()->toArray();
        $this->getModes = MasterLocationMode::select('code', 'display')->get()->toArray();
        $this->getPhysicalTypes = MasterLocationType::select('code', 'display')->get()->toArray();
    }

    public function openModal() {
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal() {
        $this->resetValidation();
        $this->reset(['data_id', 'name', 'description', 'image', 'image_old', 'mode', 'physical_type', 'status']);
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function edit($id) {
        $poly = Location::findOrFail($id);
        $this->data_id = $poly->id;
        $this->name = $poly->name;
        $this->description = $poly->description;
        $this->image_old = $poly->image;
        $this->mode = $poly->mode;
        $this->physical_type = $poly->physical_type;
        $this->status = $poly->status;

        $this->openModal();
    }

    public function confirmDelete($id) {
        return AlertHelper::confirmDelete('delete', 'Anda Yakin Menghapus Data Ini?', $id);
    }

    public function delete($id) {
        $poly = Poly::findOrFail($id[0]);
        $poly->delete();
        return AlertHelper::success('Berhasil Menghapus Data', 'Data Berhasil Dihapus');
    }

    public function submit() {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048', // Maksimal 2MB
            'status'=> 'required',
            'mode'=> 'required',
            'physical_type'=> 'required',
        ]);

        try {
            DB::beginTransaction();

            $location = Location::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'slug'=> Str::slug($this->name),
                    'description' => $this->description,
                    'image' => $this->image ? $this->image->store('polies', 'public') : $this->image_old,
                    'mode' => $this->mode,
                    'physical_type' => $this->physical_type,
                    'status' => $this->status,
                    'slug'=> Str::slug($this->name). '-' . Str::random(5),
                ]
            );

            app(apiservice::class)->syncLocation($location);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving poly data: ' . $e->getMessage(), [
                'data_id' => $this->data_id,
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
                'mode' => $this->mode,
                'physical_type' => $this->physical_type,
            ]);
            return AlertHelper::error('Gagal Menyimpan Data', $e->getMessage());
        }



        $this->closeModal();
        return AlertHelper::success('Berhasil Menyimpan Data', 'Data Berhasil Disimpan');
    }

    public function render()
    {
        $locations = Location::search($this->search)
        ->where('company_id', auth()->user()->company_id)
        ->orderBy('name', 'asc');

        return view('livewire.admin.master.poly.admin-master-poly-index',[
            'locations'=> $locations->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
