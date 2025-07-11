<?php

namespace Database\Seeders\Question;

use App\Models\Company\Company;
use App\Models\Master\Question\Answer;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $companys = Company::select('id')->get();
        $topics = [
            ['name' => 'Sistem Saraf'],
            ['name' => 'Sistem Pernapasan'],
            ['name' => 'Sistem Gigi'],
            ['name' => 'Sistem Tulang'],
            ['name' => 'Sistem Kelenjar'],
            ['name' => 'Sistem Pencernaan'],
            ['name' => 'Sistem Peredaran Darah'],
            ['name' => 'Sistem Otot'],
            ['name' => 'Sistem Reproduksi'],
            ['name' => 'Sistem Imun']
        ];
        foreach ($companys as $company) {
            // Create topics for this company
            $companyTopics = [];
            foreach ($topics as $topic) {
                $topic['company_id'] = $company->id;
                $companyTopics[] = Topic::create($topic);
            }

            // Get question types for this company
            $questionTypes = QuestionType::get();

            // Only proceed if we have topics and question types
            if ($companyTopics && $questionTypes->count() > 0) {
                $faker = \Faker\Factory::create('id_ID');

                for ($i = 0; $i < 100; $i++) {
                    $randomTopic = $companyTopics[array_rand($companyTopics)];
                    $randomQuestionType = $questionTypes->random();

                    $question = Question::create([
                        'topic_id' => $randomTopic->id,
                        'question_type_id' => $randomQuestionType->id,
                        'question' => $faker->sentence,
                        'description' => $faker->paragraph,
                        'company_id' => $company->id,
                    ]);

                    $correctIndex = rand(0, 4);   // pilih salah satu 0‑3 secara acak

                    for ($j = 0; $j < 5; $j++) {
                        Answer::create([
                            'question_id' => $question->id,
                            // 'alphabet'   => chr(65 + $j),   // A, B, C, D (opsional)
                            'context'     => $faker->sentence,
                            'is_correct'  => $j === $correctIndex,   // hanya yang terpilih bernilai true
                            'company_id'  => $company->id,
                        ]);
                    }
                }
            }
        }
    }
}
