<?php

namespace App\Livewire\Admin\Master\CategoryQuestion;

use Exception;
use Livewire\Component;
use App\Helpers\AlertHelper;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Category\CategoryQuestion;
use App\Services\CategoryQuestion\CategoryQuestionService;
use Throwable;

class AdminMasterCategoryQuestionIndex extends Component
{
     use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $name, $description;

    public function render()
    {
        $category_questions = CategoryQuestion::search($this->search)->select('id', 'name', 'description');
        return view('livewire.admin.master.category-question.admin-master-category-question-index', [
             'category_questions' => $category_questions->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

     public function mount()
    {
        // dd(Auth::user()?->company);
    }

    // public function hydrate ()
    // {
    //     $this->resetPage();
    // }

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
        $this->validate(
            [
                'name'                 => 'required',
                'description'          => 'nullable',
            ],
            [
                'name.required' => 'Nama tipe ujian wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();
                 $request = [
                    'id'                   => $this->data_id,
                    'company_id'           => Auth::user()?->company?->id,
                    'name'                 => $this->name,
                    'description'          => $this->description,
                ];

                $exam_type = app(CategoryQuestionService::class)->updateOrCreate($request);
                if (!$exam_type) {
                    throw new Exception("Ada kesalahaan saat CategoryQuestionService => updateOrCreate", 500);
                }

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterCategoryQuestionIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

     public function edit($id)
    {
        $result                     = CategoryQuestion::findOrFail($id);
        $this->data_id              = $result?->id;
        $this->name                 = $result?->name;
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
            app(CategoryQuestionService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterCategoryQuestionIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
