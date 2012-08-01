<?php
require_once 'test.php';

class prueba_controller extends Controller {

    public function index() {
        hello();
        $this->view("index");
    }

}
