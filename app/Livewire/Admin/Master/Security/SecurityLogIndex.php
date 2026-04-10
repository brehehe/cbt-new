<?php

namespace App\Livewire\Admin\Master\Security;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class SecurityLogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 25;
    public $filterEvent = '';
    public $logSource = 'security'; // 'security' or 'audit'

    // Modal Form State
    public $form = [
        'causer_id' => '',
        'event_type' => '',
        'description' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterEvent' => ['except' => ''],
        'logSource' => ['except' => 'security'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedLogSource()
    {
        $this->resetPage();
        $this->filterEvent = '';
    }

    public function render()
    {
        $logs = Activity::where('log_name', $this->logSource)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('causer', function ($uq) {
                          $uq->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhere('properties->ip_address', 'like', '%' . $this->search . '%')
                      ->orWhere('subject_type', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterEvent, function ($query) {
                $query->where('event', $this->filterEvent);
            })
            ->latest()
            ->paginate($this->perPage);

        $eventTypes = Activity::where('log_name', $this->logSource)
            ->select('event')
            ->distinct()
            ->pluck('event');

        // Fetch users for the modal dropdown (limited for performance)
        $users = User::orderBy('name')->take(500)->get();

        return view('livewire.admin.security.security-log-index', [
            'logs' => $logs,
            'eventTypes' => $eventTypes,
            'users' => $users,
        ])->extends('layout.app');
    }

    public function openModal($id)
    {
        $this->form = [
            'causer_id' => '',
            'event_type' => '',
            'description' => '',
        ];
        $this->dispatch('openModal', id: $id);
    }

    public function closeModal($id)
    {
        $this->dispatch('closeModal', id: $id);
    }

    public function submit()
    {
        $this->validate([
            'form.causer_id' => 'required',
            'form.event_type' => 'required',
            'form.description' => 'required|min:5',
        ], [
            'form.causer_id.required' => 'Pengguna harus dipilih.',
            'form.event_type.required' => 'Jenis event harus dipilih.',
            'form.description.required' => 'Deskripsi tidak boleh kosong.',
        ]);

        $causer = User::find($this->form['causer_id']);

        activity('security')
            ->event($this->form['event_type'])
            ->causedBy(auth()->user()) // The admin who records it
            ->performedOn($causer)    // The user who is being logged
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'manual_entry' => true,
                'recorded_by' => auth()->user()->name
            ])
            ->log($this->form['description']);

        $this->closeModal('modal-security');
        session()->flash('message', 'Log keamanan berhasil ditambahkan secara manual.');
        $this->resetPage();
    }

    public function clearLogs()
    {
        Activity::where('log_name', 'security')->delete();
        session()->flash('message', 'Semua log keamanan telah dihapus.');
    }
}
