<?php
  spl_autoload_register(function ($class_name) {
    if(!empty($class_name)) {
      if(strlen($class_name) >= 6 && substr($class_name,strlen($class_name)-6,6) == "Worker" ) {
          include_once $_SERVER['DOCUMENT_ROOT']."/dbworkers/".$class_name.".php";
      }
      else {
          $parent = substr($class_name,0,1); $child = "";
          $parent_ready =false;
          for($i = 1; $i < strlen($class_name); ++$i)
            if(ord(substr($class_name,$i,1)) > 90 && !$parent_ready) 
                $parent.= substr($class_name,$i,1);
            else {  
                $parent_ready = true;
                $child.= substr($class_name,$i,1);
            }
          if( !empty($child) ) {
              $route = "/classes/".$parent."/".$child.".php";    
              if( file_exists($_SERVER['DOCUMENT_ROOT'].$route) ) include_once $_SERVER['DOCUMENT_ROOT'].$route;
          }
      }
    }
  });
?>