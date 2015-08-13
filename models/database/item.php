<?php

namespace Models\Database;


abstract class Item {

    protected $template;

    protected $is_refinable = false;
    protected $refine_level = 0;

    protected $max_sockets = 0;
    protected $sockets = [];

    protected $addons = [];

    public function __construct(ItemTemplate $template) {
        $this->template = $template;
    }

    public function setRefine($refine) {
        if (!$this->is_refinable)
            throw new \LogicException("Cannot refine an unrefinable file type.");
        $this->refine_level = $refine;
    }

    public function getRefine() {
        return $this->is_refinable ? 0 : $this->refine_level;
    }

    public function setSockets(array $sockets) {
        if (sizeof($sockets) > $this->max_sockets)
            throw new \InvalidArgumentException("Attempted to imbue ".sizeof($sockets)." gems in an item with ".$this->max_sockets." sockets.");
        $this->sockets = $sockets;
    }

    public function getSockets() {
        return $this->sockets;
    }

    public function setAddons(array $addons) {
        $this->template->verifyAddons($addons);
        $this->addons = $addons;
    }

    public function getAddons() {
        return $this->addons;
    }

} 