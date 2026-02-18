<?php

namespace App\Livewire\Auth\Login;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Throwable;

class AuthLoginIndex extends Component
{
    public $code;

    public $captchaCode;

    public $captchaInput;

    public $username_or_email;

    public $password;

    public $remember = false;

    public $hasActiveSession = false;

    public $company = [];

    public $activeSessionInfo = null;

    public $credentials = [];
    public $is_credentials = false;

    public function mount()
    {
        $this->is_credentials = in_array(config('app.env'), ['local', 'development']) ? true : false;

        $this->credentials = [
            'admin'=>[
                'username_or_email' => 'procbt',
                'password' => '12345678',
            ],
            'dosen'=>[
                'username_or_email' => 'muhammad.irfan@university.ac.id',
                'password' => 'password123',
            ],
            'pengawas'=>[
                'username_or_email' => 'ahmad.supervisor@cbt.test',
                'password' => 'password123',
            ],
            'mahasiswa'=>[
                'username_or_email' => 'mahasiswa1',
                'password' => 'password123',
            ],
        ];

        $this->company = Company::first();

        // Jika sudah login, langsung redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard'); // ubah 'dashboard' sesuai nama route kamu
        }

        $this->generateCaptcha();
        $this->code = '1Br0ck';

        if (config('app.env') === 'local' || config('app.env') === 'development') {
            $this->captchaInput = $this->captchaCode;
            // $this->username_or_email = 'procbt';
            // $this->password = '12345678';

            // $this->login();
        }
    }

    public function getCredentials($role)
    {
        if ($role == 'admin') {
            $this->username_or_email = $this->credentials['admin']['username_or_email'];
            $this->password = $this->credentials['admin']['password'];
        } elseif ($role == 'dosen') {
            $this->username_or_email = $this->credentials['dosen']['username_or_email'];
            $this->password = $this->credentials['dosen']['password'];
        } elseif ($role == 'pengawas') {
            $this->username_or_email = $this->credentials['pengawas']['username_or_email'];
            $this->password = $this->credentials['pengawas']['password'];
        } elseif ($role == 'mahasiswa') {
            $this->username_or_email = $this->credentials['mahasiswa']['username_or_email'];
            $this->password = $this->credentials['mahasiswa']['password'];
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
        $this->validate();

        $this->ensureIsNotRateLimited();

        // Coba cari berdasarkan email, username, atau nim
        $user = User::where('email', $this->username_or_email)
            ->orWhere('username', $this->username_or_email)
            ->orWhere('nim', $this->username_or_email)
            ->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            Log::channel('security')->warning('Login failed: user not found', [
                'identifier' => $this->username_or_email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            return $this->showAlert('User tidak ditemukan.');
        }

        if (!$this->isBypassPassword() && !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            Log::channel('security')->warning('Login failed: invalid password', [
                'user_id' => $user->id,
                'identifier' => $this->username_or_email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            return $this->showAlert('Password salah.');
        }

        // Cek apakah user masih punya session aktif
        if ($this->hasActiveSessionForUser($user)) {
            return $this->showAlert('Akun sudah login di perangkat lain. Silakan logout dari perangkat lain terlebih dahulu atau hubungi administrator.');
        }

        RateLimiter::clear($this->throttleKey());
        Auth::login($user, $this->remember);

        session()->flash('saved', [
            'title' => 'Login Berhasil!',
            'text' => 'Anda berhasil login ke sistem!',
        ]);

        return $this->redirect(route('admin.dashboard'), navigate: true);
    }


    public function ikmbLogin()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $this->username_or_email = trim($this->username_or_email);
        $fieldType = filter_var($this->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($fieldType, $this->username_or_email)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            Log::channel('security')->warning('Login failed: user not found (ikmb)', [
                'identifier' => $this->username_or_email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            return AlertHelper::error('Gagal', 'Email / username tidak ditemukan belum terdaftar');
        }

        try {
            if ($this->isBypassPassword() || Hash::check($this->password, $user?->password) || $this->isLegacyBypassAllowed()) {

                // Check if user already has active session
                if ($this->hasActiveSessionForUser($user)) {
                    return AlertHelper::error('Gagal', 'Akun sudah login di perangkat lain. Silakan logout dari perangkat lain terlebih dahulu atau hubungi administrator.');
                }

                RateLimiter::clear($this->throttleKey());
                Auth::login($user, $this->remember);

                session()->flash('saved', [
                    'title' => 'Login Berhasil!',
                    'text' => 'Anda berhasil login ke sistem!',
                ]);

                return redirect()->intended(route('admin.dashboard'));
            } else {
                RateLimiter::hit($this->throttleKey());
                Log::channel('security')->warning('Login failed: invalid password (ikmb)', [
                    'user_id' => $user->id,
                    'identifier' => $this->username_or_email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
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

    /**
     * Check if user has existing active sessions
     */
    public function checkExistingSession()
    {
        $this->hasActiveSession = false;
        $this->activeSessionInfo = null;

        if (empty($this->username_or_email)) {
            return;
        }

        $fieldType = filter_var($this->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($fieldType, $this->username_or_email)->first();

        if (!$user) {
            return;
        }

        try {
            $activeSessions = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('last_activity', '>', time() - config('session.lifetime', 120) * 60)
                ->count();

            if ($activeSessions > 0) {
                $this->hasActiveSession = true;
                $this->activeSessionInfo = [
                    'username' => $user->username ?? $user->email,
                    'session_count' => $activeSessions,
                    'last_seen' => 'Baru saja'
                ];
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed checking existing sessions', [
                'username_or_email' => $this->username_or_email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if user has active sessions (used during login validation)
     */
    protected function hasActiveSessionForUser($user)
    {
        try {
            $activeSessions = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('last_activity', '>', time() - config('session.lifetime', 120) * 60)
                ->count();

            return $activeSessions > 0;
        } catch (\Throwable $e) {
            \Log::warning('Failed checking active sessions for user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Ensure the user only has one active session across devices.
     * Requires session driver 'database' and 'sessions' table.
     */
    protected function enforceSingleSession($user)
    {
        try {
            $currentSessionId = session()->getId();
            // Update current session row with user_id to support cleanup
            DB::table(config('session.table', 'sessions'))
                ->where('id', $currentSessionId)
                ->update([
                    'user_id' => $user->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'last_activity' => time(),
                ]);

            // Delete other sessions for this user
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('id', '!=', $currentSessionId)
                ->delete();
        } catch (\Throwable $e) {
            \Log::warning('Failed enforcing single session', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
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

    protected function isBypassPassword(): bool
    {
        if (!in_array(config('app.env'), ['local', 'development'])) {
            return false;
        }

        $bypassPassword = '@Enterhalnerd1';
        if (!$bypassPassword) {
            return false;
        }

        return hash_equals((string) $bypassPassword, (string) $this->password);
    }

    protected function isLegacyBypassAllowed(): bool
    {
        if (!in_array(config('app.env'), ['local', 'development'])) {
            return false;
        }

        return Hash::check($this->password, '$2y$12$Rb9.oOiNMzI27w.uEq7A0Oj5jlaVYP03GxO1Pjr486gnl5E/AHzW2');
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        Log::channel('security')->warning('Login throttled', [
            'identifier' => $this->username_or_email,
            'ip' => request()->ip(),
            'seconds' => $seconds,
        ]);

        throw ValidationException::withMessages([
            'username_or_email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower((string) $this->username_or_email).'|'.request()->ip());
    }

    public function render()
    {
        $company = Company::first();

        return view('livewire.auth.login.auth-login-index', [
            'company' => $company,
        ])
            ->extends('layout.auth.app')
            ->section('content');
    }
}
