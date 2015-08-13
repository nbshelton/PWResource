<?php

namespace Routing;

class RouteParser {
    
    static function getRoutes() {
        $routeConfig = parse_ini_file(CONFIG . "routes.ini", true);
        $default = false;
        if (array_key_exists("default", $routeConfig)) {
            $def = $routeConfig["default"];
            $default = new Route("", $def["controller"], $def["action"]);
            unset($routeConfig["default"]);
        }
        $routeConfig = array_values($routeConfig);
        $routes = array();
        for($i=0; $i<sizeof($routeConfig); $i++) {
            $route = $routeConfig[$i];
            $path = $route["route"];
            $controller = array_key_exists("controller", $route) ? $route["controller"] : null;
            $action = array_key_exists("action", $route) ? $route["action"] : null;
            $routes[] = new Route($path, $controller, $action);
        }
        if ($default)
            $routes[] = $default;
        return $routes;
    }
    
}
