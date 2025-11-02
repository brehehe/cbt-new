<?php

use App\Http\Middleware\CheckUserTimetable;
use App\Livewire\Admin\Master\Classmate\AdminMasterClassmateIndex;
use App\Livewire\Admin\Master\Classmate\Detail\AdminMasterClassmateDetailIndex;
use App\Livewire\Admin\Master\ExamRoom\AdminMasterExamRoomIndex;
use App\Livewire\Admin\Master\ExamSession\AdminMasterExamSessionIndex;
use App\Livewire\Admin\Master\ExamType\AdminMasterExamTypeIndex;
use App\Livewire\Admin\Master\Material\AdminMasterMaterialIndex;
use App\Livewire\Admin\Master\MaterialCategory\AdminMasterMaterialCategoryIndex;
use App\Livewire\Admin\Master\Module\AdminMasterModuleIndex;
use App\Livewire\Admin\Master\Module\AdminMasterModuleQuestionIndex;
use App\Livewire\Admin\Master\Question\AdminMasterQuestionIndex;
use App\Livewire\Admin\Master\Question\AdminMasterQuestionUpdate;
use App\Livewire\Admin\Master\QuestionType\AdminMasterQuestionTypeIndex;
use App\Livewire\Admin\Master\Topic\AdminMasterTopicIndex;
use App\Livewire\Admin\Report\ItemAnalysis\AdminReportItemAnalysisIndex;
use App\Livewire\Admin\Report\ItemAnalysis\Detail\AdminReportItemAnalysisDetailIndex;
use App\Livewire\Admin\Report\Question\AdminReportQuestionIndex;
use App\Livewire\Admin\Report\Timetable\AdminReportTimetable;
use App\Livewire\Admin\Report\Timetable\AdminReportTimetableDetail;
use App\Livewire\Admin\Report\Timetable\AdminReportTimetableIndex;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';

Route::group(['namespace' => 'App\Livewire\Auth'], function () {
    // Add your routes here
    Route::get('login', 'Login\AuthLoginIndex')->name('login');
    Route::get('register', 'Register\AuthRegisterIndex')->name('register');
});

// Generic dashboard route - will redirect to role-specific dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('dosen')) {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->hasRole('mahasiswa')) {
        return redirect()->route('mahasiswa.dashboard');
    } elseif ($user->hasRole('pengawas')) {
        return redirect()->route('pengawas.dashboard');
    }

    // Default fallback
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

