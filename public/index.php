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

require_once 'run.php';
