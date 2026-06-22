<?php

use App\Http\Controllers\Admin\LatexPreviewController;
use App\Http\Controllers\Admin\Security\SecurityLogController;
use App\Http\Controllers\Api\Admin\DashboardApiController;
use App\Http\Controllers\Api\Exam\ExamApiController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Print\PrintController;
use App\Http\Controllers\SEBController;
use App\Http\Middleware\BlockBots;
use App\Http\Middleware\CheckUserTimetable;
use App\Http\Middleware\RoleBasedDashboardRedirect;
use App\Livewire\Admin\Exam\Detail\AdminExamDetailIndex;
use App\Livewire\Admin\Exam\LiveStream\AdminExamLiveStreamIndex;
use App\Livewire\Admin\Exam\Monitor\AdminExamMonitorDetailIndex;
use App\Livewire\Admin\Exam\Monitor\AdminExamMonitorIndex;
use App\Livewire\Admin\LecturerManagement;
use App\Livewire\Admin\Master\Backup\AdminMasterBackupIndex;
use App\Livewire\Admin\Master\CategoryQuestion\AdminMasterCategoryQuestionIndex;
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
use App\Livewire\Admin\Master\Timetable\UserTimetable\Correct\AdminMasterTimetableUserTimetableCorrectIndex;
use App\Livewire\Admin\Master\Topic\AdminMasterTopicIndex;
use App\Livewire\Admin\Profile\AdminProfileIndex;
use App\Livewire\Admin\Report\AnswerStatistics\AdminReportAnswerStatisticsIndex;
use App\Livewire\Admin\Report\Attendance\AdminReportAttendanceIndex;
use App\Livewire\Admin\Report\Card\AdminReportCardIndex;
use App\Livewire\Admin\Report\ExamResult\AdminReportExamResultIndex;
use App\Livewire\Admin\Report\FullExamResult\AdminReportFullExamResultIndex;
use App\Livewire\Admin\Report\ItemAnalysis\AdminReportItemAnalysisAllIndex;
use App\Livewire\Admin\Report\ItemAnalysis\AdminReportItemAnalysisIndex;
use App\Livewire\Admin\Report\ItemAnalysis\Detail\AdminReportItemAnalysisDetailIndex;
use App\Livewire\Admin\Report\Official\AdminReportOfficialIndex;
use App\Livewire\Admin\Report\Question\AdminReportQuestionIndex;
use App\Livewire\Admin\Report\StudentExamResult\AdminReportStudentExamResultIndex;
use App\Livewire\Admin\Report\Timetable\AdminReportTimetableDetail;
use App\Livewire\Admin\Report\Timetable\AdminReportTimetableIndex;
use App\Livewire\Admin\Session\AdminSessionIndex;
use App\Livewire\Admin\StudentManagement;
use App\Livewire\Mahasiswa\Onboarding\StudentOnboarding;
use App\Livewire\Public\StressTestExamDetailIndex;
use App\Models\Exam\ExamLiveSession;
use App\Models\User;
use App\Models\User\UserTimetable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Security\SecurityLogIndex;

Route::get('/document', function () {
    return view('document');
});

Route::get('/exam-demo', function () {
    return view('exam-demo');
})->name('exam.demo');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Profile route accessible by all authenticated users including Mahasiswa
Route::middleware(['auth', CheckUserTimetable::class])->group(function () {
    Route::get('/profile', AdminProfileIndex::class)->name('user.profile');
});

Route::get('/student/onboarding', StudentOnboarding::class)
    ->name('student.onboarding')
    ->middleware(['auth']);

require __DIR__ . '/auth.php';

// Public Stress Test Route (Only for testing purposes)

// Safe Exam Browser Routes
Route::prefix('seb')->name('seb.')->group(function () {
    // Generic config - no timetable required, just login page
    Route::get('/config', [SEBController::class, 'downloadGenericConfig'])
        ->name('config.generic');

    // Specific timetable config
    Route::get('/config/{timetable}', [SEBController::class, 'downloadConfig'])
        ->name('config.download');

    Route::get('/validate', [SEBController::class, 'validateSEB'])
        ->name('validate');

    Route::get('/check/{timetable}', [SEBController::class, 'checkTimetableSEB'])
        ->name('check.timetable')
        ->middleware('auth');
});

