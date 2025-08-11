<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\User\UserDetail;

echo "=== Finding Duplicate Records ===" . PHP_EOL;

// Find all records with lecturer_id = LEC001
$duplicateLecturerIds = UserDetail::where('lecturer_id', 'LEC001')->get();
echo "Records with lecturer_id 'LEC001': " . $duplicateLecturerIds->count() . PHP_EOL;

foreach ($duplicateLecturerIds as $record) {
    $user = User::find($record->user_id);
    echo "- UserDetail ID: " . $record->id . PHP_EOL;
    echo "  User ID: " . $record->user_id . PHP_EOL;
    echo "  User Name: " . ($user ? $user->name : 'NOT FOUND') . PHP_EOL;
    echo "  User Email: " . ($user ? $user->email : 'NOT FOUND') . PHP_EOL;
    echo "  Lecturer ID: " . $record->lecturer_id . PHP_EOL;
    echo "  NIDN: " . $record->lecturer_nidn . PHP_EOL;
    echo "  Created: " . $record->created_at . PHP_EOL;
    echo PHP_EOL;
}

echo "=== Finding Duplicate NIDN ===" . PHP_EOL;

$duplicateNidns = UserDetail::whereNotNull('lecturer_nidn')->get();
foreach ($duplicateNidns as $record) {
    $user = User::find($record->user_id);
    echo "- User: " . ($user ? $user->name : 'NOT FOUND') . PHP_EOL;
    echo "  NIDN: " . $record->lecturer_nidn . PHP_EOL;
    echo "  Created: " . $record->created_at . PHP_EOL;
}

echo PHP_EOL;
echo "=== Cleanup Suggestion ===" . PHP_EOL;
echo "You have duplicate records. You should clean them up first." . PHP_EOL;
