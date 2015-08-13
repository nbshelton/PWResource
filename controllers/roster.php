<?php

namespace controllers;
use Models\Roster as Models;

class Roster extends \Controller {

    protected function squads(/*$id*/) {
        $id = @func_get_arg(0);
        if (!$id)
            return new \NotFoundResponse();

        $db = \RosterDatabase::GetInstance();
        if (!$db->any("rosters", array("id" => $id)))
            return new \NotFoundResponse();

        $classes = $db->sel("class");
        $members = array();
        foreach($classes as $class) {
            $members[$class["class_name"]] = $db->select(array(
                                                             "table" => "players",
                                                             "where" => array(
                                                                 "roster_id" => $id,
                                                                 "class_id" => $class["id"]
                                                             ),
                                                             "orderBy" => array("player_name"),
                                                             "select" => array("player_id", "player_name")
                                                         ));
        }

        $roster = new Models\Roster($members);

        return $this->view($roster);
    }

} 