<?php
$project_path = dirname(__FILE__) . '/..';

defined('PROJECT_PATH') || define('PROJECT_PATH', realpath($project_path));
defined('APP_PATH') || define('APP_PATH', realpath(PROJECT_PATH.'/apps'));
defined('CONFIG_PATH') || define('CONFIG_PATH', realpath(PROJECT_PATH.'/configs'));
defined('CORE_PATH') || define('CORE_PATH', realpath(PROJECT_PATH . '/core'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(PROJECT_PATH . '/public'));
defined('CACHE_PATH') || define('CACHE_PATH', realpath(PROJECT_PATH . '/public/cache'));

$page = 'http';
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $page .= "s";
}
$page .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
    $page .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/";
} else {
    $page .= $_SERVER["SERVER_NAME"];
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

require_once CORE_PATH.'/run.php';