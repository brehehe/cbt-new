<?php

namespace Database\Seeders\Account;

use App\Models\Account\CategoryAccount;
use App\Models\Company\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategoryAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/csvs/category_account.csv');

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

                CategoryAccount::create([
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'cash_flow' => $data['cash_flow'] === 'undefined' ? 'undefined' : $data['cash_flow'],
                    'detail_category_account_id' => $data['detail_category_account_id'],
                    'company_id' => $value->id,
                ]);
            }
        }
    }
}
