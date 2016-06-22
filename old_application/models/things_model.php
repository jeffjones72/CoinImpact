<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Things_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_things($player_id = 0) {
        /**
         * CODE CI:B0201
         * calculated number of things the player has as pieces
         */
        $this->db->select('t.*, t.id as thing_id, count(t.id) as pieces , t.name as thing_name, t.description as thing_description');
        $this->db->from('things t');
        if ($player_id > 0) {
            $this->db->join('player_things pt', 't.id = pt.thing_id');
            $this->db->where('pt.player_id', $player_id);
            $this->db->group_by("t.id");
        }
        $this->db->order_by('t.name', 'asc');
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_thing_count() {
        $data = $this->db->count_all('things');

        return $data;
    }

    function collect_boost($player_id, $thing_id) {
        $data = array(
            'player_id' => $player_id,
            'thing_id' => $thing_id,
            'collected' => now()
        );

        $this->db->insert('player_things', $data) or die(mysql_error());

        return true;
    }

}

/* End of file things_model.php */
/* Location: ./application/models/things_model.php */