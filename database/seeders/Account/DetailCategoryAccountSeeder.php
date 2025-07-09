<?php

namespace Database\Seeders\Account;

use App\Models\Account\Account;
use App\Models\Account\CategoryAccount;
use App\Models\Account\DetailCategoryAccount;
use App\Models\Company\Company;
use App\Models\PaymentMethod\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class DetailCategoryAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $file = database_path('seeders/csvs/detail_category_account.csv');
        if (!File::exists($file)) {
            $this->command->error("File $file tidak ditemukan.");
            return;
        }
        $csv = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($csv));

        $fileCategory = database_path('seeders/csvs/category_account.csv');
        if (!File::exists($fileCategory)) {
            $this->command->error("File $fileCategory tidak ditemukan.");
            return;
        }
        $csvCategory = array_map('str_getcsv', file($fileCategory));
        $headerCategory = array_map('trim', array_shift($csvCategory));

        $fileAccount = database_path('seeders/csvs/account.csv');
        if (!File::exists($fileAccount)) {
            $this->command->error("File $fileAccount tidak ditemukan.");
            return;
        }
        $csvAccount = array_map('str_getcsv', file($fileAccount));
        $headerAccount = array_map('trim', array_shift($csvAccount));

        // Ambil semua company
        $companies = Company::select('id')->get();

        foreach ($companies as $company) {
            // Buat semua DetailCategoryAccount untuk perusahaan ini
            foreach ($csv as $row) {
                $data = array_combine($header, $row);

                DetailCategoryAccount::create([
                    'name' => $data['name'],
                    'company_id' => $company->id,
                    'type' => $data['type'],
                ]);
            }

            // Buat semua CategoryAccount terkait
            foreach ($csvCategory as $categoryRow) {
                $dataCategory = array_combine($headerCategory, $categoryRow);

                $categoryAccount = CategoryAccount::create([
                    'name' => $dataCategory['name'],
                    'cash_flow' => $dataCategory['cash_flow'] === 'undefined' ? 'undefined' : $dataCategory['cash_flow'],
                    'detail_category_account_id' => DetailCategoryAccount::where('name', $dataCategory['detail_category_account_name'])
                        ->where('company_id', $company->id)
                        ->first()->id ?? null, // Ambil ID dari DetailCategoryAccount yang sesuai
                    'company_id' => $company->id,
                ]);
            }

            // Buat semua Account terkait
            foreach ($csvAccount as $accountRow) {
                $dataAccount = array_combine($headerAccount, $accountRow);

                Account::create([
                    'name' => $dataAccount['name'],
                    'code' => $dataAccount['code'],
                    'is_cash' => $dataAccount['is_cash'] === '1' ? true : false,
                    'category_account_id' => CategoryAccount::where('name', $dataAccount['category_account_name'])
                        ->where('company_id', $company->id)
                        ->first()->id ?? null, // Ambil ID dari CategoryAccount yang sesuai
                    'company_id' => $company->id,
                ]);
            }


            $paymentMethods = PaymentMethod::where('company_id', $company->id)->get();
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethod->account_id = Account::where('company_id', $company->id)
                    ->orderBy('order', 'asc')
                    ->first()->id ?? null; // Ambil ID dari Account yang sesuai
                $paymentMethod->save();
            }
        }
    }
}
