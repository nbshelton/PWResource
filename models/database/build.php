<?php

namespace models\database;

use Models\StatSet;

class Build {

    private static function getHistoricalPoints($level) {
        if ($level == 0) return;
        if ($level < 100) throw new \UnexpectedValueException("Historical level cannot be less than 100.");
        switch($level) {
            case 100:
                return 20;
            case 101:
                return 25;
            case 102:
                return 32;
            case 103:
                return 42;
            case 104:
                return 56;
            case 105:
                return 76;
            default:
                throw new \UnexpectedValueException("Historical level cannot be greater than 105.");
        }
    }

    private static $meridian_multiplier = [0,
        0.005, 0.01, 0.015, 0.02, 0.025, 0.03, 0.035, 0.04, 0.045, 0.05,
        0.0558, 0.0608, 0.0667, 0.0717, 0.0775, 0.0825, 0.0883, 0.0933, 0.1, 0.11,
        0.115, 0.12, 0.125, 0.13, 0.1358, 0.1408, 0.1467, 0.1517, 0.1575, 0.1625,
        0.1683, 0.175, 0.1817, 0.1875, 0.195, 0.205, 0.215, 0.225, 0.235, 0.2533,
        0.2583, 0.265, 0.2708, 0.2767, 0.2867, 0.2967, 0.3067, 0.3175, 0.3283, 0.3383,
        0.35, 0.3625, 0.3758, 0.3883, 0.4017, 0.4167, 0.4317, 0.4467, 0.4617, 0.4933,
        0.5033, 0.5133, 0.5233, 0.5333, 0.545, 0.5575, 0.5708, 0.5858, 0.6008, 0.62,
        0.6383, 0.6633, 0.6883, 0.7217, 0.7550, 0.7875, 0.8275, 0.8675, 0.9117, 1
    ];

    private $class;
    private $gender;

    private $historical1;
    private $historical2;
    private $level;
    private $str;
    private $dex;
    private $vit;
    private $mag;

    private $meridian;
    private $cards;
    private $nuema;
    private $titles;
    private $gearset;

    private $buffset;

    public function __construct() {
        $this->class = "Blademaster";
        $this->gender = "Male";

        $this->historical1 = 0;
        $this->historical2 = 0;
        $this->level = 1;
        $this->str = 5;
        $this->dex = 5;
        $this->vit = 5;
        $this->mag = 5;

        $this->meridian = 0;
        $this->cards = null;
        $this->nuema = null;
        $this->titles = null;
        $this->gearset = null;

        $this->buffset = null;
    }

    public function getTotalStatPoints() {
        return 15 + ($this->level * 5) + self::getHistoricalPoints($this->historical1) + self::getHistoricalPoints($this->historical2);
    }

    public function getMeridianStats() {
        $max_meridian = new StatSet();
        switch($this->class) {
            case "Blademaster":
                $max_meridian->hp = 1400;
                $max_meridian->patk = 280;
                $max_meridian->pdef = 500;
                $max_meridian->mdef = 400;
                break;
            case "Wizard":
                $max_meridian->hp = 1000;
                $max_meridian->patk = 280;
                $max_meridian->matk = 280;
                $max_meridian->pdef = 250;
                $max_meridian->mdef = 600;
                break;
            case "Barbarian":
                $max_meridian->hp = 1400;
                $max_meridian->patk = 320;
                $max_meridian->pdef = 600;
                $max_meridian->mdef = 400;
                break;
            case "Venomancer":
            case "Cleric":
            case "Psychic":
                $max_meridian->hp = 1000;
                $max_meridian->patk = 280;
                $max_meridian->matk = 280;
                $max_meridian->pdef = 300;
                $max_meridian->mdef = 550;
                break;
            case "Archer":
                $max_meridian->hp = 1200;
                $max_meridian->patk = 280;
                $max_meridian->pdef = 350;
                $max_meridian->mdef = 500;
                break;
            case "Assassin":
                $max_meridian->hp = 1200;
                $max_meridian->patk = 210;
                $max_meridian->pdef = 350;
                $max_meridian->mdef = 500;
                break;
            case "Seeker":
                $max_meridian->hp = 1400;
                $max_meridian->patk = 300;
                $max_meridian->pdef = 500;
                $max_meridian->mdef = 400;
        }
        return $max_meridian->multiplyBy(self::$meridian_multiplier[$this->meridian]);
    }

} 