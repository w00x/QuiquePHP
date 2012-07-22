<?php

class test_controller extends QuiqueController{
    public function index() {
        $this->hello = "Hola mundo !!";
        $cliente = new personas_model();
        $cliente->select("nombre,id");
        $this->foo = $cliente->findAllByEdad(23);
        
        $this->view('index');
    }
}
?>
