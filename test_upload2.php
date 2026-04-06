<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$disk = 'public';
$folder = 'test_uploads';

$tmpPath = '/tmp/test_image.png';
file_put_contents($tmpPath, 'fake image data');

$file = new UploadedFile($tmpPath, 'test_image.png', 'image/png', null, true);

$stored = $file->store($folder, $disk);
echo "Stored path: $stored\n";
echo "Exists on public disk? " . (Storage::disk($disk)->exists($stored) ? 'yes' : 'no') . "\n";
echo "File content: " . Storage::disk($disk)->get($stored) . "\n";
