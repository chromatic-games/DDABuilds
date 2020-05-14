<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 04.06.2017
 * Time: 19:30
 */
if (!isset($_GET['build'])) {
    exit();
}
include_once 'config.php';

$screenshot = $_POST['screenshot'];
$img = imagecreatefrompng($screenshot->getScreenshotLink());

$width = imagesx($img);
$height = imagesy($img);
$top = 0;
$bottom = 0;
$left = 0;
$right = 0;
//$bgcolor = 0xFFFFFF; // Use this if you only want to crop out white space
$bgcolor = imagecolorat( $img, $top, $left ); // This works with any color, including transparent backgrounds
//top
for(; $top < $height; ++$top) {
    for($x = 0; $x < $width; ++$x) {
        if(imagecolorat($img, $x, $top) != $bgcolor) {
            break 2; //out of the 'top' loop
        }
    }
}
//bottom
for(; $bottom < $height; ++$bottom) {
    for($x = 0; $x < $width; ++$x) {
        if(imagecolorat($img, $x, $height - $bottom-1) != $bgcolor) {
            break 2; //out of the 'bottom' loop
        }
    }
}
//left
for(; $left < $width; ++$left) {
    for($y = 0; $y < $height; ++$y) {
        if(imagecolorat($img, $left, $y) != $bgcolor) {
            break 2; //out of the 'left' loop
        }
    }
}
//right
for(; $right < $width; ++$right) {
    for($y = 0; $y < $height; ++$y) {
        if(imagecolorat($img, $width - $right-1, $y) != $bgcolor) {
            break 2; //out of the 'right' loop
        }
    }
}
//copy the contents, excluding the border
$newimg = imagecreatetruecolor(
    $width-($left+$right), $height-($top+$bottom));
imagecopy($newimg, $img, 0, 0, $left, $top, imagesx($newimg), imagesy($newimg));

$newimg = imagescale($newimg, 200);

$sucess = imagepng($newimg, dirname(__FILE__) . '/images/thumbnails/' . $_GET['build'] . '.png');