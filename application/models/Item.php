<?php
class Item extends CI_Model {
    public static $stat_initials = array('ATK',   'DEF',     'EN',      'STA',    'HP',     'CS',    'Luck', 'Dodge', 'Capacity');
    public static $stat_fields = array('attack', 'defense', 'energy', 'stamina', 'health', 'strike', 'luck', 'dodge', 'capacity');
    const LEFT_HAND_SLOT_ID = 9;
    const RIGHT_HAND_SLOT_ID = 10;
    
    public function getClassification() {
        return 'item';
    }
    public function isWeapon() {
        $slot = $this->getSlot();
        if($slot->getId() == 9 || $slot->getId() == 10) { 
            return true;
        }
        return false;
    }
    public function getSlot() {
        $this->db->select('slot_id');
        $this->db->where('itemsection_id', $this->section_id);
        $this->db->from('slots_sections');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $slot = new EquipmentSlot($result['slot_id']);
        
        return $slot;
    }
    public function getSlotName() {
        $this->db->select('slot_id');
        $this->db->where('itemsection_id', $this->section_id);
        $this->db->from('slots_sections');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if(!$result) {
            throw new Exception('This should not happen.');
        }
        
        $this->db->select('name');
        $this->db->where('id', $result['slot_id']);
        $this->db->from('slots');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['name'];
    }
    
    public static function getAttributeMod($quality, $attribute) {
        switch ($quality) {
            case 1:
                $rand = roll(50, 26) / 100.0;
                $mod = ceil(($attribute * $rand) * -1);
                break;
            case 2:
                $rand = roll(25, 10) / 100.0;
                $mod = ceil(($attribute * $rand) * -1);
                break;
            case 3:
                $rand = roll(25, 10) / 100.0;
                $rand2 = roll(2, 1);
                if ($rand2 == 1) {
                    $mod = ceil(($attribute * $rand) * -1);
                } else {
                    $mod = ceil($attribute * $rand);
                }
                break;
            case 4:
                $rand = roll(25, 10) / 100.0;
                $mod = ceil($attribute * $rand);
                break;
            case 5:
                $rand = roll(50, 26) / 100.0;
                $mod = ceil($attribute * $rand);
                break;
        }
        return $mod;
    }
}
?>