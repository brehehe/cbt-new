<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Classmate\ClassmateStudent;
use App\Models\Company\Company;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Master\Timetable\Timetable;

class PrintController extends Controller
{
    //
    public function printDaftarHadir($session_id)
    {
        $timetable = Timetable::with(['examRoom', 'examSession', 'company'])->findOrFail($session_id);

        $company = null;
        if (auth()->check() && auth()->user()->company_id) {
            $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')
                ->where('id', auth()->user()->company_id)
                ->first();
        }
        if (! $company && $timetable->company_id) {
            $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')
                ->where('id', $timetable->company_id)
                ->first();
        }
        if (! $company) {
            $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')->first();
        }

        $exam_room = $timetable->examRoom ?? ExamRoom::find($timetable->exam_room_id);
        $exam_session = $timetable->examSession ?? ExamSession::find($timetable->exam_session_id);

        $classmateDetail = ClassmateStudent::where('classmate_id', $timetable->classmate_id)
            ->with(['user.userDetail'])
            ->get()
            ->sortBy(fn ($item) => strtolower($item->user?->name ?? ''))
            ->values();

        if ($classmateDetail->isEmpty()) {
            $classmateDetail = \App\Models\User\UserTimetable::where('timetable_id', $timetable->id)
                ->with(['user.userDetail'])
                ->get()
                ->sortBy(fn ($item) => strtolower($item->user?->name ?? ''))
                ->values();
        }

        return view('print.daftar-hadir', [
            'session_id' => $session_id,
            'company' => $company,
            'timetable' => $timetable,
            'exam_room' => $exam_room,
            'exam_session' => $exam_session,
            'classmateDetail' => $classmateDetail,
        ]);
    }

    public function printBeritaAcara($session_id)
    {
        $timetable = Timetable::with(['module.questionType', 'timetableModule.questionType', 'examRoom', 'examSession', 'company'])->findOrFail($session_id);

        $company = null;
        if (auth()->check() && auth()->user()->company_id) {
            $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')
                ->where('id', auth()->user()->company_id)
                ->first();
        }
        if (! $company && $timetable->company_id) {
            $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')
                ->where('id', $timetable->company_id)
                ->first();
        }
        if (! $company) {
            $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')->first();
        }

        $exam_room = $timetable->examRoom ?? ExamRoom::find($timetable->exam_room_id);
        $exam_session = $timetable->examSession ?? ExamSession::find($timetable->exam_session_id);

        $classmateDetail = ClassmateStudent::where('classmate_id', $timetable->classmate_id)
            ->with(['user.userDetail'])
            ->get()
            ->sortBy(fn ($item) => strtolower($item->user?->name ?? ''))
            ->values();

        if ($classmateDetail->isEmpty()) {
            $classmateDetail = \App\Models\User\UserTimetable::where('timetable_id', $timetable->id)
                ->with(['user.userDetail'])
                ->get()
                ->sortBy(fn ($item) => strtolower($item->user?->name ?? ''))
                ->values();
        }

        return view('print.berita-acara', [
            'session_id' => $session_id,
            'company' => $company,
            'timetable' => $timetable,
            'exam_room' => $exam_room,
            'exam_session' => $exam_session,
            'classmateDetail' => $classmateDetail,
        ]);
    }
}
