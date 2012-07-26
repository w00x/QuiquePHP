<?php
class test_controller extends QuiqueController{
    public function index() {
        $cliente = new personas_model();
        $cliente->select("nombre,id");
        $this->foo = $cliente->find(0);
        
        $this->view('index');
    }
}
?>
