<?php

class personas_model extends QuiqueModel {
    public function getAllClientes() {
        $sql = "SELECT * FROM personas;";
        $lala = $this->sql_query($sql)->fetchAll();
        print_r($lala);
    }
}