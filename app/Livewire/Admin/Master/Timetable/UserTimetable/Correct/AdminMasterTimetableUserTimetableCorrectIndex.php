<?php

namespace App\Livewire\Admin\Master\Timetable\UserTimetable\Correct;

use App\Helpers\AlertHelper;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Livewire\Component;

class AdminMasterTimetableUserTimetableCorrectIndex extends Component
{
    public $user_timetable_id;

    public $user_timetable;

    public function mount($user_timetable_id)
    {
        $this->user_timetable_id = $user_timetable_id;
        $this->user_timetable = UserTimetable::withoutGlobalScope('user_scope')->with(['user', 'timetable.module'])->findOrFail($this->user_timetable_id);
    }

    public function setCorrect($id)
    {
        $question = UserModuleQuestion::withoutGlobalScope('user_scope')->findOrFail($id);
        $question->update(['status' => 'correct']);

        $this->user_timetable->recalculateMark();
        AlertHelper::success('Jawaban berhasil ditandai sebagai BENAR');
    }

    public function setWrong($id)
    {
        $question = UserModuleQuestion::withoutGlobalScope('user_scope')->findOrFail($id);
        $question->update(['status' => 'wrong']);

        $this->user_timetable->recalculateMark();
        AlertHelper::success('Jawaban berhasil ditandai sebagai SALAH');
    }

    public function render()
    {
        $essayQuestions = $this->user_timetable->userModuleQuestions()
            ->withoutGlobalScope('user_scope')
            ->whereHas('timetableQuestion', function ($q) {
                $q->withoutGlobalScope('user_scope')->whereRaw('LOWER(TRIM(type)) = ?', ['essay']);
            })
            ->with(['timetableQuestion' => fn($q) => $q->withoutGlobalScope('user_scope')])
            ->get();

        return view('livewire.admin.master.timetable.user-timetable.correct.admin-master-timetable-user-timetable-correct-index', [
            'essayQuestions' => $essayQuestions,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
