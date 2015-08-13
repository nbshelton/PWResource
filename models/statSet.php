<?php

namespace models;


class StatSet {

    public $hp = 0;
    public $hp_percent = 0;
    public $hp_recovery = 0;
    public $mp = 0;
    public $mp_percent = 0;
    public $mp_recovery = 0;

    public $str = 0;
    public $dex = 0;
    public $vit = 0;
    public $mag = 0;

    public $patk = 0;
    public $patk_max = 0;
    public $matk = 0;
    public $matk_max = 0;

    public $wood_atk = 0;
    public $fire_atk = 0;
    public $earth_atk = 0;
    public $metal_atk = 0;
    public $water_atk = 0;

    public $pdef = 0;
    public $pdef_percent = 0;
    public $mdef = 0;
    public $mdef_percent = 0;

    public $phys_reduction = 0;
    public $mag_reduction = 0;

    public $wood_def = 0;
    public $wood_def_percent = 0;
    public $fire_def = 0;
    public $fire_def_percent = 0;
    public $earth_def = 0;
    public $earth_def_percent = 0;
    public $metal_def = 0;
    public $metal_def_percent = 0;
    public $water_def = 0;
    public $water_def_percent = 0;

    public $evasion = 0;
    public $evasion_percent = 0;
    public $accuracy = 0;
    public $accuracy_percent = 0;

    public $interval = 0;
    public $channeling = 0;

    public $crit = 0;
    public $rage_damage = 0;

    public $atk_level = 0;
    public $def_level = 0;
    public $spirit = 0;

    public $slaying_level = 0;
    public $warding_level = 0;

    public $soulforce = 0;

    public $speed = 0;

    public $range = 0;

    public $exp = 0;

    public function addTo(StatSet $other_set) {
        $new_set = clone $this;

        foreach($new_set as $stat => $value) {
            $new_set->$stat += $other_set->$stat;
        }

        return $new_set;
    }

    public function multiplyBy($multiplier) {
        $new_set = clone $this;

        foreach($new_set as $stat => $value) {
            $new_set->$stat *= $multiplier;
        }

        return $new_set;
    }

} 