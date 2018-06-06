<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
include_once "autoload.php";
$_REQUEST=$_POST+$_GET;
if(empty($_REQUEST['isAjax'])) include_once "autoinclude.php";
if(!isset($_REQUEST['controller'])) $_REQUEST['controller'] = "main";
if(!isset($_REQUEST['action'])) $_REQUEST['action'] = "index";
  require_once "controllers/Controller.php";
  if(!file_exists("controllers/".ucfirst($_REQUEST['controller'])."Controller.php")) {
    die($_REQUEST['controller']."->".$_REQUEST['action']." does not exist");  
  }
  require_once "controllers/".ucfirst($_REQUEST['controller'])."Controller.php";
  if( class_exists($class) ) {
    $method=$_REQUEST['action']."Action";
    if(method_exists($class,$method)) {
       $obj= new $class();
       if(isset($_REQUEST['params']) && !empty($_REQUEST['params'])) {
          $params=substr($_REQUEST['params'],1,strlen($_REQUEST['params'])-1);
          $obj->$method(explode(",",$params));
       }
       else { $obj->$method($_REQUEST); }   
    }
   else {  echo $class." -> ".$method." does not exist";   }
  }
  else {
      die($_REQUEST['controller']."->".$_REQUEST['action']." does not exist");
  }