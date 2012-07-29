<?php
class QuiqueConfig {
    public static function get_arr_yml_config($file_name) {
        $file_path = CONFIG_PATH.'/'.$file_name;
        if(file_exists($file_path)) {
            return Spyc::YAMLLoad($file_path);
        }
        else {
            try {
                throw new QuiqueExceptions(SHOW_ERRORS,"Error Configure","Configuracion <b>".$file_path."</b> no existe");
            }
            catch(QuiqueExceptions $ex) {
                $ex->echoHTMLMessage();
            }
        }
    }
}