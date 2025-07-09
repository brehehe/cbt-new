<?php

namespace Database\Seeders\Account;

use App\Models\Account\Account;
use App\Models\Company\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/csvs/account.csv');

        if (!File::exists($file)) {
            $this->command->error("File $file tidak ditemukan.");
            return;
        }

        $csv = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($csv)); // Ambil header pertama

        $companys = Company::select('id')->get();

        foreach ($companys as $key => $value) {
            foreach ($csv as $row) {
                $data = array_combine($header, $row);

                Account::create([
                    // 'id' => $data['id'],
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'is_cash' => $data['is_cash'] === '1' ? true : false,
                    'category_account_id' => $data['category_account_id'],
                    'company_id' => $value->id,
                ]);
            }
        }
    }
}
