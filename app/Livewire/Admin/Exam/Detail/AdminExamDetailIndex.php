<?php

namespace App\Livewire\Admin\Exam\Detail;

use App\Helpers\AlertHelper;
use App\Helpers\AuthHelper;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminExamDetailIndex extends Component
{
    public $userTimetableId, $remainingTime, $userTimetable;

    public function mount()
    {
        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }

        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();
        if (!$userTimetable) {
            return redirect()->route('admin.exam.timetable');
        }

        if ($userTimetable->status == 'done') {
            return redirect()->route('admin.exam.timetable');
        }

        if ($userTimetable->status == 'warning') {
            return redirect()->route('admin.exam.warning');
        }

        $this->userTimetableId = $userTimetable->id;
        $this->userTimetable = $userTimetable;
        $this->timeRun();
    }

    public function timeRun()
    {
        $event = UserTimetable::select('id', 'start_exam', 'timetable_id', 'user_id')->with([
            'timetable:id,module_id',
            'timetable.module:id,duration'
        ])
            ->where('id', $this->userTimetableId)
            ->first()
            ->toArray();

        $createdAt = Carbon::parse($event['start_exam']);
        $now = Carbon::now();
        $endTime = $createdAt->addMinutes((int) $event['timetable']['module']['duration']);

        $remainingTime = $endTime->timestamp - $now->timestamp;

        $this->remainingTime = $remainingTime;
    }

    protected $listeners = ['timeExpired'];

    public function timeExpired()
    {
        $this->finishExam();
    }

    public function confirmFinishExam()
    {
        return AlertHelper::confirmWarning('finishExam', 'Apakah Anda Yakin Untuk Menyelesaikan Ujian');
    }

    public function finishExam()
    {
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        $userTimetable->update([
            'status' => 'done',
            'end_exam' => Carbon::now(),
        ]);

        session()->flash('saved', [
            'title' => 'Ujian Telah Selesai!',
            'text' => 'Terima kasih telah mengerjakan ujian.',
        ]);

        return redirect()->route('admin.exam.timetable');
    }

    public function render()
    {
        return view('livewire.admin.exam.detail.admin-exam-detail-index')
            ->extends('layout.detail.app')
            ->section('content');
    }
}
