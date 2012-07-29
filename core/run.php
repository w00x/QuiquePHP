<?php
require_once 'includes.php';

$file_config = "config.yml";
$config = QuiqueConfig::get_arr_yml_config($file_config);

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
            require_once 'html_errors/404.php';
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
            require_once 'html_errors/404.php';
        }
    }
}

$show_errors = $config[MODULE_NAME]["show-errors"];
$enconding = $config[MODULE_NAME]["encoding"];

defined('SHOW_ERRORS') || define('SHOW_ERRORS', $show_errors);
defined('ENCODING') || define('ENCODING', $enconding);

$is_cache = $config[MODULE_NAME]["cache"]["cache"];
$time_cache = $config[MODULE_NAME]["cache"]["time"];

if($is_cache) {
    $file_config = "cache_routes.yml";
    $config_cache = QuiqueConfig::get_arr_yml_config($file_config);
    
    $cache = new Cache($config_cache["cache_all"],$config_cache["page_cached"]);
    $cache->start($time_cache);
}

header('Content-type: text/html; charset='.ENCODING);

$require_path = APP_PATH.'/'.MODULE_NAME.'/controller/'.CONTROLLER_NAME.'_controller.php';

if(file_exists($require_path)) {
    try {
        require_once $require_path;
        $class_name = CONTROLLER_NAME.'_controller';
        $controller = new $class_name();
        $action_name = ACTION_NAME;
        $controller->set_params($params);
        $controller->$action_name();
    }
    catch(Exception $ex) {
        try {
            throw new QuiqueExceptions(SHOW_ERRORS,"Error Controller",$ex->getMessage());
        }
        catch(QuiqueExceptions $ex) {
            $ex->echoHTMLMessage();
        }
    }
}
else {
    require_once 'html_errors/404.php';
}

if($is_cache) {
    $cache->end();
}