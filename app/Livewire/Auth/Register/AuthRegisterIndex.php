<?php

namespace App\Livewire\Auth\Register;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Services\Company\CompanyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class AuthRegisterIndex extends Component
{
    use WithFileUploads;

    // Step management
    public $step = 1;

    // Company information
    public $name;

    public $phone;

    public $website;

    // PIC

    public $pic_name;

    public $pic_email;

    public $pic_phone;

    public $pic_position;

    // Address information
    public $address;

    public $city;

    public $province;

    public $district;

    public $sub_district;

    public $postal_code;

    public $email_company;

    public $country;

    // Company details
    public $logo;

    public $tax_id;

    public $industry;

    public $description;

    // Account credentials
    // public $email;

    public $username;

    public $password;

    public $password_confirmation;

    public function mount()
    {
        $this->step = 1;
    }

    public function nextStep()
    {

        $validationRules = [
            1 => [
                'address' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email_company' => 'required|email|unique:companies,email',
                'website' => 'nullable|string|max:255',
            ],
            2 => [
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'sub_district' => 'required|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
            ],
            3 => [
                'logo' => 'nullable|image|max:2048',
                // 'tax_id' => 'required|string|max:50',
                // 'industry' => 'required|string|max:100',
                // 'description' => 'required|string|max:1000',
            ],
            4 => [
                'pic_name' => 'required|string|max:255',
                'pic_position' => 'required|string|max:100',
                'pic_email' => 'required|email|max:255',
                'pic_phone' => 'required|string|max:20',
            ],
            5 => [
                // 'email' => 'required|email|unique:users,email',
                'username' => [
                    'required',
                    'string',
                    'min:4',
                    'max:20',
                    'unique:users,username',
                    'regex:/^\S*$/u', // tidak boleh ada spasi
                ],
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
            ],
        ];

        $this->validate($validationRules[$this->step]);
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function register()
    {
        // Final validation before submission
        $this->validate([
            // PIC Information
            'pic_name' => 'required|string|max:255',
            'pic_position' => 'required|string|max:100',
            'pic_email' => 'required|email|max:255',
            'pic_phone' => 'required|string|max:20',

            // Company Information
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email_company' => 'required|email|unique:companies,email',
            'website' => 'nullable|string|max:255',

            // Address Information
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'sub_district' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            // 'country' => 'nullable|string|max:100',

            // Company Details
            'logo' => 'nullable|image|max:2048',
            // 'tax_id' => 'required|string|max:50',
            // 'industry' => 'required|string|max:100',
            // 'description' => 'required|string|max:1000',

            // Account Credentials
            // 'email' => 'required|email|unique:users,email',
            'username' => [
                'required',
                'string',
                'min:4',
                'max:20',
                'unique:users,username',
                'regex:/^\S*$/u', // tidak boleh ada spasi
            ],
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'website' => $this->website,
            'email_company' => $this->email_company,

            // Address
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'district' => $this->district,
            'sub_district' => $this->sub_district,
            'postal_code' => $this->postal_code,
            'country' => $this->country,

            // Company Details
            'logo' => $this->logo ? $this->logo->store('company-logos', 'public') : null,
            'tax_id' => $this->tax_id,
            'industry' => $this->industry,
            'description' => $this->description,

            // PIC Information
            'pic_name' => $this->pic_name,
            'pic_position' => $this->pic_position,
            'pic_email' => $this->pic_email,
            'pic_phone' => $this->pic_phone,

            // Account Information
            'username' => $this->username,
            'password' => $this->password,
        ];

         $company = Company::where('email', $data['email_company'])
            ->orWhere('phone', $data['phone'])
            ->orWhere('name', $data['name'])
            ->first();

        if ($company) {
            // Jika perusahaan sudah ada, kembalikan perusahaan yang ada
            return AlertHelper::error('Perusahaan Sudah Ada', 'Perusahaan dengan email, telepon, atau nama yang sama sudah terdaftar.');
        }

        try {
            DB::beginTransaction();

            $companyService = new CompanyService();
            $companyService->createCompany($data);

            DB::commit();


            AlertHelper::success('Berhasil', 'Pendaftaran Berhasil, Silahkan Login, dan Konfirmasi Akun Anda');

            return redirect()->route('login');

        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Gagal mendaftarkan klinik: ' . $th->getMessage());
            return AlertHelper::error('Gagal', 'Gagal Mendaftarkan Klinik, Silahkan Coba Lagi');
        }
    }

    public function messages()
    {
        return [
            // PIC Validation Messages
            'pic_name.required' => 'Nama PIC wajib diisi.',
            'pic_name.max' => 'Nama PIC maksimal 255 karakter.',
            'pic_position.required' => 'Jabatan PIC wajib diisi.',
            'pic_position.max' => 'Jabatan PIC maksimal 100 karakter.',
            'pic_email.required' => 'Email PIC wajib diisi.',
            'pic_email.email' => 'Format email PIC tidak valid.',
            'pic_email.max' => 'Email PIC maksimal 255 karakter.',
            'pic_phone.required' => 'Telepon PIC wajib diisi.',
            'pic_phone.max' => 'Telepon PIC maksimal 20 karakter.',

            // Company Information Messages
            'name.required' => 'Nama Klinik wajib diisi.',
            'phone.required' => 'Nomor Klinik wajib diisi.',
            'email_company.required' => 'Email Klinik wajib diisi.',
            'email_company.email' => 'Format email tidak valid.',
            'email_company.unique' => 'Email sudah terdaftar.',

            // Address Information Messages
            'address.required' => 'Alamat wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'district.required' => 'Kecamatan wajib diisi.',
            'sub_district.required' => 'Kelurahan wajib diisi.',

            // Company Details Messages
            'logo.required' => 'Logo Klinik wajib diunggah.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'tax_id.required' => 'NPWP / Tax ID wajib diisi.',
            'industry.required' => 'Industri wajib diisi.',
            'description.required' => 'Deskripsi Klinik wajib diisi.',

            // Account Credentials Messages
            // 'email.required' => 'Email pengguna wajib diisi.',
            // 'email.email' => 'Format email tidak valid.',
            // 'email.unique' => 'Email sudah terdaftar.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.max' => 'Username maksimal 20 karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'username.regex' => 'Username tidak boleh mengandung spasi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.min' => 'Konfirmasi password minimal 8 karakter.',
        ];
    }

    public function removeLogo()
    {
        $this->logo = null;
    }

    public function render()
    {
        return view('livewire.auth.register.auth-register-index')
            ->extends('layout.auth.app')
            ->section('content');
    }
}
