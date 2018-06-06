<?php
class ToolPopUp extends Base {
    
    public function __construct($sfx = false) {
        parent::__construct("Tool/Popup",$sfx);
    }
   
    public function make($class, $clickSelector, $title = false, $body = false) {
        require_once __DIR__."/Popup/js/aes.php";
        $this->include_resource("make", ['class' => $class, 'clickSelector' => $clickSelector, 'title' => $title, 'body' => $body]);
    } 
    
}