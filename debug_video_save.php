<?php
/**
 * Debug script for testing video save functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Video Save Debug Script\n";
echo "========================\n\n";

// Test 1: PHP Configuration
echo "1. PHP Configuration:\n";
echo "   - upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   - post_max_size: " . ini_get('post_max_size') . "\n";
echo "   - memory_limit: " . ini_get('memory_limit') . "\n";
echo "   - max_execution_time: " . ini_get('max_execution_time') . "\n\n";

// Test 2: Storage Configuration
echo "2. Storage Configuration:\n";
$disk = Storage::disk('public');
$diskConfig = config('filesystems.disks.public');
echo "   - Disk root: " . $disk->path('') . "\n";
echo "   - Disk exists: " . (is_dir($disk->path('')) ? 'Yes' : 'No') . "\n";
echo "   - Disk writable: " . (is_writable($disk->path('')) ? 'Yes' : 'No') . "\n";
echo "   - Permissions: " . substr(sprintf('%o', fileperms($disk->path(''))), -4) . "\n\n";

// Test 3: Directory Test
echo "3. Directory Test:\n";
$testDir = 'exam_recordings';
$fullDirPath = $disk->path($testDir);
echo "   - Test directory: $testDir\n";
echo "   - Full path: $fullDirPath\n";
echo "   - Directory exists: " . ($disk->exists($testDir) ? 'Yes' : 'No') . "\n";

if (!$disk->exists($testDir)) {
    echo "   - Creating directory...\n";
    $result = $disk->makeDirectory($testDir);
    echo "   - Creation result: " . ($result ? 'Success' : 'Failed') . "\n";
}

echo "   - Directory writable: " . (is_writable($fullDirPath) ? 'Yes' : 'No') . "\n";
echo "   - Directory permissions: " . (is_dir($fullDirPath) ? substr(sprintf('%o', fileperms($fullDirPath)), -4) : 'N/A') . "\n\n";

// Test 4: File Write Test
echo "4. File Write Test:\n";
$testFileName = 'test_' . date('Y-m-d_H-i-s') . '.txt';
$testFilePath = $testDir . '/' . $testFileName;
$testContent = "Test file created at " . date('Y-m-d H:i:s') . "\nPHP Version: " . PHP_VERSION . "\nMemory usage: " . memory_get_usage(true);

echo "   - Test file: $testFilePath\n";
echo "   - Content size: " . strlen($testContent) . " bytes\n";

try {
    $writeResult = $disk->put($testFilePath, $testContent);
    echo "   - Write result: " . ($writeResult ? 'Success' : 'Failed') . "\n";

    if ($writeResult) {
        $fileExists = $disk->exists($testFilePath);
        echo "   - File exists after write: " . ($fileExists ? 'Yes' : 'No') . "\n";

        if ($fileExists) {
            $fileSize = $disk->size($testFilePath);
            $fullFilePath = $disk->path($testFilePath);
            echo "   - File size: $fileSize bytes\n";
            echo "   - Full file path: $fullFilePath\n";
            echo "   - File readable: " . (is_readable($fullFilePath) ? 'Yes' : 'No') . "\n";
            echo "   - File permissions: " . substr(sprintf('%o', fileperms($fullFilePath)), -4) . "\n";

            // Read back test
            $readContent = $disk->get($testFilePath);
            echo "   - Read back size: " . strlen($readContent) . " bytes\n";
            echo "   - Content matches: " . ($readContent === $testContent ? 'Yes' : 'No') . "\n";
        }
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
    echo "   - File: " . $e->getFile() . "\n";
    echo "   - Line: " . $e->getLine() . "\n";
}

echo "\n";

// Test 5: Video Blob Simulation
echo "5. Video Blob Simulation:\n";
$testVideoData = str_repeat('TESTVIDEODATA', 1000); // Simulate video data
$base64Data = 'data:video/webm;base64,' . base64_encode($testVideoData);

echo "   - Simulated video size: " . strlen($testVideoData) . " bytes\n";
echo "   - Base64 blob size: " . strlen($base64Data) . " bytes\n";

// Decode test
$decodedData = base64_decode(preg_replace('#^data:video/\w+;base64,#i', '', $base64Data));
echo "   - Decode success: " . ($decodedData !== false ? 'Yes' : 'No') . "\n";
echo "   - Decoded size: " . strlen($decodedData) . " bytes\n";
echo "   - Data matches: " . ($decodedData === $testVideoData ? 'Yes' : 'No') . "\n";

// Save test
$videoFileName = 'test_video_' . date('Y-m-d_H-i-s') . '.webm';
$videoFilePath = $testDir . '/' . $videoFileName;

echo "   - Video file: $videoFilePath\n";

try {
    $videoWriteResult = $disk->put($videoFilePath, $decodedData);
    echo "   - Video write result: " . ($videoWriteResult ? 'Success' : 'Failed') . "\n";

    if ($videoWriteResult) {
        $videoFileExists = $disk->exists($videoFilePath);
        echo "   - Video file exists: " . ($videoFileExists ? 'Yes' : 'No') . "\n";

        if ($videoFileExists) {
            $videoFileSize = $disk->size($videoFilePath);
            echo "   - Video file size: $videoFileSize bytes\n";
            echo "   - Size matches: " . ($videoFileSize === strlen($decodedData) ? 'Yes' : 'No') . "\n";
        }
    }
} catch (Exception $e) {
    echo "   - VIDEO ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: Database Test (if possible)
echo "6. Database Test:\n";
try {
    $connection = DB::connection();
    echo "   - Database connection: Success\n";

    // Test exam_recordings table
    $tableExists = Schema::hasTable('exam_recordings');
    echo "   - exam_recordings table exists: " . ($tableExists ? 'Yes' : 'No') . "\n";

    if ($tableExists) {
        $columns = Schema::getColumnListing('exam_recordings');
        echo "   - Table columns: " . implode(', ', $columns) . "\n";
    }
} catch (Exception $e) {
    echo "   - Database ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 7: Laravel Storage Disk Test
echo "7. Laravel Storage Disk Details:\n";
echo "   - Default disk: " . config('filesystems.default') . "\n";
echo "   - Public disk configured: " . (config('filesystems.disks.public') ? 'Yes' : 'No') . "\n";
echo "   - Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "   - Public disk URL: " . config('filesystems.disks.public.url') . "\n";

// List all files in exam_recordings
$files = $disk->files($testDir);
echo "   - Files in $testDir: " . count($files) . "\n";
foreach ($files as $file) {
    $size = $disk->size($file);
    $lastModified = date('Y-m-d H:i:s', $disk->lastModified($file));
    echo "     * $file ($size bytes, modified: $lastModified)\n";
}

echo "\n";
echo "🎯 Debug completed!\n";
echo "If video saving still fails, check:\n";
echo "1. Web server error logs\n";
echo "2. PHP-FPM error logs\n";
echo "3. Laravel logs for more detailed errors\n";
echo "4. Browser console for JavaScript errors\n";
echo "5. Network tab for failed requests\n";
