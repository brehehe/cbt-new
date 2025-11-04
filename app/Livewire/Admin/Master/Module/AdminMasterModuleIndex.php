<?php

namespace App\Livewire\Admin\Master\Module;

use Exception;
use Livewire\Component;
use App\Helpers\AlertHelper;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\QuestionType;
use App\Models\Study\Study;
use App\Services\Module\ModuleService;
use App\Services\QuestionType\QuestionTypeService;
use Throwable;

class AdminMasterModuleIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $question_types;
    public $data_id, $question_type_id, $name, $duration, $description, $random_question;
    public $updateRandomQuestion;
    public $get_studys = [], $studys = [], $is_all_study = false;

    public function render()
    {
        $modules = Module::withoutGlobalScope('user_scope')->search($this->search)->select('id', 'question_type_id', 'name', 'duration', 'description', 'random_question', 'studys')
            ->with([
                'questionType:id,name'
            ])
            ->where('company_id', Auth::user()?->company?->id)
            ->orderBy('order', 'desc');
        return view('livewire.admin.master.module.admin-master-module-index', [
            'modules' => $modules->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        // dd(Auth::user()?->company);
        $this->question_types = QuestionType::select('id', 'name')->get();
        if (Auth::user()?->hasRole('Dosen')) {
            $studyIds = Auth::user()?->studys ?? []; // array dari JSON
            $this->get_studys = Study::whereIn('id', $studyIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->get_studys = Study::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        }
    }

    public function updatedIsAllStudy($value)
    {
        if ($value) {
            $this->studys = array_keys($this->get_studys);
        } else {
            $this->studys = [];
        }
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
        $this->reset(['data_id', 'question_type_id', 'name', 'duration', 'description', 'random_question', 'studys', 'is_all_study']);
        return $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function submit()
    {
        $this->validate(
            [
                'question_type_id' => 'required|exists:question_types,id',
                'name'             => 'required',
                'description'      => 'nullable',
                'duration'         => 'required|numeric|min:1',
                'studys'           => 'required|array',
            ],
            [
                'question_type_id.required' => 'Tipe Ujian wajib diisi.',
                'question_type_id.exists'   => 'Tipe Ujian tidak valid.',
                'name.required'             => 'Nama modul wajib diisi.',
                'duration.required'         => 'Durasi pengerjaan modul wajib diisi.',
                'duration.numeric'          => 'Durasi pengerjaan modul hanya bernilai angka.',
                'duration.min'              => 'Durasi pengerjaan modul minimal 1 menit.',
                'studys.required'           => 'Prodi wajib dipilih.',
                'studys.array'              => 'Prodi tidak valid.',
            ]
        );

        try {
            DB::beginTransaction();
            $request = [
                'id'               => $this->data_id,
                'user_id'          => Auth::user()?->id,
                'company_id'       => Auth::user()?->company?->id,
                'question_type_id' => $this->question_type_id,
                'name'             => $this->name,
                'duration'         => $this->duration,
                'random_question'  => $this->random_question,
                'description'      => $this->description,
                'studys'           => $this->studys,
            ];

            $module = app(ModuleService::class)->updateOrCreate($request);
            if (!$module) {
                throw new Exception("Ada kesalahaan saat ModuleService => updateOrCreate", 500);
            }

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => submit', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menyimpan data');
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $result                 = Module::findOrFail($id);
        $this->data_id          = $result?->id;
        $this->question_type_id = $result?->question_type_id;
        $this->name             = $result?->name;
        $this->duration         = $result?->duration;
        $this->random_question  = $result?->random_question;
        $this->description      = $result?->description;
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function toggleRandomQuestion($id)
    {
        try {
            DB::beginTransaction();
            $module = Module::find($id);
            if ($module) {
                $module->random_question = !$module->random_question;
                $module->save();
            }
            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => toggleRandomQuestion', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat mengubah data');
        }
    }

    public function delete($id)
    {
        try {
            app(ModuleService::class)->delete($id[0]);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat AdminMasterModuleIndex => delete', $error);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat menghapus data');
        }

        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }
}
