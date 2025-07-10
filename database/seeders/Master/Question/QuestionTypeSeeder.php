<?php

namespace Database\Seeders\Master\Question;

use App\Models\Master\Question\QuestionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datas = [
            [
                'name'        => 'PMB',
                'description' => 'Penerimaan Mahasiswa Baru'
            ],
            [
                'name'        => 'Ujian',
                'description' => 'Ujian'
            ],
        ];

        foreach ($datas as $key => $data) {
            QuestionType::create([
                'name'        => $data['name'],
                'description' => $data['description'],
            ]);
        }
    }
}
