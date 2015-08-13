<?php

class StatusCodeResponse extends Response {
    
    protected $status;
    protected $view;
    
    public function __construct($status, View $view=null) {
        $this->status = $status;
        $this->view = $view;
    }
    
    public function render() {
        http_response_code($this->status);
        if ($this->view != null) {
            $this->view->render();
        }
    }
    
}
