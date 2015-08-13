<?php

namespace Models\Database;

abstract class ItemType {

	const ALL = "all";
	const WEAPON = "weapon";
	const ARMOR = "armor";
	const ACCESSORY = "accessory";
	
}

class ItemList {

	const MAX_COUNT = 50;

	//Should be odd
	const NUM_SHOWN_PAGES = 7;

	public $itemtype;
	public $page;
	public $count;
	public $totalitems;

	public $list;


	public function __construct($itemtype=ItemType::ALL, $page=1, $count=40) {
		if ($count > self::MAX_COUNT)
			throw new \UnexpectedValueException("Specified item count too high.");
		if ($page < 1)
			throw new \UnexpectedValueException("Page out of range.");

		$this->itemtype = $itemtype;
		$this->page = $page;
		$this->count = $count;

		$this->list = $this->getList();
	}

	private function getList() {
		$db = \ElementDatabase::GetInstance();
		$list = array();

		if ($this->itemtype == ItemType::WEAPON) {

			$this->totalitems = $db->count("weapon_items");
			$this->maxpage = ceil($this->totalitems / $this->count);

			$templist = $db->select(array(
					"table" => "weapon_items",
					"orderBy" => array("require_level"),
					"limit" => $this->count,
					"limitOffset" => $this->count*($this->page-1)
				));

			
			foreach($templist as $dbitem) {
				$list[] = WeaponTemplate::GetByRow($dbitem);
			}

		} else {
			throw new \UnexpectedValueException("Invalid item type specified.");
		}

		return $list;
	}

	public function getPageSpan() {
		if ($this->page <= floor(self::NUM_SHOWN_PAGES/2))
			return [1, min(self::NUM_SHOWN_PAGES, $this->maxpage)];


		if ($this->maxpage-$this->page+1 <= floor(self::NUM_SHOWN_PAGES/2))
			return [max(1, $this->maxpage-self::NUM_SHOWN_PAGES+1), $this->maxpage];

		return [max($this->page-floor(self::NUM_SHOWN_PAGES/2), 1), min($this->page+floor(self::NUM_SHOWN_PAGES/2), $this->maxpage)];
	}

}