<?php

namespace App\Livewire\Admin\Master\DigitalBook\Book;

use App\Helpers\AlertHelper;
use App\Models\Master\DigitalBook\DigitalBook;
use App\Models\Master\DigitalBook\DigitalBookCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdminMasterDigitalBookIndex extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $search = '';
    public $filter_category = '';
    public $filter_type = '';

    // Form inputs
    public $data_id;
    public $digital_book_category_id;
    public $title;
    public $author;
    public $description;
    public $content_type = 'pdf'; // 'pdf', 'link', 'video'
    public $external_url;
    public $file; // for uploaded PDF
    public $cover; // for cover image
    public $existing_file_path;
    public $existing_cover;

    // Preview state
    public $previewBook;

    public function render()
    {
        $categories = DigitalBookCategory::orderBy('name', 'asc')->get();

        $query = DigitalBook::with('category')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->filter_category, fn($q) => $q->where('digital_book_category_id', $this->filter_category))
            ->when($this->filter_type, fn($q) => $q->where('content_type', $this->filter_type))
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.master.digital-book.book.admin-master-digital-book-index', [
            'books' => $query->paginate($this->perPage),
            'categories' => $categories,
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
        $this->reset([
            'data_id',
            'digital_book_category_id',
            'title',
            'author',
            'description',
            'content_type',
            'external_url',
            'file',
            'cover',
            'existing_file_path',
            'existing_cover',
        ]);
        $this->content_type = 'pdf';
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'digital_book_category_id' => 'required|exists:digital_book_categories,id',
            'content_type' => 'required|in:pdf,link,video',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ];

        if ($this->content_type === 'pdf') {
            if (! $this->data_id || $this->file) {
                $rules['file'] = $this->data_id ? 'nullable|file|mimes:pdf|max:51200' : 'required|file|mimes:pdf|max:51200';
            }
        } elseif (in_array($this->content_type, ['link', 'video'])) {
            $rules['external_url'] = 'required|url';
        }

        $this->validate($rules, [
            'title.required' => 'Judul buku wajib diisi',
            'digital_book_category_id.required' => 'Kategori buku wajib dipilih',
            'file.required' => 'File PDF wajib diunggah',
            'file.mimes' => 'Format file harus berupa PDF',
            'external_url.required' => 'Tautan / Link URL wajib diisi',
            'external_url.url' => 'Format URL tidak valid (harus diawali http:// atau https://)',
        ]);

        try {
            DB::beginTransaction();

            $filePath = $this->existing_file_path;
            if ($this->content_type === 'pdf' && $this->file) {
                if ($this->existing_file_path && Storage::disk('public')->exists($this->existing_file_path)) {
                    Storage::disk('public')->delete($this->existing_file_path);
                }
                $filePath = $this->file->store('digital_books/pdf', 'public');
            }

            $coverPath = $this->existing_cover;
            if ($this->cover) {
                if ($this->existing_cover && Storage::disk('public')->exists($this->existing_cover)) {
                    Storage::disk('public')->delete($this->existing_cover);
                }
                $coverPath = $this->cover->store('digital_books/covers', 'public');
            }

            DigitalBook::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'company_id' => Auth::user()->company_id,
                    'digital_book_category_id' => $this->digital_book_category_id,
                    'title' => $this->title,
                    'author' => $this->author,
                    'description' => $this->description,
                    'content_type' => $this->content_type,
                    'file_path' => $this->content_type === 'pdf' ? $filePath : null,
                    'external_url' => in_array($this->content_type, ['link', 'video']) ? $this->external_url : null,
                    'cover_image' => $coverPath,
                ]
            );

            DB::commit();
            AlertHelper::success('Berhasil', 'Buku Digital berhasil disimpan!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error saving digital book: ' . $th->getMessage());
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $book = DigitalBook::find($id);
        if (! $book) {
            return AlertHelper::error('Gagal', 'Data tidak ditemukan.');
        }

        $this->data_id = $book->id;
        $this->digital_book_category_id = $book->digital_book_category_id;
        $this->title = $book->title;
        $this->author = $book->author;
        $this->description = $book->description;
        $this->content_type = $book->content_type;
        $this->external_url = $book->external_url;
        $this->existing_file_path = $book->file_path;
        $this->existing_cover = $book->cover_image;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data buku ini?', $id);
    }

    public function delete($id)
    {
        try {
            $bookId = is_array($id) ? ($id[0] ?? null) : $id;
            $book = DigitalBook::find($bookId);
            if (! $book) {
                return AlertHelper::error('Gagal', 'Data tidak ditemukan.');
            }

            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
            }
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $book->delete();
            AlertHelper::success('Berhasil', 'Buku Digital berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error('Error deleting digital book: ' . $th->getMessage());
            AlertHelper::error('Gagal', 'Gagal menghapus: ' . $th->getMessage());
        }
    }

    public function openPreview($id)
    {
        $this->previewBook = DigitalBook::with('category')->find($id);
        if (! $this->previewBook) {
            return AlertHelper::error('Gagal', 'Buku tidak ditemukan.');
        }
        return $this->dispatch('open-modal', ['id' => 'modal-preview-book']);
    }

    public function closePreview()
    {
        $this->previewBook = null;
        return $this->dispatch('close-modal', ['id' => 'modal-preview-book']);
    }
}
