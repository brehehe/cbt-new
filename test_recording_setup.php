<?php

require_once __DIR__ . '/vendor/autoload.php';

// Test storage capabilities
echo "=== TESTING STORAGE SETUP ===\n";

$storagePath = __DIR__ . '/storage/app/public/exam_recordings';

if (is_dir($storagePath)) {
    echo "✅ Directory exists: $storagePath\n";

    if (is_writable($storagePath)) {
        echo "✅ Directory is writable\n";

        // Test writing a small file
        $testFile = $storagePath . '/test_' . date('Y-m-d_H-i-s') . '.txt';
        $testContent = "Test recording file - " . date('Y-m-d H:i:s');

        if (file_put_contents($testFile, $testContent)) {
            echo "✅ Successfully wrote test file: " . basename($testFile) . "\n";

            // Test reading back
            $readContent = file_get_contents($testFile);
            if ($readContent === $testContent) {
                echo "✅ Successfully read back test file\n";

                // Clean up
                unlink($testFile);
                echo "✅ Test file cleaned up\n";
            } else {
                echo "❌ Failed to read back test file correctly\n";
            }
        } else {
            echo "❌ Failed to write test file\n";
        }
    } else {
        echo "❌ Directory is not writable\n";
        echo "Directory permissions: " . substr(sprintf('%o', fileperms($storagePath)), -4) . "\n";
    }
} else {
    echo "❌ Directory does not exist: $storagePath\n";
}

echo "\n=== TESTING COMPONENTS ===\n";

// Check if important files exist
$files = [
    'app/Livewire/Admin/Exam/Detail/AdminExamDetailIndex.php',
    'resources/views/livewire/admin/exam/detail/admin-exam-detail-index.blade.php'
];

foreach ($files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ File exists: $file\n";
    } else {
        echo "❌ File missing: $file\n";
    }
}

echo "\n=== READY FOR TESTING ===\n";
echo "1. Start Laravel server: php artisan serve\n";
echo "2. Open exam page in browser\n";
echo "3. Open browser console (F12)\n";
echo "4. Watch for debug messages during exam\n";
echo "5. Check this directory after exam completion: $storagePath\n";
