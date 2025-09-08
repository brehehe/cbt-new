<?php

namespace Database\Seeders\Question;

use App\Models\Company\Company;
use App\Models\Master\Question\Answer;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
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
        $companys = Company::get();
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

            Study::create([
                'name' => 'Kedokteran',
                'company_id' => $company->id,
            ]);

            Study::create([
                'name' => 'Kebidanan',
                'company_id' => $company->id,
            ]);

            // Create topics for this company
            $companyTopics = [];
            foreach ($topics as $topic) {
                $topic['company_id'] = $company->id;
                $companyTopics[] = Topic::create($topic);
            }

            // Get question types for this company
            $questionTypes = QuestionType::withoutGlobalScope('user_scope')->where('company_id', $company->id)->get();

            // Only proceed if we have topics and question types
            if ($companyTopics && $questionTypes->count() > 0) {
                $faker = \Faker\Factory::create('id_ID');

                for ($i = 0; $i < 100; $i++) {
                    $randomTopic = $companyTopics[array_rand($companyTopics)];
                    $randomQuestionType = $questionTypes->random();

                    $study = Study::withoutGlobalScope('user_scope')
                        ->where('company_id', $company->id)
                        ->inRandomOrder()
                        ->first();

                    if (!$study) {
                        throw new \Exception("Belum ada Study untuk company {$company->id}");
                    }

                    $question = Question::create([
                        'topic_id'         => $randomTopic->id,
                        'question_type_id' => $randomQuestionType->id,
                        'question'         => $faker->sentence,
                        'description'      => $faker->paragraph,
                        'company_id'       => $company->id,
                        'study_id'         => $study->id,
                    ]);

                    $correctIndex = rand(0, 4);   // pilih salah satu 0‑3 secara acak

                    for ($j = 0; $j < 5; $j++) {
                        Answer::create([
                            'question_id' => $question->id,
                            // 'alphabet'   => chr(65 + $j),   // A, B, C, D (opsional)
                            'context'     => $faker->sentence,
                            'is_correct'  => $j === $correctIndex,   // hanya yang terpilih bernilai true
                            'company_id'  => $company->id,
                        ]);
                    }
                }
            }

            $faker = \Faker\Factory::create('id_ID');

            for ($a = 0; $a < 10; $a++) {
                $randomQuestion = rand(0, 1);
                $questionTypeCompany = QuestionType::withoutGlobalScope('user_scope')->where('company_id', $company->id)->inRandomOrder()->first();

                $module = Module::create([
                    'company_id' => $company->id,
                    'question_type_id' => $questionTypeCompany->id,
                    'name' => 'Modul ' . $a + 1,
                    'duration' => 120,
                    'description' => $faker->paragraph,
                    'random_question' => $randomQuestion ? true : false,
                ]);

                // Get all questions for this question type
                $questions = Question::withoutGlobalScope('user_scope')
                    ->where('question_type_id', $questionTypeCompany->id)
                    ->where('company_id', $company->id)
                    ->inRandomOrder()
                    ->take(10)
                    ->get();

                foreach ($questions as $question) {
                    ModuleQuestion::create([
                        'module_id' => $module->id,
                        'question_id' => $question->id,
                        'company_id' => $company->id,
                    ]);
                }
            }
        }
    }
}
