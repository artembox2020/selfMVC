<?php 
  class MainController extends Controller {
      public function __construct() {
         $this->controller = 'main'; 
         $this->layout = 'main'; 
      }
      
      public function indexAction($arr, $layout='main') {
        $this->setTitle("selfMVC framework");
        $this->render(['content' => '<a href="https://artembox.info" target=_blank><img style="width: 100%;" src= "/img/self-mvc-banner.png" /><p></p><p>selfMVC framework, follow the link for details</p></a>']);
      }
      
  }
  $class = "MainController";