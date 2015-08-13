<?php

namespace Models\Database;

abstract class ItemTemplate {
    use ItemTrait;
}

trait ItemTrait {
    
    protected static $_cache = array();
    private static $_color_cache;
    
    protected $item;
    
    public $id;
    public $name;
    public $color;

    protected abstract function verifyAddons(array $addons);

    protected abstract static function getItem($id);

    protected abstract function build();
    
    protected function __construct() {}
    
    public static function getById($id) {
        return isset(self::$_cache[$id]) ? self::$_cache[$id] : self::getByRow(static::getItem($id));
    }
    
    public static function getByRow($row) {
        $id = $row["id"];
        if (isset(self::$_cache[$id])) {
            return self::$_cache[$id];
        } else {
            $item = new static();
            $item->item = $row;
            $item->id = $id;
            $item->build();
            $item->setColor();
            self::$_cache[$id] = $item;
            return $item;
        }
    }
    
    protected function setColor() {
        if (!isset(self::$_color_cache)) {
            $colors = \ElementDatabase::GetInstance()->select(array(
                "table" => "item_color"
            ));
            
            self::$_color_cache = array();
            foreach($colors as $color) {
                self::$_color_cache[$color["item_id"]] = $color["color_id"];
            }
        }
        
        if (isset(self::$_color_cache[$this->id])) {
            $this->color = self::$_color_cache[$this->id];
        }
        if (isset($this->item["fixed_props"])) {
            switch($this->item["fixed_props"]) {
                case 3:
                    $this->color = 4;
                    break;
                case 2:
                    $this->color = $this->color ?: 2;
                    break;
                case 1:
                    $this->color = $this->color ?: 1;
            }
        }
        $this->color = $this->color ?: 0;
    }
    
    public function exists() {
        return isset($this->item);
    }
    
}