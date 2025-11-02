<?php

namespace App\Livewire\Admin\Master\ExamRoom;

use App\Helpers\AlertHelper;
use App\Models\Master\Exam\ExamRoom;
use App\Services\ExamRoom\ExamRoomService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class AdminMasterExamRoomIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $name, $code, $is_active, $description;

    public function render()
    {
        $exam_rooms = ExamRoom::search($this->search)->select('id', 'name', 'code', 'description');
        return view('livewire.admin.master.exam-room.admin-master-exam-room-index', [
            'exam_rooms' => $exam_rooms->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        //
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
        $this->reset(['data_id', 'name', 'code', 'is_active', 'description']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'name'        => 'required',
                'code'        => 'required',
                'description' => 'nullable',
            ],
            [
                'name.required' => 'Nama ruang ujian wajib diisi.',
                'code.required' => 'Code ruang ujian wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();
                $request = [
                    'id'          => $this->data_id,
                    'company_id'  => Auth::user()?->company?->id,
                    'name'        => $this->name,
                    'code'        => $this->code,
                    'description' => $this->description,
                ];

                $exam_room = app(ExamRoomService::class)->updateOrCreate($request);
                if (!$exam_room) {
                    throw new Exception("Ada kesalahaan saat ExamRoomService => updateOrCreate", 500);
                }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterExamRoomIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result            = ExamRoom::findOrFail($id);
        $this->data_id     = $result?->id;
        $this->name        = $result?->name;
        $this->code        = $result?->code;
        $this->description = $result?->description;
        $this->openModal();
    }

    public function toggleExamRoomIsActive($id)
    {
        try {
            $result            = ExamRoom::findOrFail($id);
            $this->data_id     = $result?->id;
            $this->name        = $result?->name;
            $this->code        = $result?->code;
            $this->is_active   = $result?->is_active ? false : true;
            $this->description = $result?->description;

            DB::beginTransaction();
                $request = [
                    'id'          => $this->data_id,
                    'company_id'  => Auth::user()?->company?->id,
                    'name'        => $this->name,
                    'code'        => $this->code,
                    'is_active'   => $this->is_active,
                    'description' => $this->description,
                ];

                $exam_room = app(ExamRoomService::class)->updateOrCreate($request);
                if (!$exam_room) {
                    throw new Exception("Ada kesalahaan saat ExamRoomService => updateOrCreate", 500);
                }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterExamRoomIndex => toggleExamRoomIsActive', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            app(ExamRoomService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterExamRoomIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
