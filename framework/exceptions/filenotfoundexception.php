<?php

class FileNotFoundException extends Exception {
    
    public $file;
    
    public function __construct($file) {
        parent::__construct("File not found.", 0, null);
        $this->file = $file;
    }
    
}