<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Achievments_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_acieivemet($achievement_id) {
        $query = $this->db->get_where('achievements', array('id' => $account_id));
        return $query->row_array();
    }

    function get_all_acievements() {
        $query = $this->db->get('achievements');
        return $query->result();
    }

}

/* End of file achievements_model.php */
/* Location: ./application/models/achievements_model.php */