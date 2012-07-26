<?php
$requestURI = explode('/', $_SERVER['REQUEST_URI']);

$route = new Route();
$routes_match = $route->match_route($_SERVER['REQUEST_URI']);
$params = array();

if($routes_match !== false) {
    define('MODULE_NAME',$routes_match["app"]);
    define('CONTROLLER_NAME',$routes_match["controller"]);
    define('ACTION_NAME',$routes_match["action"]);
    $params = $routes_match["params"];
}
else {
    $requestURI[1] = str_replace("?", "", $requestURI[1]);

    if($route->isModule($requestURI[1])) {
        if(count($requestURI) == 4) {
            define('MODULE_NAME',$requestURI[1]);
            define('CONTROLLER_NAME',$requestURI[2]);
            define('ACTION_NAME',$requestURI[3]);
            $params = array();
        }
        else {
            echo "Pagina no encontrada";die;
        }
    }
    else {
        if(count($requestURI) == 3) {
            define('MODULE_NAME','default');
            define('CONTROLLER_NAME',$requestURI[1]);
            define('ACTION_NAME',$requestURI[2]);
            $params = array();
        }
        else {
            echo "Pagina no encontrada";die;
        }
    }
}

$require_path = APP_PATH.'/'.MODULE_NAME.'/controller/'.CONTROLLER_NAME.'_controller.php';

if(file_exists($require_path)) {
    require_once $require_path;
    $class_name = CONTROLLER_NAME.'_controller';
    $controller = new $class_name();
    $action_name = ACTION_NAME;
    $controller->set_params($params);
    $controller->$action_name();
}
else {
    echo "Pagina no encontrada";die;
}