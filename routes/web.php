<?php

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

Route::group(['namespace' => 'App\Livewire\Admin', 'prefix' => 'user', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', 'Dashboard\AdminDashboardIndex')->name('user.dashboard');

    Route::group(['namespace' => 'Master', 'prefix' => 'master'], function () {
        Route::get('/role', 'Role\AdminMasterRoleIndex')->name('user.master.role');
        Route::get('/user', 'User\AdminMasterUserIndex')->name('user.master.user');
        Route::get('/setting', 'Setting\AdminMasterSettingIndex')->name('user.master.setting');
    });
});

if (config('app.env') === 'local' || config('app.env') === 'development') {
    Route::redirect('', '/user');
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
