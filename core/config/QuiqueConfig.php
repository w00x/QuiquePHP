<?php
class QuiqueConfig {
    public static function get_arr_yml_config($file_name) {
        return Spyc::YAMLLoad(CONFIG_PATH.'/'.$file_name);
    }
}