// Admin Dashboard Routes
Route::group(['namespace' => 'App\Livewire\Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'verified', CheckUserTimetable::class]], function () {
    Route::get('/', 'Dashboard\AdminDashboardIndex')->name('admin.dashboard');

    // Debug route for video testing
    Route::get('/debug-video', function () {
        return view('debug_video_frontend');
    })->name('admin.debug.video');

    // User Management Routes
    Route::get('/students', \App\Livewire\Admin\StudentManagement::class)->name('admin.students');
    Route::get('/lecturers', \App\Livewire\Admin\LecturerManagement::class)->name('admin.lecturers');

    Route::group(['namespace' => 'Profile', 'prefix' => 'profile'], function () {
        Route::get('/profile', 'AdminProfileIndex')->name('user.profile.profile');
    });

    Route::group(['namespace' => 'ChangePassword', 'prefix' => 'change-password'], function () {
        Route::get('/change-password', 'AdminChangePasswordIndex')->name('user.change-password.change-password');
    });

    Route::group(['namespace' => 'Exam', 'prefix' => 'exam'], function () {
        Route::get('/timetable', 'Timetable\AdminExamTimetableIndex')->name('admin.exam.timetable');
        Route::get('/history-timetable', 'HistoryTimetable\AdminExamHistoryTimetableIndex')->name('admin.exam.history-timetable');
        Route::get('/history-timetable/{timetable_id}/{user_timetable_id}', 'HistoryTimetable\Detail\AdminExamHistoryTimetableDetailIndex')->name('admin.exam.history-timetable.detail');
        Route::get('/warning', 'Warning\AdminExamWarningIndex')->name('admin.exam.warning');
        Route::get('/detail', \App\Livewire\Admin\Exam\Detail\AdminExamDetailIndex::class)->name('admin.exam.detail');
        Route::get('/monitor', \App\Livewire\Admin\Exam\Monitor\AdminExamMonitorIndex::class)->name('admin.exam.monitor');
        Route::get('/monitor/{session}', \App\Livewire\Admin\Exam\Monitor\AdminExamMonitorDetailIndex::class)->name('admin.exam.monitor.detail');
        Route::get('/live-stream', \App\Livewire\Admin\Exam\LiveStream\AdminExamLiveStreamIndex::class)->name('admin.exam.live-stream');
    });

    Route::group(['namespace' => 'Master', 'prefix' => 'master'], function () {
        Route::get('/role', 'Role\AdminMasterRoleIndex')->name('admin.master.role');
        Route::get('/user', 'User\AdminMasterUserIndex')->name('admin.master.user');
        Route::get('/setting', 'Setting\AdminMasterSettingIndex')->name('admin.master.setting');
        Route::get('/topic-question', AdminMasterTopicIndex::class)->name('admin.master.topic');
        Route::get('/material-category', AdminMasterMaterialCategoryIndex::class)->name('admin.master.material-category');
        Route::get('/rating-scale', 'RatingScale\AdminMasterRatingScaleIndex')->name('admin.master.rating-scale');
        Route::get('/regulation', 'Regulation\AdminMasterRegulationIndex')->name('admin.master.regulation');
        Route::get('/admin', 'Admin\AdminMasterAdminIndex')->name('admin.master.admin');
        Route::get('/lecturer', 'Lecturer\AdminMasterLecturerIndex')->name('admin.master.lecturer');
        Route::get('/student', 'Student\AdminMasterStudentIndex')->name('admin.master.student');
        Route::get('/supervisor', 'Supervisor\AdminMasterSupervisorIndex')->name('admin.master.supervisor');
        Route::get('/study', 'Study\AdminMasterStudyIndex')->name('admin.master.study');
        Route::get('/timetable', 'Timetable\AdminMasterTimetableIndex')->name('admin.master.timetable');
        Route::get('/timetable/{timetable_id}/detail', 'Timetable\Detail\AdminMasterTimetableDetailIndex')->name('admin.master.timetable.detail');
        Route::get('/timetable/{timetable_id}/video', 'Timetable\Video\AdminMasterTimetableVideoIndex')->name('admin.master.timetable.video');
        Route::get('/timetable/{timetable_id}/alert', 'Timetable\Alert\AdminMasterTimetableAlertIndex')->name('admin.master.timetable.alert');
        Route::get('/timetable/{timetable_id}/streaming', 'Timetable\Streaming\AdminMasterTimetableStreamingIndex')->name('admin.master.timetable.streaming');
        Route::get('/timetable/{timetable_id}/{user_timetable_id}/answer', 'Timetable\Answer\AdminMasterTimetableAnswerIndex')->name('admin.master.timetable.answer');
        Route::get('/material', AdminMasterMaterialIndex::class)->name('admin.master.material');
        Route::get('/question-type', AdminMasterQuestionTypeIndex::class)->name('admin.master.question-type');
        Route::get('/exam-type', AdminMasterExamTypeIndex::class)->name('admin.master.exam-type');
        Route::get('/exam-room', AdminMasterExamRoomIndex::class)->name('admin.master.exam-room');
        Route::get('/exam-session', AdminMasterExamSessionIndex::class)->name('admin.master.exam-session');
        Route::get('/module', AdminMasterModuleIndex::class)->name('admin.master.module');
        Route::get('/module-question/{id}', AdminMasterModuleQuestionIndex::class)->name('admin.master.module-question');
        Route::get('/question', AdminMasterQuestionIndex::class)->name('admin.master.question');
        Route::get('/question/{id}', AdminMasterQuestionUpdate::class)->name('admin.master.question.update');

        Route::get('/classmate', AdminMasterClassmateIndex::class)->name('admin.master.classmate');
        Route::get('/classmate/{id}/detail', AdminMasterClassmateDetailIndex::class)->name('admin.master.classmate.detail');
    });

    Route::group(['namespace' => 'Report', 'prefix' => 'report'], function () {
        Route::get('/timetable', AdminReportTimetableIndex::class)->name('admin.report.timetable');
        Route::get('/timetable-detail/{id}', AdminReportTimetableDetail::class)->name('admin.report.timetable-detail');
        Route::get('/question', AdminReportQuestionIndex::class)->name('admin.report.question');
        Route::get('/item-analysis', AdminReportItemAnalysisIndex::class)->name('admin.report.item-analysis');
        Route::get('/item-analysis/{id}/detail', AdminReportItemAnalysisDetailIndex::class)->name('admin.report.item-analysis.detail');
    });
});

