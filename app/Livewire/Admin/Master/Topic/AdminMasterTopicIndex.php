<?php

namespace App\Livewire\Admin\Master\Topic;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
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

    public $studies;
    public $data_id, $name, $description, $study_id, $studys;

    public function render()
    {
        $topics = Topic::search($this->search)
            ->with('study')
            ->select('id', 'company_id', 'name', 'description', 'study_id');
        return view('livewire.admin.master.topic.admin-master-topic-index', [
            'topics' => $topics->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
       if (Auth::user()?->hasRole('Dosen')) {

            $studyIds = Auth::user()?->studys ?? [];

            // Ensure $studyIds is always an array
            if (is_string($studyIds)) {
                $studyIds = json_decode($studyIds, true) ?? [];
            }

            // Ensure it's an array and not null
            $studyIds = is_array($studyIds) ? $studyIds : [];

            $this->studies = Study::whereIn('id', $studyIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();

            // Ambil key pertama dari $this->studies
        $this->study_id = !empty($this->studies)
            ? array_key_first($this->studies)
            : null;

        } else {

            $this->studies = Study::orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        }

    }

    // public function hydrate()
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
        $this->reset(['data_id', 'name', 'description', 'study_id']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'study_id'    => 'required',
                'name'        => 'required',
                'description' => 'nullable',
            ],
            [
                'study_id.required' => 'Prodi wajib diisi.',
                'name.required'     => 'Nama Topik wajib diisi.',
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id'          => $this->data_id,
                'company_id'  => Auth::user()?->company?->id,
                'study_id'    => $this->study_id,
                'name'        => $this->name,
                'description' => $this->description,
            ];

            // dd($request);

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
        $this->study_id    = $result?->study_id;
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
        try {
            app(TopicService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterToppicIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
