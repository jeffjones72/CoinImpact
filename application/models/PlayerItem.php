<?php
class PlayerItem extends ItemInstance {
    public function isInInventory() {
        return $this->slot_id == null && !$this->isStored();
    }
    public function isInStorage(){
        return $this->isStored();
    }
    public function isEquipped() {
        return $this->slot_id !== null;
    }
    
    // Update to use storage bit in plyer_items table
    public function isStored() {
        $this->db->select('id');
        $this->db->where('id', $this->id);
        $this->db->where('stored', 1);
        $this->db->from('player_items');
        
        $query = $this->db->get();
        return $query->num_rows() != 0;
    }
    public function tryActivateBosses() {
      //  var_dump($this->item->id);(die);
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
    
    /*
     * CODE: CI:B0203
     * get the player_item by slot_id and by player_id
     */
    public function getBySlotId($player_id, $slot_id){
        $where=array("player_id"=>$player_id, "slot_id"=>$slot_id);
//          $this->db->get("player_items");
//         $this->db->where($where);
        $this->db->_protect_identifiers=false;
        $query = $this->db->get_where("player_items", $where);
       
        $result = $query->row();
        if($result!= null){
            $player_item = new PlayerItem($result->id);
            return $player_item;
        }else{
            return null;
        }
        
        
        
        
    }
}
?>