<?php

namespace App\Exports;

use App\Models\Master\RatingScale\RatingScale;
use App\Models\User\UserTimetable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimetableDetailExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $timetable_id;

    protected $search;

    protected $ratingScales;

    public function __construct($timetable_id, $search = '')
    {
        $this->timetable_id = $timetable_id;
        $this->search = $search;
        $this->ratingScales = RatingScale::orderBy('order')->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return UserTimetable::search($this->search)
            ->where('timetable_id', $this->timetable_id)
            ->with(['user', 'timetable', 'userModuleQuestions'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIM',
            'Nama',
            'Terjawab',
            'Tidak Terjawab',
            'Benar',
            'Salah',
            'Nilai',
            'Grade',
        ];
    }

    /**
     * @param  mixed  $userTimetable
     */
    public function map($userTimetable): array
    {
        static $no = 0;
        $no++;

        $answered = $userTimetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count();
        $unanswered = $userTimetable->userModuleQuestions->whereNull('timetable_answer_id')->count();
        $correct = $userTimetable->userModuleQuestions->where('status', 'correct')->count();
        $wrong = $userTimetable->userModuleQuestions->where('status', 'wrong')->count();

        $mark = $userTimetable->mark;
        $grade = '-';
        if ($mark !== null) {
            $scale = $this->ratingScales->first(function ($item) use ($mark) {
                return $item->min_score <= $mark && $item->max_score >= $mark;
            });
            $grade = $scale?->grade_letter ?? '-';
        }

        return [
            $no,
            $userTimetable->user->nim ?? ($userTimetable->user->username ?? '-'),
            $userTimetable->user->name ?? '-',
            $answered,
            $unanswered,
            $correct,
            $wrong,
            $mark ?? 0,
            $grade,
        ];
    }
}
