<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Boosts_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_boosts($player_id = 0) {
        $this->db->select('b.*, b.id as boost_id, b.name as boost_name, b.description as boost_description');
        $this->db->from('boosts b');
        if ($player_id > 0) {
            $this->db->join('player_boosts pb', 'b.id = pb.boost_id and player_id = ' . $player_id, 'LEFT OUTER', FALSE);
            $this->db->where('pb.id is not null');
        }
        $this->db->order_by('b.name', 'asc');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }

            $this->db->select('b.*, pb.expires');
            $this->db->from('boosts b');
            $this->db->join('player_boosts pb', 'b.id = pb.boost_id');
            $this->db->where('pb.player_id', $player_id);
            $this->db->where('pb.item_id is not null');
            $this->db->where('pb.expires > ', date("Y-m-d H:i:s", time()));
            $query = $this->db->get();

            if ($query->num_rows > 0) {
                foreach ($query->result_array() as $row) {
                    $data['active'] = $row;
                }
            }
            return $data;

            return $data;
        }
    }

    function get_applied_boosts_deltas($player_id) {
        
    }

    function get_boost_count() {
        $data = $this->db->count_all('boosts');

        return $data;
    }

    function collect_boost($player_id, $boost_id) {
        $data = array(
            'player_id' => $player_id,
            'boost_id' => $boost_id,
            'collected' => now()
        );

        /* 	TODO: Add this and two arguments to receive combatant_id and is_staff for 
         * 	modifiers and security.

          // Get additional data from the modifiers table.
          $this->db->select('has_quality');
          $query = $this->db->get_where('modifiers',$modifier_id, 1);

          foreach ($query->result_array() as $row
          {
          $data['quality'] = $row['has_quality'];
          }
         */

        $this->db->insert('player_boosts', $data) or die(mysql_error());

        return true;
    }

    function get_boost($boost_id) {
        $this->db->where('id', $boost_id);
        $query = $this->db->get('boosts');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row;
            }
        }

        return $data;
    }

    function insert($player_id, $boost_id) {

        $date = date("Y-m-d H:i:s", time());

        $arr = array(
            'player_id' => $player_id,
            'boost_id' => $boost_id,
            'collected' => $date
        );

        $this->db->insert('player_boosts', $arr);
    }

}

/* End of file boosts_model.php */
/* Location: ./application/models/boosts_model.php */