<?php
class ItemInstance extends CI_Model {
    public function getAttack() {
        if($this->mod_atk === null) {
            return $this->item->attack;
        }
        return $this->item->attack + $this->mod_atk;
    }
    public function getDefense() {
        if($this->mod_def === null) {
            return $this->item->defense;
        }
        return $this->item->defense + $this->mod_def;
    }
    public function getEnergy() {
        return $this->item->energy;
    }
    public function getStamina() {
        return $this->item->stamina;
    }
    public function getHealth() {
        return $this->item->health;
    }
    public function getStrike() {
        return $this->item->strike;
    }
    public function getStrikeBoost() {
        return $this->item->strike_boost;
    }
    public function getDamageBoost() {
        return $this->item->damage_boost;
    }
    public function getLuck() {
        return $this->item->luck;
    }
    public function getDodge() {
        return $this->item->stamina;
    }
    public function getCapacity() {
        return $this->item->capacity;
    }
}
?>