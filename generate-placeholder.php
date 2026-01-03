<?php
// Generate placeholder image
$width = 400;
$height = 400;

// Create image
$image = imagecreatetruecolor($width, $height);

// Colors
$bg_color = imagecolorallocate($image, 241, 245, 249);
$text_color = imagecolorallocate($image, 148, 163, 184);
$icon_color = imagecolorallocate($image, 203, 213, 225);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Draw simple icon (circle)
imagefilledellipse($image, 200, 160, 80, 80, $icon_color);

// Draw rectangle
imagefilledrectangle($image, 160, 220, 240, 280, $icon_color);

// Add text
$font_size = 20;
$text = "No Image";
$text2 = "Available";

// Calculate text position
$bbox = imagettfbbox($font_size, 0, __DIR__ . '/../../assets/fonts/arial.ttf', $text);
if ($bbox) {
    $text_width = abs($bbox[4] - $bbox[0]);
    $x = ($width - $text_width) / 2;
} else {
    $x = 150;
}

// Simple text fallback (if TTF not available)
imagestring($image, 5, 150, 180, $text, $text_color);
imagestring($image, 5, 145, 200, $text2, $text_color);

// Output image
header('Content-Type: image/jpeg');
imagejpeg($image, __DIR__ . '/placeholder.jpg', 90);
imagedestroy($image);

echo "Placeholder image created successfully!";
?>
