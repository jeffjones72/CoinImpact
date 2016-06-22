<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Players_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    function deal_damage_to_player($player_id, $damage) {
        $this->set_health($player_id, $this->get_player_health($player_id)-$damage);
    }
    function set_health($player_id, $health) {
        $data = array("health" => $health);
        $this->db->where("id", $player_id);
        $this->db->update('players', $data);
    }
    function get_player_health($player_id) {
        $this->db->select("health");
        $this->db->where("id", $player_id);
        $this->db->from("players");
    }
    function get_player_by_id($player_id) {
        $this->db->select("*");
        $this->db->where("id", $player_id);
        $this->db->from("players");
        //...
    }
    function get_player() {
        return $this->get_player_by_account_id($this->session->userdata('id'));
    }
    function get_player_by_account_id($account_id) {
        $this->db->select("*");
        $this->db->where("account_id", $account_id);
        $this->db->from("players");

        $query = $this->db->get();
        return $query->row_array();
    }
    function get_player_id($account_id) {
        $this->db->select('id');
        $query = $this->db->get('players');

        $data = $query_result_array();

        return $data['id'];
    }

    function get_player_info($account_id) {
        $sql = 'select p.*,p.id as player_id, r.label as rank_label, pl.energy as place_energy, pp.progress, pl.id as place_id, pl.name as place_name, pl.description as place_description, UNIX_TIMESTAMP(now()) as player_now,
		UNIX_TIMESTAMP(p.health_refill) as health_refill, UNIX_TIMESTAMP(p.energy_refill) as  energy_refill, UNIX_TIMESTAMP(p.stamina_refill) as stamina_refill,cl.experience as current_level_xp,nl.experience as next_level_xp
		from players p
		inner join ranks r on p.rank_id = r.id
		inner join player_places pp on p.id = pp.player_id and pp.active = 1
		inner join places pl on pp.place_id = pl.id
		inner join levels cl on p.level_id = cl.id
		inner join levels nl on p.level_id + 1 = nl.id
		where p.account_id = ' . $account_id;
        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            $data = $query->row_array();
        }
        $data['server_time'] = time();
        $player_now = $data['player_now'];

        //$this->output->enable_profiler(TRUE);
        $this->db->select('pp.place_id, pp.progress, p.name as place_name, p.description as place_description, p.energy');
        $this->db->from('player_places pp');
        $this->db->join('places p', 'pp.place_id = p.id');
        $this->db->where('pp.active', '1');
        $this->db->where('player_id', $data['player_id']);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            $row = $query->row_array();
            $data['place_id'] = $row['place_id'];
            $data['progress'] = $row['progress'];
            $data['place_name'] = $row['place_name'];
            $data['place_description'] = $row['place_description'];
            $data['energy'] = $row['energy'];
        }

        $data['inventory_capacity'] = $this->get_player_inventory_capacity($data['player_id']);
        $data['inventory_count'] = $this->get_player_inventory_count($data['player_id']);

        return $data;
    }

    function get_friends($player_id) {
        //$this->output->enable_profiler(TRUE);
        $this->db->select('f.added, p.*');
        $this->db->from('player_friends f');
        $this->db->join('players p', 'f.friend_id = p.id');
        $this->db->where('f.player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    private function get_player_inventory_capacity($player_id) {

        $data1['inventory_capacity'] = 10;

        $this->db->select_sum('i.capacity', 'capacity');
        $this->db->from('player_items pi');
        $this->db->join('items i', 'pi.item_id = i.id');
        $this->db->where('pi.player_id', $player_id);
        $this->db->where('pi.slot_id is not null');
        $query = $this->db->get();

        foreach ($query->result_array() as $row) {
            $data1[] = $row;
            $data1['inventory_capacity'] += $data1[0]['capacity'];
        }

        return $data1['inventory_capacity'];
    }

    private function get_player_inventory_count($player_id) {

        $this->db->select('count(pi.id) as item_count');
        $this->db->from('player_items pi');
        $this->db->where('pi.player_id', $player_id);
        $this->db->where('pi.slot_id is null');
        $query = $this->db->get();

        foreach ($query->result_array() as $row) {
            $data1[] = $row;
        }

        return $data1[0]['item_count'];
    }

    function get_player_places($player_id) {
        //$this->output->enable_profiler(TRUE);
        $this->db->select('*');
        $this->db->from('player_places pp');
        $this->db->join('places p', 'pp.place_id  = p.id');
        $this->db->where('pp.player_id', $player_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_current_player_place($player_id) {
        $this->db->select('*');
        $this->db->from('player_places pp');
        $this->db->join('places p', 'pp.place_id  = p.id');
        $this->db->join('player_combatants pc', 'pc.place_id = pp.id', 'LEFT OUTER');
        $this->db->where('pp.player_id', $player_id);
        $this->db->where('pc.active', 1);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function has_trader($player_id) {
        //$this->output->enable_profiler(TRUE);
        $this->db->select('t.id as trader_id, pt.id as player_trader_id, t.name, t.description, t.cost, t.experience_reward, t.credit_reward, t.place_progress');
        $this->db->from('player_traders pt');
        $this->db->join('traders t', 'pt.trader_id = t.id');
        $this->db->join('player_places pp', 'pt.place_id = pp.id');
        $this->db->where('pp.player_id', $player_id);
        $this->db->where('pt.active', '1');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data['trader'] = $row;
            }
            $data['hasTrader'] = 1;
        } else {
            $data['hasTrader'] = 0;
        }
        return $data;
    }

    function has_event($player_id) {
        $data = array();
        $player = newPlayer();
        $events = $player->getUncompletedEvents();
        foreach($events as $p_event) {
            if($p_event->event->damage) {
                $player->recieveDamage($p_event->event->damage);
                $data['hasEvent'] = 1;
                $data['hasEvent']['event'] = $p_event;
            }
        }
        if(!isset($data['hasEvent'])) {
            $data['hasEvent'] = 0;
        }
        return $data;
    }

    function has_combatant($player_id, $place_id) {
        $this->db->select('c.id as combatant_id, pc.id as c_id, c.name, c.description, pc.health as current_health, c.health as full_health, pc.completed,c.attack,c.defense');
        $this->db->from('player_combatants pc');
        $this->db->join('player_places pp', 'pc.place_id = pp.id');
        $this->db->join('combatants c', 'pc.combatant_id = c.id');
        $this->db->where('pp.player_id', $player_id);
        $this->db->where('pp.active', '1');
        $this->db->where('pc.active', '1');
        $this->db->where('pp.place_id', $place_id);
        $this->db->where('pc.completed is null');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                //Should only ever be one
                $data['combatant'] = $row;
            }

            $data['combatant_item_count'] = 0;
            $data['combatant_thing_count'] = 0;

            if ($data['combatant']['current_health'] != $data['combatant']['full_health']) {
                $this->db->select('a.date, a.critical_hit, a.fatal_hit, a.damage, a.credit, a.experience, a.energy, a.health, a.stamina, c.name');
                $this->db->from('actions a');
                $this->db->join('player_combatants pc', 'a.combatant_id = pc.id');
                $this->db->join('combatants c', 'pc.combatant_id = c.id');
                $this->db->where('player_id', $player_id);
                $this->db->where('coalesce(a.new_combatant,0) <> 1');
                $this->db->where('a.combatant_id', $data['combatant']['c_id']);
                $this->db->order_by('a.id', 'desc');
                $query = $this->db->get();

                if ($query->num_rows > 0) {
                    $data['health_lost'] = 0;
                    $data['stamina_used'] = 0;
                    foreach ($query->result_array() as $row) {
                        $actions[] = $row;
                        $data['health_lost'] += $row['health'];
                        $data['stamina_used'] += $row['stamina'];
                        if ($row['fatal_hit'] > 0) {
                            $data['xp_gained'] = $row['experience'];
                            $data['credit_gained'] = $row['credit'];
                        }
                    }
                    $data['actions'] = $actions;
                }
            }
            if ($data['combatant']['current_health'] == 0) {
                $this->output->enable_profiler(TRUE);
                $this->db->select('*, pci.id as player_combatant_item_id');
                $this->db->from('player_combatant_items pci');
                $this->db->join('items i', 'pci.item_id = i.id');
                $this->db->where('pci.combatant_id', $data['combatant']['c_id']);
                $query = $this->db->get();

                if ($query->num_rows > 0) {
                    foreach ($query->result_array() as $row) {
                        $data['combatant_item_count'] ++;
                        $tmp[] = $row;
                    }
                    $data['combatant_items'] = $tmp;
                }
                $this->db->select('*, pct.id as player_combatant_thing_id');
                $this->db->from('player_combatant_things pct');
                $this->db->join('things t', 'pct.thing_id = t.id');
                $this->db->where('pct.combatant_id', $data['combatant']['c_id']);
                $query = $this->db->get();

                if ($query->num_rows > 0) {
                    foreach ($query->result_array() as $row) {
                        $data['combatant_thing_count'] ++;
                        $tmp2[] = $row;
                    }
                    $data['combatant_things'] = $tmp2;
                }
            }
            $data['hasCombatant'] = 1;
        } else {
            $data['hasCombatant'] = 0;
        }
        return $data;
    }

    function has_boss($player_id) {
        $this->db->select('*');
        $this->db->from('player_bosses pb');
        $this->db->join('bosses b', 'pb.boss_id = b.id');
        $this->db->where('pb.player_id', $player_id);
        $this->db->where('pb.completed is null');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            $data['has_boss'] = 1;
        } else {
            $data['has_boss'] = 0;
        }
        return $data;
    }

    function get_actions($player_id, $place_id) {
        $this->db->select('a.*, c.name as combatant_name, e.name as event_name');
        $this->db->from('actions a');
        $this->db->join('places p', 'a.place_id = p.id');
        $this->db->join('combatants c', 'a.combatant_id = COALESCE(c.id,0)', 'LEFT OUTER');
        //$this->db->join('traders t','a.trader_id = COALESCE(t.id,0)','LEFT OUTER');
        $this->db->join('events e', 'a.event_id = COALESCE(e.id,0)', 'LEFT OUTER');
        //$this->db->join('bosses b','a.boss_id = COALESCE(b.id,0)','LEFT OUTER');
        $this->db->where('a.player_id', $player_id);
        $this->db->where('a.place_id', $place_id);
        $this->db->order_by('date', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return array();
    }

    public function deduct_price($player_id, $price, $premium_price) {
        $this->db->set('balance', 'balance - ' . $price, FALSE);
        $this->db->set('premium_balance', 'premium_balance - ' . $premium_price, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

    public function counter_increment($player_id, $energy, $health, $stamina) {
        $date = date("Y-m-d H:i:s", time());

        if ($energy > 0) {
            $this->db->set('energy', 'energy + ' . $energy, FALSE);
            $this->db->set('energy_refill', $date);
        }
        if ($health > 0) {
            $this->db->set('health', 'health + ' . $health, FALSE);
            $this->db->set('health_refill', $date);
        }
        if ($stamina > 0) {
            $this->db->set('stamina', 'stamina + ' . $stamina, FALSE);
            $this->db->set('stamina_refill', $date);
        }
        $this->db->set('account_id', 'account_id', FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

    public function update_stats($player_id, $stamina_points = 0, $energy_points = 0, $health_points = 0, $attack_points = 0, $defense_points = 0, $skill_cost = 0) {
        $this->db->set('stamina_limit', 'stamina_limit +' . $stamina_points, FALSE);
        $this->db->set('health_limit', 'health_limit +' . $health_points, FALSE);
        $this->db->set('energy_limit', 'energy_limit +' . $energy_points, FALSE);
        $this->db->set('attack', 'attack +' . $attack_points, FALSE);
        $this->db->set('defense', 'defense +' . $defense_points, FALSE);
        $this->db->set('skill', 'skill - ' . $skill_cost, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

    public function updatePlayer($id, $data){
        $this->db->where("id", $id);
        $this->db->update("players", $data);

    }



}

/* End of file players_model.php */
/* Location: ./application/models/players_model.php */