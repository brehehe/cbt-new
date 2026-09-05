<?php

$source = '/var/www/html/procbt_id/public/asset/img/logo-procbt.png';
if (! file_exists($source)) {
    exit("Source image not found.\n");
}

$sizes = [
    [192, 192],
    [256, 256],
    [512, 512],
    [1280, 720],
    [750, 1334],
];
$basePath = '/var/www/html/procbt_id/public/asset/img/logo-';

$im = imagecreatefrompng($source);
$srcW = imagesx($im);
$srcH = imagesy($im);

foreach ($sizes as $size) {
    [$dstW, $dstH] = $size;
    $dst = imagecreatetruecolor($dstW, $dstH);

    // Fill with white background
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);

    // Fit source into dest
    $ratio = min($dstW / $srcW, $dstH / $srcH);
    $newW = $srcW * $ratio;
    $newH = $srcH * $ratio;

    $dstX = ($dstW - $newW) / 2;
    $dstY = ($dstH - $newH) / 2;

    imagecopyresampled($dst, $im, $dstX, $dstY, 0, 0, $newW, $newH, $srcW, $srcH);

    $outPath = $basePath."{$dstW}x{$dstH}.png";
    imagepng($dst, $outPath);
    imagedestroy($dst);
    echo "Created $outPath\n";
}
imagedestroy($im);
