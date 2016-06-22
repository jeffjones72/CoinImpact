<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_user_info($user_id) {
        $query = $query = $this->db->get_where('accounts', array('id' => $user_id));
        return $query->row_array();
    }

    function get_alluser_info() {
        $query = $query = $this->db->get();
        return $query->row_array();
    }

}

/* End of file user.php */
/* Location: ./application/models/user.php */