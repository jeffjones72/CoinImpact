<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Player_inventory_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_player_inventory_info($account_id) {
        $data['inventory_capacity'] = 10;

        //print_r($query);
        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

}

/* End of file players_inventory_model.php */
/* Location: ./application/models/players_inventory_model.php */