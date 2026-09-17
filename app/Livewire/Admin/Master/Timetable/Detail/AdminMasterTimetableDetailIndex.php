<?php

namespace App\Livewire\Admin\Master\Timetable\Detail;

use App\Exports\TimetableAttendanceExport;
use App\Exports\TimetableDetailExport;
use App\Helpers\AlertHelper;
use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Timetable\TimetableAttendance;
use App\Models\Master\Timetable\TimetableDetail;
use App\Models\User;
use App\Models\User\UserTimetable;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminMasterTimetableDetailIndex extends Component
{
    use WithPagination;

    public $timetable_id;

    public $timetable;

    public $timetableModel;

    public $activeTab = 'attendance'; // 'attendance' or 'scores'

    public $selectedDetailId = null;

    public $search = '';

    public $perPage = 10;

    // Legacy timetable properties
    public $modules = [];

    public $supervisors = [];

    public $module_id;

    public $getSupervisors = [];

    public $start_time = '-';

    public $end_time = '-';

    protected $queryString = [
        'activeTab' => ['except' => 'attendance'],
        'selectedDetailId' => ['except' => null],
        'search' => ['except' => ''],
    ];

    public function mount($timetable_id = null)
    {
        $this->timetable_id = $timetable_id;

        if (! $this->timetable_id) {
            return redirect()->route('admin.master.timetable');
        }

        $timetable = Timetable::with([
            'userTimetables',
            'classmate.classmateStudents.user.userDetail',
            'timetableDetails.module',
            'timetableDetails.digitalBook',
            'timetableDetails.examRoom',
            'timetableDetails.examSession',
            'attendances',
        ])->find($this->timetable_id);

        if (! $timetable) {
            return redirect()->route('admin.master.timetable');
        }

        if (auth()->user()->hasRole(['Pengawas', 'pengawas'])) {
            $supervisors = is_array($timetable->supervisors) ? $timetable->supervisors : (json_decode($timetable->supervisors, true) ?? []);
            $supervisorsStr = array_map('strval', $supervisors);
            if (!in_array((string)auth()->id(), $supervisorsStr)) {
                return redirect()->route('admin.master.timetable');
            }
        }

        $this->timetableModel = $timetable;
        $this->timetable = $timetable->toArray();

        // Handle URL query string
        if (request()->has('tab')) {
            $this->activeTab = request()->get('tab') === 'scores' ? 'scores' : 'attendance';
        } else {
            // Default: if is_lemes and there are attendances or materials, default to attendance
            $this->activeTab = function_exists('is_lemes') && is_lemes() ? 'attendance' : 'scores';
        }

        if (request()->has('detail_id')) {
            $this->selectedDetailId = request()->get('detail_id');
        }

        // Safe time formatting
        $this->start_time = $timetable->start_time ? Carbon::parse($timetable->start_time)->format('d/m/Y H:i') : '-';
        $this->end_time = $timetable->end_time ? Carbon::parse($timetable->end_time)->format('d/m/Y H:i') : '-';

        // Load modules & supervisors for legacy mode
        $this->modules = Module::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $supervisorsData = $timetable->supervisors ?? $timetable['supervisors'] ?? [];
        $this->supervisors = is_array($supervisorsData) ? $supervisorsData : (json_decode($supervisorsData, true) ?: []);
        $this->module_id = $timetable['module_id'] ?? null;
    }

    public function switchTab($tab)
    {
        $this->activeTab = in_array($tab, ['attendance', 'scores']) ? $tab : 'attendance';
        $this->resetPage();
    }

    public function filterDetail($detailId = null)
    {
        $this->selectedDetailId = $detailId;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDetail($id)
    {
        return redirect()->route('admin.master.timetable.answer', [
            'timetable_id' => $this->timetable_id,
            'user_timetable_id' => $id,
        ]);
    }

    public function getGrade($mark)
    {
        if ($mark === null) {
            return '-';
        }

        return RatingScale::where('min_score', '<=', $mark)
            ->where('max_score', '>=', $mark)
            ->orderBy('order')
            ->first()
            ?->grade_letter ?? '-';
    }

    public function markManualAttendance($userId, $status = 'present')
    {
        try {
            if ($status === 'present') {
                TimetableAttendance::updateOrCreate(
                    [
                        'timetable_id' => $this->timetable_id,
                        'timetable_detail_id' => $this->selectedDetailId ?: null,
                        'user_id' => $userId,
                    ],
                    [
                        'scanned_by' => Auth::id(),
                        'attended_at' => now(),
                        'status' => 'present',
                        'method' => 'manual',
                        'company_id' => Auth::user()->company_id,
                    ]
                );

                $this->dispatch('swal:alert', [
                    'type' => 'success',
                    'title' => 'Presensi Dicatat',
                    'text' => 'Kehadiran peserta berhasil ditandai secara manual.',
                ]);
            } else {
                TimetableAttendance::where('timetable_id', $this->timetable_id)
                    ->when($this->selectedDetailId, fn($q) => $q->where('timetable_detail_id', $this->selectedDetailId))
                    ->where('user_id', $userId)
                    ->delete();

                $this->dispatch('swal:alert', [
                    'type' => 'success',
                    'title' => 'Presensi Dibatalkan',
                    'text' => 'Status kehadiran peserta telah dibatalkan.',
                ]);
            }
        } catch (\Throwable $th) {
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan: '.$th->getMessage(),
            ]);
        }
    }

    public function exportAttendancePdf()
    {
        $timetable = Timetable::with(['classmate.classmateStudents.user.userDetail', 'attendances'])->find($this->timetable_id);
        if (! $timetable || ! $timetable->classmate) {
            return AlertHelper::error('Gagal', 'Data kelas tidak ditemukan.');
        }

        $attendedRecords = $timetable->attendances
            ->when($this->selectedDetailId, fn($q) => $q->where('timetable_detail_id', $this->selectedDetailId))
            ->keyBy('user_id');

        $students = [];
        foreach ($timetable->classmate->classmateStudents as $cs) {
            if ($cs->user) {
                $user = $cs->user;
                if ($this->search) {
                    $s = strtolower($this->search);
                    $matches = str_contains(strtolower($user->name), $s) ||
                               str_contains(strtolower($user->username ?? ''), $s) ||
                               str_contains(strtolower($user->nim ?? ''), $s);
                    if (! $matches) {
                        continue;
                    }
                }

                $hasAttended = isset($attendedRecords[$user->id]);
                $att = $attendedRecords[$user->id] ?? null;

                $students[] = [
                    'name' => $user->name,
                    'username' => $user->nim ?? ($user->username ?? '-'),
                    'has_attended' => $hasAttended,
                    'attended_at' => $att?->attended_at ? Carbon::parse($att->attended_at)->format('d/m/Y H:i') : '-',
                    'method' => $att?->method ? ucfirst($att->method) : '-',
                ];
            }
        }

        $detailModel = $this->selectedDetailId ? TimetableDetail::find($this->selectedDetailId) : null;
        $detailInfo = $detailModel ? ($detailModel->examRoom?->name.' - '.$detailModel->examSession?->name) : null;

        $total = count($students);
        $present = collect($students)->where('has_attended', true)->count();
        $absent = $total - $present;
        $percent = $total > 0 ? round(($present / $total) * 100) : 0;

        $pdf = Pdf::loadView('livewire.admin.master.timetable.detail.admin-master-timetable-attendance-pdf', [
            'timetable' => $this->timetable,
            'classmateName' => $timetable->classmate?->name,
            'detailInfo' => $detailInfo,
            'students' => $students,
            'stats' => [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'percent' => $percent,
            ],
        ])->setPaper('a4', 'portrait');

        $fileName = 'rekap-kehadiran-'.($this->timetable['name'] ?? 'jadwal').'-'.date('YmdHis').'.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function exportAttendanceExcel()
    {
        try {
            $fileName = 'rekap-kehadiran-'.($this->timetable['name'] ?? 'jadwal').'-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new TimetableAttendanceExport($this->timetable_id, $this->selectedDetailId, $this->search),
                $fileName
            );
        } catch (\Exception $e) {
            Log::error('Attendance Export Error: '.$e->getMessage());
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengekspor rekap kehadiran ke Excel: '.$e->getMessage(),
            ]);
        }
    }

    public function exportPdf()
    {
        $userTimetables = UserTimetable::search($this->search)
            ->where('timetable_id', $this->timetable_id)
            ->with(['user', 'timetable', 'userModuleQuestions'])
            ->get();

        $ratingScales = RatingScale::orderBy('order')->get();
        $gradeMap = [];
        $countMap = [];

        foreach ($userTimetables as $userTimetable) {
            $mark = $userTimetable->mark;
            $grade = '-';
            if ($mark !== null) {
                $scale = $ratingScales->first(function ($item) use ($mark) {
                    return $item->min_score <= $mark && $item->max_score >= $mark;
                });
                $grade = $scale?->grade_letter ?? '-';
            }

            $countMap[$userTimetable->id] = [
                'total' => $userTimetable->userModuleQuestions->count(),
                'answered' => $userTimetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count(),
                'unanswered' => $userTimetable->userModuleQuestions->whereNull('timetable_answer_id')->count(),
                'correct' => $userTimetable->userModuleQuestions->where('status', 'correct')->count(),
                'wrong' => $userTimetable->userModuleQuestions->where('status', 'wrong')->count(),
            ];

            $gradeMap[$userTimetable->id] = $grade;
        }

        $pdf = Pdf::loadView('livewire.admin.master.timetable.detail.admin-master-timetable-detail-pdf', [
            'timetable' => $this->timetable,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'userTimetables' => $userTimetables,
            'countMap' => $countMap,
            'gradeMap' => $gradeMap,
        ])->setPaper('a4', 'landscape');

        $fileName = 'nilai-ujian-'.($this->timetable_id ?? 'timetable').'.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function exportExcel()
    {
        try {
            $fileName = 'nilai-ujian-'.($this->timetable['name'] ?? 'detail').'-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new TimetableDetailExport($this->timetable_id, $this->search),
                $fileName
            );
        } catch (\Exception $e) {
            Log::error('Timetable Detail Export Error: '.$e->getMessage());
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengekspor data nilai ke Excel.',
            ]);
        }
    }

    public function render()
    {
        // 1. Exam Scores Query (Always available for scores tab or legacy view)
        $userTimetablesQuery = UserTimetable::search($this->search)
            ->where('timetable_id', $this->timetable_id)
            ->with(['user', 'timetable', 'userModuleQuestions']);

        $userTimetables = $userTimetablesQuery->paginate($this->perPage);

        // 2. Attendance Query (For LEMES attendance tab)
        $paginatedAttendance = null;
        $attendanceStats = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'percent' => 0,
        ];

        $timetable = Timetable::with([
            'classmate.classmateStudents.user.userDetail',
            'timetableDetails.module',
            'timetableDetails.digitalBook',
            'timetableDetails.examRoom',
            'timetableDetails.examSession',
            'attendances',
        ])->find($this->timetable_id);

        if ($timetable && $timetable->classmate) {
            $attendedRecords = $timetable->attendances
                ->when($this->selectedDetailId, fn($q) => $q->where('timetable_detail_id', $this->selectedDetailId))
                ->keyBy('user_id');

            $allStudents = collect();
            foreach ($timetable->classmate->classmateStudents as $cs) {
                if ($cs->user) {
                    $user = $cs->user;
                    if ($this->search) {
                        $s = strtolower($this->search);
                        $matches = str_contains(strtolower($user->name), $s) ||
                                   str_contains(strtolower($user->username ?? ''), $s) ||
                                   str_contains(strtolower($user->nim ?? ''), $s);
                        if (! $matches) {
                            continue;
                        }
                    }

                    $hasAttended = isset($attendedRecords[$user->id]);
                    $att = $attendedRecords[$user->id] ?? null;

                    $allStudents->push([
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->nim ?? ($user->username ?? '-'),
                        'has_attended' => $hasAttended,
                        'attended_at' => $att?->attended_at ? Carbon::parse($att->attended_at)->format('d/m/Y H:i') : '-',
                        'method' => $att?->method ? ucfirst($att->method) : '-',
                    ]);
                }
            }

            $totalCount = $allStudents->count();
            $presentCount = $allStudents->where('has_attended', true)->count();
            $absentCount = $totalCount - $presentCount;
            $percent = $totalCount > 0 ? round(($presentCount / $totalCount) * 100) : 0;

            $attendanceStats = [
                'total' => $totalCount,
                'present' => $presentCount,
                'absent' => $absentCount,
                'percent' => $percent,
            ];

            // Paginate collection
            $page = $this->getPage();
            $paginatedAttendance = new LengthAwarePaginator(
                $allStudents->forPage($page, $this->perPage),
                $totalCount,
                $this->perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('livewire.admin.master.timetable.detail.admin-master-timetable-detail-index', [
            'userTimetables' => $userTimetables,
            'timetableModel' => $timetable,
            'attendanceList' => $paginatedAttendance,
            'attendanceStats' => $attendanceStats,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
