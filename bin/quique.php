<?php
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__) . '/../apps'));
defined('CONFIG_PATH') || define('CONFIG_PATH', realpath(dirname(__FILE__) . '/../configs'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public'));
defined('CORE') || define('CORE', realpath(dirname(__FILE__) . '/../core'));

require_once CORE.'/libs/spyc/spyc.php';
require_once CORE.'/config/QuiqueConfig.php';

function generate_app($app,$argumentos) {
    if(!is_dir(APP_PATH."/".$app)) {
        if(!is_dir(APP_PATH."/".$app)) {
            mkdir(APP_PATH."/".$app);
            echo "create dir: ".APP_PATH."/".$app.PHP_EOL;
        }
        
        if(!is_dir(APP_PATH."/".$app."/controller")) {
            mkdir(APP_PATH."/".$app."/controller");
            echo "create dir: ".APP_PATH."/".$app."/controller".PHP_EOL;
        }
        
        if(!is_dir(APP_PATH."/".$app."/libs")) {
            mkdir(APP_PATH."/".$app."/libs");
            echo "create dir: ".APP_PATH."/".$app."/libs".PHP_EOL;
        }
        
        if(!is_dir(APP_PATH."/".$app."/helpers")) {
            mkdir(APP_PATH."/".$app."/helpers");
            echo "create dir: ".APP_PATH."/".$app."/helpers".PHP_EOL;
        }
        
        if(!is_dir(APP_PATH."/".$app."/model")) {
            mkdir(APP_PATH."/".$app."/model");
            echo "create dir: ".APP_PATH."/".$app."/model".PHP_EOL;
        }
        
        if(!is_dir(APP_PATH."/".$app."/view")) {
            mkdir(APP_PATH."/".$app."/view");
            echo "create dir: ".APP_PATH."/".$app."/view".PHP_EOL;
        }
        
        if(!is_dir(PUBLIC_PATH."/assets/".$app)) {
            mkdir(PUBLIC_PATH."/assets/".$app);
            echo "create dir: ".PUBLIC_PATH."/assets/".$app.PHP_EOL;
        }
        
        if(!is_dir(PUBLIC_PATH."/assets/".$app."/css")) {
            mkdir(PUBLIC_PATH."/assets/".$app."/css");
            echo "create dir: ".PUBLIC_PATH."/assets/".$app."/css".PHP_EOL;
        }
        
        if(!is_dir(PUBLIC_PATH."/assets/".$app."/img")) {
            mkdir(PUBLIC_PATH."/assets/".$app."/img");
            echo "create dir: ".PUBLIC_PATH."/assets/".$app."/img".PHP_EOL;
        }
        
        if(!is_dir(PUBLIC_PATH."/assets/".$app."/js")) {
            mkdir(PUBLIC_PATH."/assets/".$app."/js");
            echo "create dir: ".PUBLIC_PATH."/assets/".$app."/js".PHP_EOL;
        }
        
        $file = fopen(APP_PATH."/".$app."/view/layout.php","w");
        echo "create file: ".APP_PATH."/".$app."/view/layout.php".PHP_EOL;
        
        $php_code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
    <html>
        <head>
            <?php $this->echo_meta_charset(); ?>
            <?php $this->load_js(); ?>
            <?php $this->load_css(); ?>
            <title>Quique title</title>
        </head>
        <body>
            <?php $this->load_views(); ?>
        </body>
    </html>
    ';

        fwrite($file,$php_code);
        fclose($file);
        
        $file_controller = fopen(APP_PATH."/{$app}/controller/controller.php","w");
        echo "create file: ".APP_PATH."/{$app}/controller/controller.php".PHP_EOL;
        
        $php_code_controller = '<?php
class Controller extends QuiqueController {

}
';
        fwrite($file_controller,$php_code_controller);
        fclose($file_controller);
        
        $file_model = fopen(APP_PATH."/{$app}/model/model.php","w");
        echo "create file: ".APP_PATH."/{$app}/model/model.php".PHP_EOL;
        
        $php_code_model = '<?php
class Model extends QuiqueModel {

}
';
        fwrite($file_model,$php_code_model);
        fclose($file_model);
        
        $db_yml = array();
        $db_yml[$app]["host"] = "localhost";
        $db_yml[$app]["user"] = "";
        $db_yml[$app]["password"] = "";
        $db_yml[$app]["driver"] = "";
        $db_yml[$app]["db_name"] = "";
        
        QuiqueConfig::apend_array_to_yml("database.yml", $db_yml);
        echo "add config into database.yml".PHP_EOL;
        
        $config_yml = array();
        $config_yml[$app]["show-errors"] = false;
        $config_yml[$app]["encoding"] = "utf-8";
        $config_yml[$app]["cache"]["cache"] = false;
        $config_yml[$app]["cache"]["time"] = 0;
        
        QuiqueConfig::apend_array_to_yml("config.yml", $config_yml);
        echo "add config into config.yml".PHP_EOL;
    }
    else {
        echo "La aplicación {$app} ya se encuentra creada.".PHP_EOL;
    }
}

function generate_controller($app,$argumentos) {
    $controller_name = $argumentos[0];
    $actions = array_slice($argumentos, 1);

    if(!is_dir(APP_PATH."/{$app}/view/{$controller_name}")) {
        mkdir(APP_PATH."/{$app}/view/{$controller_name}");
        echo "create dir: ".APP_PATH."/{$app}/view/{$controller_name}".PHP_EOL;
    }
    if(is_dir(APP_PATH."/{$app}/view/{$controller_name}")) {
        $file = fopen(APP_PATH."/{$app}/controller/{$controller_name}_controller.php","w");
        echo "create file: ".APP_PATH."/{$app}/controller/{$controller_name}_controller.php".PHP_EOL;
        $php_code = '<?php
class '.$controller_name.'_controller extends Controller {';
    foreach($actions as $action) {
        $php_code .= '

    public function '.$action.'() {
        $this->view("'.$action.'");
    }';
        $file_view = fopen(APP_PATH."/{$app}/view/{$controller_name}/{$action}.php","w");
        echo "create file: ".APP_PATH."/{$app}/view/{$controller_name}/{$action}.php".PHP_EOL;
        fwrite($file_view,"<h1>View {$action}</h1>");
        fclose($file_view);
    }
    $php_code .= '

}
';
        fwrite($file,$php_code);
        fclose($file);
    }
    else {
        echo "Problemas al crear el controlador".PHP_EOL;
    }
}

function generate_model($app,$argumentos) {
    foreach($argumentos as $argumento) {
        $file = fopen(APP_PATH."/{$app}/model/{$argumento}_model.php","w");
        echo "create file: ".APP_PATH."/{$app}/controller/{$argumento}_model.php".PHP_EOL;
        $php_code = '<?php
class '.$argumento.'_model extends Model {

}
';
        fwrite($file,$php_code);
        fclose($file);
    }
}

if($argc >= 2) {
    
    $options_generate = array("app",
                     "controller",
                     "action",
                     "model");
    
    if($argv[1] == "generate") {
        if(array_search($argv[2], $options_generate) !== false) {
            $function_name = "generate_{$argv[2]}";
            $app = str_replace(".", "", $argv[3]);
            $app = str_replace("/", "", $app);
            $app = str_replace("\\", "", $app);
            
            if($argc > 4) {
                $argumentos = array_slice($argv, 4);
            }
            else {
                $argumentos = array();
            }
            $function_name($app,$argumentos);
        }
    }
    elseif($argv[1] == "update") {
        $url_status = "https://raw.github.com/w00x/QuiquePHP-Core/master/status.yml";
        $url_base = "https://raw.github.com/w00x/QuiquePHP-Core/master";
        
        $status_yml = download_file_content($url_status);
                
        $yml_arr_status = Spyc::YAMLLoadString($status_yml);
        $yml_arr_status_local = Spyc::YAMLLoad(CORE."/status.yml");
        
        $version_cloud = $yml_arr_status["version"];
        $version_local = $yml_arr_status_local["version"];
        
        if($version_cloud == $version_local) {
            echo PHP_EOL."Comenzando la actualizacion del sistema a la version del core: {$version_cloud}".PHP_EOL;
            
            if(isset($yml_arr_status["delete"])) {
                if(isset($yml_arr_status["delete"]["files"])) {
                    foreach($yml_arr_status["delete"]["files"] as $file) {
                        echo "Eliminando archivo ".CORE."/".$file;
                        unlink(CORE."/".$file);
                        echo "\t[OK]".PHP_EOL;
                    }
                }
                
                if(isset($yml_arr_status["delete"]["dirs"])) {
                    foreach($yml_arr_status["delete"]["dirs"] as $dir) {
                        if(is_dir(CORE."/".$dir)) {
                            rmdir(CORE."/".$dir);
                            echo "Eliminando directorio ".CORE."/".$dir.PHP_EOL;
                        }
                    }
                }
            }
            
            if(isset($yml_arr_status["create"])) {
                if(isset($yml_arr_status["create"]["dirs"])) {
                    foreach($yml_arr_status["create"]["dirs"] as $dir) {
                        if(!is_dir(CORE."/".$dir)) {
                            mkdir(CORE."/".$dir);
                            echo "Creando directorio ".CORE."/".$dir.PHP_EOL;
                        }
                    }
                }
                
                if(isset($yml_arr_status["create"]["files"])) {
                    foreach($yml_arr_status["create"]["files"] as $file) {
                        echo "Actualizando archivo ".CORE."/".$file.PHP_EOL;
                        echo "\tDescargando ".$url_base."/".$file;
                        $web_content = download_file_content($url_base."/".$file);
                        $file = fopen(CORE."/".$file,"w");
                        fwrite($file, $web_content);
                        fclose($file);
                        echo "\t[OK]".PHP_EOL;
                    }
                }
            }
            
            echo PHP_EOL."Proceso de actualizacion finalizado.".PHP_EOL;
        }
        else {
            echo "El sistema se encuentra actualizado".PHP_EOL;
        }
    }
}
else {
    echo "QuiquePHP Lightweight PHP Framework".PHP_EOL.PHP_EOL;
    echo "Cantidad de parametros erronea.".PHP_EOL;
    echo "Modo de uso:".PHP_EOL;
    echo "\tphp bin/quique generate app [app_name]\t".PHP_EOL;
    echo "\tphp bin/quique generate controller [app_name] [controller_name] {actions_name}\t".PHP_EOL;
    echo "\tphp bin/quique generate model [app_name] [model_name]\t".PHP_EOL;
    echo "\tphp bin/quique update\t".PHP_EOL;
    
    echo PHP_EOL;
}

function download_file_content($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $web_content = curl_exec($ch);
    curl_close($ch);
    
    return $web_content;
}