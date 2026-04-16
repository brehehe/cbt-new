<?php

namespace App\Livewire\Admin\Exam\HistoryTimetable;

use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserTimetable;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminExamHistoryTimetableIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // atau 'tailwind' sesuai UI

    protected $queryString = [
        // 'page' => ['except' => 1],
        'search' => ['except' => ''],
    ];

    public $perPage = 5;
    public $data_id;
    public $search;
    public $timetable_id;
    public $timetables = [];

    public function mount()
    {
        $this->timetables = Timetable::select('id', 'name')->get()->pluck('name', 'id')->toArray();
    }

    public function getGradeDetail($mark)
    {
        if ($mark === null) {
            return null;
        }

        return RatingScale::where('min_score', '<=', $mark)
            ->where('max_score', '>=', $mark)
            ->orderBy('order')
            ->first();
    }

    public function render()
    {
        $userTimetables = UserTimetable::with(['timetable', 'user'])
            ->whereHas('timetable') // <-- hanya tampil jika timetable masih ada
            ->where(function ($query) {
                $query->whereHas('timetable', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->where('user_id', Auth::id())
            ->where('status', 'done')
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.exam.history-timetable.admin-exam-history-timetable-index', [
            'userTimetables' => $userTimetables->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }

}
