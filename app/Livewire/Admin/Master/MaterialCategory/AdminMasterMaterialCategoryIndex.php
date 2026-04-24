<?php

namespace App\Livewire\Admin\Master\MaterialCategory;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\Topic;
use App\Services\MaterialCategory\MaterialCategoryService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class AdminMasterMaterialCategoryIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search;

    public $data_id;

    public $topic_id;

    public $material_category_id;

    public $name;

    public $description;

    public function render()
    {
        $material_categories = MaterialCategory::search($this->search)
            ->select('id', 'company_id', 'topic_id', 'material_category_id', 'name', 'description')
            ->with([
                'topic:id,name',
            ]);
        $select_material_categories = MaterialCategory::search($this->search)
            ->select('id', 'company_id', 'topic_id', 'material_category_id', 'name', 'description')
            ->with([
                'topic:id,name',
            ])->when(! empty($this->topic_id), fn ($q) => $q->where('topic_id', $this->topic_id))->get();

        $topics = Topic::select('id', 'name')->get();

        return view('livewire.admin.master.material-category.admin-master-material-category-index', [
            'material_categories' => $material_categories->paginate($this->perPage),
            'select_material_categories' => $select_material_categories,
            'topics' => $topics,
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
    }

    public function hydrate()
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
        $this->reset(['data_id', 'topic_id', 'material_category_id', 'name', 'description']);

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'topic_id' => 'required|exists:topics,id',
                'material_category_id' => 'nullable|exists:material_categories,id',
                'name' => 'required',
                'description' => 'nullable',
            ],
            [
                'topic_id.required' => 'Topik ujian wajib diisi.',
                'topic_id.exists' => 'Topik ujian tidak valid.',
                'material_category_id.exists' => 'Induk kategori materi tidak valid.',
                'name.required' => 'Nama kategori materi wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id' => $this->data_id,
                'company_id' => Auth::user()?->company?->id,
                'topic_id' => $this->topic_id,
                'material_category_id' => $this->material_category_id,
                'name' => $this->name,
                'description' => $this->description,
            ];

            $material_category = app(MaterialCategoryService::class)->updateOrCreate($request);
            if (! $material_category) {
                throw new Exception('Ada kesalahaan saat MaterialCategoryService => updateOrCreate', 500);
            }

            DB::commit();
        } catch (Exception|Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterMaterialCategoryIndex => submit', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();

        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result = MaterialCategory::findOrFail($id);
        $this->data_id = $result?->id;
        $this->topic_id = $result?->topic_id;
        $this->material_category_id = $result?->material_category_id;
        $this->name = $result?->name;
        $this->description = $result?->description;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            app(MaterialCategoryService::class)->delete($id[0]);
        } catch (Exception|Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterMaterialCategoryIndex => delete', $error);

            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
