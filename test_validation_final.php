<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Validator;

echo "=== Testing New Validation Rules ===" . PHP_EOL;

// Find test lecturer
$user = User::with('userDetail')->where('email', 'test.lecturer@university.ac.id')->first();

if ($user && $user->userDetail) {
    echo "Test User: " . $user->name . PHP_EOL;
    echo "User ID: " . $user->id . PHP_EOL;
    echo "Lecturer ID: " . $user->userDetail->lecturer_id . PHP_EOL;
    echo "NIDN: " . $user->userDetail->lecturer_nidn . PHP_EOL;

    echo PHP_EOL;
    echo "=== Testing Edit Validation (Simple Syntax) ===" . PHP_EOL;

    // Test validation rules for edit with simple syntax
    $rules = [
        'email' => 'required|email|unique:users,email,' . $user->id,
        'lecturer_id' => 'required|string|unique:user_details,lecturer_id,NULL,id,user_id,' . $user->id,
        'lecturer_nidn' => 'required|string|unique:user_details,lecturer_nidn,NULL,id,user_id,' . $user->id,
    ];

    $validator = Validator::make([
        'email' => $user->email,
        'lecturer_id' => $user->userDetail->lecturer_id,
        'lecturer_nidn' => $user->userDetail->lecturer_nidn,
    ], $rules);

    if ($validator->passes()) {
        echo "✅ Edit validation PASSED - same data allowed in edit mode" . PHP_EOL;
    } else {
        echo "❌ Edit validation FAILED:" . PHP_EOL;
        foreach ($validator->errors()->all() as $error) {
            echo "  - " . $error . PHP_EOL;
        }
    }

    echo PHP_EOL;
    echo "=== Testing Create Validation (Should Fail) ===" . PHP_EOL;

    // Test validation rules for create mode (should fail with existing data)
    $createRules = [
        'email' => 'required|email|unique:users,email',
        'lecturer_id' => 'required|string|unique:user_details,lecturer_id',
        'lecturer_nidn' => 'required|string|unique:user_details,lecturer_nidn',
    ];

    $createValidator = Validator::make([
        'email' => $user->email,
        'lecturer_id' => $user->userDetail->lecturer_id,
        'lecturer_nidn' => $user->userDetail->lecturer_nidn,
    ], $createRules);

    if ($createValidator->passes()) {
        echo "❌ Create validation PASSED - this should have failed!" . PHP_EOL;
    } else {
        echo "✅ Create validation FAILED as expected:" . PHP_EOL;
        foreach ($createValidator->errors()->all() as $error) {
            echo "  - " . $error . PHP_EOL;
        }
    }
} else {
    echo "❌ Test lecturer not found!" . PHP_EOL;
}
