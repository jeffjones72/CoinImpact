<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Combatant_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_combatants_for_player($player_id) {
        $this->db->select('*');
        $this->db->from('combatants c');
        $this->db->join('player_combatants pc', 'c.id = pc.combatant_id');
        $query = $this->db->get_where('actions', array('player_id' => $player_id));
        return $query->row_array();
    }

    function get_all_combatant() {
        $query = $this->db->get('combatants');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data['combatants'] = $row;
            }
        }
        return $data;
    }

}

/* End of file combatant_model.php */
/* Location: ./application/models/combatant_model.php */