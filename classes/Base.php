<?php
class Base {
    public $rootUrl;
    public $sfx;
    public function __construct($rootUrl = "/",$sfx=false) {
        $this->rootUrl = $rootUrl;
        if($sfx != false) $this->sfx = $sfx;
        else $this->sfx = ConfigLoader::generatePassword();
    }
    
    public function generateSfx() {
        $this->sfx = ConfigLoader::generatePassword();
    }
    
    public function run() {
        echo "<link rel='stylesheet' href='/classes/".$this->rootUrl."/style.css' >";
        include $_SERVER['DOCUMENT_ROOT']."/classes/".$this->rootUrl."/maket.php";
        include $_SERVER['DOCUMENT_ROOT']."/classes/".$this->rootUrl."/script.php";
    }
    
    public function escapeSign($text) {
        return str_replace('"','loij09ujiojklnui786', $text);
    }
    public function resurrectSign($text) {
        return str_replace('loij09ujiojklnui786','"', $text);
    }
    
    public function include_resource($name, $vars= array()) {
        extract($vars);
        include $_SERVER['DOCUMENT_ROOT']."/classes/".$this->rootUrl."/".$name.".php";
    }
    
    
    
    public function get_resource($name, $vars= array(), $escapeSign = false) {
        ob_start();
        extract($vars);
        include $_SERVER['DOCUMENT_ROOT']."/classes/".$this->rootUrl."/".$name.".php";
        $content = ob_get_clean();
        if($escapeSign) return $this->escapeSign($content);
        return $content;
    }
    
    
}