<?php
class Equipment extends CI_Model {
    // "i_items" means kind of "instance of items"
    private $i_items = array();
    public function __construct($i_items = array()) {
        foreach($i_items as $i_item) {
            if(!$i_item->isEquipped()) {
                continue;
            }
            $type = $i_item->item->getSlot()->getType();
            if($type == 'weapon' && isset($this->i_items['weapon'])) {
                $this->i_items['weapon2'] = $i_item;
                continue;
            }
            $this->i_items[$type] = $i_item;
        }
    }
    
    /**
     * Ticket #58
     * changed this function to display only items with slot!=0; 0 is for squad members
     */
    public function getItemEquippedAt($pos) {
       
        //slot_id = 0 when that item is eqquiped to a squad member
        if(isset($this->i_items[$pos]) && $this->i_items[$pos]->slot_id != 0) {
         //   var_dump($this->i_items[$pos]->slot_id);die();
            return $this->i_items[$pos];
        }
        return null;
    }
    public static function getSlotTypes() {
        return EquipmentSlot::getAll();
    }
    public function getItems() {
        return $this->i_items;
    }
}
?>