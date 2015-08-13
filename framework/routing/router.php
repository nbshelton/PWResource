<?php

namespace Routing;

class Router {
    
    private $command;
    
    public static function buildURL($filepath) {
        return 'http'.($_SERVER['HTTPS'] ? 's' : '')."://".$_SERVER['HTTP_HOST'].str_replace('\\', '/', $filepath);
    }
    
    public function __construct() {
        $routes = RouteParser::getRoutes();
        
        $this->command = null;
        $uri = rtrim($_SERVER['REQUEST_URI'], '/');
        for($i=0; $i<sizeof($routes) && $this->command == null; $i++) {
            $this->command = $routes[$i]->tryMatch($uri);
        }
    }
    
    public function run() {
        $response = new \NotFoundResponse();
        if ($this->command != null)
            $response = $this->command->execute();
        
        $response->render();
    }
    
}
