<?php

namespace App\Livewire\Admin\Profile;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminProfileIndex extends Component
{
    public $user;

    public $canEdit = false;

    public $isEditing = false;

    // Form Properties
    public $name;

    public $email;

    public $username;

    public $phone;

    public $address;

    public $gender;

    public $birth_place;

    public $birth_date;

    // Role Specific
    public $nim;

    public $nidn;

    public $nip;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
        ];
    }

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

        $this->initForm();
    }

    public function initForm()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->username = $this->user->username;
        $this->phone = $this->user->phone ?? $this->user->userDetail?->phone;

        // User Details
        if ($this->user->userDetail) {
            $this->address = $this->user->userDetail->address;
            $this->gender = $this->user->userDetail->gender;
            $this->birth_place = $this->user->userDetail->birth_place;
            $this->birth_date = $this->user->userDetail->birth_date;

            // Set ID numbers based on role for display
            if ($this->user->hasRole('Mahasiswa')) {
                $this->nim = $this->user->userDetail->nim;
            } elseif ($this->user->hasRole('Dosen')) {
                $this->nidn = $this->user->userDetail->lecturer_nidn;
                $this->nip = $this->user->userDetail->lecturer_nip;
            } elseif ($this->user->hasRole('Pengawas')) {
                $this->nip = $this->user->userDetail->supervisor_nip;
            }
        }
    }

    public function toggleEdit()
    {
        $this->isEditing = ! $this->isEditing;
        if (! $this->isEditing) {
            $this->initForm(); // Reset if cancelling
        }
    }

    public function updateProfile()
    {
        if (! $this->canEdit) {
            return;
        }

        $this->validate();

        try {
            \DB::beginTransaction();

            // Update User Main Data
            $this->user->update([
                'name' => $this->name,
                'phone' => $this->phone,
            ]);

            // Update or Create User Details
            $this->user->userDetail()->updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'phone' => $this->phone, // Sync phone
                    'address' => $this->address,
                    'gender' => $this->gender,
                    'birth_place' => $this->birth_place,
                    'birth_date' => $this->birth_date,
                    // Identity numbers are guarded/not updated here
                ]
            );

            \DB::commit();

            $this->isEditing = false;

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Profil berhasil diperbaharui.',
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        // Explicitly fetch company data with correct namespace
        $companyData = Company::first();

        return view('livewire.admin.profile.admin-profile-index', [
            'user' => $this->user,
            'canEdit' => $this->canEdit,
            'companyData' => $companyData,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
