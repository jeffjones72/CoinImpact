<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Store_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_items() {
        $release = 1;

        $this->db->where('from_store', 1);
        $this->db->where('release_id', $release);
        $query = $this->db->get('items');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_things() {
        $release = 1;

        $this->db->where('from_store', 1);
        $this->db->where('release_id', $release);
        $query = $this->db->get('things');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_boosts() {
        $release = 1;

        $this->db->where('from_store', 1);
        $this->db->where('release_id', $release);
        $query = $this->db->get('boosts');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_modifiers() {
        $release = 1;

        $this->db->where('from_store', 1);
        $this->db->where('release_id', $release);
        $query = $this->db->get('modifiers');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

}

/* End of file store_model.php */
/* Location: ./application/models/store_model.php */