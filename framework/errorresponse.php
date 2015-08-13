<?php

class ErrorResponse extends StatusCodeResponse {
    
    public function __construct() {
        parent::__construct(500, new View("500"));
    }
    
}
