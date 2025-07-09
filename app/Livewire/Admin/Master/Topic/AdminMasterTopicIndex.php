<?php

namespace App\Livewire\Admin\Master\Topic;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\Topic;
use App\Services\Topic\TopicService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class AdminMasterTopicIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $name, $description;

    public function render()
    {
        $topics = Topic::search($this->search)
            ->select('id', 'company_id', 'name', 'description');
        return view('livewire.admin.master.topic.admin-master-topic-index', [
            'topics' => $topics->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
    }

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
        $this->reset(['data_id', 'name', 'description']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'name'        => 'required',
                'description' => 'nullable',
            ],
            [
                'name.required' => 'Nama Topik wajib diisi.'
            ]
        );

        try {
            DB::beginTransaction();
                $request = [
                    'id'          => $this->data_id,
                    'company_id'  => Auth::user()?->company?->id,
                    'name'        => $this->name,
                    'description' => $this->description,
                ];

                $topic = app(TopicService::class)->updateOrCreate($request);

                if (!$topic) {
                    throw new Exception("Ada kesalahaan saat TopicService => updateOrCreate", 500);
                }
            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterTopicIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result            = Topic::findOrFail($id);
        $this->data_id     = $result?->id;
        $this->name        = $result?->name;
        $this->description = $result?->description;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        $result = Topic::findOrFail($id[0]);

        app(TopicService::class)->delete($result);

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
