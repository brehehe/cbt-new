<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Company\CompanySeeder;
use Database\Seeders\Country\CountrySeeder;
use Database\Seeders\Master\Question\QuestionTypeSeeder;
use Database\Seeders\Question\QuestionSeeder;
use Database\Seeders\Service\ServiceMonthSeeder;
use Database\Seeders\Service\ServiceSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('optimize:clear');

        $this->call([
            ServiceSeeder::class,
            ServiceMonthSeeder::class,

            // Region
            CountrySeeder::class,

            // Company
            CompanySeeder::class,

            //Master
            QuestionTypeSeeder::class,

            // Question
            UserDetailSeeder::class,
        ]);
        if (env('QUESTION_PACKAGE', 'NO_PACKAGE')) {
            $this->call([
                QuestionSeeder::class,
            ]);
        } else {
            $this->call([
                QuestionPaketSeeder::class,
            ]);
        }
    }
}
