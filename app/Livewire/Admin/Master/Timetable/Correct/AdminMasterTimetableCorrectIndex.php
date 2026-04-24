<?php

namespace App\Livewire\Admin\Master\Timetable\Correct;

use App\Models\Master\Timetable\Timetable;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterTimetableCorrectIndex extends Component
{
    use WithPagination;

    public $timetable_id;

    public $timetable;

    public $search = '';

    public $perPage = 10;

    public function mount($timetable_id)
    {
        $this->timetable_id = $timetable_id;
        $this->timetable = Timetable::findOrFail($this->timetable_id);
    }

    public function render()
    {
        $userTimetables = $this->timetable->userTimetables()
            ->search($this->search)
            ->withCount([
                'userModuleQuestions as total_essay' => function ($q) {
                    $q->whereHas('timetableQuestion', function ($sq) {
                        $sq->where('type', 'essay');
                    });
                },
                'userModuleQuestions as pending_essay' => function ($q) {
                    $q->where('status', 'check');
                },
            ])
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.correct.admin-master-timetable-correct-index', [
            'userTimetables' => $userTimetables,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
