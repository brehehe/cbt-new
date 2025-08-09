<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Hash;

echo "=== Creating Fresh Test Lecturer ===" . PHP_EOL;

// Delete any existing test lecturer
$existingTest = User::where('email', 'test.lecturer.fresh@university.ac.id')->first();
if ($existingTest) {
    if ($existingTest->userDetail) {
        $existingTest->userDetail->delete();
    }
    $existingTest->delete();
    echo "Deleted existing test user" . PHP_EOL;
}

// Create new test lecturer
$user = User::create([
    'name' => 'Dr. Test Fresh Lecturer',
    'email' => 'test.lecturer.fresh@university.ac.id',
    'password' => Hash::make('password123'),
    'email_verified_at' => now()
]);

$user->assignRole('Dosen');

UserDetail::create([
    'user_id' => $user->id,
    'lecturer_id' => 'LEC888',
    'lecturer_nidn' => '8888777666',
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

echo "✅ Fresh test lecturer created:" . PHP_EOL;
echo "Name: " . $user->name . PHP_EOL;
echo "Email: " . $user->email . PHP_EOL;
echo "ID: " . $user->id . PHP_EOL;
echo "Lecturer ID: LEC888" . PHP_EOL;
echo "NIDN: 8888777666" . PHP_EOL;

echo PHP_EOL;
echo "=== Testing Edit Validation ===" . PHP_EOL;

// Test validation rules for edit
$rules = [
    'email' => 'required|email|unique:users,email,' . $user->id . ',id',
    'lecturer_id' => 'required|string|unique:user_details,lecturer_id,' . $user->id . ',user_id',
    'lecturer_nidn' => 'required|string|unique:user_details,lecturer_nidn,' . $user->id . ',user_id',
];

$validator = \Illuminate\Support\Facades\Validator::make([
    'email' => $user->email,
    'lecturer_id' => 'LEC888',
    'lecturer_nidn' => '8888777666',
], $rules);

if ($validator->passes()) {
    echo "✅ Edit validation PASSED!" . PHP_EOL;
} else {
    echo "❌ Edit validation FAILED:" . PHP_EOL;
    foreach ($validator->errors()->all() as $error) {
        echo "  - " . $error . PHP_EOL;
    }
}
