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
        
        foreach ($routes as $key => $route) {
            $pos_dos_puntos = strpos($key, ":");
            $variables_ruta = array();
            
            if($pos_dos_puntos !== FALSE) {
                $ruta_pura = substr($key, 0,$pos_dos_puntos);
                $variables_ruta = $this->variables_route($key);
                
            }
            else {
                $ruta_pura = $key;
            }
            
            if($ruta_pura[strlen($ruta_pura)-1] != "/") {
                $ruta_pura = $ruta_pura."/";
            }
            
            if($url[strlen($url)-1] != "/") {
                $url = $url."/";
            }
            
            if(strpos($ruta_pura, $url) == 0) {
                if(count($variables_ruta) > 0) {
                    $params_str = str_replace($ruta_pura, "", $url);
                    if($params_str[strlen($params_str)-1] == "/") {
                        $params_str = substr($params_str, 0,-1);
                    }
                    $params_arr = explode("/", $params_str);
                    if(count($params_arr) == count($variables_ruta)) {
                        $params = array();
                        for($i=0;$i<count($params_arr);$i++) {
                            $params[$variables_ruta[$i]] = $params_arr[$i];
                        }
                        return array("app"=>$routes[$key]["app"],"controller"=>$routes[$key]["controller"],"action"=>$routes[$key]["action"],"params"=>$params);
                    }
                }
                else {
                    return array("app"=>$routes[$key]["app"],"controller"=>$routes[$key]["controller"],"action"=>$routes[$key]["action"],"params"=>array());
                }
            }
            else {
                return false;
            }
        }
    }
    
    private function variables_route($route) {
        $url_tmp = $route;
        
        if($route[strlen($route)] != "/") {
            $url_tmp = $url_tmp."/";
        }
        
        $variables = array();
        do {
            $pos_dos_puntos = strpos($url_tmp, ":");
            $url_tmp = substr($url_tmp,$pos_dos_puntos+1);
            $pos_slash = strpos($url_tmp,"/");
            $tmp = substr($url_tmp, 0,$pos_slash);
            if($tmp !== false) {
                $variables[] = $tmp;
            }
            $url_tmp = substr($url_tmp,$pos_slash+1);
        } while($pos_slash !== false);
        
        return $variables;
    }
}
?>
