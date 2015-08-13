<?php

namespace Controllers;

use \Models\Database as Models;

class Api extends \Controller {
    
    protected function weaponTypes() {
        $db = \ElementDatabase::GetInstance();
        return new \Json($db->select(array("table" => "weapon_major_type")));
    }
    
    protected function weaponSubtypes($type) {
        $db = \ElementDatabase::GetInstance();
        $result = $db->query("SELECT * FROM weapon_sub_type WHERE id IN (SELECT id_sub_type FROM weapon_items WHERE id_major_type = ".$db->escape($type)." GROUP BY id_sub_type)");
        return new \Json($result->fetch_all(MYSQLI_ASSOC));
    }
    
    protected function weaponList($subtype) {
        $db = \ElementDatabase::GetInstance();
        $weapons = $db->select(array(
            "table" => "weapon_items",
            "where" => array("id_sub_type" => $subtype, "name" => array("", false)),
            "orderBy" => array("require_level", "name")
        ));
        $list = array();
        foreach($weapons as $weapon) {
            $list[] = Models\WeaponTemplate::getByRow($weapon, true);
        }
        return new \Json($list);
    }
    
    protected function weapon($id) {
        return new \Json(new Models\WeaponTemplate($id));
    }
    
}