// Dosen Dashboard Routes
Route::group(['namespace' => 'App\Livewire\Dosen', 'prefix' => 'dosen', 'middleware' => ['auth', 'verified', CheckUserTimetable::class]], function () {
    Route::get('/', 'Dashboard\DosenDashboardIndex')->name('dosen.dashboard');

    // Route::group(['prefix' => 'exam'], function () {
    //     Route::get('/create', 'Exam\DosenExamCreate')->name('dosen.exam.create');
    //     Route::get('/manage', 'Exam\DosenExamManage')->name('dosen.exam.manage');
    //     Route::get('/monitor', 'Exam\DosenExamMonitor')->name('dosen.exam.monitor');
    //     Route::get('/results', 'Exam\DosenExamResults')->name('dosen.exam.results');
    // });

    // Route::group(['prefix' => 'question'], function () {
    //     Route::get('/', 'Question\DosenQuestionIndex')->name('dosen.question.index');
    //     Route::get('/create', 'Question\DosenQuestionCreate')->name('dosen.question.create');
    // });

    // Route::group(['prefix' => 'report'], function () {
    //     Route::get('/analysis', 'Report\DosenReportAnalysis')->name('dosen.report.analysis');
    //     Route::get('/grades', 'Report\DosenReportGrades')->name('dosen.report.grades');
    // });
});

// Mahasiswa Dashboard Routes
Route::group(['namespace' => 'App\Livewire\Mahasiswa', 'prefix' => 'mahasiswa', 'middleware' => ['auth', 'verified', CheckUserTimetable::class]], function () {
    Route::get('/', 'Dashboard\MahasiswaDashboardIndex')->name('mahasiswa.dashboard');

    // Route::group(['prefix' => 'exam'], function () {
    //     Route::get('/schedule', 'Exam\MahasiswaExamSchedule')->name('mahasiswa.exam.schedule');
    //     Route::get('/take/{id}', 'Exam\MahasiswaExamTake')->name('mahasiswa.exam.take');
    //     Route::get('/results', 'Exam\MahasiswaExamResults')->name('mahasiswa.exam.results');
    // });

    // Route::get('/results', 'Result\MahasiswaResultIndex')->name('mahasiswa.results');
    // Route::get('/profile', 'Profile\MahasiswaProfileIndex')->name('mahasiswa.profile');
    // Route::get('/help', 'Help\MahasiswaHelpIndex')->name('mahasiswa.help');
});

// Pengawas Dashboard Routes
Route::group(['namespace' => 'App\Livewire\Pengawas', 'prefix' => 'pengawas', 'middleware' => ['auth', 'verified', CheckUserTimetable::class]], function () {
    Route::get('/', 'Dashboard\PengawasDashboardIndex')->name('pengawas.dashboard');

    // Route::group(['prefix' => 'monitor'], function () {
    //     Route::get('/real-time', 'Monitor\PengawasMonitorRealTime')->name('pengawas.monitor.realtime');
    //     Route::get('/camera', 'Monitor\PengawasMonitorCamera')->name('pengawas.monitor.camera');
    //     Route::get('/alerts', 'Monitor\PengawasMonitorAlerts')->name('pengawas.monitor.alerts');
    // });

    // Route::group(['prefix' => 'report'], function () {
    //     Route::get('/violations', 'Report\PengawasReportViolations')->name('pengawas.report.violations');
    //     Route::get('/incidents', 'Report\PengawasReportIncidents')->name('pengawas.report.incidents');
    // });
});

if (config('app.env') === 'local' || config('app.env') === 'development' || config('app.env') === 'production') {
    Route::redirect('', '/dashboard');
}

Route::get('logout', function () {
    // if (Auth::check()) {
    //     $user = User::find(auth()->user()->id);
    //     $user->update([
    //         'company_id' => null,
    //     ]);
    // }
    auth()->logout();

    return redirect()->route('login');
})->name('logout');
