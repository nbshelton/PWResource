<?php

class NotFoundResponse extends StatusCodeResponse {
    
    public function __construct() {
        parent::__construct(404, new View("404"));
    }
    
}
