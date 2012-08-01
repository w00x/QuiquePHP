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

if(!isset($config[MODULE_NAME])) {
    try {
        throw new QuiqueExceptions(SHOW_ERRORS,"Error Config",MODULE_NAME." No tiene configuracion definida.");
    }
    catch(QuiqueExceptions $ex) {
        $ex->echoHTMLMessage();
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
$require_controller_path = APP_PATH.'/'.MODULE_NAME.'/controller/controller.php';
$require_model_path = APP_PATH.'/'.MODULE_NAME.'/model/model.php';

defined('LIB_APP_PATH') || define('LIB_APP_PATH', realpath(dirname(__FILE__) . '/../apps/'.MODULE_NAME.'/libs'));
defined('MODE_APPL_PATH') || define('MODE_APPL_PATH', realpath(dirname(__FILE__) . '/../apps/'.MODULE_NAME.'/model'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(LIB_APP_PATH),
    get_include_path(),
)));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(MODE_APPL_PATH),
    get_include_path(),
)));


if(file_exists($require_path)) {
    try {
        require_once $require_controller_path;
        require_once $require_model_path;
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