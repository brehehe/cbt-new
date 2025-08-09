<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;

echo "=== Cleaning Duplicate Test Data ===" . PHP_EOL;

// Find the test lecturer we created
$testUser = User::where('email', 'test.dosen@example.com')->first();
if ($testUser) {
    echo "Found test user: " . $testUser->name . " (" . $testUser->email . ")" . PHP_EOL;
    echo "Deleting test user and its details..." . PHP_EOL;

    // Delete user detail first
    if ($testUser->userDetail) {
        $testUser->userDetail->delete();
        echo "✅ UserDetail deleted" . PHP_EOL;
    }

    // Delete user
    $testUser->delete();
    echo "✅ User deleted" . PHP_EOL;
} else {
    echo "Test user not found" . PHP_EOL;
}

echo PHP_EOL;
echo "=== Creating New Test Lecturer ===" . PHP_EOL;

// Create new test lecturer with unique IDs
$user = User::create([
    'name' => 'Dr. Test Dosen Baru',
    'email' => 'test.lecturer@university.ac.id',
    'password' => Hash::make('password123'),
    'email_verified_at' => now()
]);

$user->assignRole('Dosen');

UserDetail::create([
    'user_id' => $user->id,
    'lecturer_id' => 'LEC999',
    'lecturer_nidn' => '9999888777',
    'lecturer_department' => 'Teknik Informatika',
    'lecturer_faculty' => 'Fakultas Teknik',
    'lecturer_position' => 'Lektor',
    'lecturer_education_level' => 'S2',
    'lecturer_specialization' => 'Data Science',
    'birth_place' => 'Jakarta',
    'birth_date' => '1980-01-01',
    'gender' => 'male',
    'address' => 'Jl. Test No. 123',
    'city' => 'Jakarta',
    'province' => 'DKI Jakarta',
    'verification_status' => 'verified',
    'status' => 'active'
]);

echo "✅ New test lecturer created:" . PHP_EOL;
echo "Name: " . $user->name . PHP_EOL;
echo "Email: " . $user->email . PHP_EOL;
echo "ID: " . $user->id . PHP_EOL;
echo "Lecturer ID: LEC999" . PHP_EOL;
echo "NIDN: 9999888777" . PHP_EOL;
