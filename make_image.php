<?php
// kirjoita kuvaan.
  function writeToFile($text1, $text2){
    $text1 = wordwrap($text1, 48)."";
    $text2 = wordwrap($text2, 48)."";
    $text1 = preg_replace( '/[\x{202F}]/u', ' ', $text1);
    $text2 = preg_replace( '/[\x{202F}]/u', ' ', $text2);
    // create image
    $factor = 1;
    $width = 1200;
    $height = 675;
    $margin = 40;
    $fontsize = 34;

    $image = imagecreatetruecolor($width * $factor, $height * $factor);
    $center_line = ($height * $factor)/2;
    $darkgrey = imagecolorallocate($image, 102, 102, 102);
    $grey = imagecolorallocate($image, 204, 204, 204);
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, 0, $width * $factor, $margin * $factor, $grey);
    imagefilledrectangle($image, 0, $margin * $factor, $width * $factor, ($height - $margin) * $factor, $white);

    imagefilledrectangle($image, 0, $center_line - 1 * $factor, $width * $factor, $center_line + (1 * $factor), $grey);
    imagefilledrectangle($image, 0, ($height - $margin) * $factor, $width * $factor, $height * $factor, $grey);

      $font2 =  __DIR__."/Merriweather-Black.ttf";
      $font1 =  __DIR__."/Merriweather-Light.ttf";

    imagettftext ($image, $fontsize * $factor, 0, 20 , ($margin + $fontsize * 1.5) * $factor + 12 * $factor , $darkgrey ,$font1 , $text1);
    imagettftext ($image, $fontsize * $factor, 0, 20 , $center_line + $fontsize * 1.5 * $factor + 12 * $factor , $black ,$font2 , $text2);

    // write it to file
    imagejpeg($image, "otsikko.jpg");
  }
