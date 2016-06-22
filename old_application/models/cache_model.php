<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cache_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_cache_info($cache_id = 0) {
        $release = 1;

        if ($cache_id == 0) {
            $this->db->where('release_id', $release);
            $query = $this->db->get('caches');
        } else {
            $this->db->where('id', $cache_id);
            $query = $this->db->get('caches');
        }

        if ($query->num_rows > 0) {
            $i = 0;
            foreach ($query->result_array() as $row) {
                $data[] = $row;

                $cache_id = $row['id'];

                //Get items
                $this->db->select('*');
                $this->db->from('caches_allowed_items cai');
                $this->db->join('items i', 'cai.item_id = i.id');
                $this->db->where('cai.cache_id', $cache_id);
                $this->db->where('from_cache', 1);
                $query2 = $this->db->get();

                if ($query2->num_rows > 0) {
                    foreach ($query2->result_array() as $row) {
                        $data[$i]['items'][] = $row;
                    }
                }

                //Get things
                $this->db->select('*');
                $this->db->from('caches_allowed_things cat');
                $this->db->join('things t', 'cat.thing_id = t.id');
                $this->db->where('cat.cache_id', $cache_id);
                $this->db->where('from_cache', 1);
                $query3 = $this->db->get();

                if ($query3->num_rows > 0) {
                    foreach ($query3->result_array() as $row) {
                        $data[$i]['things'][] = $row;
                    }
                }

                //Get boosts
                $this->db->select('*');
                $this->db->from('caches_allowed_boosts cab');
                $this->db->join('boosts b', 'cab.boost_id = b.id');
                $this->db->where('cab.cache_id', $cache_id);
                $this->db->where('from_cache', 1);
                $query4 = $this->db->get();

                if ($query4->num_rows > 0) {
                    foreach ($query4->result_array() as $row) {
                        $data[$i]['boosts'][] = $row;
                    }
                }

                //Get modifiers
                $this->db->select('*');
                $this->db->from('caches_allowed_modifiers cam');
                $this->db->join('modifiers m', 'cam.modifier_id = m.id');
                $this->db->where('cam.cache_id', $cache_id);
                $this->db->where('from_cache', 1);
                $query5 = $this->db->get();

                if ($query5->num_rows > 0) {
                    foreach ($query5->result_array() as $row) {
                        $data[$i]['modifiers'][] = $row;
                    }
                }
            }
            return $data;
        }
    }

}

/* End of file cache_model.php */
/* Location: ./application/models/cache_model.php */