<?php

namespace Routing;

class Route {
    private $format;
    private $controller;
    private $action;
    private $parameters;
    
    function __construct($format, $controller=null, $action=null) {
        $format = str_replace("\{", "{", str_replace("\}", "}", preg_quote($format)));
        $this->format = $format == "" ? ".+" : $format;
        $this->controller = $controller;
        $this->action = $action;
        
        $this->parameters = array();
        preg_match_all('/\{[A-Za-z0-9_]+\}/', $format, $matches);
        for($i=0; $i<sizeof($matches[0]); $i++) {
            $var = $matches[0][$i];
            $this->parameters[] = substr($var, 1, -1);
            $this->format = str_replace($var, "([A-Za-z0-9_.]+)", $this->format);
        }
    }
    
    function tryMatch($uri) {
        $uri = substr($uri, 0, strpos($uri, "?")?:strlen($uri));
        if ($uri[strlen($uri)-1] == '/')
            $uri = substr($uri, 0, -1);
        if ($uri == "")
            $uri = "/";
        if (preg_match("~^".$this->format."$~", $uri, $matches)) {
            $matches = array_splice($matches, 1);
            $cmdArray = array_combine($this->parameters, $matches);
            if (array_key_exists("controller", $cmdArray)) {
                    $this->controller = $cmdArray["controller"];
                    unset($cmdArray["controller"]);
            }
            if (array_key_exists("action", $cmdArray)) {
                    $this->action = $cmdArray["action"];
                    unset($cmdArray["action"]);
            }
            $cmdArray = array_merge($cmdArray, $_GET, $_POST);

            if (DEBUG) {
                echo("Route Selected: ".$this->controller." -> ".$this->action.var_dump($cmdArray)."<br /><br />");
            }

            return new RouteCommand($this->controller, $this->action, $cmdArray);
        } else {
            return null;
        }
    }
}