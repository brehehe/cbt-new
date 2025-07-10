<?php

namespace App\Livewire\Admin\Exam\Warning;

use App\Helpers\AlertHelper;
use App\Models\User\UserTimetable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Session;

class AdminExamWarningIndex extends Component
{
    public $user_timetable_id, $userTimetable;

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

        if ($userTimetable->status == 'exam') {
            return redirect()->route('admin.exam.detail');
        }
    }

    public function confirmStartUjian()
    {
        return AlertHelper::confirmInfo('startUjian', 'Apakah yakin ingin memulai ujian?');
    }

    public function startUjian()
    {
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        $userTimetable->update([
            'status' => 'exam',
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
