<?php

namespace Models\Database;

class AddonTemplate {
    
    private static $_addons = array();
    private static $_groups;
    
    private static function GetAddonGroup($id) {
        if (!isset(self::$_groups)) {
            $groups = \ElementDatabase::GetInstance()->select(array(
                "table" => "addon_groups"
            ));
            
            self::$_groups = array();
            foreach ($groups as $groupinfo) {
                self::$_groups[$groupinfo["addon_id"]] = $groupinfo["group_id"];
            }
        }
        
        return self::$_groups[$id];
    }
    
    public static function Get($id) {
        if (!isset(self::$_addons[$id]))
            self::$_addons[$id] = new self($id);
            
        return self::$_addons[$id];
    }
    
    public static function GetRefineBase($id) {
        return self::Get($id)->params[0];
    }
    
    public $id;
    public $group_id;
    public $name;
    public $params;
    public $text;
    public $var_count;
    public $discrete;
    
    private function __construct($id) {
        $this->id = $id;
        $this->params = array();
        $this->discrete = false;
        $db = \ElementDatabase::GetInstance();
                
        if (!$db->anyById('equipment_addon', $id)) {
            $this->name = "Addon $id not found.";
        } else {
            $addon = $db->getById('equipment_addon', $id);
            $this->name = $addon["name"];
            for($i=1; $i<= $addon["num_params"]; $i++) {
                $this->params[] = $addon["param".$i];
            }
        }
        
        $this->group_id = self::GetAddonGroup($this->id);
        
        switch($this->group_id) {
            
            case 0:
            case 100:
            case 135:
            case 200:
                $this->text = "Physical Attack +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
            case 1:
            case 87:
            case 101:
                $this->text = "Maximum Physical Attack +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
             
                
            case 3:
            case 102:
            case 136:
                $this->text = "Magic Attack +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 4:
            case 88:
            case 103:
                $this->text = "Maximum Magic Attack +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
                
                
            case 9:
                $this->text = "Interval Between Hits -%.2f seconds";
                $this->discrete = true;
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 10:
            case 81:
                $this->text = "Range +%.2f";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 11:
            case 80:
            case 113:
                $this->text = "Channelling -%d%%";
                $this->discrete = true;
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 12:
            case 76:
            case 104:
            case 133:
                $this->text = "Phys. Res.: +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 13:
            case 83:
                $this->text = "Physical Defense +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 14:
            case 77:
            case 134:
                $this->text = "Mag. Res.: +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 15:
            case 120:
                $this->text = "Metal Resistance %d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
            case 16:
            case 64:
            case 137:
                $this->text = "Metal Damage Reduced by +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 17:
            case 121:
                $this->text = "Wood Resistance %d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
            case 18:
            case 65:
            case 138:
                $this->text = "Wood Damage Reduced by +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 19:
            case 122:
                $this->text = "Water Resistance %d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
            case 20:
            case 66:
            case 139:
                $this->text = "Water Damage Reduced by +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 21:
            case 123:
                $this->text = "Fire Resistance %d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
            case 22:
            case 67:
            case 140:
                $this->text = "Fire Damage Reduced by +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 23:
            case 124:
                $this->text = "Earth Resistance %d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
            case 24:
            case 68:
            case 141:
                $this->text = "Earth Damage Reduced by +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
                
                
            case 35:
            case 73:
            case 105:
            case 132:
                $this->text = "HP: +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 36:
            case 74:
            case 127:
                $this->text = "MP +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 37:
                $this->text = "Maximum HP +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
            
                
                
            case 39:
                $this->params[0] /= 2;
                if (isset($this->params[1])) $this->params[1] /= 2;
            case 85:
                $this->text = "HP Recovery +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 40:
                $this->params[0] /= 2;
                if (isset($this->params[1])) $this->params[1] /= 2;
            case 82:
                $this->text = "MP Recovery +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 41:
            case 95:
            case 106:
            case 129:
                $this->text = "Strength +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 42:
            case 96:
            case 107:
            case 130:
                $this->text = "Dexterity +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 43:
            case 97:
            case 108:
            case 131:
                $this->text = "Magic +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 44:
            case 98:
            case 128:
                $this->text = "Vitality +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 45:
            case 72:
            case 110:
            case 143:
            case 150:
            case 152:
            case 154:
                $this->text = "Critical Hit Rate +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 46:
            case 109:
            case 125:
                $this->text = "Accuracy +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 47:
            case 75:
                $this->text = "Accuracy +%d%%";
                $this->discrete = true;
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 48:
                $this->text = "Speed +%.2f meters/second";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
                
                
            case 50:
            case 86:
            case 126:
                $this->text = "Evasion +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
                
                
            case 53:
                $this->text = "Maximum Endurance +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 54:
            case 78:
            case 146:
                $this->text = "Reduce Physical damage taken +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 55:
                $this->text = sprintf(\ElementDatabase::GetInstance()->getById("skill_strings", $this->params[0])["description"]);
                $this->var_count = 0;
                break;
            
            case 56:
                $this->text = "Requirement +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 57:
                $this->text = "Unidentified";
                $this->var_count = 0;
                break;
                
            case 58:
                $this->text = "EXP +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 59:
            case 70:
            case 111:
            case 144:
                $this->text = "Atk. Level +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 60:
            case 71:
            case 112:
            case 145:
                $this->text = "Def. Level +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 61:
            case 84:
                $this->text = "Elemental Resistance +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 62:
                $this->text = "Eye of the Jungle: Nothing escapes this eye.";
                $this->var_count = 0;
                break;
                
            case 63:
            case 156:
                $this->text = "Soulforce +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
                
                
            case 69:
            case 79:
            case 142:
                $this->text = "Reduce Magic damage taken +%d%%";
                $f = $this->extractFloat(0,1);
                $this->params[0] = $f*100;
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $f2 = $this->extractFloat(1,1);
                    $this->params[1] = $f2*100;
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
                
                
            case 90:
                $this->text = "Slaying Level: +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 91:
                $this->text = "Warding Level: +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
                
                
            case 93:
                $this->text = "Maximum HP +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            case 94:
            case 99:
                $this->text = "Maximum MP +%d";
                if (sizeof($this->params) > 1 && $this->params[1] > $this->params[0]) {
                    $this->var_count = 2;
                } else {
                    $this->var_count = 1;
                }
                break;
                
            default:
                $this->text = sprintf("[%d] Unknown", $this->id);
                $this->var_count = 0;
        }
    }

    private function extractFloat($i1, $i2) {
        return round(unpack("f", pack("i", $this->params[$i1]))[$i2], 2);
    }

}