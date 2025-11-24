<?php

namespace App\Livewire\Admin\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminProfileIndex extends Component
{
    public $user;
    public $canEdit = false;

    public function mount()
    {
        // Get authenticated user
        $currentUser = Auth::user();

        // Check if user has Mahasiswa role
        if ($currentUser->hasRole('Mahasiswa')) {
            // Mahasiswa can only view their own profile
            $this->user = $currentUser;
            $this->canEdit = true; // Mahasiswa can edit their own profile
        } elseif ($currentUser->hasRole(['Admin', 'Dosen', 'Pengawas'])) {
            // Admin, Dosen, and Pengawas can view and edit their own profile
            $this->user = $currentUser;
            $this->canEdit = true;
        } else {
            // For other roles, default to current user
            $this->user = $currentUser;
            $this->canEdit = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.profile.admin-profile-index', [
            'user' => $this->user,
            'canEdit' => $this->canEdit,
        ]);
    }
}
