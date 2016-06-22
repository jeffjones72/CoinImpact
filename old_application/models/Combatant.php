<?php
class Combatant extends CI_Model {
    public function getSkulls() {
        $pow = floor($this->health + (($this->attack + $this->defense) * 3));
        if ($pow <= 175) {
            return 1;
        }
        if ($pow <= 275) {
            return 2;
        } 
        if ($pow <= 350) {
            return 3;
        } 
        if ($pow <= 450) {
            return 4;
        }
        return 5;
    }    
    public static function generate(Place $place) {
        return Encountarable::generateByType($place, 'combatant');
    }
    public function generatePlaceInstance(PlayerPlace $p_place) {
        $p_combatant = new PlayerCombatant();
        
        $p_combatant->setPlace($p_place);
        $p_combatant->setCombatant($this);
        $p_combatant->setHealth($this->health);
        $p_combatant->setActive(false);
        $p_combatant->setFighting(false);
        
        return $p_combatant;
    }
}
?>