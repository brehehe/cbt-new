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

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public function setSorting(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function toggleSortDirection(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function mount()
    {
        $this->timetables = Timetable::select('id', 'name')->get()->pluck('name', 'id')->toArray();
    }

    public function getGradeDetail($mark)
    {
        return RatingScale::getGrade($mark);
    }

    public function render()
    {
        $dir = strtolower($this->sortDirection) === 'asc' ? 'asc' : 'desc';

        $userTimetables = UserTimetable::with(['timetable.module', 'user'])
            ->whereHas('timetable') // <-- hanya tampil jika timetable masih ada
            ->where(function ($query) {
                $query->whereHas('timetable', function ($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%');
                })
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'ilike', '%'.$this->search.'%')
                            ->orWhere('email', 'ilike', '%'.$this->search.'%');
                    });
            })
            ->where('user_id', Auth::id())
            ->where('status', 'done');

        if ($this->sortBy === 'timetable') {
            $userTimetables->join('timetables', 'user_timetables.timetable_id', '=', 'timetables.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(timetables.name)) '.$dir);
        } elseif ($this->sortBy === 'mark') {
            $userTimetables->orderBy('user_timetables.mark', $dir);
        } else {
            $userTimetables->orderBy('user_timetables.created_at', $dir);
        }

        $viewName = config('app.new_template', false)
            ? 'livewire.admin.exam.history-timetable.admin-exam-history-timetable-index-new'
            : 'livewire.admin.exam.history-timetable.admin-exam-history-timetable-index';

        $layoutName = config('app.new_template', false)
            ? 'layout.app-horizontal'
            : 'layout.app';

        return view($viewName, [
            'userTimetables' => $userTimetables->paginate($this->perPage),
        ])
            ->extends($layoutName)
            ->section('content');
    }
}
