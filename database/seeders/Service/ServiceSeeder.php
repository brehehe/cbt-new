<?php

namespace Database\Seeders\Service;

use App\Models\Service\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Free Demo
        Service::create([
            'name' => 'Rawat Inap',
            'description' => 'Rawat Inap',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Rapat Jalan',
            'description' => 'Rapat Jalan',
            'is_active' => true,
        ]);

        // Basic Plan
        Service::create([
            'name' => 'Laboratorium',
            'description' => 'Laboratorium',
            'is_active' => true,
        ]);

        // Premium Plan
        Service::create([
            'name' => 'Farmasi',
            'description' => 'Farmasi',
            'is_active' => true,
        ]);
    }
}
