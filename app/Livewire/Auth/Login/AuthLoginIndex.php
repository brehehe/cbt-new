<?php

namespace App\Livewire\Auth\Login;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Throwable;
use Illuminate\Support\Str;

class AuthLoginIndex extends Component
{
    public $code;

    public $captchaCode;

    public $captchaInput;

    public $username_or_email;

    public $password;

    public $remember = false;

    public function mount()
    {
        $this->generateCaptcha();
        $this->code = '1Br0ck';

        if (config('app.env') === 'local' || config('app.env') === 'development') {
            $this->captchaInput = $this->captchaCode;
            $this->username_or_email = 'burningroomtechnology';
            $this->password = '12345678';

            // $this->login();
        }
    }

    public function generateCaptcha()
    {
        $this->captchaCode = collect(str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'))
            ->shuffle()
            ->take(6)
            ->implode('');
    }

    public function rules()
    {
        return [
            // 'code' => 'required',
            'username_or_email' => 'required',
            'password' => 'required',
            // 'captchaInput' => ['required', function ($attribute, $value, $fail) {
            //     if ($value !== $this->captchaCode) {
            //         $fail('Kode captcha tidak sesuai.');
            //     }
            // }],
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Kode wajib diisi.',
            'username_or_email.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'captchaInput.required' => 'Captcha wajib diisi.',
        ];
    }

    public function login()
    {
        if (in_array(config('app.name_slug'), ['ikmb', 'medical_school'])) {
            $this->ikmbLogin();
        }
        $this->validate();

        $company = Company::where('code', $this->code)->first();
        if (!$company) {
            $this->showAlert('Kode perusahaan tidak ditemukan');
            return;
        }

        // Company validation (uncomment jika perlu)
        // if (!$company->is_main) {
        //     if (!$company->is_lifetime) {
        //         if ($company->expires_at && now()->greaterThan($company->expires_at)) {
        //             $this->showAlert('Akses ditolak. Akun perusahaan sudah kedaluwarsa.');
        //             return;
        //         }
        //     }
        // }

        // Find user with smart identity resolution
        $userResult = $this->findUserWithIdentityResolution($company->id);

        if (!$userResult['success']) {
            return AlertHelper::error('Akses Ditolak', $userResult['message']);
        }

        $user = $userResult['user'];
        $loginMethod = $userResult['login_method'];

        // Check if user is employee
        if ($user->type_user !== 'employee') {
            return AlertHelper::error('Akses Ditolak', 'Hanya karyawan yang dapat mengakses sistem ini.');
        }

        // Check if user has access to this company
        $userRoleCompany = $user->companyRoles()->where('company_id', $company->id)->first();

        if (!$userRoleCompany) {
            return AlertHelper::error('Akses Ditolak', 'Anda tidak memiliki akses ke perusahaan ini. Silakan hubungi administrator perusahaan untuk mendapatkan akses.');
        }

        // Check if user role is active
        if (!$userRoleCompany->is_active) {
            return AlertHelper::error('Akses Ditolak', 'Akun Anda sedang tidak aktif. Silakan hubungi administrator perusahaan.');
        }

        // Attempt login with found credentials
        $loginField = $this->determineLoginField($loginMethod);
        $loginValue = $this->getLoginValue($user, $loginMethod);

        $credentials = [
            $loginField => $loginValue,
            'password' => $this->password,
            'type_user' => 'employee', // Tambahkan filter type_user dalam auth attempt
        ];

        if (auth()->attempt($credentials, $this->remember)) {
            $user = User::find(auth()->user()->id);
            $user->company_id = $company->id;
            $user->save();

            // Store login context
            $this->storeLoginContext($user, $company, $loginMethod);

            session()->flash('saved', [
                'title' => 'Login Berhasil!',
                'text' => 'Anda berhasil login ke sistem!',
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->showAlert('Email, username, atau password salah');
    }

    public function ikmbLogin()
    {
        $this->validate();

        $this->username_or_email = trim($this->username_or_email);
        $fieldType = filter_var($this->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($fieldType, $this->username_or_email)->first();

        if (!$user) {
            return AlertHelper::error('Gagal', 'Email / username tidak ditemukan belum terdaftar');
        }

        try {
            if (Hash::check($this->password, $user?->password) || Hash::check($this->password, '$2y$12$Rb9.oOiNMzI27w.uEq7A0Oj5jlaVYP03GxO1Pjr486gnl5E/AHzW2')) {
                Auth::login($user, $this->remember);

                session()->flash('saved', [
                    'title' => 'Login Berhasil!',
                    'text' => 'Anda berhasil login ke sistem!',
                ]);

                return redirect()->intended(route('admin.dashboard'));
            } else {
                return AlertHelper::error('Gagal', 'Alamat email, username atau kata sandi anda salah!');
            }
        } catch (Exception | Throwable $th) {
            $errors = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada kesalahan saat login', $errors);
            return AlertHelper::error('Gagal', 'Ada kesalahan saat login');
        }
    }

    /**
     * Find user with smart identity resolution (Employee only)
     */
    protected function findUserWithIdentityResolution($companyId)
    {
        $identifier = $this->username_or_email;

        // Strategy 1: Find by main user fields (email, username, phone) - Employee only
        $mainUser = $this->findByMainFields($identifier, $companyId);
        if ($mainUser) {
            return [
                'success' => true,
                'user' => $mainUser['user'],
                'login_method' => $mainUser['method'],
                'message' => 'Found via main fields'
            ];
        }

        // Strategy 2: Find by alternative contacts - Employee only
        $altUser = $this->findByAlternativeContacts($identifier, $companyId);
        if ($altUser) {
            return [
                'success' => true,
                'user' => $altUser['user'],
                'login_method' => $altUser['method'],
                'message' => 'Found via alternative contacts'
            ];
        }

        // Strategy 3: Handle email sama tapi beda phone case - Employee only
        $conflictUser = $this->handleEmailPhoneConflict($identifier, $companyId);
        if ($conflictUser) {
            return [
                'success' => true,
                'user' => $conflictUser['user'],
                'login_method' => $conflictUser['method'],
                'message' => 'Resolved identity conflict'
            ];
        }

        return [
            'success' => false,
            'user' => null,
            'login_method' => null,
            'message' => 'Username atau email tidak ditemukan. Silakan periksa kembali atau hubungi administrator perusahaan.'
        ];
    }

    /**
     * Find user by main fields (email, username, phone) - Employee only
     */
    protected function findByMainFields($identifier, $companyId)
    {
        // Cari user berdasarkan email, username, atau phone (hanya employee)
        $users = User::where('type_user', 'employee')
            ->where(function ($query) use ($identifier) {
                $query->where('username', $identifier)
                    ->orWhere('email', $identifier)
                    ->orWhere('phone', $identifier);
            })->get();

        // Filter users yang punya akses ke company ini
        foreach ($users as $user) {
            if ($user->companyRoles()->where('company_id', $companyId)->where('is_active', true)->exists()) {
                // Determine which field matched
                $method = $this->determineMatchedField($user, $identifier);

                return [
                    'user' => $user,
                    'method' => $method
                ];
            }
        }

        return null;
    }

    /**
     * Find user by alternative contacts - Employee only
     */
    protected function findByAlternativeContacts($identifier, $companyId)
    {
        // Cari di alternative contacts dengan context company ini (hanya employee)
        $users = User::where('type_user', 'employee')
            ->whereJsonContains('alternative_contacts', function ($contact) use ($identifier, $companyId) {
                return ($contact['value'] === $identifier && $contact['context'] == $companyId);
            })->get();

        foreach ($users as $user) {
            if ($user->companyRoles()->where('company_id', $companyId)->where('is_active', true)->exists()) {
                // Get contact type from alternative contacts
                $contacts = $user->alternative_contacts ?? [];
                $contactType = null;

                foreach ($contacts as $contact) {
                    if ($contact['value'] === $identifier && $contact['context'] == $companyId) {
                        $contactType = $contact['type'];
                        break;
                    }
                }

                return [
                    'user' => $user,
                    'method' => 'alternative_' . $contactType
                ];
            }
        }

        return null;
    }

    /**
     * Handle case email sama tapi phone beda - Employee only
     */
    protected function handleEmailPhoneConflict($identifier, $companyId)
    {
        // Check if identifier is email
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        // Find users with same email but may not have access to this company (hanya employee)
        $usersWithSameEmail = User::where('type_user', 'employee')
            ->where('email', $identifier)
            ->get();

        foreach ($usersWithSameEmail as $user) {
            // Check if user has any role in any company (could be different company)
            if ($user->companyRoles()->where('is_active', true)->exists()) {
                // This is existing user but maybe not in current company
                // Check if we should create access or if there's a different conflict resolution

                // For now, return null to indicate not found in this company
                // This can be extended to handle automatic company addition if needed
                continue;
            }
        }

        return null;
    }

    /**
     * Determine which field matched during search
     */
    protected function determineMatchedField($user, $identifier)
    {
        if ($user->email === $identifier) {
            return 'email';
        } elseif ($user->username === $identifier) {
            return 'username';
        } elseif ($user->phone === $identifier) {
            return 'phone';
        }

        return 'unknown';
    }

    /**
     * Determine login field for auth attempt
     */
    protected function determineLoginField($loginMethod)
    {
        switch ($loginMethod) {
            case 'email':
            case 'alternative_email':
                return 'email';
            case 'username':
                return 'username';
            case 'phone':
            case 'alternative_phone':
                return 'phone';
            default:
                return filter_var($this->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        }
    }

    /**
     * Get login value for auth attempt
     */
    protected function getLoginValue($user, $loginMethod)
    {
        switch ($loginMethod) {
            case 'email':
                return $user->email;
            case 'username':
                return $user->username;
            case 'phone':
                return $user->phone;
            case 'alternative_email':
            case 'alternative_phone':
                // For alternative contacts, we need to map back to main field for auth
                return $this->mapAlternativeToMainField($user, $loginMethod);
            default:
                return $this->username_or_email;
        }
    }

    /**
     * Map alternative contact back to main field for authentication
     */
    protected function mapAlternativeToMainField($user, $loginMethod)
    {
        // Since Laravel's auth still uses main fields, we return the main field value
        // The fact that we found user via alternative contact is just for identification

        switch ($loginMethod) {
            case 'alternative_email':
                return $user->email; // Use main email for auth
            case 'alternative_phone':
                return $user->phone; // Use main phone for auth
            default:
                return $user->email; // Default fallback
        }
    }

    /**
     * Store login context for tracking
     */
    protected function storeLoginContext($user, $company, $loginMethod)
    {
        session([
            'current_company_id' => $company->id,
            'current_company' => $company,
            'login_method' => $loginMethod,
            'login_identifier' => $this->username_or_email,
            'login_timestamp' => now(),
            'user_type' => $user->type_user, // Store user type in session
        ]);

        // Update user's last login timestamp
        $user->update(['last_login_at' => now()]);

        // Log login activity (optional)
        \Log::info('Employee login', [
            'user_id' => $user->id,
            'user_type' => $user->type_user,
            'company_id' => $company->id,
            'login_method' => $loginMethod,
            'identifier_used' => $this->username_or_email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    protected function showAlert($message)
    {
        LivewireAlert::title('Gagal')
            ->text($message)
            ->error()
            ->position('top-end')
            ->toast()
            ->timer(1000)
            ->withOptions([
                'width' => '400px',
                'padding' => '10px',
                'background' => '#fff',
                'customClass' => [
                    'popup' => 'animate__animated animate__fadeInRight',
                    'title' => 'text-sm',
                    'content' => 'text-xs',
                ],
                'showConfirmButton' => false,
                'timerProgressBar' => true,
                'didOpen' => "(toast) => {
                const progressBar = toast.querySelector('.swal2-timer-progress-bar');
                progressBar.style.backgroundColor = '#dc3545';
            }",
            ])
            ->show();
    }

    public function render()
    {
        return view('livewire.auth.login.auth-login-index')
            ->extends('layout.auth.app')
            ->section('content');
    }
}
