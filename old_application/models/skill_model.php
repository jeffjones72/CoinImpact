<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Skill_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_items($player_id = 0) {
        $this->db->select('i.*, i.id as item_id, i.name as item_name, i.description as item_description, r.name as rarity_name');
        $this->db->from('items i');
        $this->db->join('rarities r', 'i.rarity_id = r.id');
        if ($player_id != 0) {
            $this->db->join('player_items pi', 'pi.item_id = i.id');
            $this->db->where('pi.player_id', $player_id);
        }
        $this->db->order_by('r.id', 'desc');
        $this->db->order_by('i.name', 'asc');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }

            $this->db->select('sum(coalesce(mod_atk,0)) + sum(coalesce(attack,0)) as attack_delta, 
							   sum(coalesce(mod_def,0)) + sum(coalesce(defense,0)) as defense_delta, 
							   sum(coalesce(stamina,0)) as stamina_delta, sum(coalesce(energy,0)) as energy_delta, 
							   sum(coalesce(health,0)) as health_delta, sum(coalesce(strike,0)) as strike_delta, 
							   sum(coalesce(strike_boost,0)) as strike_boost_delta, sum(coalesce(damage_boost,0)) as damage_boost_delta, 
							   sum(coalesce(dodge,0)) as dodge_delta, sum(coalesce(luck,0)) as luck_delta', FALSE);
            $this->db->from('player_items pi');
            $this->db->join('items i', 'pi.item_id = i.id');
            $this->db->where('pi.player_id', $player_id);
            $this->db->where('pi.slot_id is not null');
            $query = $this->db->get();

            if ($query->num_rows > 0) {
                foreach ($query->result_array() as $row) {
                    $item_deltas[] = $row;
                }
                $data['item_deltas'] = $item_deltas;
            }

            $this->db->select('pi.quality, pi.durability, pi.id as player_item_id, i.id, i.name, i.description, i.price, i.premium_price, i.value, i.section_id,i.has_quality, i.weight, coalesce(pi.mod_atk,0) + i.attack as attack, coalesce(pi.mod_def,0) + i.defense as defense, i.energy, i.stamina, i.health, i.strike, i.strike_boost, i.damage_boost, i.luck, i.dodge, i.capacity, i.modifiers_limit', FALSE);
            $this->db->from('player_items pi');
            $this->db->join('items i', 'pi.item_id = i.id');
            $this->db->where('pi.slot_id is null');
            $this->db->where('pi.player_id', $player_id);
            $query = $this->db->get();

            if ($query->num_rows > 0) {
                foreach ($query->result_array() as $row) {
                    $unequipped[] = $row;
                }
                $data['unequipped'] = $unequipped;
            }

            $this->db->select('pi.id as player_item_id, pi.slot_id, s.name as slot_name, i.*, coalesce(i.attack,0) + coalesce(pi.mod_atk,0) as attack, coalesce(i.defense,0) + coalesce(pi.mod_def,0) as defense, pi.quality, pi.durability', FALSE);
            $this->db->from('player_items pi');
            $this->db->join('items i', 'pi.item_id = i.id and pi.player_id = ' . $player_id);
            $this->db->join('slots s', 'pi.slot_id = s.id', 'right outer');
            $this->db->order_by('s.id', 'asc');
            $query = $this->db->get();

            if ($query->num_rows > 0) {
                foreach ($query->result_array() as $row) {
                    $item_slots[] = $row;
                }
                $data['item_slots'] = $item_slots;
            }

            return $data;
        }
    }

    public function get_slot($player_item_id) {
        $this->db->select('ss.slot_id,s.weight_limit,i.weight');
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id = i.id');
        $this->db->join('slots_sections ss', 'i.section_id = ss.itemsection_id');
        $this->db->join('slots s', 'ss.slot_id = s.id');
        $this->db->where('pi.id', $player_item_id);
        $this->db->order_by('s.id');
        $this->db->limit(1);
        $query = $this->db->get();

        $tmp = $query->result_array();

        return $tmp[0];
    }

    public function check_weapon($player_id) {
        $this->db->select('s.id, s.weight_limit, pi.slot_id, i.weight');
        $this->db->from('slots s');
        $this->db->join('player_items pi', 's.id = pi.slot_id and pi.player_id = ' . $player_id, 'left outer');
        $this->db->join('items i', 'pi.item_id = i.id', 'left outer');
        $this->db->where('s.weight_limit >', 0);
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_item_count() {
        $data = $this->db->count_all('items');

        return $data;
    }

    function collect_boost($player_id, $item_id) {
        // Build data array to insert in to the player_items table.
        $data = array(
            'player_id' => $player_id,
            'item_id' => $item_id,
            'collected' => now()
        );

        /* 	TODO: Add this and two arguments to receive combatant_id and is_staff for 
         * 	modifiers and security.

          $this->db->select('has_quality');
          $query = $this->db->get_where('modifiers',$modifier_id, 1);

          foreach ($query->result_array() as $row
          {
          $data['quality'] = $row['has_quality'];
          }

          $this->db->insert('player_itms', $data) or die(mysql_error());

          return true;
         */
    }

}

/* End of file skill_model.php */
/* Location: ./application/models/skill_model.php */