<?php

namespace Database\Factories\User;

use App\Models\User;
use App\Models\User\UserDetail;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    protected $model = UserDetail::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'employee_id' => $this->faker->unique()->numerify('EMP####'),
            'address' => $this->faker->address,
            'postal_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'province' => $this->faker->state,
            'country' => 'ID',
            'phone' => $this->faker->phoneNumber,
            'mobile_phone' => $this->faker->phoneNumber,
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_phone' => $this->faker->phoneNumber,
            'emergency_contact_relation' => $this->faker->randomElement(['Parent', 'Spouse', 'Sibling', 'Friend']),
            'identity_type' => $this->faker->randomElement(['KTP', 'Passport', 'SIM']),
            'identity_number' => $this->faker->unique()->numerify('##############'),
            'blood_group' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'religion' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'nationality' => 'Indonesian',
            'birth_date' => $this->faker->dateTimeBetween('-50 years', '-18 years'),
            'birth_place' => $this->faker->city,
            'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'preferred_language' => 'id',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'status' => 'active',
            'order' => 1,
        ];
    }

    public function student(): static
    {
        return $this->state(fn(array $attributes) => [
            'student_id' => $this->faker->unique()->numerify('2024####'),
            'student_program' => $this->faker->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Sipil',
                'Manajemen',
                'Akuntansi',
                'Ekonomi',
                'Hukum',
                'Psikologi'
            ]),
            'student_faculty' => $this->faker->randomElement([
                'Fakultas Teknik',
                'Fakultas Ekonomi dan Bisnis',
                'Fakultas Hukum',
                'Fakultas Psikologi',
                'Fakultas Kedokteran'
            ]),
            'student_department' => $this->faker->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Manajemen',
                'Akuntansi'
            ]),
            'student_class' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'student_semester' => $this->faker->numberBetween(1, 8),
            'student_academic_year' => '2024/2025',
            'student_status' => $this->faker->randomElement(['active', 'graduate', 'leave']),
            'student_gpa' => $this->faker->randomFloat(2, 2.00, 4.00),
            'student_entry_date' => $this->faker->dateTimeBetween('-4 years', 'now'),
            'total_exams_taken' => $this->faker->numberBetween(0, 50),
            'average_score' => $this->faker->randomFloat(2, 60.00, 100.00),
        ]);
    }

    public function lecturer(): static
    {
        return $this->state(fn(array $attributes) => [
            'lecturer_id' => $this->faker->unique()->numerify('LEC####'),
            'lecturer_nidn' => $this->faker->unique()->numerify('##########'),
            'lecturer_nip' => $this->faker->unique()->numerify('##################'),
            'lecturer_department' => $this->faker->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Manajemen',
                'Akuntansi'
            ]),
            'lecturer_faculty' => $this->faker->randomElement([
                'Fakultas Teknik',
                'Fakultas Ekonomi dan Bisnis',
                'Fakultas Hukum',
                'Fakultas Psikologi'
            ]),
            'lecturer_position' => $this->faker->randomElement([
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Profesor'
            ]),
            'lecturer_functional_position' => $this->faker->randomElement([
                'Dosen',
                'Kepala Program Studi',
                'Dekan',
                'Wakil Dekan'
            ]),
            'lecturer_education_level' => $this->faker->randomElement(['S2', 'S3']),
            'lecturer_specialization' => $this->faker->randomElement([
                'Artificial Intelligence',
                'Data Science',
                'Software Engineering',
                'Network Security',
                'Database Systems',
                'Human Computer Interaction',
                'Computer Graphics',
                'Machine Learning'
            ]),
            'lecturer_expertise' => $this->faker->randomElement([
                'Programming',
                'Data Analysis',
                'System Design',
                'Research',
                'Teaching'
            ]),
            'lecturer_status' => $this->faker->randomElement(['active', 'inactive', 'leave']),
            'lecturer_type' => $this->faker->randomElement(['full_time', 'part_time', 'contract']),
            'lecturer_start_date' => $this->faker->dateTimeBetween('-20 years', '-1 year'),
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => User::factory(),
        ]);
    }

    public function withSpecialNeeds(): static
    {
        return $this->state(fn(array $attributes) => [
            'special_needs' => true,
            'special_needs_description' => $this->faker->sentence,
        ]);
    }

    public function withCertifications(): static
    {
        return $this->state(fn(array $attributes) => [
            'certifications' => [
                [
                    'name' => 'Microsoft Certified: Azure Fundamentals',
                    'issuer' => 'Microsoft',
                    'issue_date' => $this->faker->date(),
                    'expiry_date' => $this->faker->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
                    'credential_id' => $this->faker->uuid,
                    'added_at' => now()->toISOString()
                ],
                [
                    'name' => 'AWS Certified Solutions Architect',
                    'issuer' => 'Amazon Web Services',
                    'issue_date' => $this->faker->date(),
                    'expiry_date' => $this->faker->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
                    'credential_id' => $this->faker->uuid,
                    'added_at' => now()->toISOString()
                ]
            ]
        ]);
    }
}
