<?php
class PlayerModifier extends CI_Model {
    public function getItems() {
        $p_items = array();
        
        $query = $this->db->query('SELECT * FROM player_items WHERE item_id IN('
                . 'SELECT id FROM items WHERE section_id IN('
                . 'SELECT itemsection_id FROM modifiers_sections_whitelist WHERE modifier_id="'.$this->modifier_id.'") AND player_id="'.$this->player_id.'")');
        $result = $query->result_array();
        
        foreach($result as $p_item_arr) {
            $p_items[] = new PlayerItem($p_item_arr);
        }
        
        return $p_items;
    }
}
?>