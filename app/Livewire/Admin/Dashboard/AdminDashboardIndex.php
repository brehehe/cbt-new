<?php

namespace App\Livewire\Admin\Dashboard;

use App\Helpers\AlertHelper;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class AdminDashboardIndex extends Component
{
    public function mount()
    {
        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard.admin-dashboard-index')
            ->extends('layout.app')
            ->section('content');
    }
}
