<?php
class hola_controller extends Controller {

    public function index() {
        $this->view("index");
    }
    
    public function foo() {
        
        
        $this->view("foo");
    }

}
