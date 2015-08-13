<?php

class Json extends Response {
    
    public $obj;
    
    public function __construct($item) {
        $this->obj = $item;
    }
    
    public function render() {
        echo json_encode($this->obj);
    }
    
}
