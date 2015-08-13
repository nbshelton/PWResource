<?php

namespace models\database;


class Addon {

    private $template;
    private $value;

    public function __construct(AddonTemplate $template) {
        $this->template = $template;
        if ($template->var_count > 0)
            $this->value = $template->params[0];
    }

    public function is(AddonTemplate $template) {
        return $this->template->id == $template->id;
    }

    public function validate() {
        if ($this->template->var_count == 0) return;
        if ($this->template->var_count == 1 && $this->value == $this->template->params[0]) return;
        if ($this->template->var_count > 1) {
            if ($this->template->discrete && ($this->value == $this->template->params[0] || $this->value == $this->template->params[1])) return;
            if (!$this->template->discrete && $this->value >= $this->template->params[0] && $this->value <= $this->template->params[2]) return;
        }

        throw new \UnexpectedValueException("Addon value out of range.");
    }

} 