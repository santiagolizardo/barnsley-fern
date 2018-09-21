<?php

/**
 * Fractal created following the documentation in https://en.wikipedia.org/wiki/Barnsley_fern
 *
 * @author Santiago Lizardo
 */

const ImageWidth = 320;
const ImageHeight = 320;

const PixelScale = 30;

const LeftMargin = 140;
const BottomMargin = 10;

const NoCompression = 0;

$im = imagecreatetruecolor(ImageWidth, ImageHeight);
$white = imagecolorallocate($im, 0xff, 0xff, 0xff);
imagefill($im, 0, 0, $white);
$green = imagecolorallocate($im, 0xf, 0x7e, 12);

const coefficients = [
	[     0,     0,     0, 0.16, 0,    0, 0.01 ], // Stem
	[  0.85,  0.04, -0.04, 0.85, 0,  1.6, 0.85 ], // Successively smaller leaflets
	[   0.2, -0.26,  0.23, 0.22, 0,  1.6, 0.07 ], // Largest left-hand leaflet
	[ -0.15,  0.28,  0.26, 0.24, 0, 0.44, 0.07 ]  // Largest right-hand leaflet
];

$probabilities = [];
foreach(coefficients as $i => $c) {
	$probabilities = array_merge($probabilities, array_fill(0, $c[6] * 100, $i));
}

$x = 0;
$y = 0;
for($i = 0; $i < 100000; $i++) {
	imagesetpixel($im, round($x * PixelScale) + LeftMargin, ImageHeight - BottomMargin - round($y * PixelScale), $green);
	$p = $probabilities[array_rand($probabilities)];
	$c = coefficients[$p];
	$x = ($c[0] * $x + $c[1] * $y) + $c[4];
	$y = ($c[2] * $x + $c[3] * $y) + $c[5];
}

imagepng($im, 'output.png', NoCompression);
imagedestroy($im);

