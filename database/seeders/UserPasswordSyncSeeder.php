<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPasswordSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::chunk(200, function ($users) {
            \App\Models\UsrSecKey::truncate();
            foreach ($users as $user) {
                // Determine raw password based on Admin role in their company
                $rawPassword = $user->HasRole(['Admin']) ? '12345678' : 'password123';
                
                \App\Models\UsrSecKey::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_id' => $user->company_id,
                        'sec_val' => encrypt($rawPassword)
                    ]
                );
            }
        });
        
        $this->command->info('All user passports have been encrypted conditionally (Admin 12345678, others password123) successfully.');
    }
}
