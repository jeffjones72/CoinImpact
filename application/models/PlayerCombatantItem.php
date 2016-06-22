<?php
class PlayerCombatantItem extends CI_Model {
    public function __construct($data = null) {
        parent::__construct($data);
        if(!$this->isValid()) {
            return;
        }
        $this->player_combatant = new PlayerCombatant($this->combatant_id);
    }
    public function getClassification() {
        return 'item';
    }
    public function getObj() {
        return $this->item;
    }
}
?>