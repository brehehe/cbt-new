<?php

namespace App\Livewire\Admin\Master\RatingScale;

use App\Helpers\AlertHelper;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\RatingScale\RatingScale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminMasterRatingScaleIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    public $data_id;
    public $grade_letter;
    public $min_score;
    public $max_score;
    public $description;

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal-rating-scale']);
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'grade_letter',
            'min_score',
            'max_score',
            'description',
        ]);
        return $this->dispatch('close-modal', ['id' => 'modal-rating-scale']);
    }

    public function edit($id)
    {
        $data = RatingScale::find($id);
        $this->data_id = $data->id;
        $this->grade_letter = $data->grade_letter;
        $this->min_score = $data->min_score;
        $this->max_score = $data->max_score;
        $this->description = $data->description;
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = RatingScale::find($id);
            $data->delete();
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal dihapus!');
            return Log::info('Gagal Menghapus Data Role : ' . $th);
        }
    }

    public function submit()
    {
        $this->validate([
            'grade_letter' => 'required',
            'min_score' => 'required',
            'max_score' => 'required',
            'description' => 'required',
        ]);

        try {
            DB::beginTransaction();
            RatingScale::updateOrCreate([
                'id' => $this->data_id,
            ], [
                'grade_letter' => $this->grade_letter,
                'min_score' => $this->min_score,
                'max_score' => $this->max_score,
                'description' => $this->description,
            ]);
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!');
            return Log::info('Gagal Menyimpan Data Role : ' . $th);
        }
    }

    public function render()
    {
        $data = RatingScale::query()
            ->when($this->search, function ($query, $search) {
                $query->where('grade_letter', 'like', '%' . $search . '%')
                    ->orWhere('min_score', 'like', '%' . $search . '%')
                    ->orWhere('max_score', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.rating-scale.admin-master-rating-scale-index', [
            'datas' => $data,
        ])
            ->extends('layout.app')
            ->section('content')
        ;
    }
}
