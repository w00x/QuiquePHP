<?php
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__) . '/../apps'));
defined('CONFIG_PATH') || define('CONFIG_PATH', realpath(dirname(__FILE__) . '/../configs'));
defined('CORE_PATH') || define('CORE_PATH', realpath(dirname(__FILE__) . '/../core'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APP_PATH . '/../libs'),
    get_include_path(),
)));

set_include_path(implode(PATH_SEPARATOR, array(
    CORE_PATH,
    get_include_path(),
)));

set_include_path(implode(PATH_SEPARATOR, array(
    CORE_PATH.'/libs',
    get_include_path(),
)));

$requestURI = explode('/', $_SERVER['REQUEST_URI']);

require_once 'includes.php';

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
        define('MODULE_NAME',$requestURI[1]);
        define('CONTROLLER_NAME',$requestURI[2]);
        define('ACTION_NAME',$requestURI[3]);
    }
    else {
        define('MODULE_NAME','default');
        define('CONTROLLER_NAME',$requestURI[1]);
        define('ACTION_NAME',$requestURI[2]);
    }
}

require_once APP_PATH.'/'.MODULE_NAME.'/controller/'.CONTROLLER_NAME.'_controller.php';
$class_name = CONTROLLER_NAME.'_controller';
$controller = new $class_name();
$action_name = ACTION_NAME;
$controller->$action_name();

