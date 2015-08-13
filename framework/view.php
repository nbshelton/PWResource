<?php

use \Routing\Router;

class View extends Response {
    
    public $view;
    public $model;
    public $title;
    
    public function __construct($view, $model=null, $title="") {
        if (file_exists(VIEWS . $view . ".php")) {
            $this->view = $view;
            $this->model = $model;
        } else {
            http_response_code(404);
            $this->view = "404";
            $this->model = null;
        }
        
        if (empty($title)) {
            $viewArr = explode("\\", $view);
            $this->title = ucfirst(end($viewArr));
        } else {
            $this->title = $title;
        }
    }
    
    public function render() {
        $model = $this;
        include(VIEWS . "_layout.php");
    }
    
    public function output() {
        $partial = new \PartialView($this->view, $this->model, $this->title);
        $partial->render();
    }

    public function renderLink($path, $name, $newtab=false) {
        $str = '<a href="'.Router::buildURL($path).'"';
        if ($newtab)
            $str .= ' target="_blank"';
        $str .= ">$name</a>";
        echo($str);

    }
    
    public function renderStyle($style="") {
        if (empty($style))
            $style = $this->view . '.css';

        if (file_exists(STYLES . $style))
            echo('<link rel="stylesheet" type="text/css" href="'.Router::buildURL("/styles/$style").'" />');
    }
    
    public function renderScript($script="") {
        if (empty($script))
            $script = $this->view . '.js';
        
        if (file_exists(SCRIPTS . $script))
            echo('<script src="'.Router::buildURL("/scripts/$script").'"></script>');
    }
    
}
