<?php

namespace App\Services\Company;

use App\Helpers\AlertHelper;
use App\Helpers\RoleHelper;
use App\Mail\Company\CompanyMail;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Company\CompanyService as CompanyCompanyService;
use App\Models\Company\CompanyServiceHistory;
use App\Models\Company\CompanyServiceMonth;
use App\Models\Master\CodeSystem\Patient\AdministrativeGender;
use App\Models\Role\RoleCompany;
use App\Models\Service\Service;
use App\Models\Service\ServiceMonth;
use App\Models\Spatie\Role;
use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Class CompanyService.
 */
class CompanyService
{
    public function createCompany(array $data) {
        $serviceMonth = ServiceMonth::where('name', '14 (Trial)')->first();
        $image = $data['logo'] ? $data['logo']->store('company','public') : null;

        $company = Company::where('email', $data['email_company'])
            ->orWhere('phone', $data['phone'])
            ->orWhere('name', $data['name'])
            ->first();

        if ($company) {
            // Jika perusahaan sudah ada, kembalikan perusahaan yang ada
            return AlertHelper::error('Perusahaan Sudah Ada', 'Perusahaan dengan email, telepon, atau nama yang sama sudah terdaftar.');
        }

        $company = Company::create([
            'code'=> Str::random(6),
            'name' => $data['name'],
            'email' => $data['email_company'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'website' => $data['website'],
            'service_month_id' => $serviceMonth->id,
            'city' => $data['city'],
            'province' => $data['province'],
            'district' => $data['district'],
            'sub_district' => $data['sub_district'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'logo' => $image,
            'tax_id' => $data['tax_id'],
            'industry' => $data['industry'],
            'description' => $data['description'],
            'pic_name' => $data['pic_name'],
            'pic_position' => $data['pic_position'],
            'pic_email' => $data['pic_email'],
            'pic_phone' => $data['pic_phone'],
            'is_active' => true,
            'is_central' => true,
            'is_main'=>false,
            'is_lifetime' => $serviceMonth->is_lifetime,
            'expires_at'  => $serviceMonth->is_lifetime ? null : now()->addDays($serviceMonth->duration_days),
            'service_id' => $serviceMonth->service_id,
            'start_date' => now(),
            'duration_days' => $serviceMonth->is_lifetime ? 0 : $serviceMonth->duration_days,
        ]);

        $roles = Role::get();

        foreach ($roles as $role) {
            RoleCompany::create([
                'role_id'    => $role->uuid,
                'company_id' => $company->id,
            ]);
        }

        $user = User::where('phone', $data['pic_phone'])->orWhere('name', $data['pic_name'])->orWhere('email', $data['pic_email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $data['pic_name'],
                'email' => $data['pic_email'],
                'username'=> $data['username'] ?? Str::slug($data['pic_name'], '_') . '_' . Str::random(5),
                'phone' => $data['pic_phone'],
                'password' => Hash::make(Str::random(8)),
                'is_active' => true,
            ]);

            UserDetail::create([
                'user_id'=>$user->id,
                'administrative_gender'=>'male',
                'address' => $company->address,
                'status' => 'active',
            ]);
        }

        RoleHelper::assignRoleToUserInCompany($user, 'Super Admin', $company->id);

        Branch::create([
            'company_id' => $company->id,
            'name'       => 'pusat',
        ]);

        $companyService = CompanyCompanyService::create([
            'company_id'      => $company->id,
            'service_month_id'=> $serviceMonth->id,
            'start_date'      => now(),
            'duration_days'   => $serviceMonth->is_lifetime ? 0 : $serviceMonth->duration_days,
            'expires_at'      => $serviceMonth->is_lifetime ? null : now()->addDays($serviceMonth->duration_days),
            'is_lifetime'     => $serviceMonth->is_lifetime,
        ]);

        $serviceMonthDetails = ServiceMonth::where('id', $companyService->service_month_id)
                ->with('serviceMonthDetails')
                ->first()
                ->serviceMonthDetails;

        foreach ($serviceMonthDetails as $detail) {
            CompanyServiceMonth::create([
                'company_id'=> $company->id,
                'company_service_id' => $companyService->id,
                'service_month_id' => $detail->id,
                'start_date'       => now(),
                'duration_days'    => $serviceMonth->is_lifetime ? 0 : $serviceMonth->duration_days,
                'expires_at'      => $serviceMonth->is_lifetime ? null : now()->addDays($serviceMonth->duration_days),
                'is_lifetime'      => $serviceMonth->is_lifetime,
                'order'            => 0,
            ]);
        }

        Mail::to($company->email)->send(new CompanyMail($company));
    }
}
