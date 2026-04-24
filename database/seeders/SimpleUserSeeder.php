<?php

namespace Database\Seeders;

use App\Helpers\RoleHelper;
use App\Models\Company\Company;
use App\Models\Spatie\Role;
use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimpleUserSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = Company::first()->id; // Assuming you have a company with ID 1

        // Create roles if they don't exist
        $studentRole = Role::firstOrCreate([
            'name' => 'Mahasiswa',
            'guard_name' => 'web',
        ]);

        $lecturerRole = Role::firstOrCreate([
            'name' => 'Dosen',
            'guard_name' => 'web',
        ]);

        // Create a sample student
        $student = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@student.test.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        $student->assignRole('Mahasiswa');

        UserDetail::create([
            'user_id' => $student->id,
            'student_id' => '20241001',
            'student_program' => 'Teknik Informatika',
            'student_faculty' => 'Fakultas Teknik',
            'student_department' => 'Teknik Informatika',
            'student_semester' => '6',
            'birth_place' => 'Jakarta',
            'birth_date' => '2002-03-15',
            'gender' => 'male',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'status' => 'active',
        ]);

        RoleHelper::assignRoleToUserInCompany($student, 'Mahasiswa', $companyId);

        // Create a sample lecturer
        $lecturer = User::create([
            'name' => 'Dr. Muhammad Irfan, S.Kom., M.T.',
            'email' => 'muhammad.irfan@test.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        $lecturer->assignRole('Dosen');

        UserDetail::create([
            'user_id' => $lecturer->id,
            'lecturer_id' => 'LEC001',
            'lecturer_nidn' => '0315088901',
            'lecturer_department' => 'Teknik Informatika',
            'lecturer_faculty' => 'Fakultas Teknik',
            'lecturer_position' => 'Lektor Kepala',
            'lecturer_education_level' => 'S3',
            'lecturer_specialization' => 'Artificial Intelligence',
            'birth_place' => 'Jakarta',
            'birth_date' => '1979-08-15',
            'gender' => 'male',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'status' => 'active',
        ]);

        RoleHelper::assignRoleToUserInCompany($lecturer, 'Dosen', $companyId);
    }
}
