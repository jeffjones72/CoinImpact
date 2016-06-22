<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Action_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function travel($player_id, $place_id) {
        $repo = Repo::getInstance();
        $player = newPlayer($player_id);
        $place = $repo->getByID('Place', $place_id);
        if(!$player->canGoTo($place)) {
            return false;
        }
        $this->db->select('pp.id,progress,energy');
        $this->db->from('player_places pp');
        $this->db->join('places p', 'pp.player_id = p.id');
        $this->db->where('pp.player_id', $player_id);
        $this->db->where('pp.place_id', $place_id);
        $query = $this->db->get();
        $energy = $place->energy;

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $progress = $row['progress'];
                $id = $row['id'];
            }
        }

        $date = date("Y-m-d H:i:s", time());

        $data = array(
            'date' => $date,
            'progress' => $progress,
            'energy' => $energy,
            'place_id' => $place_id,
            'player_id' => $player_id,
            'new_place' => 1
        );

        $this->db->insert('actions', $data);

        $this->db->set('active', 0);
        $this->db->where('player_id', $player_id);
        $this->db->update('player_places');

        $this->db->set('active', 1);
        $this->db->where('player_id', $player_id);
        $this->db->where('place_id', $place_id);
        $this->db->update('player_places');

        $this->db->set('location_id', $id);
        $this->db->where('id', $player_id);
        $this->db->update('players');

        $this->db->set('energy', 'energy - ' . $energy, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

    public function explore($player_id, $energy) {
        $this->db->set('energy', 'energy - ' . $energy, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
        echo 'action_model:explore';
        echo $this->db->last_query();
    }

    public function get_combatant_stats($player_combatant_id) {
        //$this->output->enable_profiler(TRUE);
        $this->db->select('pc.health as current_health, attack, defense, dodge, strike, coalesce(minimum_objects,0) as minimum_objects, maximum_objects, items_ratio, things_ratio, experience_reward, coalesce(credit_reward,0) as credit_reward, place_progress', FALSE);
        $this->db->from('player_combatants pc');
        $this->db->join('combatants c', 'pc.combatant_id = c.id');
        $this->db->where('pc.id', $player_combatant_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_combatant($place_id, $rarity_level, $player_id) {
        $this->output->enable_profiler(TRUE);
        $date = date("Y-m-d H:i:s", time());

        $this->db->select('id');
        $this->db->from('player_places');
        $this->db->where('place_id', $place_id);
        $this->db->where('player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $player_place_id = $row['id'];
            }
        }

        //$this->output->enable_profiler(TRUE);
        /*
        $sql = 'select c.id as combatant_id, ' . $place_id . ' as place_id, \'' . $date . '\' as generated, c.health, 1 as active
		from combatants c
			inner join combatant_sections s
			on c.section_id = s.id
		where rarity_id <= ' . $rarity_level . '
			and (exists (select combatantsection_id from places_allowed_combatant_sections where combatantsection_id = s.id)
			or exists (select combatant_id from places_allowed_combatants where combatant_id = c.id))
			and not exists (select combatant_id from places_forbidden_combatants where combatant_id = c.id and place_id = ' . $place_id . ')
			and not exists (select combatantsection_id from places_forbidden_combatant_sections where combatantsection_id = s.id and place_id = ' . $place_id . '	)
		order by RAND()
		LIMIT 1';
        */
        $sql = 'select c.id combatant_id, c.health, 1 active
                from combatants c 
                inner join combatant_sections s
                on c.section_id = s.id
                join places_allowed_combatant_sections pacs
                on c.section_id = pacs.combatantsection_id
                where pacs.place_id  = ' . $place_id . '
                order by rand();';
                
        $query = $this->db->query($sql);
        echo 'action_model:get_combatant';
        echo $this->db->last_query();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $combatant_id = $row['combatant_id'];
                $health = $row['health'];
                $active = $row['active'];
            }

            $data = array(
                'combatant_id' => $combatant_id,
                'place_id' => $player_place_id,
                'generated' => $date,
                'health' => $health,
                'active' => $active
            );

            $this->db->insert('player_combatants', $data);

            $data['inserted_id'] = $this->db->insert_id();

            $data2 = array(
                'date' => $date,
                'player_id' => $player_id,
                'place_id' => $place_id,
                'combatant_id' => $data['inserted_id'],
                'new_combatant' => 1
            );

            $this->db->insert('actions', $data2);
        }
    }

    public function get_item_drop($combatant_id, $rarity_id) {
        $sql = 'select ' . $combatant_id . ' as combatant_id, i.id as item_id, i.has_quality,' . 100 . ' as durability, i.attack, i.defense from items i
				inner join item_sections s
				on i.section_id = s.id
				left outer join combatants_allowed_items cai
				on i.id = cai.item_id
				and cai.combatant_id = ' . $combatant_id . '
				left outer join combatants_allowed_item_sections cais
				on s.id = cais.itemsection_id
				and cais.combatant_id = ' . $combatant_id . '
				where from_combatants = 1
				and  i.rarity_id = ' . $rarity_id . '
				and not exists (select i2.id from items i2
								inner join item_sections s2
								on i2.section_id = s2.id
								inner join combatants_forbidden_item_sections cfis
								on s2.id = cfis.itemsection_id
								where i2.from_combatants = 1 and cais.itemsection_id = cfis.itemsection_id)
				and not exists (select i3.id from items i3
								inner join combatants_forbidden_items cfi
								on i3.id = cfi.item_id
								where i3.from_combatants = 1 and cfi.item_id = i.id)
				order by RAND()
				limit 1';

        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            $data = $query->row_array();
        }

        return $data;
    }

    public function insert_combatant_item($data) {
        $this->db->insert('player_combatant_items', $data);
    }

    public function get_thing_drop($combatant_id, $rarity_id) {
        $sql = 'select ' . $combatant_id . ' as combatant_id, t.id as thing_id from things t
				left outer join combatants_allowed_things cat
				on t.id = cat.thing_id
				and cat.combatant_id = ' . $combatant_id . '
				where from_combatants = 1
				and  t.rarity_id = ' . $rarity_id . '
				and not exists (select t2.id from things t2
								inner join combatants_forbidden_things cft
								on t2.id = cft.thing_id
								where t2.from_combatants = 1 and cft.thing_id = t.id)
				order by RAND()
				limit 1';

        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            $data = $query->row_array();
        }

        return $data;
    }

    public function insert_combatant_thing($data) {
        $this->db->insert('player_combatant_things', $data);
    }

    public function flee($player_combatant_id) {
        //Multiplier for fleeing. Variable in case this needs to be modified.
        $modifier = 5;

        //Get energy to deduct for fleeing.
        $this->db->select('pp.player_id, energy');
        $this->db->from('places p');
        $this->db->join('player_places pp', 'p.id = pp.place_id');
        $this->db->join('player_combatants pc', 'pp.id = pc.place_id');
        $this->db->where('pc.id', $player_combatant_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $tmp = $row;
            }
            $energy = $tmp['energy'];
            $player_id = $tmp['player_id'];
        }

        $this->db->set('active', '0');
        $this->db->where('id', $player_combatant_id);
        $this->db->update('player_combatants');

        //Deduct stamina
        $this->db->set('energy', 'energy-' . ($energy * $modifier), FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

    public function attack_combatant($data) {
        extract($data);
        $this->load->model("players_model");
        $this->db->set('stamina', 'stamina - ' . 1, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');

        $arr = array(
            'health' => $remaining_combatant_health
        );

        $this->db->where('id', $combatant_id);
        $this->db->update('player_combatants', $arr);

        if ($remaining_combatant_health > 0) {
            $arr2 = array(
                'date' => $date,
                'player_id' => $player_id,
                'place_id' => $place_id,
                'combatant_id' => $combatant_id,
                'damage' => $damage,
                'health' => $health
            );
        } else {
            $arr2 = array(
                'date' => $date,
                'player_id' => $player_id,
                'place_id' => $place_id,
                'combatant_id' => $combatant_id,
                'damage' => $damage,
                'health' => $health,
                'progress' => $progress,
                'fatal_hit' => 1,
                'credit' => $credit
            );

            if ($experience + $current_xp >= $next_level_xp)
                $level_id++;

            $experience = $experience + $current_xp;

            $progress = ($progress <= 100 ? $progress : 100);
            $arr3 = array(
                'progress' => $progress
            );

            //Set progress
            $this->db->where('player_id', $player_id);
            $this->db->where('place_id', $place_id);
            $this->db->update('player_places', $arr3);

            $this->db->select('*');
            $this->db->where('player_id', $player_id);
            $this->db->where('place_id', $place_id);
            $this->db->from('player_places');

            $query = $this->db->get();
            $result = $query->row_array();

            $player_place = new PlayerPlace($result);
            $player_place->tryActivateBosses();

            //Repo::getInstance()->finish();
            //Apply rewards and experience
            $this->db->set('balance', 'balance + ' . $credit, FALSE);
            $this->db->set('experience', $experience);
            $this->db->set('level_id', $level_id);
            $this->db->where('id', $player_id);
            $this->db->update('players');
        }

        $this->db->insert('actions', $arr2);
        //$this->players_model->deal_damage_to_player($player_id, $damage);
    }

    public function confirm_combatant($data) {
        extract($data);

        $arr = array(
            'completed' => $completed,
            'active' => $active
        );
        $this->db->where('id', $player_combatant_id);
        $this->db->update('player_combatants', $arr);
    }

    public function collect_combatant_item($player_combatant_item_id) {
        /*$date = date("Y-m-d H:i:s", time());

        $this->db->select('item_id, quality, durability, mod_atk, mod_def');
        $this->db->from('player_combatant_items');
        $this->db->where('id', $player_combatant_item_id);
        $query = $this->db->get();

        if ($query->num_rows == 1)
            $item = $query->row_array();

        extract($item);

        $this->db->set('player_id', $player_id);
        $this->db->set('item_id', $item_id);
        $this->db->set('collected', $date);
        $this->db->set('quality', $quality);
        $this->db->set('durability', $durability);
        $this->db->set('mod_atk', $mod_atk);
        $this->db->set('mod_def', $mod_def);
        $this->db->insert('player_items');*/

        $player = newPlayer();
        $p_c_item = new PlayerCombatantItem($player_combatant_item_id);
        if(!$p_c_item->isValid()) {
            return;
        }
        $player->tryAdd($p_c_item);

        $this->db->where('id', $player_combatant_item_id);
        $this->db->delete('player_combatant_items');
    }

    public function drop_combatant_item($player_combatant_item_id) {
        $this->db->where('id', $player_combatant_item_id);
        $this->db->delete('player_combatant_items');
    }

    public function collect_combatant_thing($player_combatant_thing_id) {
        $player = newPlayer();
        $p_c_thing = new PlayerCombatantThing($player_combatant_thing_id);

        if(!$p_c_thing->isValid()) {
            return;
        }
        $player->tryAdd($p_c_thing);

        $this->db->where('id', $player_combatant_thing_id);
        $this->db->delete('player_combatant_things');
    }

    public function unequip($player_item_id) {
        $this->db->set('slot_id', NULL);
        $this->db->where('id', $player_item_id);
        $this->db->update('player_items');
    }

    public function unequipslot($player_id, $slot_id) {
        $this->db->set('slot_id', NULL);
        $this->db->where('slot_id', $slot_id);
        $this->db->where('player_id', $player_id);
        $this->db->update('player_items');
    }

    public function equip($player_item_id, $slot_id) {
        $this->db->set('slot_id', $slot_id);
        $this->db->where('id', $player_item_id);
        $this->db->update('player_items');
    }

    public function enable_modifier($modifier_id, $player_item_id) {
        $this->db->set('item_id', $player_item_id);
        $this->db->where('id', $modifier_id);
        $this->db->update('player_modifiers');
    }

    public function drop_modifier($modifier_id) {
        $this->db->where('id', $modifier_id);
        $this->db->delete('player_modifiers');
    }

    public function sell_item($player_id, $player_item_id) {
        
        $this->db->select('i.value');
        $this->db->from('player_items as pi');
        $this->db->join('items as i','pi.item_id = i.id');
        $this->db->where('pi.id', $player_item_id);
        $this->db->where('pi.player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            $row = $query->result_array()[0];
            $value = intval($row['value']);
        }
        
        return $value;
    }
    
    public function store_item($player_id, $player_item_id){
        $this->db->set('stored', 1);
        $this->db->where('id', $player_item_id);
        $this->db->where('player_id', $player_id);
        $this->db->update('player_items');
    }
    
    public function retrieve_item($player_id, $player_item_id){
        $this->db->set('stored', 0);
        $this->db->where('id', $player_item_id);
        $this->db->where('player_id', $player_id);
        $this->db->update('player_items');
    }

    public function sell_thing($player_id, $player_thing_id) {
        
        $this->db->select('t.value');
        $this->db->from('player_things as pt');
        $this->db->join('things as t','pt.thing_id = t.id');
        $this->db->where('pt.id', $player_thing_id);
        $this->db->where('pt.player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            $row = $query->result_array()[0];
            $value = intval($row['value']);
        }
        
        return $value;
    }
    
    public function add_balance($player_id, $value)
    {
        $this->db->set('balance', 'balance + ' . $value, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }


    public function drop($player_item_id) {
        $this->db->where('id', $player_item_id);
        $this->db->delete('player_items');
    }


    public function drop_thing($player_thing_id) {
        $this->db->where('id', $player_thing_id);
        $this->db->delete('player_things');
    }



    public function get_trader_details($id){
        $this->db->select('*');
        $this->db->from("player_traders");
        $this->db->where("player_traders.id", $id);
        $this->db->join("traders", "player_traders.trader_id=traders.id", "left");
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row_array();
        }else{
            return null;
        }
    }

    public function get_trader($place_id, $rarity_level, $player_id) {


        $date = date("Y-m-d H:i:s", time());

        $this->db->select('id');
        $this->db->from('player_places');
        $this->db->where('place_id', $place_id);
        $this->db->where('player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $player_place_id = $row['id'];
            }
        }

        $sql = 'select t.id as trader_id, ' . $place_id . ' as place_id, \'' . $date . '\' as generated, 1 as active
				from traders t
				inner join combatant_sections s on t.section_id = s.id
				where rarity_id <= ' . $rarity_level . '
				and (exists (select tradersection_id from places_allowed_trader_sections where tradersection_id = s.id)
					 or exists (select trader_id from places_allowed_traders where trader_id = t.id))
				and not exists (select trader_id from places_forbidden_traders where trader_id = t.id and place_id = ' . $place_id . ')
				and not exists (select tradersection_id from places_forbidden_trader_sections where tradersection_id = s.id and place_id = ' . $place_id . ')
				order by RAND()
				LIMIT 1';
        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $trader_id = $row['trader_id'];
                $active = $row['active'];
            }

            $data = array(
                'trader_id' => $trader_id,
                'place_id' => $player_place_id,
                'generated' => $date,
                'active' => $active
            );

            $this->db->insert('player_traders', $data);

            $data['inserted_id'] = $this->db->insert_id();

            $data2 = array(
                'date' => $date,
                'player_id' => $player_id,
                'place_id' => $place_id,
                'trader_id' => $data['inserted_id'],
                'new_trader' => 1
            );

            $this->db->insert('actions', $data2);
        }
    }

    public function accept_trader($player_trader_id, $player_id) {
//        $this->output->enable_profiler(TRUE);
        $this->db->select('cost');
        $this->db->from('player_traders pt');
        $this->db->join('traders t', 'pt.trader_id = t.id');
        $this->db->where('pt.id', $player_trader_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $trader['cost'] = $row['cost'];
            }
        }

        $this->db->set('balance', 'balance - ' . $trader['cost'], FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
       // var_dump($player_trader_id);die();
        $this->db->set('active', 0);
        $this->db->where('id', $player_trader_id);
        $this->db->update('player_traders');
    }

    public function ignore_trader($player_trader_id) {
        $this->db->set('active', 0);
        $this->db->where('id', $player_trader_id);
        $this->db->update('player_traders');
    }

    public function get_event($place_id, $rarity_level, $player_id) {

        $date = date("Y-m-d H:i:s", time());

        $this->db->select('id');
        $this->db->from('player_places');
        $this->db->where('place_id', $place_id);
        $this->db->where('player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $player_place_id = $row['id'];
            }
        }

        $sql = 'select e.id as event_id, ' . $place_id . ' as place_id, \'' . $date . '\' as generated, e.damage from events e
				inner join event_sections s
				on e.section_id = s.id
				where (exists (select event_id from places_allowed_events pae where pae.event_id = e.id)
				or exists(select eventsection_id from places_allowed_event_sections paes where paes.eventsection_id = e.section_id))
				and not exists(select event_id from places_forbidden_events pfe where pfe.event_id = e.id and pfe.place_id = ' . $place_id . ')
				and not exists(select eventsection_id from places_forbidden_event_sections pfes where pfes.eventsection_id = e.section_id and pfes.place_id = ' . $place_id . ')
				order by RAND()
				LIMIT 1';
        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $event_id = $row['event_id'];
                $damage = $row['damage'];
            }

            $data = array(
                'event_id' => $event_id,
                'place_id' => $player_place_id,
                'generated' => $date
            );

            $this->db->insert('player_events', $data);

            $data['inserted_id'] = $this->db->insert_id();

            $data2 = array(
                'date' => $date,
                'player_id' => $player_id,
                'place_id' => $place_id,
                'event_id' => $data['inserted_id'],
                'health' => $damage
            );

            $this->db->insert('actions', $data2);
        }
    }

    public function confirm_event($player_event_id) {

        $date = date("Y-m-d H:i:s", time());

        $this->db->set('completed', $date);
        $this->db->where('id', $player_event_id);
        $this->db->update('player_events');
    }

    public function intro_stats($player_id, $health) {
        $this->db->set('health', $health, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

}

/* End of file action_model.php */
/* Location: ./application/models/action_model.php */