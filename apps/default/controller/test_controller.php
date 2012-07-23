<?php

class test_controller extends QuiqueController{
    public function index() {
        $this->hello = "Hola mundo !!";
        $cliente = new personas_model();
        $cliente->select("nombre,id");
        $this->foo = $cliente->find(0);
        
        $this->view('index');
    }
    
    public function hello() {
        $this->hola = "Helloooo!!!!";
        
        $this->view('test2/oliii');
    }
}
?>
