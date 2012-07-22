<?php

class QuiqueModel {
    private $dbh;
    private $model_name;
    private $sql_select;
    
    public function __construct() {
        $this->sql_select = " * ";
        
        $class_name = strtolower(get_class($this));
        $this->model_name = "";
        
        if(strpos($class_name,"_model") !== FALSE) {
             $this->model_name = str_replace("_model", "", $class_name);
        }
        
        $db_config = QuiqueConfig::get_arr_yml_config("database.yml");
        $app_db_config = $db_config[MODULE_NAME];
        
        $db_name = $app_db_config["db_name"];
        $host = $app_db_config["host"];
        $driver = $app_db_config["driver"];
        $user = $app_db_config["user"];
        $password = $app_db_config["password"];
        
        $dsn = "{$driver}:dbname={$db_name};host={$host}";
        $this->dbh = new PDO($dsn, $user, $password);
    }
    
    public function get_dbh() {
        return $this->dbh;
    }
    
    public function sql_query($sql,$params = array()) {
        $sth = $this->dbh->prepare($sql);
        
        if(count($params) > 0) {
            $sth->execute($params);
        }
        else {
            $sth->execute();
        }
        return $sth;
    }
    
    public function select($columns) {
        $this->sql_select = " ".$columns." ";
    }
    
    public function where($sql_where,$arr_params = array()) {
        $sql = "SELECT {$this->sql_select} FROM {$this->model_name} WHERE ";
        return $this->sql_query($sql.$sql_where,$arr_params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete_where($sql_where,$arr_params = array()) {
        $sql = "DELETE FROM {$this->model_name} WHERE ";
        return $this->sql_query($sql.$sql_where,$arr_params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function __call($name, $arguments) {
        return $this->parser_funtion_to_sql_query($name, $arguments);
    }
    
    private function parser_funtion_to_sql_query($function_name,$arguments) {
        if(strpos($function_name,"findBy") !== FALSE) {
            $column = strtolower(str_replace("findBy", "", $function_name));
            $val = $this->where($column." = :".$column." LIMIT 1", array(":".$column => $arguments[0]));
            return $val[0];
        }
        elseif(strpos($function_name,"findAllBy") !== FALSE) {
            $column = strtolower(str_replace("findAllBy", "", $function_name));
            $val = $this->where($column." = :".$column." LIMIT 1", array(":".$column => $arguments[0]));
            return $val[0];
        }
        elseif(strpos($function_name,"deleteBy") !== FALSE) {
            $column = strtolower(str_replace("deleteBy", "", $function_name));
            $val = $this->delete_where($column." = :".$column, array(":".$column => $arguments[0]));
            return $val[0];
        }
    }
}