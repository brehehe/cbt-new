<?php

namespace App\Livewire\Admin\Exam\Warning;

use App\Helpers\AlertHelper;
use App\Models\Master\Regulation\Regulation;
use App\Models\User\UserTimetable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Carbon;

class AdminExamWarningIndex extends Component
{
    public $user_timetable_id, $userTimetable, $regulations = [];

    public function mount()
    {
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$userTimetable) {
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

        session()->flash('saved', [
            'title' => 'Ujian Telah Dimulai!',
            'text' => 'Anda berhasil memulai ujian!',
        ]);

        return redirect()->route('admin.exam.detail');
    }

    public function render()
    {
        return view('livewire.admin.exam.warning.admin-exam-warning-index')
            ->extends('layout.warning')
            ->section('content');
    }
}
