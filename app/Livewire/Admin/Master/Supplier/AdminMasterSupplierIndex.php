<?php

namespace App\Livewire\Admin\Master\Supplier;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Supplier\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterSupplierIndex extends Component
{
    use WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];

    public $search = '';

    public $perPage = 5;

    public $data_id;

    public $name;

    public $email;

    public $phone;

    public $address;

    public function edit($id)
    {
        $productUnit = Supplier::findOrFail($id);
        $this->data_id = $productUnit->id;
        $this->name = $productUnit->name;
        $this->email = $productUnit->email;
        $this->email = $productUnit->email;
        $this->phone = $productUnit->phone;
        $this->phone = $productUnit->phone;
        $this->address = $productUnit->address;

        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when perPage changes
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        try {
            DB::beginTransaction();

            Supplier::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            DB::commit();
            $this->dispatch('close-modal', ['id' => 'modal']);
            $this->reset(['data_id', 'name', 'email', 'phone', 'address']);

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving supplier', [
                'id' => $this->data_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function confirmDelete($id)
    {
        LivewireAlert::title('Delete?')
            ->text('Apakah Anda yakin ingin menghapus data ini?')
            ->withConfirmButton('Delete', '#1E3A8A')
            ->withCancelButton('Batal')
            ->confirmButtonColor('#1E3A8A')
            ->denyButtonColor('#dc3545')
            ->withOptions([
                'customClass' => [
                    'title' => 'text-lg font-bold text-start',
                    'content' => 'text-start text-sm',
                    'popup' => 'text-left',
                ],
            ])
            ->onConfirm('delete', ['id' => $id])
            ->show();
    }

    public function delete($data)
    {
        $itemId = $data['id'];

        try {
            DB::beginTransaction();

            $productRack = Supplier::findOrFail($itemId);
            if ($productRack) {
                $productRack->delete();

                DB::commit();

                return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
            }

            DB::rollBack();
            Log::error('supplier not found for deletion', ['id' => $itemId]);

            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product unit', [
                'id' => $itemId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function closeModal($modalId)
    {
        $this->resetValidation();
        $this->reset(['data_id', 'name', 'email', 'phone', 'address']);
        $this->dispatch('close-modal', ['id' => $modalId]);
    }

    public function openModal($modalId)
    {
        $this->dispatch('open-modal', ['id' => $modalId]);
    }

    public function render()
    {
        $supplier = Supplier::search($this->search)
            ->select('id', 'name', 'email', 'phone', 'address', 'company_id')
            ->with('company:id,name')
            ->where('company_id', auth()->user()->company_id);

        return view('livewire.admin.master.supplier.admin-master-supplier-index', [
            'suppliers' => $supplier->paginate(10),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
