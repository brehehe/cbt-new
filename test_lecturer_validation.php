<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

// Test existing lecturer
$user = User::with('userDetail')->where('email', 'test.lecturer@university.ac.id')->first();

if ($user) {
    echo "=== Existing Test Lecturer ===" . PHP_EOL;
    echo "User: " . $user->name . PHP_EOL;
    echo "Email: " . $user->email . PHP_EOL;
    echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . PHP_EOL;

    if ($user->userDetail) {
        echo "Lecturer ID: " . $user->userDetail->lecturer_id . PHP_EOL;
        echo "NIDN: " . $user->userDetail->lecturer_nidn . PHP_EOL;
        echo "Department: " . $user->userDetail->lecturer_department . PHP_EOL;
        echo "Faculty: " . $user->userDetail->lecturer_faculty . PHP_EOL;
    }

    echo PHP_EOL;
    echo "=== Testing Edit Validation (New Method) ===" . PHP_EOL;

    // Test validation rules for edit with Rule class
    $rules = [
        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($user->id)
        ],
        'lecturer_id' => [
            'required',
            'string',
            Rule::unique('user_details', 'lecturer_id')->where(function ($query) use ($user) {
                return $query->where('user_id', '!=', $user->id);
            })
        ],
        'lecturer_nidn' => [
            'required',
            'string',
            Rule::unique('user_details', 'lecturer_nidn')->where(function ($query) use ($user) {
                return $query->where('user_id', '!=', $user->id);
            })
        ],
    ];

    $validator = Validator::make([
        'email' => $user->email,
        'lecturer_id' => $user->userDetail->lecturer_id,
        'lecturer_nidn' => $user->userDetail->lecturer_nidn,
    ], $rules);

    if ($validator->passes()) {
        echo "✅ Edit validation PASSED - same data should be allowed" . PHP_EOL;
    } else {
        echo "❌ Edit validation FAILED:" . PHP_EOL;
        foreach ($validator->errors()->all() as $error) {
            echo "  - " . $error . PHP_EOL;
        }
    }
} else {
    echo "❌ Test lecturer not found!" . PHP_EOL;
}
