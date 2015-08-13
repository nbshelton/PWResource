<?php

namespace Routing;

class RouteCommand {
    private $controller;
    private $action;
    private $parameters;
    
    function __construct($controller, $action, $parameters = null) {
        $this->controller = $controller;
        $this->action = $action;
        $this->parameters = $parameters;
    }
    
    function execute() {
        try {
            $controller = \Controller::getController($this->controller, $this->action, $this->parameters);
            return $controller->run();
        } catch (\FileNotFoundException $e) {
            return new \NotFoundResponse();
        }
    }
}
