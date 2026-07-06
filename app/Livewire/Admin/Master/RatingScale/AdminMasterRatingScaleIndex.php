<?php

namespace App\Livewire\Admin\Master\RatingScale;

use App\Helpers\AlertHelper;
use App\Models\Master\RatingScale\RatingScale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminMasterRatingScaleIndex extends Component
{
    public $search = '';

    public $data_id;

    public $grade_letter;

    public $min_score;

    public $max_score;

    public $description;

    // Inline add row properties
    public $new_grade_letter = '';

    public $new_min_score = '';

    public $new_description = '';

    public $calculated_max_score = null;

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
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = RatingScale::find($id[0]);
            $data->delete();
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal dihapus!');

            return Log::info('Gagal Menghapus Data Role : '.$th);
        }
    }

    public function submit()
    {
        $this->validate([
            'grade_letter' => 'required',
            'min_score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0|gte:min_score',
            'description' => 'required',
        ], [
            'max_score.gte' => 'Nilai Maksimum harus lebih besar atau sama dengan Nilai Minimum.',
        ]);

        // Check for overlapping ranges
        $overlap = RatingScale::where(function ($query) {
            $query->where(function ($q) {
                $q->where('min_score', '<=', $this->min_score)
                    ->where('max_score', '>=', $this->min_score);
            })->orWhere(function ($q) {
                $q->where('min_score', '<=', $this->max_score)
                    ->where('max_score', '>=', $this->max_score);
            })->orWhere(function ($q) {
                $q->where('min_score', '>=', $this->min_score)
                    ->where('max_score', '<=', $this->max_score);
            });
        })
            ->when($this->data_id, function ($query) {
                $query->where('id', '!=', $this->data_id);
            })
            ->where('company_id', auth()->user()->company_id)
            ->first();

        if ($overlap) {
            $this->addError('min_score', 'Rentang nilai tumpang tindih dengan Grade '.$overlap->grade_letter.' ('.$overlap->min_score.'-'.$overlap->max_score.')');

            return;
        }

        try {
            DB::beginTransaction();
            RatingScale::updateOrCreate([
                'id' => $this->data_id,
            ], [
                'grade_letter' => $this->grade_letter,
                'min_score' => $this->min_score,
                'max_score' => $this->max_score,
                'description' => $this->description,
                'company_id' => auth()->user()->company_id,
            ]);
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!');

            return Log::info('Gagal Menyimpan Data Rating Scale : '.$th);
        }
    }

    public function openInfoModal()
    {
        $this->dispatch('openModalRatingScaleInfo');
    }

    public function closeInfoModal()
    {
        $this->dispatch('closeModalRatingScaleInfo');
    }

    public function updatedNewMinScore($value)
    {
        if ($value === '' || ! is_numeric($value)) {
            $this->calculated_max_score = null;

            return;
        }

        $minScore = (float) $value;

        // Find the next higher min_score from existing data
        $nextEntry = RatingScale::where('company_id', auth()->user()->company_id)
            ->where('min_score', '>', $minScore)
            ->orderBy('min_score', 'asc')
            ->first();

        $this->calculated_max_score = $nextEntry ? $nextEntry->min_score : 100;
    }

    public function storeInline()
    {
        $this->validate([
            'new_grade_letter' => 'required|string|max:10',
            'new_min_score' => 'required|numeric|min:0|max:100',
            'new_description' => 'required|string|max:255',
        ], [
            'new_grade_letter.required' => 'Grade wajib diisi.',
            'new_min_score.required' => 'Nilai wajib diisi.',
            'new_min_score.numeric' => 'Nilai harus berupa angka.',
            'new_description.required' => 'Deskripsi wajib diisi.',
        ]);

        $minScore = (float) $this->new_min_score;

        // Recalculate max_score fresh from DB
        $nextEntry = RatingScale::where('company_id', auth()->user()->company_id)
            ->where('min_score', '>', $minScore)
            ->orderBy('min_score', 'asc')
            ->first();

        $maxScore = $nextEntry ? $nextEntry->min_score : 100;

        // Check overlap
        $overlap = RatingScale::where('company_id', auth()->user()->company_id)
            ->where('grade_letter', $this->new_grade_letter)
            ->first();

        if ($overlap) {
            $this->addError('new_grade_letter', 'Grade '.$this->new_grade_letter.' sudah ada.');

            return;
        }

        try {
            DB::beginTransaction();
            RatingScale::create([
                'grade_letter' => strtoupper(trim($this->new_grade_letter)),
                'min_score' => $minScore,
                'max_score' => $maxScore,
                'description' => $this->new_description,
                'company_id' => auth()->user()->company_id,
            ]);
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil ditambahkan!');
            $this->reset(['new_grade_letter', 'new_min_score', 'new_description', 'calculated_max_score']);
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!');
            Log::info('Gagal Menyimpan Data Rating Scale : '.$th);
        }
    }

    public function render()
    {
        $data = RatingScale::query()
            ->when($this->search, function ($query, $search) {
                $query->where('grade_letter', 'like', '%'.$search.'%')
                    ->orWhere('min_score', 'like', '%'.$search.'%')
                    ->orWhere('max_score', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            })
            ->orderBy('order', 'asc')
            ->get();

        return view('livewire.admin.master.rating-scale.admin-master-rating-scale-index', [
            'datas' => $data,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
