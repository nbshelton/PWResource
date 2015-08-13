<?php

namespace Controllers;
use Models\Database as Models;

class Database extends \Controller {
    
    protected function item(/*$id*/) {
        if (func_num_args() != 1)
            return $this->itemList();
        $id = func_get_arg(0);
        
        $db = \ElementDatabase::GetInstance();
        if ($item = $db->getById("weapon_items", $id)) {
            $weapon = Models\WeaponTemplate::getByRow($item);
            return $this->view($weapon, "weapon", $weapon->name);
        } else {
            return new \NotFoundResponse();
        }
    }
    
    protected function itemList(/*$id, $page*/) {
        //return new \NotFoundResponse();

        $id = (func_num_args() > 0) ? func_get_arg(0) : "weapon";
        $page = (func_num_args() > 1) ? func_get_arg(1) : 1;

        $model = new Models\itemList($id, $page);
        return $this->view($model);

    }

    protected function index(/*$page*/) {
        $page = (func_num_args() == 0) ? 1 : func_get_arg(0);

        return new \RedirectResponse("database/weapon/$page");
    }
    
}
