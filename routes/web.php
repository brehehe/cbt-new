<?php

use App\Livewire\Admin\Master\Topic\AdminMasterTopicIndex;
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

Route::group(['namespace' => 'App\Livewire\Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', 'Dashboard\AdminDashboardIndex')->name('admin.dashboard');

    Route::group(['namespace' => 'Master', 'prefix' => 'master'], function () {
        Route::get('/role', 'Role\AdminMasterRoleIndex')->name('admin.master.role');
        Route::get('/user', 'User\AdminMasterUserIndex')->name('admin.master.user');
        Route::get('/setting', 'Setting\AdminMasterSettingIndex')->name('admin.master.setting');
        Route::get('/topic-question', AdminMasterTopicIndex::class)->name('admin.master.topic');
        Route::get('/rating-scale', 'RatingScale\AdminMasterRatingScaleIndex')->name('admin.master.rating-scale');
        Route::get('/admin', 'Admin\AdminMasterAdminIndex')->name('admin.master.admin');
        Route::get('/lecturer', 'Lecturer\AdminMasterLecturerIndex')->name('admin.master.lecturer');
        Route::get('/student', 'Student\AdminMasterStudentIndex')->name('admin.master.student');
        Route::get('/supervisor', 'Supervisor\AdminMasterSupervisorIndex')->name('admin.master.supervisor');
        Route::get('/timetable', 'Timetable\AdminMasterTimetableIndex')->name('admin.master.timetable');
    });
});

if (config('app.env') === 'local' || config('app.env') === 'development') {
    Route::redirect('', '/admin');
}

Route::get('logout', function () {
    if (Auth::check()) {
        $user = User::find(auth()->user()->id);
        $user->update([
            'company_id' => null,
        ]);
    }
    auth()->logout();

    return redirect()->route('login');
})->name('logout');
