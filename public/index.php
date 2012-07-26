<?php

defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__) . '/../apps'));
defined('CONFIG_PATH') || define('CONFIG_PATH', realpath(dirname(__FILE__) . '/../configs'));
defined('CORE_PATH') || define('CORE_PATH', realpath(dirname(__FILE__) . '/../core'));
defined('PROJECT_PATH') || define('PROJECT_PATH', realpath(dirname(__FILE__) . '/..'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public'));
defined('CACHE_PATH') || define('CACHE_PATH', realpath(dirname(__FILE__) . '/../public/cache'));

$page = 'http';
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $page .= "s";
}
$page .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
    $page .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/";
} else {
    $page .= $_SERVER["SERVER_NAME"]."/";
}

defined('URL_BASE') || define('URL_BASE', $page);

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

require_once 'includes.php';

$file_config = "config.yml";
$config = QuiqueConfig::get_arr_yml_config($file_config);

$is_cache = $config["default"]["cache"]["cache"];
$time_cache = $config["default"]["cache"]["time"];

if($is_cache) {
    $file_config = "cache_routes.yml";
    $config_cache = QuiqueConfig::get_arr_yml_config($file_config);
    
    $cache = new Cache($config_cache["cache_all"],$config_cache["page_cached"]);
    $cache->start($time_cache);
}

require_once 'run.php';

if($is_cache) {
    $cache->end();
}