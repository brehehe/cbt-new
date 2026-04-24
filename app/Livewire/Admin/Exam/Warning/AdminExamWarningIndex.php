<?php

namespace App\Livewire\Admin\Exam\Warning;

use App\Helpers\AlertHelper;
use App\Models\Exam\ExamLiveSession;
use App\Models\Master\Regulation\Regulation;
use App\Models\User\UserTimetable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminExamWarningIndex extends Component
{
    public $user_timetable_id;

    public $userTimetable;

    public $regulations = [];

    public $camera_device_id;

    public function mount()
    {
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $userTimetable) {
            return redirect()->route('admin.exam.timetable');
        }

        if ($userTimetable->status == 'done') {
            return redirect()->route('admin.exam.timetable');
        }

        if ($userTimetable->status == 'exam') {
            return redirect()->route('admin.exam.detail');
        }

        $this->userTimetable = $userTimetable;

        $this->regulations = Regulation::select('description', 'type')
            ->orderBy('type', 'desc')
            ->get()
            // ->pluck('type', 'description')
            ->toArray();

        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }
    }

    public function confirmStartUjian()
    {
        return AlertHelper::confirmWarning('startUjian', 'Apakah yakin ingin memulai ujian?');
    }

    public function startUjian()
    {
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        $userTimetable->update([
            'status' => 'exam',
            'start_exam' => Carbon::now(),
        ]);

        // Create or update ExamLiveSession with camera_device_id
        ExamLiveSession::updateOrCreate(
            [
                'user_timetable_id' => $userTimetable->id,
                'user_id' => Auth::id(),
            ],
            [
                'timetable_id' => $userTimetable->timetable_id,
                'company_id' => $userTimetable->company_id,
                'camera_device_id' => $this->camera_device_id,
                'is_active' => true, // Mark as active so Detail page picks it up
                'last_activity' => Carbon::now(),
                'session_metadata' => [
                    'start_time' => Carbon::now()->toISOString(),
                    'user_agent' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                    'session_id' => session()->getId(),
                ],
            ]
        );

        session()->flash('saved', [
            'title' => 'Ujian Telah Dimulai!',
            'text' => 'Anda berhasil memulai ujian!',
        ]);

        return redirect()->route('admin.exam.detail.react', [
            'userTimetableId' => $userTimetable->id,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.exam.warning.admin-exam-warning-index')
            ->extends('layout.warning')
            ->section('content');
    }
}
