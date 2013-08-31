<?php

function createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality)
{
  // Open the original image.
  $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original");
  list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");
 
  // Resample the image.
  $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die("Cant create temp image");
  imagecopyresized($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die("Cant resize copy");
 
  // Create the new file name.
  $newNameE = explode(".", $img);
  $newName = ''. $newNameE[0] .''. $suffix .'.'. $newNameE[1] .'';
 
  // Save the image.
  imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Cant save image");
 
  // Clean up.
  imagedestroy($original);
  imagedestroy($tempImg);
  return true;
}

function rotateImage($img, $imgPath, $suffix, $degrees, $quality, $save)
{
    // Open the original image.
    $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original");
    list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");
 
    // Resample the image.
    $tempImg = imagecreatetruecolor($width, $height) or die("Cant create temp image");
    imagecopyresized($tempImg, $original, 0, 0, 0, 0, $width, $height, $width, $height) or die("Cant resize copy");
 
    // Rotate the image.
    $rotate = imagerotate($original, $degrees, 0);
 
    // Save.
    if($save)
    {
        // Create the new file name.
    $newNameE = explode(".", $img);
    $newName = ''. $newNameE[0] .''. $suffix .'.'. $newNameE[1] .'';
 
    // Save the image.
    imagejpeg($rotate, "$imgPath/$newName", $quality) or die("Cant save image");
    }
 
    // Clean up.
    imagedestroy($original);
    imagedestroy($tempImg);
    return true;
}

function resizeImage($img, $imgPath, $suffix, $by, $quality)
{
    // Open the original image.
    $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original (<em>$imgPath/$img</em>)");
    list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");
 
    // Determine new width and height.
    $newWidth = ($width/$by);
    $newHeight = ($height/$by);
 
    // Resample the image.
    $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die("Cant create temp image");
    imagecopyresized($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die("Cant resize copy");
 
    // Create the new file name.
    $newNameE = explode(".", $img);
    $newName = ''. $newNameE[0] .''. $suffix .'.'. $newNameE[1] .'';
 
    // Save the image.
    imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Cant save image");
 
    // Clean up.
    imagedestroy($original);
    imagedestroy($tempImg);
    return true;
}

function reduceImage($img, $imgPath, $suffix, $quality)
{
    // Open the original image.
    $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original (<em>$imgPath/$img</em>)");
    list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");
 
    // Resample the image.
    $tempImg = imagecreatetruecolor($width, $height) or die("Cant create temp image");
    imagecopyresized($tempImg, $original, 0, 0, 0, 0, $width, $height, $width, $height) or die("Cant resize copy");
 
    // Create the new file name.
    $newNameE = explode(".", $img);
    $newName = ''. $newNameE[0] .''. $suffix .'.'. $newNameE[1] .'';
 
    // Save the image.
    imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Cant save image");
 
    // Clean up.
    imagedestroy($original);
    imagedestroy($tempImg);
    return true;
}

?>