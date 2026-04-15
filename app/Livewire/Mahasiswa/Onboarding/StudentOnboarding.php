<?php

namespace App\Livewire\Mahasiswa\Onboarding;

use App\Helpers\AlertHelper;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentOnboarding extends Component
{
    public $currentStep = 1;
    public $debugMsg = ''; 

    // Simulation State
    public $simCurrentIndex = 0;
    public $simAnswers = [];
    public $simMarks = [];
    public $simTimeSeconds = 3585; // 00:59:45
    public $simQuestions = [
        [
            'id' => 1,
            'type' => 'single',
            'question' => 'Apa nama ibukota Indonesia saat ini?',
            'options' => [
                ['id' => 'a', 'text' => 'Jakarta'],
                ['id' => 'b', 'text' => 'Bandung'],
                ['id' => 'c', 'text' => 'Surabaya'],
                ['id' => 'd', 'text' => 'IKN (Nusantara)'],
            ]
        ],
        [
            'id' => 2,
            'type' => 'essay',
            'question' => 'Jelaskan secara singkat apa itu ujian berbasis komputer (CBT)!',
        ],
        [
            'id' => 3,
            'type' => 'single',
            'question' => 'Manakah shortcut yang BENAR untuk melakukan refresh halaman tanpa kehilangan data?',
            'options' => [
                ['id' => 'a', 'text' => 'F5 atau Ctrl+R'],
                ['id' => 'b', 'text' => 'Ctrl+Shift+I'],
                ['id' => 'c', 'text' => 'Alt+F4'],
                ['id' => 'd', 'text' => 'Ctrl+C'],
            ]
        ]
    ];

    // Step 1: Profile
    public $name;
    public $nim;
    public $phone;
    public $address;

    // Step 2: Password
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_check) {
            return redirect()->route('admin.exam.timetable');
        }

        $this->name = $user->name;
        $this->nim = $user->userDetail->nim ?? $user->nim ?? '';
        $this->phone = $user->userDetail->phone ?? $user->phone ?? '';
        $this->address = $user->userDetail->address ?? '';

        // Init sim state
        foreach ($this->simQuestions as $q) {
            $this->simAnswers[$q['id']] = '';
            $this->simMarks[$q['id']] = false;
        }
    }

    public function decrementTimer()
    {
        if ($this->simTimeSeconds > 0) {
            $this->simTimeSeconds--;
        }
    }

    public function getFormattedSimTimeProperty()
    {
        $h = floor($this->simTimeSeconds / 3600);
        $m = floor(($this->simTimeSeconds % 3600) / 60);
        $s = $this->simTimeSeconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    public function selectSimAnswer($qId, $ansId)
    {
        $this->simAnswers[$qId] = $ansId;
    }

    public function toggleSimMark($qId)
    {
        $this->simMarks[$qId] = !$this->simMarks[$qId];
    }

    public function setSimIndex($index)
    {
        $this->simCurrentIndex = $index;
    }

    public function nextStep()
    {
        try {
            Log::info('Onboarding nextStep triggered', ['currentStep' => $this->currentStep]);

            if ($this->currentStep === 1) {
                $this->validate([
                    'name' => 'required|string|max:255',
                    'nim' => 'required|string|max:50',
                    'phone' => 'required|string|max:20',
                    'address' => 'required|string',
                ]);

                $user = Auth::user();
                $user->update([
                    'name' => $this->name,
                    'nim' => $this->nim,
                    'phone' => substr($this->phone, 0, 15),
                ]);

                $user->userDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nim' => $this->nim,
                        'phone' => $this->phone,
                        'address' => $this->address,
                    ]
                );

                Log::info('Step 1 complete');
                AlertHelper::success('Berhasil', 'Data profil berhasil diperbarui!');
            } elseif ($this->currentStep === 2) {
                $this->validate([
                    'password' => 'required|min:8|confirmed',
                ]);

                Auth::user()->update([
                    'password' => Hash::make($this->password),
                ]);

                Log::info('Step 2 complete');
                AlertHelper::success('Berhasil', 'Kata sandi berhasil diperbarui!');
            }

            $this->currentStep++;
            $this->debugMsg = 'Moved to step ' . $this->currentStep;
            Log::info('Step incremented to ' . $this->currentStep);
            
        } catch (\Exception $e) {
            Log::error('Onboarding Error: ' . $e->getMessage());
            $this->debugMsg = 'Error: ' . $e->getMessage();
            AlertHelper::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function finish()
    {
        $user = Auth::user();
        $user->update(['user_check' => true]);

        AlertHelper::success('Selesai', 'Selamat! Onboarding selesai.');

        return redirect()->route('admin.exam.timetable');
    }

    public function render()
    {
        return view('livewire.mahasiswa.onboarding.student-onboarding')
            ->layout('layout.onboarding');
    }
}
