<?php

class QuiqueController {
    private $vista;
    
    public function __construct() {
        $helper_path = APP_PATH.'/'.MODULE_NAME.'/helpers/'.CONTROLLER_NAME.'_helper.php';
                
        if(file_exists($helper_path)) {
            require_once $helper_path;
        }
        $this->load_all_models();
    }
    
    public function view($vista) {
        $this->vista = $vista;                
        require_once APP_PATH.'/'.MODULE_NAME.'/view/layout.php';
    }
    
    public function load_views() {
        require_once APP_PATH.'/'.MODULE_NAME.'/view/'.CONTROLLER_NAME.'/'.$this->vista.'.php';
    }
    
    private function load_all_models() {
        $model_path = APP_PATH.'/'.MODULE_NAME.'/model/';
        
        $d = dir($model_path);
        
        while (false !== ($entry = $d->read())) {
            if($entry != "." && $entry != "..") {
                require_once $model_path.$entry;
            }
        }
        $d->close();
    }
}
