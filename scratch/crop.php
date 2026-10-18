<?php
$src = 'C:\\Users\\Labaik Laptop\\.gemini\\antigravity-ide\\brain\\f58ea8ad-29a1-438d-ab99-b54cbdf5599f\\.user_uploaded\\media_1792328460539.png';
$im = imagecreatefrompng($src);
$w = imagesx($im);
$h = imagesy($im);
echo "Image Size: {$w}x{$h}\n";

$crop = imagecrop($im, [
    'x' => (int)($w * 0.37),
    'y' => (int)($h * 0.30),
    'width' => (int)($w * 0.12),
    'height' => (int)($h * 0.10)
]);
if ($crop) {
    imagepng($crop, 'C:\\Users\\Labaik Laptop\\.gemini\\antigravity-ide\\brain\\f58ea8ad-29a1-438d-ab99-b54cbdf5599f\\scratch\\figma_crop.png');
    echo "Saved to figma_crop.png\n";
}
