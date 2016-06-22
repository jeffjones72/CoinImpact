<?php
class PlayerCombatantThing extends CI_Model {
    
    public function __construct($data = null) {
        parent::__construct($data);
        $this->player_combatant = new PlayerCombatant($this->combatant_id);
    }
    public function getClassification() {
        return 'thing';
    }

    public function getObj() {
        return $this->thing;
    }
}
?>