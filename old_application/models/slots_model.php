<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slots_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_slots() {
        $query = $this->db->get('slots');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

}

/* End of file slots_model.php */
/* Location: ./application/models/slots_model.php */