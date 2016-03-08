<?php
error_reporting(0);
// Set the content-type
header('Content-Type: image/png');
header('cache-control: private, max-age=0, no-cache');

// Create the image
$im = imagecreatefrompng("template.png");

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
//imagefilledrectangle($im, 0, 0, 399, 29, $white);

$fname = strtolower($_GET['font']);
$fname = strtr($fname, " ", "-");
$zip = new ZipArchive;
mkdir("temp");
if (!(@filesize("temp/" . $fname . ".zip") > 0)) {
	$page = file_get_contents("http://www.1001fonts.com/search.html?search=" . urlencode($_GET['font']). "&x=0&y=0");
	preg_match('@(http://dl\.1001fonts\.com/.*?\.zip)@', $page, $m);
	$zz = file_get_contents($m[1]);
	file_put_contents("temp/" . $fname . ".zip", $zz);
	$res = $zip->open("temp/" . $fname . ".zip");
	@mkdir("temp/" . $fname);
	$zip->extractTo("temp/" . $fname);
	$zip->close();
}
$font = array_shift(glob("temp/" . $fname . "/*.?tf"));

// The text to draw
$text = $_GET['quote'];
// Replace path by your own font path

$mw = 515;
$mh = 50;

$bbox = imageftbbox("90", 0, $font, $text);

$w = $bbox[4] - $bbox[0];
$h = $bbox[5] - $bbox[1];

$fm = $fw = $fh = 90;

if ($w > $mw) {
	$fw = round($fm * $mw / $w);
}

$fs = min($fm, $fh, $fw);

$bbox = imageftbbox($fs, 0, $font, $text);

$x = $bbox[0] + ($mw / 2) - ($bbox[4] / 2) - 5;
$y = $bbox[1] + ($mh / 2) - ($bbox[5] / 2) - 5;
$ox = 230;
$oy = 210;

// Add some shadow to the text
imagettftext($im, $fs, 0, $x + $ox, $y + $oy, $grey, $font, $text);

// Add the text
imagettftext($im, $fs, 0, $x + $ox + 1, $y + $oy + 1, $black, $font, $text);


// podpis
$text = $_GET['author'];

$mw = 171;
$mh = 23;

$bbox = imageftbbox("90", 0, $font, $text);

$w = $bbox[4] - $bbox[0];
$h = $bbox[5] - $bbox[1];

$fm = $fw = $fh = 90;

if ($w > $mw) {
	$fw = round($fm * $mw / $w);
}

$fs = min($fm, $fh, $fw);

$bbox = imageftbbox($fs, 0, $font, $text);

$x = $bbox[0] + ($mw / 2) - ($bbox[4] / 2) - 5;
$y = $bbox[1] + ($mh / 2) - ($bbox[5] / 2) - 5;
$ox = 530;
$oy = 285;

// Add some shadow to the text
imagettftext($im, $fs, 0, $x + $ox, $y + $oy, $grey, $font, $text);

// Add the text
imagettftext($im, $fs, 0, $x + $ox + 1, $y + $oy + 1, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);
