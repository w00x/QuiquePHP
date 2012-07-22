<?php
class QuiqueConfig {
    public static function get_arr_yml_config($file_name) {
        require_once 'spyc/spyc.php';
        return Spyc::YAMLLoad(CONFIG_PATH.'/'.$file_name);
    }
}