<?php

class PartialView extends View {
    
    public function render() {
        $model = $this->model;
        include(VIEWS . $this->view . ".php");
    }
    
    public function output() {}
    
}
