<?php

namespace Database\Seeders;

use App\Helpers\RoleHelper;
use App\Models\Company\Company;
use App\Models\User;
use App\Models\User\UserDetail;
use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        if (!Role::where('name', 'Mahasiswa')->exists()) {
            Role::create([
                'name' => 'Mahasiswa',
                'guard_name' => 'web'
            ]);
        }
        if (!Role::where('name', 'Dosen')->exists()) {
            Role::create([
                'name' => 'Dosen',
                'guard_name' => 'web'
            ]);
        }
        if (!Role::where('name', 'Pengawas')->exists()) {
            Role::create([
                'name' => 'Pengawas',
                'guard_name' => 'web'
            ]);
        }

        $companyId = Company::first()->id; // Assuming you have a company with ID 1

        // Create sample students
        $this->createSampleStudents($companyId);

        // Create sample lecturers
        $this->createSampleLecturers($companyId);

        // Create sample supervisors
        $this->createSampleSupervisors($companyId);
    }

    private function createSampleStudents($companyId)
    {
        $studentData = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@student.university.ac.id',
                'student_id' => '20241001',
                'student_program' => 'Teknik Informatika',
                'student_faculty' => 'Fakultas Teknik',
                'student_department' => 'Teknik Informatika',
                'student_semester' => '6',
                'birth_place' => 'Jakarta',
                'birth_date' => '2002-03-15',
                'gender' => 'male'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.university.ac.id',
                'student_id' => '20241002',
                'student_program' => 'Sistem Informasi',
                'student_faculty' => 'Fakultas Teknik',
                'student_department' => 'Sistem Informasi',
                'student_semester' => '4',
                'birth_place' => 'Bandung',
                'birth_date' => '2003-07-22',
                'gender' => 'female'
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.university.ac.id',
                'student_id' => '20241003',
                'student_program' => 'Teknik Elektro',
                'student_faculty' => 'Fakultas Teknik',
                'student_department' => 'Teknik Elektro',
                'student_semester' => '2',
                'birth_place' => 'Surabaya',
                'birth_date' => '2004-01-10',
                'gender' => 'male'
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@student.university.ac.id',
                'student_id' => '20241004',
                'student_program' => 'Manajemen',
                'student_faculty' => 'Fakultas Ekonomi dan Bisnis',
                'student_department' => 'Manajemen',
                'student_semester' => '8',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '2001-11-05',
                'gender' => 'female'
            ],
            [
                'name' => 'Reza Pratama',
                'email' => 'reza.pratama@student.university.ac.id',
                'student_id' => '20241005',
                'student_program' => 'Akuntansi',
                'student_faculty' => 'Fakultas Ekonomi dan Bisnis',
                'student_department' => 'Akuntansi',
                'student_semester' => '3',
                'birth_place' => 'Medan',
                'birth_date' => '2003-09-18',
                'gender' => 'male'
            ],
            [
                'name' => 'Mahasiswa 1',
                'email' => 'mahasiswa1@gmail.com',
                'student_id' => '0001',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 2',
                'email' => 'mahasiswa2@gmail.com',
                'student_id' => '0002',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 3',
                'email' => 'mahasiswa3@gmail.com',
                'student_id' => '0003',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 4',
                'email' => 'mahasiswa4@gmail.com',
                'student_id' => '0004',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 5',
                'email' => 'mahasiswa5@gmail.com',
                'student_id' => '0005',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 6',
                'email' => 'mahasiswa6@gmail.com',
                'student_id' => '0006',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 7',
                'email' => 'mahasiswa7@gmail.com',
                'student_id' => '0007',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 8',
                'email' => 'mahasiswa8@gmail.com',
                'student_id' => '0008',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 9',
                'email' => 'mahasiswa9@gmail.com',
                'student_id' => '0009',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
            [
                'name' => 'Mahasiswa 10',
                'email' => 'mahasiswa10@gmail.com',
                'student_id' => '00010',
                'student_program' => 'Kedokteran',
                'student_faculty' => 'Fakultas Kedokteran',
                'student_department' => 'Kedokteran',
                'student_semester' => '3',
                'birth_place' => 'Surabaya',
                'birth_date' => '2003-09-18',
                'gender' => 'male',
                'password' => 'User1234',
            ],
        ];

        foreach ($studentData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => Str::replace(' ', '', strtolower($data['name'])),
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'company_id' => $companyId,
            ]);

            $user->assignRole('Mahasiswa');

            UserDetail::create([
                'user_id' => $user->id,
                'student_id' => $data['student_id'],
                'student_program' => $data['student_program'],
                'student_faculty' => $data['student_faculty'],
                'student_department' => $data['student_department'],
                'student_class' => 'A',
                'student_semester' => $data['student_semester'],
                'student_academic_year' => '2024/2025',
                'student_status' => 'active',
                'student_entry_date' => '2024-09-01',
                'student_gpa' => rand(300, 400) / 100,
                'birth_place' => $data['birth_place'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'religion' => 'Islam',
                'nationality' => 'Indonesian',
                'marital_status' => 'single',
                'address' => 'Jl. Contoh No. ' . rand(1, 100),
                'city' => $data['birth_place'],
                'province' => 'DKI Jakarta',
                'country' => 'ID',
                'phone' => '021' . rand(10000000, 99999999),
                'mobile_phone' => '08' . rand(1000000000, 9999999999),
                'identity_type' => 'KTP',
                'identity_number' => rand(1000000000000000, 9999999999999999),
                'blood_group' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                'emergency_contact_name' => 'Orang Tua',
                'emergency_contact_phone' => '08' . rand(1000000000, 9999999999),
                'emergency_contact_relation' => 'Parent',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'status' => 'active',
                'total_exams_taken' => rand(5, 25),
                'average_score' => rand(6500, 9500) / 100
            ]);

            RoleHelper::assignRoleToUserInCompany($user, 'Mahasiswa', $companyId);
        }
    }

    private function createSampleLecturers($companyId)
    {
        $lecturerData = [
            [
                'name' => 'Dr. Muhammad Irfan, S.Kom., M.T.',
                'email' => 'muhammad.irfan@university.ac.id',
                'lecturer_id' => 'LEC001',
                'lecturer_nidn' => '0315088901',
                'lecturer_nip' => '197908152008121001',
                'lecturer_department' => 'Teknik Informatika',
                'lecturer_faculty' => 'Fakultas Teknik',
                'lecturer_position' => 'Lektor Kepala',
                'lecturer_education_level' => 'S3',
                'lecturer_specialization' => 'Artificial Intelligence',
                'birth_place' => 'Jakarta',
                'birth_date' => '1979-08-15',
                'gender' => 'male'
            ],
            [
                'name' => 'Prof. Dr. Sari Wijayanti, S.Si., M.Kom.',
                'email' => 'sari.wijayanti@university.ac.id',
                'lecturer_id' => 'LEC002',
                'lecturer_nidn' => '0310077502',
                'lecturer_nip' => '197507102002122001',
                'lecturer_department' => 'Sistem Informasi',
                'lecturer_faculty' => 'Fakultas Teknik',
                'lecturer_position' => 'Profesor',
                'lecturer_education_level' => 'S3',
                'lecturer_specialization' => 'Data Science',
                'birth_place' => 'Bandung',
                'birth_date' => '1975-07-10',
                'gender' => 'female'
            ],
            [
                'name' => 'Bambang Kurniawan, S.T., M.T.',
                'email' => 'bambang.kurniawan@university.ac.id',
                'lecturer_id' => 'LEC003',
                'lecturer_nidn' => '0320118301',
                'lecturer_nip' => '198311202010121002',
                'lecturer_department' => 'Teknik Elektro',
                'lecturer_faculty' => 'Fakultas Teknik',
                'lecturer_position' => 'Lektor',
                'lecturer_education_level' => 'S2',
                'lecturer_specialization' => 'Network Security',
                'birth_place' => 'Surabaya',
                'birth_date' => '1983-11-20',
                'gender' => 'male'
            ],
            [
                'name' => 'Dr. Rina Kartika, S.E., M.M.',
                'email' => 'rina.kartika@university.ac.id',
                'lecturer_id' => 'LEC004',
                'lecturer_nidn' => '0305068001',
                'lecturer_nip' => '198006052005122002',
                'lecturer_department' => 'Manajemen',
                'lecturer_faculty' => 'Fakultas Ekonomi dan Bisnis',
                'lecturer_position' => 'Lektor Kepala',
                'lecturer_education_level' => 'S3',
                'lecturer_specialization' => 'Strategic Management',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '1980-06-05',
                'gender' => 'female'
            ],
            [
                'name' => 'Agus Setiawan, S.E., M.Ak.',
                'email' => 'agus.setiawan@university.ac.id',
                'lecturer_id' => 'LEC005',
                'lecturer_nidn' => '0312098501',
                'lecturer_nip' => '198509122012121001',
                'lecturer_department' => 'Akuntansi',
                'lecturer_faculty' => 'Fakultas Ekonomi dan Bisnis',
                'lecturer_position' => 'Asisten Ahli',
                'lecturer_education_level' => 'S2',
                'lecturer_specialization' => 'Financial Accounting',
                'birth_place' => 'Medan',
                'birth_date' => '1985-09-12',
                'gender' => 'male'
            ]
        ];

        foreach ($lecturerData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => Str::replace(' ', '', strtolower($data['name'])),
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'company_id' => $companyId,
            ]);

            $user->assignRole('Dosen');

            UserDetail::create([
                'user_id' => $user->id,
                'lecturer_id' => $data['lecturer_id'],
                'lecturer_nidn' => $data['lecturer_nidn'],
                'lecturer_nip' => $data['lecturer_nip'],
                'lecturer_department' => $data['lecturer_department'],
                'lecturer_faculty' => $data['lecturer_faculty'],
                'lecturer_position' => $data['lecturer_position'],
                'lecturer_functional_position' => 'Dosen',
                'lecturer_education_level' => $data['lecturer_education_level'],
                'lecturer_specialization' => $data['lecturer_specialization'],
                'lecturer_expertise' => 'Teaching and Research',
                'lecturer_status' => 'active',
                'lecturer_type' => 'full_time',
                'lecturer_start_date' => '2010-09-01',
                'birth_place' => $data['birth_place'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'religion' => 'Islam',
                'nationality' => 'Indonesian',
                'marital_status' => 'married',
                'address' => 'Jl. Dosen No. ' . rand(1, 50),
                'city' => $data['birth_place'],
                'province' => 'DKI Jakarta',
                'country' => 'ID',
                'phone' => '021' . rand(10000000, 99999999),
                'mobile_phone' => '08' . rand(1000000000, 9999999999),
                'identity_type' => 'KTP',
                'identity_number' => rand(1000000000000000, 9999999999999999),
                'blood_group' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                'emergency_contact_name' => 'Keluarga',
                'emergency_contact_phone' => '08' . rand(1000000000, 9999999999),
                'emergency_contact_relation' => 'Spouse',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'status' => 'active',
                'certifications' => [
                    [
                        'name' => 'Sertifikat Pendidik',
                        'issuer' => 'Kementerian Pendidikan',
                        'issue_date' => '2015-01-01',
                        'credential_id' => 'CERT' . rand(100000, 999999),
                        'added_at' => now()->toISOString()
                    ]
                ]
            ]);

            RoleHelper::assignRoleToUserInCompany($user, 'Dosen', $companyId);
        }
    }

    private function createSampleSupervisors($companyId)
    {
        $supervisorsData = [
            [
                'name' => 'Dr. Ahmad Supervisor',
                'email' => 'ahmad.supervisor@cbt.test',
                'employee_id' => 'SUP001',
                'phone' => '081234567890',
                'department' => 'Academic Affairs',
                'position' => 'Head of Academic Affairs',
                'specialization' => 'Educational Administration',
                'experience_years' => 15,
                'hire_date' => '2010-01-15',
            ],
            [
                'name' => 'Prof. Siti Monitoring',
                'email' => 'siti.monitoring@cbt.test',
                'employee_id' => 'SUP002',
                'phone' => '081234567891',
                'department' => 'Quality Assurance',
                'position' => 'Quality Assurance Manager',
                'specialization' => 'Educational Quality Management',
                'experience_years' => 20,
                'hire_date' => '2005-03-20',
            ],
            [
                'name' => 'Dr. Budi Evaluator',
                'email' => 'budi.evaluator@cbt.test',
                'employee_id' => 'SUP003',
                'phone' => '081234567892',
                'department' => 'Assessment Center',
                'position' => 'Senior Assessment Supervisor',
                'specialization' => 'Educational Assessment',
                'experience_years' => 12,
                'hire_date' => '2012-08-10',
            ],
            [
                'name' => 'Dra. Rina Controller',
                'email' => 'rina.controller@cbt.test',
                'employee_id' => 'SUP004',
                'phone' => '081234567893',
                'department' => 'Examination Unit',
                'position' => 'Examination Controller',
                'specialization' => 'Examination Management',
                'experience_years' => 18,
                'hire_date' => '2007-05-25',
            ],
            [
                'name' => 'M.Pd. Hasan Observer',
                'email' => 'hasan.observer@cbt.test',
                'employee_id' => 'SUP005',
                'phone' => '081234567894',
                'department' => 'Student Affairs',
                'position' => 'Student Affairs Supervisor',
                'specialization' => 'Student Development',
                'experience_years' => 10,
                'hire_date' => '2014-11-30',
            ]
        ];

        foreach ($supervisorsData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'username' => Str::replace(' ', '', strtolower($data['name'])),
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'company_id' => $companyId,
            ]);

            $user->assignRole('Pengawas');

            UserDetail::create([
                'user_id' => $user->id,
                'employee_id' => $data['employee_id'],
                'supervisor_id' => $data['employee_id'], // Same as employee_id for supervisor
                'supervisor_nip' => $data['employee_id'],
                'phone' => $data['phone'],
                'birth_date' => '1980-' . rand(1, 12) . '-' . rand(1, 28),
                'birth_place' => ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang'][rand(0, 4)],
                'gender' => rand(0, 1) ? 'male' : 'female',
                'address' => 'Jl. Supervisor ' . rand(1, 100) . ', Jakarta',
                'postal_code' => '1' . rand(1000, 9999),
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'emergency_contact_name' => 'Emergency Contact ' . $data['name'],
                'emergency_contact_phone' => '081' . rand(100000000, 999999999),
                'emergency_contact_relation' => 'Spouse',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'status' => 'active',
                'supervisor_department' => $data['department'],
                'supervisor_unit' => $data['department'] . ' Unit',
                'supervisor_position' => $data['position'],
                'supervisor_level' => ['Junior', 'Senior', 'Lead', 'Principal'][rand(0, 3)],
                'supervisor_area' => ['Academic', 'Administrative', 'Technical', 'General'][rand(0, 3)],
                'supervisor_specialization' => $data['specialization'],
                'supervisor_status' => 'active',
                'supervisor_type' => ['internal', 'external'][rand(0, 1)],
                'supervisor_start_date' => $data['hire_date'],
                'supervisor_experience_years' => $data['experience_years'],
                'notes' => 'Supervisor Role - ' . $data['department'],
                'certifications' => json_encode([
                    [
                        'name' => 'Educational Supervision Certificate',
                        'issuer' => 'Ministry of Education',
                        'issue_date' => '2018-01-01',
                        'credential_id' => 'SUP' . rand(100000, 999999),
                        'added_at' => now()->toISOString()
                    ]
                ]),
                'supervisor_certifications' => json_encode([
                    [
                        'name' => 'Supervisor Management Certification',
                        'issuer' => 'Professional Supervisors Association',
                        'issue_date' => '2020-01-01',
                        'credential_id' => 'SMC' . rand(100000, 999999),
                        'added_at' => now()->toISOString()
                    ]
                ])
            ]);

            RoleHelper::assignRoleToUserInCompany($user, 'Pengawas', $companyId);
        }
    }
}