Route::group(['middleware' => [BlockBots::class, RoleBasedDashboardRedirect::class]], function () {
    Route::group(['namespace' => 'App\Livewire\Auth'], function () {
        // Add your routes here
        Route::get('login', 'Login\AuthLoginIndex')->name('login');
        Route::get('register', 'Register\AuthRegisterIndex')->name('register');

        // React Login API Bridges
        Route::post('/api/login/check-session', function (\Illuminate\Http\Request $request) {
            $usernameOrEmail = $request->input('username_or_email');
            if (empty($usernameOrEmail)) {
                return response()->json(['hasActiveSession' => false, 'activeSessionInfo' => null]);
            }

            $fieldType = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = \App\Models\User::where($fieldType, $usernameOrEmail)->first();

            if (!$user) {
                return response()->json(['hasActiveSession' => false, 'activeSessionInfo' => null]);
            }

            try {
                $activeSessions = 0;
                if ($user->hasRole('Mahasiswa')) {
                    $activeSessions = \Illuminate\Support\Facades\DB::table(config('session.table', 'sessions'))
                        ->where('user_id', $user->id)
                        ->where('last_activity', '>', time() - config('session.lifetime', 120) * 60)
                        ->count();
                }

                if ($activeSessions > 0) {
                    return response()->json([
                        'hasActiveSession' => true,
                        'activeSessionInfo' => [
                            'username' => $user->username ?? $user->email,
                            'session_count' => $activeSessions,
                            'last_seen' => 'Baru saja',
                        ]
                    ]);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed checking existing sessions (React API)', [
                    'username_or_email' => $usernameOrEmail,
                    'error' => $e->getMessage(),
                ]);
            }

            return response()->json(['hasActiveSession' => false, 'activeSessionInfo' => null]);
        });

        Route::post('/api/login/react', function (\Illuminate\Http\Request $request) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'username_or_email' => 'required',
                'password' => 'required',
            ], [
                'username_or_email.required' => 'Username atau email wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $usernameOrEmail = $request->input('username_or_email');
            $password = $request->input('password');
            $remember = $request->boolean('remember');

            // Rate Limiter Check
            $throttleKey = \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower((string) $usernameOrEmail) . '|' . $request->ip());
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
                event(new \Illuminate\Auth\Events\Lockout($request));
                $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
                return response()->json([
                    'success' => false,
                    'message' => __('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ])
                ], 429);
            }

            // Find user by email, username, or nim
            $user = \App\Models\User::where('email', $usernameOrEmail)
                ->orWhere('username', $usernameOrEmail)
                ->orWhere('nim', $usernameOrEmail)
                ->first();

            if (!$user) {
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);
                \Illuminate\Support\Facades\Log::channel('security')->warning('Login failed: user not found (React API)', [
                    'identifier' => $usernameOrEmail,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 401);
            }

            if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);
                \Illuminate\Support\Facades\Log::channel('security')->warning('Login failed: invalid password (React API)', [
                    'user_id' => $user->id,
                    'identifier' => $usernameOrEmail,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah.'
                ], 401);
            }

            // Single session enforcement for Mahasiswa
            if ($user->hasRole('Mahasiswa')) {
                try {
                    $activeSessions = \Illuminate\Support\Facades\DB::table(config('session.table', 'sessions'))
                        ->where('user_id', $user->id)
                        ->where('last_activity', '>', time() - config('session.lifetime', 120) * 60)
                        ->count();

                    if ($activeSessions > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Akun sudah login di perangkat lain. Silakan logout dari perangkat lain terlebih dahulu atau hubungi administrator.'
                        ], 403);
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed checking active sessions for user during login (React API)', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Clear rate limiter & perform login
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            \Illuminate\Support\Facades\Auth::login($user, $remember);

            session()->flash('saved', [
                'title' => 'Login Berhasil!',
                'text' => 'Anda berhasil login ke sistem!',
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route('admin.dashboard')
            ]);
        });
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
    Route::group(['namespace' => 'App\Livewire\Admin', 'prefix' => 'admin', 'middleware' => ['auth', CheckUserTimetable::class]], function () {
        Route::get('/', 'Dashboard\AdminDashboardIndex')->name('admin.dashboard');

        // Debug route for video testing
        Route::get('/debug-video', function () {
            return view('debug_video_frontend');
        })->name('admin.debug.video');

        // User Management Routes
        Route::get('/students', StudentManagement::class)->name('admin.students');
        Route::get('/lecturers', LecturerManagement::class)->name('admin.lecturers');

        // Session Management Route
        Route::get('/session', AdminSessionIndex::class)->name('admin.session');

        Route::group(['namespace' => 'ChangePassword', 'prefix' => 'change-password'], function () {
            Route::get('/change-password', 'AdminChangePasswordIndex')->name('user.change-password.change-password');
        });

        Route::group(['namespace' => 'Profile', 'prefix' => 'profile'], function () {
            Route::get('/profile', 'AdminProfileIndex')->name('user.profile.profile');
        });

        Route::group(['namespace' => 'Exam', 'prefix' => 'exam'], function () {
            Route::get('/timetable', 'Timetable\AdminExamTimetableIndex')->name('admin.exam.timetable');
            Route::get('/history-timetable', 'HistoryTimetable\AdminExamHistoryTimetableIndex')->name('admin.exam.history-timetable');
            Route::get('/history-timetable/{timetable_id}/{user_timetable_id}', 'HistoryTimetable\Detail\AdminExamHistoryTimetableDetailIndex')->name('admin.exam.history-timetable.detail');
            Route::get('/warning', 'Warning\AdminExamWarningIndex')->name('admin.exam.warning');
            Route::get('/detail', AdminExamDetailIndex::class)->name('admin.exam.detail');
            Route::get('/monitor', AdminExamMonitorIndex::class)->name('admin.exam.monitor');
            Route::get('/monitor/{session}', AdminExamMonitorDetailIndex::class)->name('admin.exam.monitor.detail');
            Route::get('/live-stream', AdminExamLiveStreamIndex::class)->name('admin.exam.live-stream');
        });

        // Backup Download Route (outside master group)
        Route::get('/backup/download', [BackupController::class, 'download'])->name('admin.backup.download');

        Route::group(['namespace' => 'Master', 'prefix' => 'master'], function () {
            Route::get('/role', 'Role\AdminMasterRoleIndex')->name('admin.master.role');
            Route::get('/user', 'User\AdminMasterUserIndex')->name('admin.master.user');
            Route::get('/setting', 'Setting\AdminMasterSettingIndex')->name('admin.master.setting');
            Route::get('/backup', AdminMasterBackupIndex::class)->name('admin.master.backup');
            Route::get('/topic-question', AdminMasterTopicIndex::class)->name('admin.master.topic');
            Route::get('/material-category', AdminMasterMaterialCategoryIndex::class)->name('admin.master.material-category');
            Route::get('/rating-scale', 'RatingScale\AdminMasterRatingScaleIndex')->name('admin.master.rating-scale');
            Route::get('/security-log', SecurityLogIndex::class)->name('admin.security.log.index');
            Route::get('/regulation', 'Regulation\AdminMasterRegulationIndex')->name('admin.master.regulation');
            Route::get('/admin', 'Admin\AdminMasterAdminIndex')->name('admin.master.admin');
            Route::get('/lecturer', 'Lecturer\AdminMasterLecturerIndex')->name('admin.master.lecturer');
            Route::get('/student', 'Student\AdminMasterStudentIndex')->name('admin.master.student');
            Route::get('/supervisor', 'Supervisor\AdminMasterSupervisorIndex')->name('admin.master.supervisor');
            Route::get('/study', 'Study\AdminMasterStudyIndex')->name('admin.master.study');
            Route::get('/timetable', 'Timetable\AdminMasterTimetableIndex')->name('admin.master.timetable');
            Route::get('/timetable/create', 'Timetable\AdminMasterTimetableCreate')->name('admin.master.timetable.create');
            Route::get('/timetable/{timetable_id}/detail', 'Timetable\Detail\AdminMasterTimetableDetailIndex')->name('admin.master.timetable.detail');
            Route::get('/timetable/{timetable_id}/video', 'Timetable\Video\AdminMasterTimetableVideoIndex')->name('admin.master.timetable.video');
            Route::get('/timetable/{timetable_id}/alert', 'Timetable\Alert\AdminMasterTimetableAlertIndex')->name('admin.master.timetable.alert');
            Route::get('/timetable/{timetable_id}/streaming', 'Timetable\Streaming\AdminMasterTimetableStreamingIndex')->name('admin.master.timetable.streaming');
            Route::get('/timetable/{timetable_id}/session', 'Timetable\Session\AdminMasterTimetableSessionIndex')->name('admin.master.timetable.session');
            Route::get('/timetable/{timetable_id}/{user_timetable_id}/answer', 'Timetable\Answer\AdminMasterTimetableAnswerIndex')->name('admin.master.timetable.answer');
            Route::get('/timetable/{timetable_id}/correct', 'Timetable\Correct\AdminMasterTimetableCorrectIndex')->name('admin.master.timetable.correct');
            Route::get('/timetable/user-timetable/{user_timetable_id}/correct', AdminMasterTimetableUserTimetableCorrectIndex::class)->name('admin.master.timetable.user-timetable.correct');
            Route::get('/material', AdminMasterMaterialIndex::class)->name('admin.master.material');
            Route::get('/question-type', AdminMasterQuestionTypeIndex::class)->name('admin.master.question-type');
            Route::get('/category-question', AdminMasterCategoryQuestionIndex::class)->name('admin.master.category-question');
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
            Route::get('/item-analysis-all', AdminReportItemAnalysisAllIndex::class)->name('admin.report.item-analysis-all');
            Route::get('/item-analysis/{id}/detail', AdminReportItemAnalysisDetailIndex::class)->name('admin.report.item-analysis.detail');
            Route::get('/exam-result', AdminReportExamResultIndex::class)->name('admin.report.exam-result');

            // New Reports
            Route::get('/official', AdminReportOfficialIndex::class)->name('admin.report.official');
            Route::get('/attendance', AdminReportAttendanceIndex::class)->name('admin.report.attendance');
            Route::get('/card', AdminReportCardIndex::class)->name('admin.report.card');
            Route::get('/full-exam-result', AdminReportFullExamResultIndex::class)->name('admin.report.full-exam-result');
            Route::get('/answer-statistics', AdminReportAnswerStatisticsIndex::class)->name('admin.report.answer-statistics');
            Route::get('/student-exam-result', AdminReportStudentExamResultIndex::class)->name('admin.report.student-exam-result');
        });

        // Print previews (temporary demo routes)
        Route::group(['prefix' => 'print'], function () {
            Route::get('/daftar-hadir/{session_id}', [PrintController::class, 'printDaftarHadir'])
                ->name('admin.print.daftar-hadir');

            Route::get('/berita-acara/{session_id}', [PrintController::class, 'printBeritaAcara'])
                ->name('admin.print.berita-acara');
        });

        Route::post('/latex/preview', [LatexPreviewController::class, 'preview'])
            ->name('admin.latex.preview');

        Route::post('/security/log', [SecurityLogController::class, 'store'])
            ->name('admin.security.log');
    });

    // Dosen Dashboard Routes
    Route::group(['namespace' => 'App\Livewire\Dosen', 'prefix' => 'dosen', 'middleware' => ['auth', CheckUserTimetable::class]], function () {
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
    Route::group(['namespace' => 'App\Livewire\Mahasiswa', 'prefix' => 'mahasiswa', 'middleware' => ['auth', CheckUserTimetable::class]], function () {
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
    Route::group(['namespace' => 'App\Livewire\Pengawas', 'prefix' => 'pengawas', 'middleware' => ['auth', CheckUserTimetable::class]], function () {
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

    Route::get('/clearallsession', function () {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'superadmin', 'Admin', 'Super Admin', 'pengawas', 'Pengawas'])) {
            abort(403, 'Unauthorized.');
        }

        // ========== JIKA BELUM VERIFIKASI PASSWORD ==========
        if (!session('clearsession_verified')) {

            return '
                <h2>Masukkan Password Admin</h2>
                <form method="POST" action="/clearallsession/check">
                    ' . csrf_field() . '
                    <input type="password" name="password" placeholder="Password"
                        style="padding:10px; width:200px;">
                    <br><br>
                    <button type="submit" style="padding:10px 20px; background:blue; color:white;">
                        Verifikasi
                    </button>
                </form>
            ';
        }

        // ========== JIKA SUDAH VERIFIKASI PASSWORD ==========

        $sessions = DB::table('sessions')->get();

        $html = "
        <h2>Daftar User yang Sedang Login</h2>

        <!-- FORM HAPUS SESSION TERPILIH -->
        <form method='POST' action='/clearallsession/confirm'>
            " . csrf_field() . "
            <table border='1' cellpadding='10' cellspacing='0'>
                <tr>
                    <th>Pilih</th>
                    <th>User ID</th>
                    <th>Nama</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Last Activity</th>
                </tr>
        ";

        foreach ($sessions as $s) {
            $userName = '-';
            if ($s->user_id) {
                $user = DB::table('users')->where('id', $s->user_id)->first();
                $userName = $user ? $user->name : '(User tidak ditemukan)';
            }

            $html .= "
                <tr>
                    <td><input type='checkbox' name='sessions[]' value='{$s->id}'></td>
                    <td>{$s->user_id}</td>
                    <td>{$userName}</td>
                    <td>{$s->ip_address}</td>
                    <td>{$s->user_agent}</td>
                    <td>" . date('Y-m-d H:i:s', $s->last_activity) . '</td>
                </tr>
            ';
        }

        $html .= "
            </table>
            <br>

            <!-- BUTTON HAPUS TERPILIH -->
            <button type='submit' style='padding:10px 20px; background:red; color:white;'>
                Hapus Session Terpilih
            </button>

        </form>

        <br><br>

        <!-- FORM HAPUS SEMUA -->
        <form method='POST' action='/clearallsession/clearall'>
            " . csrf_field() . "
            <button type='submit' style='padding:10px 20px; background:darkred; color:white;'>
                Hapus Semua Session (Force Logout Semua User)
            </button>
        </form>

        <br><br>
        <a href='/' style='padding:10px 20px; background:gray; color:white; text-decoration:none;'>Batal</a>
        ";

        return $html;
    });

    Route::post('/clearallsession/check', function () {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'superadmin', 'Admin', 'Super Admin', 'pengawas', 'Pengawas'])) {
            abort(403, 'Unauthorized.');
        }

        $input = request()->password;
        $password = env('CLEAR_SESSION_PASSWORD');

        if ($input === $password) {
            session(['clearsession_verified' => true]);

            return redirect('/clearallsession');
        }

        return "<h2>Password salah!</h2>
                <a href='/clearallsession'>Coba Lagi</a>";
    })->middleware('throttle:5,1');

    Route::post('/clearallsession/confirm', function () {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'superadmin', 'Admin', 'Super Admin', 'pengawas', 'Pengawas'])) {
            abort(403, 'Unauthorized.');
        }

        if (!session('clearsession_verified')) {
            return redirect('/clearallsession')->with('error', 'Tidak diizinkan.');
        }

        if (!request()->has('sessions')) {
            return 'Tidak ada session yang dipilih.';
        }

        // Ambil user_id dari session yang akan dihapus
        $sessions = DB::table('sessions')->whereIn('id', request()->sessions)->get();
        $userIds = $sessions->pluck('user_id')->filter()->unique();

        if ($userIds->isNotEmpty()) {
            try {
                // 1. Putuskan live session untuk user-user tersebut
                ExamLiveSession::whereIn('user_id', $userIds)
                    ->update([
                        'is_active' => false,
                        'connection_status' => 'disconnected',
                        'last_activity' => now(),
                        'end_time' => now(),
                    ]);

                // 2. Pause timer ujian untuk user-user tersebut
                UserTimetable::whereIn('user_id', $userIds)
                    ->whereIn('status', ['exam', 'warning'])
                    ->whereNull('paused_at')
                    ->update(['paused_at' => now()]);
            } catch (Throwable $e) {
                Log::error('ClearSession Confirm Error: ' . $e->getMessage());
            }
        }

        DB::table('sessions')
            ->whereIn('id', request()->sessions)
            ->delete();

        return redirect('/clearallsession')->with('message', 'Session terpilih telah dihapus. Status ujian & live session telah disesuaikan.');
    });

    Route::post('/clearallsession/clearall', function () {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'superadmin', 'Admin', 'Super Admin', 'pengawas', 'Pengawas'])) {
            abort(403, 'Unauthorized.');
        }

        if (!session('clearsession_verified')) {
            return redirect('/clearallsession')->with('error', 'Tidak diizinkan.');
        }

        try {
            // 1. Putuskan semua live session yang aktif
            ExamLiveSession::where('is_active', true)
                ->update([
                    'is_active' => false,
                    'connection_status' => 'disconnected',
                    'last_activity' => now(),
                    'end_time' => now(),
                ]);

            // 2. Pause semua ujian yang sedang berjalan
            UserTimetable::whereIn('status', ['exam', 'warning'])
                ->whereNull('paused_at')
                ->update(['paused_at' => now()]);
        } catch (Throwable $e) {
            Log::error('ClearSession ClearAll Error: ' . $e->getMessage());
        }

        DB::table('sessions')->truncate();

        return redirect('/clearallsession')->with('message', 'Semua session telah dihapus. Semua user logout, exam dipause & live session diputus.');
    });

    // React-based Exam Detail Migration
    Route::get('/exam/detail/{userTimetableId}/react', function ($userTimetableId) {
        return view('exam-react', ['userTimetableId' => $userTimetableId]);
    })->name('admin.exam.detail.react')->middleware(['auth']);

    // Admin Dashboard API Routes
    Route::prefix('api/admin/dashboard')->middleware('auth')->group(function () {
        Route::get('/stats', [DashboardApiController::class, 'getStats'])->name('admin.api.dashboard.stats');
        Route::get('/realtime', [DashboardApiController::class, 'getRealtime'])->name('admin.api.dashboard.realtime');
    });

    // Exam API Routes (using web middleware for session persistence)
    Route::prefix('api/exam')->middleware('auth')->group(function () {
        // Auth Check Routes — ditangani AuthCheckController
        // GET /api/exam/ping        → cek apakah session masih valid (401 jika expired)
        // GET /api/exam/{id}/status → cek is_active ExamLiveSession (deteksi force-logout)
        Route::get('/ping', [App\Http\Controllers\Api\Auth\AuthCheckController::class, 'ping']);

        Route::get('/{user_timetable_id}/data', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'getInitialState']);
        Route::post('/save-answer', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'saveAnswer']);
        Route::post('/toggle-mark', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'toggleMark']);
        Route::post('/log-alert', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'logAlert']);
        Route::post('/recording/upload-full', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'uploadFullRecording']);
        Route::post('/recording/upload-chunk', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'uploadChunk']);
        Route::post('/recording/merge', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'mergeRecordingChunks']);

        Route::get('/live-session/{user_timetable_id}/update', [ExamApiController::class, 'updateLiveSession']);
        Route::get('/live-session/{user_timetable_id}/token', [ExamApiController::class, 'getLiveKitToken']);

        // Admin Monitoring API
        Route::get('/admin/monitoring/{timetable_id}/sessions', [ExamApiController::class, 'getMonitoringSessions']);
        Route::get('/admin/monitoring/{timetable_id}/token', [ExamApiController::class, 'getMonitoringToken']);

        // Auth Check Routes — status ujian (cek ExamLiveSession.is_active)
        Route::get('/{user_timetable_id}/status', [App\Http\Controllers\Api\Auth\AuthCheckController::class, 'examStatus']);

        Route::post('/{user_timetable_id}/finish', [App\Http\Controllers\Api\Exam\ExamApiController::class, 'finishExam']);
    });

    Route::get('/stress-test/exam/{userTimetableId}', StressTestExamDetailIndex::class)->name('public.stress-test.exam');
});
