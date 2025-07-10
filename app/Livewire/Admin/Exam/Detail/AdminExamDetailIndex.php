<?php

namespace App\Livewire\Admin\Exam\Detail;

use App\Helpers\AlertHelper;
use App\Helpers\AuthHelper;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminExamDetailIndex extends Component
{
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
    }

    public function render()
    {
        return view('livewire.admin.exam.detail.admin-exam-detail-index')
            ->extends('layout.detail.app')
            ->section('content');
    }
}
