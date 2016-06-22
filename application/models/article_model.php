<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Article_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function fetch($top = 5) {
        $this->db->where('unix_timestamp(date) >', 'unix_timestamp()', FALSE);
        $this->db->order_by('date');
        $this->db->limit($top);
        $query = $this->db->get('articles');

        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $article) {
                $articles[] = $article;
            }
            return $articles;
        }
        $empty = array();

        return $empty;
    }

}
