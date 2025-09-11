<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Find the specific record with invalid status
    $record = DB::table('exam_recordings')
        ->where('id', '019938ea-6711-70ca-bc2f-392a045d23cc')
        ->first();

    if ($record) {
        echo "Found record with status: " . $record->status . "\n";

        // Update to valid status
        DB::table('exam_recordings')
            ->where('id', '019938ea-6711-70ca-bc2f-392a045d23cc')
            ->update(['status' => 'completed']);

        echo "Updated status to 'completed'\n";
    } else {
        echo "Record not found\n";
    }

    // Check for any other records with invalid status
    $invalidRecords = DB::select("SELECT id, status FROM exam_recordings WHERE status NOT IN ('recording', 'completed', 'failed')");

    if (count($invalidRecords) > 0) {
        echo "\nFound " . count($invalidRecords) . " records with invalid status:\n";
        foreach ($invalidRecords as $invalid) {
            echo "ID: {$invalid->id}, Status: {$invalid->status}\n";

            // Fix them
            DB::table('exam_recordings')
                ->where('id', $invalid->id)
                ->update(['status' => 'completed']);
            echo "Fixed record {$invalid->id}\n";
        }
    } else {
        echo "\nNo records with invalid status found.\n";
    }

    echo "\nAll done!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
