<?php

namespace App\Livewire\Admin\Master\Material;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\MaterialCategory;
use App\Services\Material\MaterialService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class AdminMasterMaterialIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $material_categories;
    public $data_id, $material_category_id, $name, $level, $description;

    public function render()
    {
        $materials = Material::search($this->search)->select('id', 'material_category_id', 'name', 'level', 'description')
            ->with([
                'materialCategory:id,name'
            ]);
        return view('livewire.admin.master.material.admin-master-material-index', [
            'materials' => $materials->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
        $this->material_categories = MaterialCategory::search($this->search)
            ->select('id', 'company_id', 'topic_id', 'material_category_id', 'name', 'description')
            ->with([
                'topic:id,name'
            ])->whereDoesntHave('childs')->get();
    }

    public function hydrate ()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['data_id', 'material_category_id', 'name', 'level', 'description']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'material_category_id' => 'required|exists:material_categories,id',
                'name'                 => 'required',
                'level'                => 'required',
                'description'          => 'nullable',
            ],
            [
                'material_category_id.required' => 'kategori materi wajib diisi.',
                'material_category_id.exists'   => 'Kategori materi tidak valid.',
                'name.required'                 => 'Nama materi wajib diisi.',
                'level.required'                => 'Level materi wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();
                $request = [
                    'id'                   => $this->data_id,
                    'company_id'           => Auth::user()?->company?->id,
                    'material_category_id' => $this->material_category_id,
                    'name'                 => $this->name,
                    'level'                => $this->level,
                    'description'          => $this->description,
                ];

                $material = app(MaterialService::class)->updateOrCreate($request);
                if (!$material) {
                    throw new Exception("Ada kesalahaan saat MaterialService => updateOrCreate", 500);
                }

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterMaterialIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result                     = Material::findOrFail($id);
        $this->data_id              = $result?->id;
        $this->material_category_id = $result?->material_category_id;
        $this->name                 = $result?->name;
        $this->level                = $result?->level;
        $this->description          = $result?->description;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            app(MaterialService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterMaterialIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
