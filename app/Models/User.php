<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\RoleHelper;
use App\Models\Classmate\ClassmateStudent;
use App\Models\Company\Company;
use App\Models\Study\Study;
use App\Models\User\UserCompanyRole;
use App\Models\User\UserDetail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use \App\Traits\LogsSystemActivity, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'studys' => 'array',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user_role')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function rolesInCompany($companyId)
    {
        return DB::table('company_user_role')
            ->where('user_id', $this->id)
            ->where('company_id', $companyId)
            ->pluck('role_id');
    }

    public function hasCompanyRole($roles, $companyId): bool
    {
        if (empty($companyId)) {
            return false;
        }

        // Biar fleksibel, bisa string atau array
        $roles = is_array($roles) ? $roles : [$roles];

        foreach ($roles as $role) {
            if (RoleHelper::hasCompanyRole($this, $role, $companyId)) {
                return true;
            }
        }

        return false;
    }

    public function scopeCompanyChoice($query, $companyId, $is_head = false)
    {
        return $query->whereHas('companyRoles', function ($q) use ($companyId, $is_head) {
            $q->where('company_id', $companyId);
            if ($is_head) {
                $q->where('is_head', true);
            }
        });
    }

    public function scopeCompanyRole($query, $roleName, $companyId)
    {
        return $query->whereHas('companyRoles', function ($q) use ($roleName, $companyId) {
            $q->whereHas('role', function ($qr) use ($roleName) {
                $qr->where('name', $roleName);
            })->where('company_id', $companyId);
        });
    }

    public function scopeCompanyWithoutRolePasienAndDokter($query, $companyId)
    {
        return $query->whereHas('companyRoles', function ($q) use ($companyId) {
            $q->whereHas('role', function ($qr) {
                $qr->whereNotIn('name', ['Pasien', 'Dokter']);
            })->where('company_id', $companyId);
        });
    }

    public function companyRoles()
    {
        return $this->hasMany(UserCompanyRole::class);
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    public function usrSecKey()
    {
        return $this->hasOne(UsrSecKey::class, 'user_id', 'id');
    }

    // booted method has been removed because UsrSecKey is handled by controllers

    public function hasRoleInCompany($companyId, $roleId = null)
    {
        $query = $this->companyRoles()
            ->where('company_id', $companyId)
            ->where('is_active', true);

        if ($roleId) {
            $query->where('role_id', $roleId);
        }

        return $query->exists();
    }

    public function addAlternativeContact($type, $value, $context = null, $reason = null)
    {
        $contacts = $this->alternative_contacts ?? [];

        $contacts[] = [
            'type' => $type, // email, phone
            'value' => $value,
            'context' => $context, // company_id
            'reason' => $reason, // conflict_resolution, user_preference, etc
            'added_at' => now()->toISOString(),
        ];

        $this->update(['alternative_contacts' => $contacts]);

        return $this;
    }

    public function getContactForContext($type, $context = null)
    {
        $contacts = $this->alternative_contacts ?? [];

        foreach ($contacts as $contact) {
            if ($contact['type'] === $type && $contact['context'] === $context) {
                return $contact['value'];
            }
        }

        // Fallback to main contact
        return $type === 'email' ? $this->email : $this->phone;
    }

    public function getAllEmails()
    {
        $emails = [$this->email];
        $contacts = $this->alternative_contacts ?? [];

        foreach ($contacts as $contact) {
            if ($contact['type'] === 'email') {
                $emails[] = $contact['value'];
            }
        }

        return array_unique(array_filter($emails));
    }

    public function getAllPhones()
    {
        $phones = [$this->phone];
        $contacts = $this->alternative_contacts ?? [];

        foreach ($contacts as $contact) {
            if ($contact['type'] === 'phone') {
                $phones[] = $contact['value'];
            }
        }

        return array_unique(array_filter($phones));
    }

    // Static Methods
    public static function findByEmailOrPhone($emailOrPhone)
    {
        // Check main email/phone first
        $user = static::where('email', $emailOrPhone)
            ->orWhere('phone', $emailOrPhone)
            ->orWhere('username', $emailOrPhone)
            ->first();

        if ($user) {
            return $user;
        }

        // Check alternative contacts
        return static::whereJsonContains('alternative_contacts', function ($contact) use ($emailOrPhone) {
            return ($contact['type'] === 'email' && $contact['value'] === $emailOrPhone) ||
                ($contact['type'] === 'phone' && $contact['value'] === $emailOrPhone);
        })->first();
    }

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ilike', "%{$search}%")
                ->orWhere('email', 'ilike', "%{$search}%")
                ->orWhere('phone', 'ilike', "%{$search}%")
                ->orWhereHas('userDetail', function ($qd) use ($search) {
                    $qd->where('identity_number', 'ilike', "%{$search}%")
                        ->orWhere('address', 'ilike', "%{$search}%");
                });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function classmateStudent()
    {
        return $this->hasOne(ClassmateStudent::class, 'user_id', 'id');
    }

    public function classmateStudents()
    {
        return $this->hasMany(ClassmateStudent::class, 'user_id', 'id');
    }
}
