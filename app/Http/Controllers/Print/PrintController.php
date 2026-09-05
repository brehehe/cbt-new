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
        $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')->where('id', auth()->user()->company_id)->first();
        $timetable = Timetable::find($session_id);
        $exam_room = ExamRoom::find($timetable->exam_room_id);
        $exam_session = ExamSession::find($timetable->exam_session_id);
        $classmateDetail = ClassmateStudent::where('classmate_id', $timetable->classmate_id)->with('user')->get();

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
        $company = Company::select('id', 'name', 'logo_potrait', 'code_name', 'code_region', 'region')->where('id', auth()->user()->company_id)->first();
        $timetable = Timetable::with(['module.questionType', 'timetableModule.questionType'])->find($session_id);
        $exam_room = ExamRoom::find($timetable->exam_room_id);
        $exam_session = ExamSession::find($timetable->exam_session_id);
        $classmateDetail = ClassmateStudent::where('classmate_id', $timetable->classmate_id)->with('user')->get();

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
