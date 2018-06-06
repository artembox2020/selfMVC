<?php
class ToolJssor extends Base {
    
    public $galleryUsed = false; // marks whether galery() method has been already called
    
    public function __construct($sfx = false) {
         parent::__construct("Tool/Jssor",$sfx);
         $this->include_resource("js/jssor");
    }
    
    public function basic() {
       return $this->get_resource("basic",['h' => 480, "images" => ["https://images.pexels.com/photos/160699/girl-dandelion-yellow-flowers-160699.jpeg?auto=compress&cs=tinysrgb&h=350","https://images.pexels.com/photos/160826/girl-dress-bounce-nature-160826.jpeg?auto=compress&cs=tinysrgb&h=350"] ] );
    }
    
    /**
     * Additional function to retrieve the files from the dir in the specified presentation
     * @param $dir
     * @return array
     */
    public function getDirValues($dir) {
          $files = scandir($dir);
          if(empty($files)) { return []; }
          $arr = [];
          foreach($files as $f) {
              if(in_array($f, [".",".."]) || is_dir($f)) continue;
              $arrParts = explode("-",$f);
              if(count($arrParts) > 1) { $arr[$arrParts[0].".".explode(".",$arrParts[1])[1]] = $arrParts[0]."-".$arrParts[1];    }
          }
          return $arr;
    }
    
    public function getDetailsInfoScript() {
       return $this->get_resource("js/detailInfo");          
    }
    
    public function getPopupForGallery($args = ['gallerySelector'=> "panel-jssor-gallery"]) {
       if(empty($args)) $args = ['gallerySelector'=> "panel-jssor-gallery"];
       $popup = new ToolPopup();
       $popup->make($args['gallerySelector'],"div[data-u='slides'] img[data-u='image']");
    }
    
    public function gallery($arr = []) {
       if(empty($arr)) $arr = ['h' => 480, 'img' => "/classes/Tool/Jssor/Favor",'code' => false];
       else {
           if(empty($arr['h'])) $arr['h'] = 480;
           if(empty($arr['img'])) $arr['img'] = "/classes/Tool/Jssor/Favor";
            if(empty($arr['code'])) $arr['code'] = false;
       }
       $this->generateSfx();    
       return $this->get_resource("gallery",[ "h" => $arr['h'], "img" => $arr['img'], "code" => $arr['code'] ]);
    }
    
    public function getMimeType($filename)
    {
        $mimetype = false;
        /*if(function_exists('finfo_fopen')) {
        // open with FileInfo
        } elseif(function_exists('getimagesize')) {
        // open with GD
        }     elseif(function_exists('exif_imagetype')) {
       // open with EXIF
        } elseif(function_exists('mime_content_type')) {
        */
       $mimetype = mime_content_type($filename);
        //}
        return $mimetype;
    }
    
    public function banner($img = "img", $code = false) {
       $this->generateSfx();        
       return $this->get_resource("banner",['img' => $img, 'imgurl' => "/classes/".$this->rootUrl."/".$img, 'siteRoot' => $_SERVER['DOCUMENT_ROOT'], 'id' => $this->sfx, 'code' => $code]); 
    }
    
}