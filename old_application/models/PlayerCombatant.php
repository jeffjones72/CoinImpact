<?php
class PlayerCombatant extends EncountarableInstance {
    private $drop = null;
    public $player_place;
    public $loaded_items = false;
    public $loaded_things = false;
    public function __construct($data = null) {
        parent::__construct($data);
        if($data === null) {
            return;
        }
        $this->player_place = new PlayerPlace($this->place_id);
        $this->player = $this->player_place->player;
    }
    public function setCombatant(Combatant $combatant) {
        $this->combatant = $combatant;
        $this->combatant_id = $combatant->id;
    }
    public function setHealth($health) {
        $this->health = $health;
    }
    public function setActive($active) {
        $this->active = $active;
    }
    public function queryDrop() {
        $this->queryItemDrop();
        $this->queryThingDrop();
    }
    public function queryItemDrop() {
        if($this->loaded_items) {
            return;
        }
        if($this->drop === null) {
            $this->drop = array();
        }
        $this->db->select('*');
        $this->db->where('combatant_id', $this->id);
        $this->db->from('player_combatant_items');
        
        $query = $this->db->get();
        $result = $query->result_array();
        foreach($result as $row) {
            $this->drop[] = new PlayerCombatantItem($row);
        }
        $this->loaded_items = true;
    }
    public function queryThingDrop() {
        if($this->loaded_things) {
            return;
        }
        if($this->drop === null) {
            $this->drop = array();
        }
        $this->db->select('*');
        $this->db->where('combatant_id', $this->id);
        $this->db->from('player_combatant_things');
        
        $query = $this->db->get();
        $result = $query->result_array();
        foreach($result as $row) {
            $this->drop[] = new PlayerCombatantThing($row);
        }
        $this->loaded_things = true;
    }
    public function getDrop() {
        $this->queryDrop();
        return $this->drop;
    }
    public function hit(Player $player) {
        $dmg = round($this->combatant->attack * ((100 - $player->defense) / 100));
        $player->recieveDamage($dmg);
        return $dmg;
    }
    public function recieveDamage($dmg) {
        if($dmg > $this->health) {
            $dmg = $this->health;
        }
        $this->health -= $dmg;
        if($this->isDead()) {
            $loot = $this->generateLoot();
            $this->addLoot($loot);
            $this->player->awardXP($this->combatant->experience_reward);
            $this->player->awardCredit($this->combatant->credit_reward);
            $this->player_place->addProgress($this->combatant->place_progress);
        }
        $this->db->set('health', 'health-'.$this->db->escape($dmg), false);
        $this->db->where('id', $this->id);
        $this->db->update('player_combatants');
    }
    public function generateLoot() {
        $loot = array();
        $objects_count = roll($this->combatant->maximum_objects, $this->combatant->minimum_objects);
        for($i=0;$i<$objects_count;++$i) {
            $type_roll = roll($this->combatant->items_ratio + $this->combatant->things_ratio);
            if($type_roll <= $this->combatant->items_ratio) {
                $item = $this->generateItemDrop();
                //$this->addItemDrop($item);
                $loot[] = $item;
            } else {
                $thing = $this->generateThingDrop();
                //$this->addThingDrop($thing);
                $loot[] = $thing;
            }
        }
        return $loot;
    }
    public function addLoot($loot) {
        for($i=0;$i<sizeof($loot);++$i) {
            if(is_array($loot[$i])) {
                $this->addItemDrop($loot[$i]);
            } else if($loot[$i] instanceof Thing) {
                $this->addThingDrop($loot[$i]);
            }
        }
    }
    public function generateThingDrop() {
        $rarity_id = rarity_roll();
        $this->db->select('*');
//        $this->db->where('id IN (SELECT thing_id FROM combatants_allowed_things '
//                . 'WHERE combatant_id='.$this->db->escape($this->id).')', NULL, false);
        $this->db->where('rarity_id', $rarity_id);
        $this->db->where('from_combatants', true);
        $this->db->order_by('RAND()');
        $this->db->from('things');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if(!$result) {
            return;
        }
        
        $thing = new Thing($result);
        
        return $thing;
    }
    public function generateItemDrop() {
        $rarity_id = rarity_roll();
        $this->db->select('*');
//        $this->db->where('id IN (SELECT item_id FROM combatants_allowed_items '
//                . 'WHERE combatant_id='.$this->db->escape($this->combatant->id).')', NULL, false);
        $this->db->where('rarity_id', $rarity_id);
        $this->db->where('from_combatants', true);
        $this->db->order_by('RAND()');
        $this->db->from('items');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        if(!$result) {
            return;
        }
        
        $item = new Item($result);
        
        $quality = 0;
        if($item->has_quality) {
            $quality = quality_roll();
        }
        $data = array();
        $data['quality'] = $quality;
        $data['item_id'] = $item->id;
        $data['combatant_id'] = $this->id;
        if($item->has_quality) {
            $attack = Item::getAttributeMod($quality, $item->attack);
            $defense = Item::getAttributeMod($quality, $item->defense);
            $data['mod_atk'] = $attack;
            $data['mod_def'] = $defense;            
        }
        
        return $data;
    }
    private function addItemDrop($data) {
        $this->db->set('durability', 100);
        $this->db->set('quality', $data['quality']);
        $this->db->set('item_id', $data['item_id']);
        $this->db->set('combatant_id', $data['combatant_id']);
        if($data['quality']) {
            $this->db->set('mod_atk', $data['mod_atk']);
            $this->db->set('mod_def', $data['mod_def']);
        }
        $this->db->insert('player_combatant_items');
    }
    private function addThingDrop(Thing $thing) {
        $this->db->set('combatant_id', $this->id);
        $this->db->set('thing_id', $thing->id);
        $this->db->insert('player_combatant_things');
    }
    public function isDead() {
        return $this->health == 0;
    }
    public function getAllPlayerCombatantItems() {
        $this->db->select('*');
        $this->db->where('combatant_id', $this->id);
        $this->db->from('player_combatant_items');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $p_c_items = array();
        
        foreach($result as $p_c_i_arr) {
            $p_c_items[] = new PlayerCombatantItem($p_c_i_arr);
        }
        
        return $p_c_items;
    }
    public function getAllPlayerCombatantThings() {
        $this->db->select('*');
        $this->db->where('combatant_id', $this->id);
        $this->db->from('player_combatant_things');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $p_c_things = array();
        
        foreach($result as $p_c_i_arr) {
            $p_c_things[] = new PlayerCombatantThing($p_c_i_arr);
        }
        
        return $p_c_things;
    }
    public function setFighting($fighting = null) {
        if(isset($this->id)) {
            $this->db->set('fighting', 1);
            $this->db->where('id', $this->id);
            $this->db->update('player_combatants');
        }
        if($fighting === null) {
            $fighting = true;
        }
        $this->fighting = $fighting;
    }
    public function getFullHealth() {
        return $this->combatant->health;
    }
    public function isFullHealth() {
        return $this->health == $this->combatant->health;
    }
    public function getHealthPercent() {
        return $this->health*100/$this->combatant->health;
    }
    public function getActions() {
        $this->db->select('*');
        $this->db->where('combatant_id', $this->id);
        $this->db->from('actions');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }
    public function delete() {
        $p_c_items = $this->getAllPlayerCombatantItems();
        $p_c_things = $this->getAllPlayerCombatantThings();
        
        foreach($p_c_items as $p_c_item) {
            $p_c_item->delete();
        }
        foreach($p_c_things as $p_c_thing) {
            $p_c_thing->delete();
        }
        
        $this->db->where('id', $this->id);
        $this->db->delete('player_combatants');
    }
}
?>