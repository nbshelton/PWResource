<?php

namespace Controllers;

class Build extends \Controller {
    
    protected function display(/*$id*/) {
        $id = @func_get_arg(0);
        @$model->id = $id ? $id : "NONE";
        return $this->view($model);
    }
    
    protected function weaponInfo($id) {
        return $this->partial(\Models\Database\WeaponTemplate::getById($id));
    }
    
}
