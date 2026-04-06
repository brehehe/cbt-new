<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

$disk = 'public';
$folder = 'test_uploads';

// Create fake uploaded file
$path = storage_path('app/livewire-tmp/' . Str::random(10) . '.png');
if (!is_dir(storage_path('app/livewire-tmp'))) mkdir(storage_path('app/livewire-tmp'), 0775, true);
file_put_contents($path, 'fake image data');

$tuf = TemporaryUploadedFile::createFromLivewire($path);

echo "First store:\n";
$first = $tuf->store($folder, $disk);
echo "Result1: $first\n";

echo "Second store:\n";
$second = $tuf->store($folder, $disk);
echo "Result2: $second\n";

echo "Exists first? " . (Storage::disk($disk)->exists($first) ? 'yes' : 'no') . "\n";
echo "Exists second? " . (Storage::disk($disk)->exists($second) ? 'yes' : 'no') . "\n";
