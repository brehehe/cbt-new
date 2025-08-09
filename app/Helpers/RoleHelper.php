<?php

namespace App\Helpers;

use App\Models\Role\RoleCompany;
use App\Models\Spatie\Role;
use App\Models\User\UserCompanyRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoleHelper
{

    public static function assignRoleToUserInCompany($user, $roleName, $companyId, $medicalRecordNumber = null, $is_head = null, $is_active = null)
    {
        $is_head   = $is_head ?? false;
        $is_active = $is_active ?? true;

        // Cari role global dulu
        $role = Role::where('name', $roleName)->firstOrFail();

        $user->syncRoles($roleName);

        // Cari mapping role_company sesuai role_id dan company_id
        $roleCompany = RoleCompany::where('role_id', $role->uuid)
            ->where('company_id', $companyId)
            ->firstOrFail();

        // Cek apakah user sudah punya role di company ini
        $userCompanyRole = UserCompanyRole::where('user_id', $user->id)
            ->where('company_id', $companyId)
            ->where('role_company_id', $roleCompany->id)  // pastikan pakai role_company_id
            ->first();

        UserCompanyRole::updateOrCreate([
            'user_id' => $user->id,
            'company_id' => $companyId,
            'role_id' => $role->uuid,
            'role_company_id' => $roleCompany->id,
        ], [
            'medical_record_number' => $userCompanyRole
                ? $userCompanyRole->medical_record_number
                : ($medicalRecordNumber
                    ? $medicalRecordNumber
                    : ($role->name == 'Pasien'
                        ? 'PMR' . date('ymd') . str_pad(UserCompanyRole::where('company_id', $companyId)
                            ->whereDate('created_at', Carbon::now())->count() + 1, 5, '0', STR_PAD_LEFT)
                        : null)),
            'is_head' => $is_head,
            'is_active' => $is_active,
        ]);
    }

    public static function hasCompanyRole($user, string $roleName, string $companyId): bool
    {
        // Cari role global
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return false;
        }

        // Cari mapping role_company
        $roleCompany = RoleCompany::where('role_id', $role->uuid)
            ->where('company_id', $companyId)
            ->first();

        if (!$roleCompany) {
            return false;
        }

        // Cek di tabel user_company_role dengan role_company_id
        return UserCompanyRole::where('user_id', $user->id)
            ->where('role_id', $role->uuid)
            ->where('role_company_id', $roleCompany->id)
            ->where('company_id', $companyId)
            ->exists();
    }
}
