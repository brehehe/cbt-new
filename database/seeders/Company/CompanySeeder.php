<?php

namespace Database\Seeders\Company;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Company\CompanyDetail;
use App\Models\Company\CompanyService;
use App\Models\Company\CompanyServiceHistory;
use App\Models\Service\Service;
use App\Models\Service\ServiceMonth;
use App\Models\Spatie\Role;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\RoleHelper;
use App\Models\Company\CompanyServiceMonth;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\Patient\AdministrativeGender;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Poly\Poly;
use App\Models\Role\RoleCompany;
use App\Models\User\UserDetail;
use App\service\apiservice;
use App\Services\System\Organization\OrganizationService;
use Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ 
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Dosen'],
            ['name' => 'Mahasiswa'],
            ['name' => 'Pengawas'],
            ['name' => 'Anonymous'], // Tanpa company_id
            // ['name' => 'Sales'],
            // ['name' => 'Medis'],
        ];

        foreach ($roles as $role) {
            Role::create([
                'name'        => $role['name'],
                'guard_name'  => 'web',
            ]);
        }

        $serviceMonth = ServiceMonth::where('name', 'Lifetime')->first();

        $company_datas = [
            [
                'code'    => '1Br0ck',                                                      // Str::random(6)
                'name'    => 'Burningroom PRO CBT',
                'email'   => 'burningroomPRO CBT@gmail.com',
                'phone'   => '08' . Factory::create()->numberBetween(100000000, 999999999),
                'website' => 'https://burningroom.co.id',
                'service_id' => $serviceMonth->id,

                'address'      => Factory::create()->streetAddress(),
                'province'     => 35,
                'city'         => 3578,
                'district'     => 357809,
                'sub_district' => 3578091005,
                'postal_code'  => Factory::create()->postcode(),
                'country'      => 'Indonesia',

                'pic_name'     => 'Burningroom Technology',
                'pic_position' => 'CEO',
                'pic_email'    => 'burningroomofficial.co.id@gmail.com',
                'pic_phone'    => '08' . Factory::create()->numberBetween(100000000, 999999999),

                'is_active'   => true,
                'is_central'  => true,
                'is_main'     => true,
                'is_lifetime' => true,
                'expires_at'  => $serviceMonth->is_lifetime ? null : now()->addDays($serviceMonth->duration_days),

                'roles'      => ['Anonymous'],
                'company_detail' => [
                    'one_health_code'   => '1004946874',
                    'facility_code'     => '35780100662',
                    'organization_id'   => '100494687',
                    'longitude'         => '112.75353',
                    'latitude'          => '-7.31622',
                    'province_code'     => 35,
                    'city_code'         => 3515,
                    'district_code'     => 351508,
                    'sub_district_code' => 3515081005,
                    'postal_code'       => 12345,
                    'address'           => "Jl. Raya Jemursari No.240A, Surabaya",
                    'country'           => 'ID',
                    'rt'                => 001,
                    'rw'                => 002,
                ],
            ],
        ];

        foreach ($company_datas as $key => $company_data) {
            $company = Company::create([
                'code'    => $company_data['code'],
                'name'    => $company_data['name'],
                'email'   => $company_data['email'],
                'phone'   => $company_data['phone'],
                'website' => $company_data['website'],

                'pic_name'     => $company_data['pic_name'],
                'pic_position' => $company_data['pic_position'],
                'pic_email'    => $company_data['pic_email'],
                'pic_phone'    => $company_data['pic_phone'],

                'is_active'   => $company_data['is_active'],
                'is_central'  => $company_data['is_central'],
                'is_main'     => $company_data['is_main'],
                'is_lifetime' => $serviceMonth->is_lifetime,
                'expires_at'  => $company_data['expires_at'],
                'service_id' => $company_data['service_id'],
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

            $user = User::create([
                'name'              => $company->pic_name,
                'email'             => $company->email_company,
                'username'          => strtolower(str_replace(' ', '', $company->pic_name)),
                'password'          => bcrypt('12345678'),                                     // Default password, should be changed
                'email_verified_at' => now(),
                'company_id'        => $company->id,
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                // 'administrative_gender' => AdministrativeGender::first()->code,
                'address' => $company->address,
                'status' => 'active',
            ]);

            $user->assignRole('Anonymous');

            RoleHelper::assignRoleToUserInCompany($user, 'Admin', $company->id, null, true, true);

            if (isset($company_data['company_detail'])) {
                $company?->companyDetail()->create(
                    [
                        'one_health_code'   => $company_data['company_detail']['one_health_code'],
                        'facility_code'     => $company_data['company_detail']['facility_code'],
                        'organization_id'   => $company_data['company_detail']['organization_id'],
                        'longitude'         => $company_data['company_detail']['longitude'],
                        'latitude'          => $company_data['company_detail']['latitude'],
                        'province_code'     => $company_data['company_detail']['province_code'],
                        'city_code'         => $company_data['company_detail']['city_code'],
                        'district_code'     => $company_data['company_detail']['district_code'],
                        'sub_district_code' => $company_data['company_detail']['sub_district_code'],
                        'postal_code'       => $company_data['company_detail']['postal_code'],
                        'address'           => $company_data['company_detail']['address'],
                        'rt'                => $company_data['company_detail']['rt'],
                        'rw'                => $company_data['company_detail']['rw'],
                    ]
                );
            }
        }
    }
}
