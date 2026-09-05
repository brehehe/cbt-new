<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Buat data company default agar view tidak error saat mencoba membaca propertinya
        DB::table('companies')->insertOrIgnore([
            'id' => Str::uuid(),
            'name' => 'Pro CBT',
            'code' => 'PRCBT1',
            'email' => 'admin@procbt.com',
            'phone' => '08123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat role admin agar assignRole di DashboardTest tidak error
        DB::table('roles')->insertOrIgnore([
            'uuid' => Str::uuid(),
            'name' => 'admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
