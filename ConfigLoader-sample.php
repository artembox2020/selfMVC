<?php
 include_once $_SERVER['DOCUMENT_ROOT']."/ConfigLoaderBase.php";
 class ConfigLoader {
     
     use ConfigLoaderBase;
     
     public static function generatePassword($length = 8) {
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
          $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
     }
     
     public static function getSiteUrl() { return "https://artembox.info";  }
     
 }
?>