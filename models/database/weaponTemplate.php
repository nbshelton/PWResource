<?php

namespace Models\Database;

class WeaponTemplate extends ItemTemplate {

    private static $brief = false;

    private static $_major_type_cache;
    private static $_sub_type_cache;

    public $majorType;
    public $subType;
    public $grade;
    public $ranged;
    public $attack_rate;
    public $attack_range;
    public $attack_range_min;

    public $min_str;
    public $min_dex;
    public $min_mag;
    public $min_vit;
    public $min_level;
    public $min_rep;

    public $damage_low;
    public $damage_high;
    public $magic_damage_low;
    public $magic_damage_high;
    public $refine_amount;

    public $durability;
    public $sell_price;
    public $buy_price;
    public $repair_fee;

    public $socketrates_drop;
    public $socketrates_make;

    public $addons_fixed;
    public $addon_probabilities;
    public $addon_unique_chance;

    public $addons_normal;
    public $addons_random;
    public $addons_unique;

    public static function getByRow($row, $brief = false) {
        self::$brief = $brief;
        $item = parent::getByRow($row);
        self::$brief = false;
        return $item;
    }

    public static function getItem($id) {
        $db = \ElementDatabase::GetInstance();
        return $db->getById("weapon_items", $id);
    }

    private static function GetMajorType($id) {
        if (!isset(self::$_major_type_cache)) {
            $types = \ElementDatabase::GetInstance()->select(array(
                "table" => "weapon_major_type"
            ));

            self::$_major_type_cache = array();
            foreach($types as $type) {
                self::$_major_type_cache[$type["id"]] = $type;
            }
        }
        return self::$_major_type_cache[$id];
    }

    private static function GetSubType($id) {
        if (!isset(self::$_sub_type_cache)) {
            $subtypes = \ElementDatabase::GetInstance()->select(array(
                "table" => "weapon_sub_type"
            ));

            self::$_sub_type_cache = array();
            foreach($subtypes as $subtype) {
                self::$_sub_type_cache[$subtype["id"]] = $subtype;
            }
        }
        return self::$_sub_type_cache[$id];
    }

    public function verifyAddons(array $addons) {
        assert($this->exists());
        assert(!self::$brief);

        if ($this->addons_fixed) {
            $this->verifyFixedAddons($addons);
        } else {
            if (sizeof($addons) > $this->getMaxAddons())
                throw new \InvalidArgumentException("Item has too many addons.");

        }
    }

    private function verifyFixedAddons(array $addons) {

    }

    private function verifyAddon($addon) {

    }

    private function getMaxAddons($index=4) {
        assert($this->exists());
        assert(!self::$brief);

        if ($index > 4) throw new \InvalidArgumentException();
        if ($index == 0) return 0;
        return $this->addon_probabilities[$index] > 0 ? $index : $this->getMaxAddons($index-1);
    }

    protected function build() {
        $this->name = $this->item["name"] ?: "[No Name]";
        $this->majorType = self::GetMajorType($this->item["id_major_type"])["name"];
        $sub = self::GetSubType($this->item["id_sub_type"]);
        $this->subType = $sub["name"];
        $this->attack_rate = round(1/$sub["attack_speed"], 2);
        $this->attack_range_min = $sub["attack_short_range"];

        $this->grade = $this->item["level"];
        $this->ranged = $this->item["require_projectile"] != 0;
        $this->attack_range = $this->item["attack_range"];

        $this->min_str = $this->item["require_strength"];
        $this->min_dex = $this->item["require_dexterity"];
        $this->min_mag = $this->item["require_magic"];
        $this->min_vit = $this->item["require_vitality"];
        $this->min_level = $this->item["require_level"];
        $this->min_rep = $this->item["require_reputation"];

        $this->damage_low = $this->item["damage_low"];
        $this->damage_high = $this->item["damage_high"];
        $this->magic_damage_low = $this->item["magic_damage_low"];
        $this->magic_damage_high = $this->item["magic_damage_high"];
        $this->refine_amount = AddonTemplate::GetRefineBase($this->item["refine_addon"]);

        $this->durability = $this->item["durability"];
        $this->sell_price = $this->item["price"];
        $this->buy_price = $this->item["shop_price"];
        $this->repair_fee = $this->item["repairfee"];

        $this->socketrates_drop = array();
        $this->socketrates_drop[0] = $this->item["drop_probability_socket0"];
        $this->socketrates_drop[1] = $this->item["drop_probability_socket1"];
        $this->socketrates_drop[2] = $this->item["drop_probability_socket2"];
        $this->socketrates_make = array();
        $this->socketrates_make[0] = $this->item["make_probability_socket0"];
        $this->socketrates_make[1] = $this->item["make_probability_socket1"];
        $this->socketrates_make[2] = $this->item["make_probability_socket2"];

        if (!self::$brief) {
            $this->addons_fixed = $this->item["uniques_1_id_unique"] == 0 && $this->item["uniques_1_probability_unique"] == 1;
            $this->addon_probabilities = array();
            $this->addon_probabilities[0] = $this->item["probability_addon_num0"];
            $this->addon_probabilities[1] = $this->item["probability_addon_num1"];
            $this->addon_probabilities[2] = $this->item["probability_addon_num2"];
            $this->addon_probabilities[3] = $this->item["probability_addon_num3"];
            $this->addon_probabilities[4] = $this->item["probability_addon_num4"];
            $this->addon_unique_chance = $this->item["probability_unique"];

            $this->addons_normal = array();
            $this->addons_random = array();
            $this->addons_unique = array();
            if ($this->addon_probabilities[0] != 1) {
                for($i = 1; $i <= 32; $i++) {
                    if ($this->item["addons_".$i."_id_addon"] != 0)
                            $this->addons_normal[] = array(
                                AddonTemplate::Get($this->item["addons_".$i."_id_addon"]),
                                $this->item["addons_".$i."_probability_addon"]
                            );

                    if ($this->item["rands_".$i."_id_rand"] != 0)
                            $this->addons_random[] = array(
                                AddonTemplate::Get($this->item["rands_".$i."_id_rand"]),
                                $this->item["rands_".$i."_probability_rand"]
                            );
                }
                for($i = 1; $i <= 16; $i++) {
                    if ($this->item["uniques_".$i."_id_unique"] != 0)
                            $this->addons_unique[] = array(
                                AddonTemplate::Get($this->item["uniques_".$i."_id_unique"]),
                                $this->item["uniques_".$i."_probability_unique"]
                            );
                }
            }
        }
    }

}
