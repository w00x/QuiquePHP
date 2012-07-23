<?php
class Route {
    public function __construct() {
        
    }
    
    public function isModule($module) {
        $list_dir = scandir(APP_PATH);
        if(array_search($module, $list_dir) !== FALSE) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function match_route($url) {
        require_once 'spyc/spyc.php';
        $routes = Spyc::YAMLLoad(CONFIG_PATH.'/routes.yml');
        if(array_key_exists($url, $routes)) {
            return array("app"=>$routes[$url]["app"],"controller"=>$routes[$url]["controller"],"action"=>$routes[$url]["action"]);
        }
    }
}
?>
