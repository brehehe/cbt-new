<?php

namespace App\Http\Middleware;

use App\Models\User\UserTimetable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserTimetable
{
    public function handle(Request $request, Closure $next)
    {
        // Skip check jika user belum login
        if (!Auth::check()) {
            return $next($request);
        }

        // Skip check jika sudah berada di route tujuan atau route auth
        $currentRoute = $request->route()->getName();
        if (in_array($currentRoute, [
            'admin.exam.detail', 
            'admin.exam.warning', 
            'login', 
            'logout', 
            'register', 
            'password.request', 
            'password.reset'
        ])) {
            return $next($request);
        }

        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        if ($userTimetable) {
            // Check for timeout
            if ($userTimetable->start_exam) {
                // Eager load necessary relationships if not already loaded
                $userTimetable->load(['timetable.module:id,duration']);
                
                $startTime = \Illuminate\Support\Carbon::parse($userTimetable->start_exam);
                $duration = $userTimetable->timetable->module->duration ?? 60;
                $pauseSeconds = (int) ($userTimetable->pause_total_seconds ?? 0);
                
                // Add a small buffer (e.g., 30 seconds) to account for network latency
                $endTime = $startTime->addMinutes($duration)->addSeconds($pauseSeconds)->addSeconds(30);

                if (now()->greaterThan($endTime)) {
                    // Time expired - Finalize Exam Logic
                    $userTimetableQuestions = \App\Models\User\UserModuleQuestion::where('user_timetable_id', $userTimetable->id)->get();
                    
                    $totalQuestions = $userTimetableQuestions->count();
                    $correctAnswers = 0;

                    foreach ($userTimetableQuestions as $question) {
                        if ($question->timetable_answer_id) {
                            $answer = \App\Models\Timetable\TimetableAnswer::find($question->timetable_answer_id);
                            if ($answer && $answer->is_correct) {
                                $question->update(['status' => 'correct']);
                                $correctAnswers++;
                            } else {
                                $question->update(['status' => 'wrong']);
                            }
                        } else {
                            $question->update(['status' => 'unanswered']);
                        }
                    }

                    $mark = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
                    $mark = min($mark, 100);

                    $userTimetable->update([
                        'status' => 'done',
                        'end_exam' => now(),
                        'mark' => $mark,
                    ]);
                    
                    // Add flash message
                    session()->flash('saved', [
                        'title' => 'Ujian Telah Selesai!',
                        'text' => "Waktu ujian telah habis. Terima kasih telah mengerjakan ujian.",
                    ]);

                    return redirect()->route('admin.exam.timetable');
                }
            }

            if ($userTimetable->status === 'exam') {
                return redirect()->route('admin.exam.detail');
            }

            if ($userTimetable->status === 'warning') {
                return redirect()->route('admin.exam.warning');
            }
        }

        return $next($request);
    }
}
