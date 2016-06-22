<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Places_model extends CI_Model {
    public $base_place = 2;
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    public function get_place_by_id($id) {
        $this->db->select('*');
        $this->db->from('places');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows > 0) {
            return $query->row_array();
        }
    }
}
?>