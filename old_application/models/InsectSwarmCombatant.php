<?php
class InsectSwarmCombatant extends PlayerBossCombatant {
    public function generateLoot() {
        $this->db->select('item_id');
        $this->db->where('dmg_required >= '.$this->db->escape($this->damage), null, false);
        $this->db->from('insect_swarm_boss');
        
        $query = $this->db->get();
        $item_ids = $query->result_array();
        foreach($item_ids as $item_id) {
            $this->player->add(new Item($item_id['item_id']));
        }
    }
}
?>