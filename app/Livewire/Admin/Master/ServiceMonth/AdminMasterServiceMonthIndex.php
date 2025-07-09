<?php

namespace App\Livewire\Admin\Master\ServiceMonth;

use App\Helpers\AlertHelper;
use App\Models\Service\Service;
use App\Models\Service\ServiceMonth;
use App\Models\Service\ServiceMonthDetail;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterServiceMonthIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $perPage = 5;
    public $search = '';
    public $data_id;
    public $name;
    public $description;
    public $price;
    public $is_trial;
    public $is_active;
    public $is_lifetime;
    public $services = [];
    public $serviceMonthDetails = [];

    public function mount() {
        $this->services = Service::select('id', 'name')->get()->toArray();
    }

    public function openModal() {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal() {
        $this->resetValidation();
        $this->reset(['data_id', 'name', 'description', 'price', 'is_trial', 'is_active', 'is_lifetime','serviceMonthDetails']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function edit($id) {
        $serviceMonth = ServiceMonth::findOrFail($id);
        $this->data_id = $serviceMonth->id;
        $this->name = $serviceMonth->name;
        $this->description = $serviceMonth->description;
        $this->price = number_format($serviceMonth->price, 0, ',', '.');
        $this->is_trial = $serviceMonth->is_trial;
        $this->is_active = $serviceMonth->is_active;
        $this->is_lifetime = $serviceMonth->is_lifetime;

        $serviceMonthDetails = ServiceMonthDetail::where('service_month_id', $this->data_id)
            ->orderBy('order', 'asc')
            ->where('status', 'active')
            ->get();

        foreach ($serviceMonthDetails as $key => $value) {
            $this->serviceMonthDetails[$key] = $value->service_id;
        }

        return $this->openModal();
    }

    public function confirmDelete($id) {
        return AlertHelper::confirmDelete('delete', 'Anda Yakin Menghapus Data Ini?', $id);
    }

    public function delete($id) {
        $serviceMonth = ServiceMonth::findOrFail($id[0]);
        $serviceMonth->delete();
        return AlertHelper::success('Berhasil Menghapus Data', 'Data Berhasil Dihapus');
    }

    public function submit() {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required',
            'is_trial' => 'boolean',
            'is_active' => 'boolean',
            'is_lifetime' => 'boolean',
            'serviceMonthDetails' => 'required|array',
        ]);

        $serviceMonth = ServiceMonth::updateOrCreate(
            ['id' => $this->data_id],
            [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'is_trial' => $this->is_trial,
                'is_active' => $this->is_active,
                'is_lifetime' => $this->is_lifetime,
            ]
        );

        foreach ($this->services as $key => $service) {
            $serviceMonthDetail = ServiceMonthDetail::where('service_month_id', $serviceMonth->id)
                ->where('service_id', $service['id'])
                ->first();

            if (!$serviceMonthDetail) {
                ServiceMonthDetail::create([
                    'service_month_id' => $serviceMonth->id,
                    'service_id' => $service['id'],
                    'status' => 'deactived',
                ]);
            }

            if (in_array($service['id'], $this->serviceMonthDetails)) {
                $serviceMonthDetail->update([
                    'status' => 'active',
                ]);
            } else {
                if ($serviceMonthDetail) {
                    $serviceMonthDetail->update([
                        'status' => 'deactived',
                    ]);
                }
            }
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil Menyimpan Data', 'Data Berhasil Disimpan');
    }

    public function render()
    {
        $serviceMonths = ServiceMonth::search($this->search)
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.service-month.admin-master-service-month-index', [
            'serviceMonths' => $serviceMonths,
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
