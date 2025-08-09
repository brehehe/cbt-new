<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\Validator;

echo "=== Testing Current Validation Rules ===" . PHP_EOL;

// Find test lecturer
$user = User::with('userDetail')->where('email', 'agus.setiawan@university.ac.id')->first();

if ($user && $user->userDetail) {
    echo "Test User: " . $user->name . PHP_EOL;
    echo "User ID: " . $user->id . PHP_EOL;
    echo "Lecturer ID: " . $user->userDetail->lecturer_id . PHP_EOL;
    echo "NIDN: " . $user->userDetail->lecturer_nidn . PHP_EOL;

    echo PHP_EOL;
    echo "=== Testing Edit Validation (Current Format) ===" . PHP_EOL;

    // Test validation rules for edit - same format as in the component
    $rules = [
        'email' => 'required|email|unique:users,email,' . $user->id . ',id',
        'lecturer_id' => 'required|string|unique:user_details,lecturer_id,' . $user->id . ',user_id',
        'lecturer_nidn' => 'required|string|unique:user_details,lecturer_nidn,' . $user->id . ',user_id',
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
} else {
    echo "❌ Test lecturer not found!" . PHP_EOL;
}

echo PHP_EOL;
echo "=== Manual Check for Understanding ===" . PHP_EOL;

// Manual check to understand the validation better
if ($user && $user->userDetail) {
    $userId = $user->id;
    $lecturerId = $user->userDetail->lecturer_id;
    $nidn = $user->userDetail->lecturer_nidn;

    echo "Checking lecturer_id '$lecturerId' with user_id != '$userId'" . PHP_EOL;
    $conflictLecturerId = UserDetail::where('lecturer_id', $lecturerId)
        ->where('user_id', '!=', $userId)
        ->count();
    echo "Found conflicts: " . $conflictLecturerId . PHP_EOL;

    echo "Checking lecturer_nidn '$nidn' with user_id != '$userId'" . PHP_EOL;
    $conflictNidn = UserDetail::where('lecturer_nidn', $nidn)
        ->where('user_id', '!=', $userId)
        ->count();
    echo "Found conflicts: " . $conflictNidn . PHP_EOL;
}
