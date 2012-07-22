<?php
class test_controller extends QuiqueController{
    public function index() {
        $this->hello = "Hola mundo en prueba !!";
        $this->view('index');
    }
}
?>
