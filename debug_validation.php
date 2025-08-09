<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Facades\DB;

// Get UserDetail record to understand the structure
echo "=== UserDetail Table Structure ===" . PHP_EOL;

$userDetail = UserDetail::where('lecturer_id', 'LEC001')->first();
if ($userDetail) {
    echo "UserDetail ID: " . $userDetail->id . PHP_EOL;
    echo "User ID: " . $userDetail->user_id . PHP_EOL;
    echo "Lecturer ID: " . $userDetail->lecturer_id . PHP_EOL;
    echo "NIDN: " . $userDetail->lecturer_nidn . PHP_EOL;

    echo PHP_EOL;
    echo "=== Testing Simple Query ===" . PHP_EOL;

    // Test if there are other records with same lecturer_id
    $count = UserDetail::where('lecturer_id', 'LEC001')
        ->where('user_id', '!=', $userDetail->user_id)
        ->count();
    echo "Other records with same lecturer_id: " . $count . PHP_EOL;

    $count = UserDetail::where('lecturer_nidn', '1234567890')
        ->where('user_id', '!=', $userDetail->user_id)
        ->count();
    echo "Other records with same lecturer_nidn: " . $count . PHP_EOL;

    echo PHP_EOL;
    echo "=== Testing Manual Validation ===" . PHP_EOL;

    // Manual check
    $isDuplicateLecturerId = UserDetail::where('lecturer_id', 'LEC001')
        ->where('user_id', '!=', $userDetail->user_id)
        ->exists();

    $isDuplicateNidn = UserDetail::where('lecturer_nidn', '1234567890')
        ->where('user_id', '!=', $userDetail->user_id)
        ->exists();

    echo "Is lecturer_id duplicate: " . ($isDuplicateLecturerId ? 'YES' : 'NO') . PHP_EOL;
    echo "Is lecturer_nidn duplicate: " . ($isDuplicateNidn ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo "UserDetail not found!" . PHP_EOL;
}
