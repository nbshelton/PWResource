<?php

namespace Controllers;

class Home extends \Controller {
    
    protected function index() {
        //return new \RedirectResponse("build");
        
        return $this->view();
    }
    
}