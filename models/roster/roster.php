<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/8/2015
 * Time: 8:53 PM
 */

namespace models\roster;


class Roster {

    public function __construct($members) {
        $this->members = $members;
    }

    public function getMembersByClass($class) {
        return $this->members[$class];
    }

    public function getMembersByClassId($classId) {
        return getMembersByClass($classId);
    }

} 