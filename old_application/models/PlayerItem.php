<?php
class PlayerItem extends ItemInstance {
    public function isInInventory() {
        return $this->slot_id == null && !$this->isStored();
    }
    public function isEquipped() {
        return $this->slot_id !== null;
    }
    public function isStored() {
        $this->db->select('id');
        $this->db->where('player_item_id', $this->id);
        $this->db->from('storage');
        
        $query = $this->db->get();
        return $query->num_rows() != 0;
    }
    public function tryActivateBosses() {
        $this->db->select('boss_id');
        $this->db->where('item_id', $this->item->id);
        $this->db->from('bosses_required_items');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach($result_arr as $result) {
            $this->player->tryActivate(new Boss($result['boss_id']));
        }
    }
    public function tryAddMissions() {
        $this->db->select('mission_id');
        $this->db->where('item_id', $this->item->id);
        $this->db->from('missions_required_items');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach($result_arr as $result) {
            $this->player->tryAdd(new Mission($result['mission_id']));
        }
    }
}
?>