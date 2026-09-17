<?php

namespace App\Livewire\Admin\Master\DigitalBook\Category;

use App\Helpers\AlertHelper;
use App\Models\Master\DigitalBook\DigitalBookCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterDigitalBookCategoryIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $search = '';
    public $data_id;
    public $name;
    public $description;

    public function render()
    {
        $categories = DigitalBookCategory::when($this->search, fn($q) => $q->search($this->search))
            ->withCount('books')
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.master.digital-book.category.admin-master-digital-book-category-index', [
            'categories' => $categories->paginate($this->perPage),
        ])->extends('layout.app')->section('content');
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
        $this->reset(['data_id', 'name', 'description']);

        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            DigitalBookCategory::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'company_id' => Auth::user()->company_id,
                ]
            );

            DB::commit();
            AlertHelper::success('Berhasil', 'Kategori Buku Digital berhasil disimpan!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error saving digital book category: ' . $th->getMessage());
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan data: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $category = DigitalBookCategory::find($id);
        if (! $category) {
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');
        }

        $this->data_id = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            $categoryId = is_array($id) ? ($id[0] ?? null) : $id;
            $category = DigitalBookCategory::find($categoryId);
            if (! $category) {
                return AlertHelper::error('Gagal', 'Data tidak ditemukan.');
            }

            if ($category->books()->count() > 0) {
                return AlertHelper::error('Gagal', 'Kategori tidak dapat dihapus karena masih memiliki buku digital terhubung.');
            }

            $category->delete();
            AlertHelper::success('Berhasil', 'Kategori berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error('Error deleting category: ' . $th->getMessage());
            AlertHelper::error('Gagal', 'Gagal menghapus kategori: ' . $th->getMessage());
        }
    }
}
