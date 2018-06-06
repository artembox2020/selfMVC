<?php
  abstract class Worker {
    protected $dbh;
      public function __construct() {
          if( !class_exists('ConfigLoader') ) require_once "ConfigLoader.php";
          $connection = ConfigLoader::getDbConnection();
          $this->dbh = new PDO($connection['string'],$connection['user'],$connection['pass']);
          $this->dbh->exec('SET CHARACTER SET utf8');
      }  
      
      public function normalize($string) {
          $string = htmlspecialchars($string);
          return $string;
      }
  }