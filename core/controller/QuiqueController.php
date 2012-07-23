<?php

class QuiqueController {
    private $vista;
    private $controller;
    
    public function __construct() {
        $helper_path = APP_PATH.'/'.MODULE_NAME.'/helpers/'.CONTROLLER_NAME.'_helper.php';
                
        if(file_exists($helper_path)) {
            require_once $helper_path;
        }
        $this->controller = CONTROLLER_NAME;
        $this->load_all_models();
    }
    
    public function view($vista) {
        $params = explode("/", $vista);
        if(count($params) == 1) {
            $this->vista = $params[0];
        }
        elseif(count($params) == 2) {
            $this->controller = $params[0];
            $this->vista = $params[1];            
        }
        require_once APP_PATH.'/'.MODULE_NAME.'/view/layout.php';
    }
    
    public function load_views() {
        require_once APP_PATH.'/'.MODULE_NAME.'/view/'.$this->controller.'/'.$this->vista.'.php';
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
    
    public function load_css() {
        $css_path = APP_PATH.'/'.MODULE_NAME.'/assets/css/';
        $list_css = scandir($css_path);
        
        foreach ($list_css as $css_file) {
            if($css_file != "." && $css_file != "..") {
                echo '<link href="'.$css_path.$css_file.'" rel="stylesheet" type="text/css">'.PHP_EOL;
            }
        }
    }
    
    public function load_js() {
        $js_path = APP_PATH.'/'.MODULE_NAME.'/assets/js/';
        $list_js = scandir($js_path);
        
        foreach ($list_js as $js_file) {
            if($js_file != "." && $js_file != "..") {
                echo '<script type="text/javascript" src="'.$js_path.$js_file.'"></script>'.PHP_EOL;
            }
        }
    }
}
