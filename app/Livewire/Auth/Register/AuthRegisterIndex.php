<?php

namespace App\Livewire\Auth\Register;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\User;
use App\Services\Company\CompanyService;
use App\Traits\UploadFile;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class AuthRegisterIndex extends Component
{
    use WithFileUploads, UploadFile;
    public $step = 1, $progress_bar = 33.3;

    public $name, $birth_place, $birth_date, $email, $password;
    public $nim, $program_study, $semester;
    public $krs_file, $payment_registration;

    public function render()
    {
        $program_studies = ['Farmasi', 'Kebidanan', 'Keperawatan (D-III)', 'Keperawatan (S1)', 'Profesi Ners'];
        return view('livewire.auth.register.auth-register-index',[
            'program_studies' => $program_studies
        ])->extends('layout.auth.app')->section('content');
    }

    public function mount()
    {

    }

    protected $messages = [
        'name.required'        => 'Nama lengkap wajib diisi',
        'birth_place.required' => 'Tempat lahir wajib diisi',
        'birth_date.required'  => 'Tanggal lahir wajib diisi',
        'birth_date.date'      => 'Tanggal lahir hanya berformat tanggal',
        'email.required'       => 'Email wajib diisi',
        'email.email'          => 'Email tidak valid',
        'password.required'    => 'Kata sandi wajib diisi',

        'nim.required'           => 'NIM wajib diisi',
        'program_study.required' => 'Program studi wajib diisi',
        'semester.required'      => 'Semester wajib diisi',
        'semester.numeric'       => 'Semester hanya berformat angka',

        'krs_file.required'             => 'Berkas KRS wajib diupload',
        'krs_file.file'                 => 'Berkas KRS hanya berupa file/berkas',
        'payment_registration.required' => 'Berkas pembayaran wajib diupload',
        'payment_registration.file'     => 'Berkas pembayaran hanya berupa file/berkas',
    ];

    public function nextStep()
    {
        $validationRules = [
            1 => [
                'name'        => 'required',
                'birth_place' => 'required',
                'birth_date'  => 'required|date',
                'email'       => 'required|email',
                'password'    => 'required',
            ],
            2 => [
                'nim'           => 'required',
                'program_study' => 'required',
                'semester'      => 'required|numeric',
            ],
            3 => [
                'krs_file'             => 'required|file',
                'payment_registration' => 'required|file',
            ],
        ];

        $this->validate($validationRules[$this->step], $this->messages);
        $this->step += 1;
        $this->progress_bar += 33.3;
    }

    public function prevStep()
    {
        $this->step -= 1;
        $this->progress_bar -= 33.3;
    }

    public function registration()
    {
        // $this->validate();
        // dd($this->krs_file);
        try {
            $main_folder = Carbon::now()->isoFormat('Y') . '/' . Carbon::now()->isoFormat('MM');

            $result_krs_file                  = $this->uploadFile($this->krs_file, "/public/detail_user/krs/$main_folder");
            $result_payment_registration_file = $this->uploadFile($this->payment_registration, "/public/detail_user/payment_registration/$main_folder");

            DB::beginTransaction();
                $user = User::create([
                    'name'     => $this->name,
                    'email'    => $this->email,
                    'password' => Hash::make($this->password),
                ]);

                $user?->userDetail()->create([
                    'birth_place'          => $this->birth_place,
                    'birth_date'           => $this->birth_date,
                    'student_program'      => $this->program_study,
                    'student_semester'     => $this->semester,
                    'krs_file'             => $result_krs_file[1],
                    'payment_registration' => $result_payment_registration_file[1],
                    'student_type'         => 'mb',
                ]);
            DB::commit();
        } catch (Exception | Throwable $th) {
            $errors = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada kesalahan saat registration', $errors);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat registrasi sistem');
        }

        $this->reset('name', 'birth_place', 'birth_date', 'email', 'password', 'nim', 'program_study', 'semester', 'krs_file', 'payment_registration');
        return AlertHelper::success('Berhasil', 'Data berhasil di registrasi');
    }
}
