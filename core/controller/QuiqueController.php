<?php

class QuiqueController {
    private $vista;
    private $controller;
    private $__params;
    
    public function __construct() {
        $helper_path = APP_PATH.'/'.MODULE_NAME.'/helpers/'.CONTROLLER_NAME.'_helper.php';
                
        if(file_exists($helper_path)) {
            require_once $helper_path;
        }
        $this->controller = CONTROLLER_NAME;
        $this->load_all_models();
    }
    
    public function view($vista,$layout = "layout") {
        $params = explode("/", $vista);
        if(count($params) == 1) {
            $this->vista = $params[0];
        }
        elseif(count($params) == 2) {
            $this->controller = $params[0];
            $this->vista = $params[1];            
        }
        $layout_path = APP_PATH.'/'.MODULE_NAME.'/view/'.$layout.'.php';
        
        if(file_exists($layout_path)) {
            require_once $layout_path;
        }
        else {
            try {
                throw new QuiqueExceptions(SHOW_ERRORS,"Error Layout","Layout <b>".$layout."</b> no existe");
            }
            catch(QuiqueExceptions $ex) {
                $ex->echoHTMLMessage();
            }
        }
    }
    
    public function load_views() {
        $view_path = APP_PATH.'/'.MODULE_NAME.'/view/'.$this->controller.'/'.$this->vista.'.php';
        if(file_exists($view_path)) {
            require_once $view_path;
        }
        else {
            try {
                throw new QuiqueExceptions(SHOW_ERRORS,"Error View","View <b>".$this->vista."</b> no existe");
            }
            catch(QuiqueExceptions $ex) {
                $ex->echoHTMLMessage();
            }
        }
    }
    
    private function load_all_models() {
        $model_path = APP_PATH.'/'.MODULE_NAME.'/model/';
        if(is_dir($model_path)) {
            $d = dir($model_path);

            while (false !== ($entry = $d->read())) {
                if($entry != "." && $entry != "..") {
                    require_once $model_path.$entry;
                }
            }
            $d->close();
        }
        else {
            try {
                throw new QuiqueExceptions(SHOW_ERRORS,"Error Application","Application <b>".MODULE_NAME."</b> no existe");
            }
            catch(QuiqueExceptions $ex) {
                $ex->echoHTMLMessage();
            }
        }
    }
    
    public function load_css() {
        $css_path = PROJECT_PATH.'/public/assets/'.MODULE_NAME.'/css/';
        if(is_dir($css_path)) {
            $list_css = scandir($css_path);

            foreach ($list_css as $css_file) {
                if($css_file != "." && $css_file != "..") {
                    echo '<link href="'.URL_BASE.'assets/'.MODULE_NAME.'/css/'.$css_file.'" rel="stylesheet" type="text/css">'.PHP_EOL;
                }
            }
        }
        else {
            try {
                throw new QuiqueExceptions(SHOW_ERRORS,"Error Application","Application <b>".MODULE_NAME."</b> no existe");
            }
            catch(QuiqueExceptions $ex) {
                $ex->echoHTMLMessage();
            }
        }
    }
    
    public function load_js() {
        $js_path = PROJECT_PATH.'/public/assets/'.MODULE_NAME.'/js/';
        if($js_path) {
            $list_js = scandir($js_path);

            foreach ($list_js as $js_file) {
                if($js_file != "." && $js_file != "..") {
                    echo '<script type="text/javascript" src="'.URL_BASE.'assets/'.MODULE_NAME.'/js/'.$js_file.'"></script>'.PHP_EOL;
                }
            }
        }
        else {
            try {
                throw new QuiqueExceptions(SHOW_ERRORS,"Error Application","Application <b>".MODULE_NAME."</b> no existe");
            }
            catch(QuiqueExceptions $ex) {
                $ex->echoHTMLMessage();
            }
        }
    }
    
    public function set_params($par) {
        $this->__params = $par;
    }
    
    public function echo_meta_charset() {
        echo '<meta charset="'.ENCODING.'" />';
    }
}
