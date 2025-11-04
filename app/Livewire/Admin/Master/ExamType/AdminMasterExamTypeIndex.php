<?php

namespace App\Livewire\Admin\Master\ExamType;

use Exception;
use Throwable;
use Livewire\Component;
use App\Helpers\AlertHelper;
use App\Models\Master\Exam\ExamType;
use App\Services\ExamType\ExamTypeService;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminMasterExamTypeIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $name, $description;

    public function render()
    {
        $exam_types = ExamType::search($this->search)->select('id', 'name', 'description');
        return view('livewire.admin.master.exam-type.admin-master-exam-type-index', [
           'exam_types' => $exam_types->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
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

                $exam_type = app(ExamTypeService::class)->updateOrCreate($request);
                if (!$exam_type) {
                    throw new Exception("Ada kesalahaan saat ExamTypeService => updateOrCreate", 500);
                }

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterExamTypeIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result                     = ExamType::findOrFail($id);
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
            app(ExamTypeService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterExamTypeIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
