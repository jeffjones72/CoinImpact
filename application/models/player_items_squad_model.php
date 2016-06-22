<?php
// Ticket #58
?>

<?php

class Player_items_squad_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getItems($player_id, $squad_player_id)
    {
        $items = array();
        $this->db->select('*');
        $where = array(
            'player_id' => $player_id,
            "squad_player_id" => $squad_player_id,
            "slot_id != " => "null"
        );
        $this->db->where($where);
        $this->db->from('player_items_squad');
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }

    public function getById($id)
    {
        return $this->db->get_where("player_items_squad", array(
            "id" => $id
        ))->row();
    }

    public function getItemsDetails($player_id, $squad_player_id)
    {
        $items = array();
        $this->db->select('player_items_squad.*');
        $where = array(
            'player_id' => $player_id,
            "squad_player_id" => $squad_player_id,
            "slot_id != " => "null"
        );
        // $this->db->join("items", "player_items_squad.item_id = items.id", "left");
        $this->db->where($where);
        $this->db->from('player_items_squad');
        
        $query = $this->db->get();
        $results = $query->result();
        
        foreach ($results as $res) {
            $res->item = $this->db->get_where("items", array(
                "id" => $res->item_id
            ))->row();
        }
        
        return $results;
    }

    public function getBySlotId($player_id, $squad_player_id, $slot_id, $is_npc = 0)
    {
        $where = array(
            "player_id" => $player_id,
            "squad_player_id" => $squad_player_id,
            "slot_id" => $slot_id, "is_NPC" => $is_npc
        );
        $query = $this->db->get_where("player_items_squad", $where);
        
        $result = $query->row();
        
        if ($result != null) {
            $item = new Item($result->item_id);
            $result->item = $item;
            return $result;
        } else {
            return null;
        }
    }
    
    // try equip the item for squad_player from player inventory
    public function tryEquip($item_id, $player_id, $squad_player_id, $is_npc)
    {
        // try equip an item which is not already equiped by player
        $player_item = $this->db->get_where("player_items", array(
            // "player_id" => $player_id,
            "id" => $item_id,
            "slot_id" => null
        ))->row();
        // var_dump($this->db->last_query()); die();
        if (! count($player_item)) {
            return false;
        }
        
        $item = new Item($player_item->item_id);
        
        $slot_id = $item->getSlot()->getId();
        // $player = new Player($player_id);
        
        if ($player_id == $squad_player_id) {
            // try equip from Player model
            // $player_item = new PlayerItem($item_id);
            return false;
        }
        if ($item->isWeapon()) {
            
            if ($item->weight == 2) {
                $removeBothHands = true;
            } else {
                $removeBothHands = false;
            }
            
            // check if old item is for both hands
            $leftHandItem = $this->getBySlotId($player_id, $squad_player_id, Item::LEFT_HAND_SLOT_ID, $is_npc);
            
            if (count($leftHandItem) && $leftHandItem->item != null && $leftHandItem->item->weight == 2) {
                $old_slot_id = Item::LEFT_HAND_SLOT_ID;
            } else {
                $old_slot_id = $slot_id;
            }
            
            $old_player_item = $this->getBySlotId($player_id, $squad_player_id, $old_slot_id, $is_npc);
        
            
            if ($old_player_item != null) {
                
                $this->unequip(array(
                    "id" => $old_player_item->id
                ));
                if ($removeBothHands) { // remove the other item too
                    if ($old_slot_id == 9) {
                        $otherSlotId = 10;
                    } else {
                        $otherSlotId = 9;
                    }
                    $otherPlayerItem = $this->getBySlotId($player_id, $squad_player_id, $otherSlotId, $is_npc);
                    if ($otherPlayerItem != null) {
                        $this->unequip(array(
                            "id" => $otherPlayerItem->id
                        ));
                    }
                }
                $where_by_old_slot_id = array(
                    "player_id" => $player_id,
                    "squad_player_id" => $squad_player_id,
                    "slot_id" => $old_slot_id
                );
                $this->unequip($where_by_old_slot_id);
            }
            // get the slot for the current weapon
            
            $slot_id = $this->getEquipSlotForWeapon($player_id, $squad_player_id, $item->id, $is_npc);
            // var_dump($slot_id);
            // die("asd");
        } else {
            $old_player_item = $this->getBySlotId($player_id, $squad_player_id, $slot_id, $is_npc);
            
            if ($old_player_item != null) {
                $where = array(
                    "id" => $old_player_item->id
                );
                $this->unequip($where);
            }
            
            // $where_by_slot_id = array(
            // "player_id" => $player_id,
            // "squad_player_id" => $squad_player_id,
            // "slot_id" => $slot_id
            // );
            // $this->unequip($where_by_slot_id);
        }
        
        // $result_add = $this->addStatsFrom($item_id, $squad_player_id,$player_id, $is_npc,$player_item->id);
        // if($result_add!=false){
        // var_dump($result_add);
        // die("here");
        
        // remove item_id from player inventory
        
        // add item in players_item_squad
        
        // $is_npc = "0";
        $insert_array = array(
            "player_id" => $player_id,
            "squad_player_id" => $squad_player_id,
            "item_id" => $player_item->item_id,
            "slot_id" => $slot_id,
            "quality" => $player_item->quality,
            "durability" => $player_item->durability,
            "mod_atk" => $player_item->mod_atk,
            "mod_def" => $player_item->mod_def,
            "player_item_id" => $player_item->id,
            "is_NPC" =>$is_npc
        );
        
        $this->db->insert('player_items_squad', $insert_array);
        // var_dump($this->db->insert_id());die("ads");
        if ($this->db->insert_id()) {
            
            // add item stats to squad member inventory
            $result_add = $this->addStatsFrom($player_item->item_id, $squad_player_id, $player_id, $is_npc, $player_item->id);
            // var_dump($result_add);die("asd");
            if ($result_add != false) {
                // remove item_id from player inventory, put slot = 0 to inform that item is attached to a squad member
                // update player_items
                $this->update_players_inventory($player_item->id, array(
                    "slot_id" => 0
                ));
            }
            
            return true;
        }
    }

    public function update_players_inventory($id, $set)
    {
        $where_player_items = array(
            "id" => $id
        );
        
        $this->db->update("player_items", $set, $where_player_items);
    }

    public function addStatsFrom($item_id, $squad_player_id, $player_id, $is_npc, $player_item_id)
    {
        $this->db->protect_identifiers = false;
        $player_item = $this->db->get_where("player_items", array(
            "id" => $player_item_id
        ))->row();
        
        if ($player_item == null) {
            return false;
        } else {
            $mod_def = $player_item->mod_def == null ? 0 : $player_item->mod_def;
            $mod_atk = $player_item->mod_atk == null ? 0 : $player_item->mod_atk;
        }
        
        $item = new Item($item_id);
        
        $item_health = $item->health == null ? 0 : $item->health;
        $item_energy = $item->energy == null ? 0 : $item->energy;
        $item_stamina = $item->stamina == null ? 0 : $item->stamina;
        $item_attack = $item->attack == null ? 0 : $item->attack;
        $item_defense = $item->defense == null ? 0 : $item->defense;
        $item_luck = $item->luck == null ? 0 : $item->luck;
        $item_dodge = $item->dodge == null ? 0 : $item->dodge;
        $item_strike = $item->strike == null ? 0 : $item->strike;
        $item_strike_boost = $item->strike_boost == null ? 0 : $item->strike_boost;
        $item_damage_boost = $item->damage_boost == null ? 0 : $item->damage_boost;
        
        $this->db->_protect_identifiers = false;
        
        $where = array(
            "player_id" => $player_id,
            "team_player_id" => $squad_player_id,
            "squad_id" => "1",
            "is_NPC" => $is_npc
        );
        
        $this->db->set("health", "health + $item_health  ", false);
        $this->db->set("energy", "energy + $item_energy  ", false);
        $this->db->set("stamina", "stamina + $item_stamina  ", false);
        $this->db->set("attack", "attack + $item_attack + $mod_atk", false);
        $this->db->set("defense", "defense + $item_defense + $mod_def", false);
        $this->db->set("luck", "luck + $item_luck", false);
        $this->db->set("dodge", "dodge + $item_dodge", false);
        $this->db->set("strike", "strike + $item_strike", false);
        $this->db->set("strike_boost", "strike_boost + $item_strike_boost", false);
        $this->db->set("damage_boost", "damage_boost + $item_damage_boost", false);
        $this->db->where($where);
        $this->db->update("players_team");
        
        // $update_array = array(
        // "health" => (int) " health + $item_health ",
        // "energy" => (int) "energy + $item_energy ",
        // "stamina" => (int) "stamina + $item_stamina ",
        // "attack" => (int) " attack + $item_attack + $mod_atk ",
        // "defense" => (int) " defense + $item_defense + $mod_def ",
        // "luck" => (int) " luck + $item_luck ",
        // "dodge" => (int) " dodge + $item_dodge ",
        // "strike" => (int) " strike + $item_strike ",
        // "strike_boost" => (int) " strike_boost + $item_strike_boost ",
        // "damage_boost" => (int) " damage_boost + $item_damage_boost "
        // );
        // this query is not working
        // $this->db->update("players_team", $update_array, $where);
        
        if ($this->db->affected_rows()) {
            return $player_item->id;
        } else {
            return false;
        }
    }

    public function removeStatsFrom($squad_player_id, $player_id, $is_npc, $player_item_id)
    {
        $this->db->protect_identifiers = false;
        $player_item = $this->db->get_where("player_items", array(
            "id" => $player_item_id
        ))->row();
        
        if ($player_item == null) {
            return false;
        }else {
            $mod_def = $player_item->mod_def == null ? 0 : $player_item->mod_def;
            $mod_atk = $player_item->mod_atk == null ? 0 : $player_item->mod_atk;
        }
 
        $item = new Item($player_item->item_id);
        
        $item_health = $item->health == null ? 0 : $item->health;
        $item_energy = $item->energy == null ? 0 : $item->energy;
        $item_stamina = $item->stamina == null ? 0 : $item->stamina;
        $item_attack = $item->attack == null ? 0 : $item->attack;
        $item_defense = $item->defense == null ? 0 : $item->defense;
        $item_luck = $item->luck == null ? 0 : $item->luck;
        $item_dodge = $item->dodge == null ? 0 : $item->dodge;
        $item_strike = $item->strike == null ? 0 : $item->strike;
        $item_strike_boost = $item->strike_boost == null ? 0 : $item->strike_boost;
        $item_damage_boost = $item->damage_boost == null ? 0 : $item->damage_boost;
 
        $this->db->_protect_identifiers = false;
        
        $where = array(
            "player_id" => $player_id,
            "team_player_id" => $squad_player_id,
            "squad_id" => "1",
            "is_NPC" => $is_npc
        );  
        
        $this->db->set("health", "health - $item_health  ", false);
        $this->db->set("energy", "energy - $item_energy  ", false);
        $this->db->set("stamina", "stamina - $item_stamina  ", false);
        $this->db->set("attack", "attack - $item_attack - $mod_atk", false);
        $this->db->set("defense", "defense - $item_defense - $mod_def", false);
        $this->db->set("luck", "luck - $item_luck", false);
        $this->db->set("dodge", "dodge - $item_dodge", false);
        $this->db->set("strike", "strike - $item_strike", false);
        $this->db->set("strike_boost", "strike_boost - $item_strike_boost", false);
        $this->db->set("damage_boost", "damage_boost - $item_damage_boost", false);
        $this->db->where($where);
        $this->db->update("players_team");
      //  var_dump($this->db->affected_rows());die();
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    private function getEquipSlotForWeapon($player_id, $squad_player_id, $squad_item_id, $is_npc)
    {
        $item = new Item($squad_item_id);
        assert($item->weight == 1 || $item->weight == 2);
        
        if ($item->weight == 2) {
            $where_left_hand = array(
                "player_id" => $player_id,
                "squad_player_id" => $squad_player_id,
                "slot_id" => Item::LEFT_HAND_SLOT_ID, 
                "is_NPC" => $is_npc
            );
            $where_right_hand = array(
                "player_id" => $player_id,
                "squad_player_id" => $squad_player_id,
                "slot_id" => Item::RIGHT_HAND_SLOT_ID,
                "is_NPC" => $is_npc
            );
            $this->unequip($where_left_hand);
            $this->unequip($where_right_hand);
            return ITEM::LEFT_HAND_SLOT_ID;
        }
        
        $left_hand_equipped = $this->getBySlotId($player_id, $squad_player_id, item::LEFT_HAND_SLOT_ID, $is_npc);
        $right_hand_equipped = $this->getBySlotId($player_id, $squad_player_id, item::RIGHT_HAND_SLOT_ID, $is_npc);
        
        if (count($left_hand_equipped) && count($right_hand_equipped)) {}
        if (! count($left_hand_equipped)) {
            return ITEM::LEFT_HAND_SLOT_ID;
        }
        assert($item->weight == 1);
        if (! count($right_hand_equipped)) {
            return ITEM::RIGHT_HAND_SLOT_ID;
        }
        $where_left_hand = array(
            "player_id" => $player_id,
            "squad_player_id" => $squad_player_id,
            "slot_id" => Item::LEFT_HAND_SLOT_ID, 
            "is_NPC" => $is_npc
        );
        $this->unequip($where_left_hand);
        return ITEM::LEFT_HAND_SLOT_ID;
    }

    public function unequip($where)
    {
        $items = $this->db->get_where("player_items_squad", $where)->result();
        foreach ($items as $item) {
            $player_where = array(
                "id" => $item->player_item_id
            );
            $data = array(
                "slot_id" => null
            );
            $this->db->update("player_items", $data, $player_where);
            $result_remove = $this->removeStatsFrom($item->squad_player_id, $item->player_id, $item->is_NPC, $item->player_item_id);
          if( !$result_remove  ){
              return false;
          }else{
               
          }
        }
        $this->db->where($where);
        $this->db->delete('player_items_squad');
        return true;
    }

    public static function getSlotTypes()
    {
        return EquipmentSlot::getAll();
    }
}