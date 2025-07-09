<?php

namespace Database\Seeders\SystemSetting;

use App\Models\Company\Company;
use App\Models\SystemSetting\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PHPUnit\Event\Telemetry\System;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SystemSetting::create([
            'tax' => 10,
            'company_id' => Company::first()->id,
        ]);
    }
}
