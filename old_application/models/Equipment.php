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
    public function getItemEquippedAt($pos) {
        if(isset($this->i_items[$pos])) {
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