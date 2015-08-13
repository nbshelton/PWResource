<?php

abstract class Controller {
    
    private $action;
    private $parameters;
    
    public static function getController($controllerName, $action, $parameters) {
        $controllerName = "\\Controllers\\".$controllerName;
        return new $controllerName($action, $parameters);
    }
    
    protected function __construct($action, $parameters) {
        $this->action = $action;
        $this->parameters = $parameters;
    }
    
    protected function partial($model=null, $view="") {
        if ($view == "") {
            $view = $this->action;
        }
        $view = join('', array_slice(explode('\\', get_class($this)), -1)) . DIRECTORY_SEPARATOR . $view;
        return new \PartialView($view, $model);
    }
    
    protected function view($model=null, $view="", $title="") {

        //Ajax requests should always return a partial view
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return $this->partial($model, $view);

        if ($view == "") {
            $view = $this->action;
        }
        $view = join('', array_slice(explode('\\', get_class($this)), -1)) . DIRECTORY_SEPARATOR . $view;
        return new \View($view, $model, $title);
    }
    
    public function run() {
        if (method_exists($this, $this->action)) {
            return call_user_func_array(array($this, $this->action), $this->parameters);
        } else {
            return new NotFoundResponse();
        }
    }
    
}
