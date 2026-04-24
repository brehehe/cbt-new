<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First check if a simulation module already exists
        $existingModule = DB::table('modules')->where('is_simulation', 'true')->first();

        if ($existingModule) {
            $this->command->info('Simulation data already exists.');

            return;
        }

        $now = Carbon::now();

        // 1. Create a Fake Module
        $moduleId = (string) Str::uuid();
        DB::table('modules')->insert([
            'id' => $moduleId,
            'name' => 'Modul Simulasi Ujian Pintar',
            'duration' => 60, // 60 minutes
            'description' => 'Modul ini dirancang khusus untuk memberikan pengalaman interaktif bagi siswa sebelum memulai rentetan ujian sesungguhnya. Soal yang diberikan merupakan contoh mudah.',
            'random_question' => false,
            'is_all_study' => true,
            'question_pick_type' => 'manual',
            'is_simulation' => 'true',
            'created_at' => $now,
            'updated_at' => $now,
            'order' => 9999,
        ]);

        // 2. Create Fake Questions
        // Single Choice Question
        $q1Id = (string) Str::uuid();
        DB::table('questions')->insert([
            'id' => $q1Id,
            'type' => 'single',
            'question' => '<p>Siapakah presiden pertama Republik Indonesia?</p>',
            'weight_correct' => 1,
            'weight_incorrect' => 0,
            'is_simulation' => 'true',
            'created_at' => $now,
            'updated_at' => $now,
            'order' => 1,
        ]);

        $ans1Id = (string) Str::uuid();
        $ans2Id = (string) Str::uuid();
        $ans3Id = (string) Str::uuid();
        $ans4Id = (string) Str::uuid();
        $ans5Id = (string) Str::uuid();

        // Single Answers
        DB::table('answers')->insert([
            ['id' => $ans1Id, 'question_id' => $q1Id, 'alphabet' => 'A', 'context' => 'Soekarno', 'is_correct' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ans2Id, 'question_id' => $q1Id, 'alphabet' => 'B', 'context' => 'Soeharto', 'is_correct' => false, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ans3Id, 'question_id' => $q1Id, 'alphabet' => 'C', 'context' => 'B.J. Habibie', 'is_correct' => false, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ans4Id, 'question_id' => $q1Id, 'alphabet' => 'D', 'context' => 'Abdurrahman Wahid', 'is_correct' => false, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ans5Id, 'question_id' => $q1Id, 'alphabet' => 'E', 'context' => 'Megawati', 'is_correct' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Essay Question
        $q2Id = (string) Str::uuid();
        DB::table('questions')->insert([
            'id' => $q2Id,
            'type' => 'essay',
            'question' => '<p>Jelaskan secara singkat tujuan dari simulasi Onboarding yang saat ini Anda ikuti.</p>',
            'weight_correct' => 5,
            'weight_incorrect' => 0,
            'is_simulation' => 'true',
            'created_at' => $now,
            'updated_at' => $now,
            'order' => 2,
        ]);

        // Map Module to Questions
        DB::table('module_questions')->insert([
            ['id' => (string) Str::uuid(), 'module_id' => $moduleId, 'question_id' => $q1Id, 'order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'module_id' => $moduleId, 'question_id' => $q2Id, 'order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. Create Timetable
        $timetableId = (string) Str::uuid();
        DB::table('timetables')->insert([
            'id' => $timetableId,
            'name' => 'Simulasi Ujian Onboarding PRO-CBT',
            'module_id' => $moduleId,
            'start_time' => Carbon::now()->subYears(1), // Always accessible
            'end_time' => Carbon::now()->addYears(10),  // Always accessible
            'code' => 'SIMULASI',
            'is_simulation' => 'true',
            'created_at' => $now,
            'updated_at' => $now,
            'order' => 9999,
        ]);

        // We also need to add them to Timetable Question (as per system requirements)
        $ttModuleId = (string) Str::uuid();
        DB::table('timetable_modules')->insert([
            'id' => $ttModuleId,
            'timetable_id' => $timetableId,
            'module_id' => $moduleId,
            'name' => 'Modul Simulasi Ujian Pintar',
            'description' => 'Modul ini dirancang khusus untuk memberikan pengalaman interaktif bagi siswa sebelum memulai rentetan ujian sesungguhnya.',
            'duration' => 60,
            'random_question' => false,
            'is_all_study' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $ttq1Id = (string) Str::uuid();
        $ttq2Id = (string) Str::uuid();

        DB::table('timetable_questions')->insert([
            [
                'id' => $ttq1Id,
                'timetable_module_id' => $ttModuleId,
                'question_id' => $q1Id,
                'question' => '<p>Siapakah presiden pertama Republik Indonesia?</p>',
                'order' => 1,
                'is_check' => true,
                'difficulty' => 'default',
                'type' => 'single',
                'weight_correct' => 1,
                'weight_incorrect' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => $ttq2Id,
                'timetable_module_id' => $ttModuleId,
                'question_id' => $q2Id,
                'question' => '<p>Jelaskan secara singkat tujuan dari simulasi Onboarding yang saat ini Anda ikuti.</p>',
                'order' => 2,
                'is_check' => true,
                'difficulty' => 'default',
                'type' => 'essay',
                'weight_correct' => 5,
                'weight_incorrect' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('timetable_answers')->insert([
            ['id' => (string) Str::uuid(), 'timetable_question_id' => $ttq1Id, 'answer_id' => $ans1Id, 'alphabet' => 'A', 'context' => 'Soekarno', 'is_correct' => true, 'order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'timetable_question_id' => $ttq1Id, 'answer_id' => $ans2Id, 'alphabet' => 'B', 'context' => 'Soeharto', 'is_correct' => false, 'order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'timetable_question_id' => $ttq1Id, 'answer_id' => $ans3Id, 'alphabet' => 'C', 'context' => 'B.J. Habibie', 'is_correct' => false, 'order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'timetable_question_id' => $ttq1Id, 'answer_id' => $ans4Id, 'alphabet' => 'D', 'context' => 'Abdurrahman Wahid', 'is_correct' => false, 'order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'timetable_question_id' => $ttq1Id, 'answer_id' => $ans5Id, 'alphabet' => 'E', 'context' => 'Megawati', 'is_correct' => false, 'order' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('Simulation Seeder executed successfully!');
    }
}
