<?php

namespace App\Livewire\Admin\Master\Timetable\UserTimetable\Correct;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Models\User\UserTimetable;
use App\Models\User\UserModuleQuestion;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AdminMasterTimetableUserTimetableCorrectIndex extends Component
{
    public $user_timetable_id, $user_timetable;

    public function mount($user_timetable_id)
    {
        $this->user_timetable_id = $user_timetable_id;
        $this->user_timetable = UserTimetable::with(['user', 'timetable.module'])->findOrFail($this->user_timetable_id);
    }

    public function setCorrect($id)
    {
        $question = UserModuleQuestion::findOrFail($id);
        $question->update(['status' => 'correct']);

        $this->user_timetable->recalculateMark();
        AlertHelper::success('Jawaban berhasil ditandai sebagai BENAR');
    }

    public function setWrong($id)
    {
        $question = UserModuleQuestion::findOrFail($id);
        $question->update(['status' => 'wrong']);

        $this->user_timetable->recalculateMark();
        AlertHelper::success('Jawaban berhasil ditandai sebagai SALAH');
    }

    public function render()
    {
        $essayQuestions = $this->user_timetable->userModuleQuestions()
            ->whereHas('timetableQuestion', function ($q) {
                $q->where('type', 'essay');
            })
            ->with('timetableQuestion')
            ->get();

        return view('livewire.admin.master.timetable.user-timetable.correct.admin-master-timetable-user-timetable-correct-index', [
            'essayQuestions' => $essayQuestions
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
