<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Action_new_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function travel($player_id, $place_id) {
        $this->db->select('progress,energy');
        $this->db->from('player_places pp');
        $this->db->join('places p', 'pp.player_id = p.id');
        $this->db->where('pp.player_id', $player_id);
        $this->db->where('pp.place_id', $place_id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $progress = $row['progress'];
                $energy = $row['energy'];
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

        $this->db->set('energy', 'energy - ' . $energy, FALSE);
        $this->db->where('id', $player_id);
        $this->db->update('players');
    }

    public function get_combatant($place_id, $rarity_level, $player_id) {
        $this->output->enable_profiler(TRUE);
        $this->db->select('id');
        $this->db->from('player_places');
        $this->db->where('place_id', $place_id);
        $this->db->where('player_id', $player_id);
        $query = $this->db->get();

        $date = date("Y-m-d H:i:s", time());

        $this->db->select('*');
        $this->db->from('combatants');
        $this->db->where('rarity_id <= ' . $rarity_level);
        $this->db->order_by('id', 'random');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $combatant_id = $row['id'];
                $health = $row['health'];
                $active = 1;
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

    public function get_trader($place_id, $rarity_level) {
        $this->db->select('*');
        $this->db->from('traders');
        $this->db->where('rarity_id <= ' . $rarity_level);
        $this->db->order_by('id', 'random');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
    }

    public function get_event($place_id, $rarity_level) {
        $this->db->select('*');
        $this->db->from('events');
        $this->db->where('rarity_id <= ' . $rarity_level);
        $this->db->order_by('id', 'random');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
    }

}
