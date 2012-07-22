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
}
?>
