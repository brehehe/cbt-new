<?php

namespace App\Livewire\Admin\Master\Role;

use App\Helpers\AlertHelper;
use App\Models\Role\RoleCompany;
use App\Models\Spatie\Role;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterRoleIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // atau 'tailwind' sesuai UI

    protected $queryString = [
        // 'page' => ['except' => 1],
        'search' => ['except' => ''],
    ];

    public $perPage = 5;
    public $search = '';
    public $data_id;
    public $name;

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->perPage();
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['data_id', 'name']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function edit($id)
    {
        $role = RoleCompany::findOrFail($id);
        $this->data_id = $role->id;
        $this->name = $role->role->name;
        $this->openModal();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);


        $role = Role::where('name', $this->name)->first();

        if (!$role) {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => 'web',
            ]);
        }

        $roleCompany = RoleCompany::where('role_id', $role->uuid)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        if ($roleCompany) {
            if (!$this->data_id) {
                return AlertHelper::error('Gagal', 'Role ini sudah ada di perusahaan Anda.');
            }
        } else {
            $roleCompany = new RoleCompany();
            $roleCompany->company_id = auth()->user()->company_id;
            $roleCompany->role_id = $role->uuid;
        }
        $roleCompany->save();

        $role->save();

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data role berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus role ini?', $id);
    }

    public function delete($id)
    {
        $roleCompany = RoleCompany::findOrFail($id);
        $roleCompany->delete();

        return AlertHelper::success('Berhasil', 'Role berhasil dihapus.');
    }

    public function render()
    {
        $roleCompany = RoleCompany::with(['role', 'company'])
            ->where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'not like', '%Pasien%');
            })
            ->search($this->search)
            ->orderBy('order', 'asc');

        return view('livewire.admin.master.role.admin-master-role-index', [
            'roles' => $roleCompany->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
