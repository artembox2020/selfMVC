<?php
trait ConfigLoaderBase {
    public static function getDbConnection() {
         $string = "mysql:host=localhost;dbname=g91016og_port;";
         $user = "g91016og_port";
         $pass = "123467";
         return array('string'=>$string,'user'=>$user,'pass'=>$pass);
    }
}