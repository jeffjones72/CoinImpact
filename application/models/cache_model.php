<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Cache_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_cache_info($cache_id = 0)
    {
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
                
                // Get items
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
                
                // Get things
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
                
                // Get boosts
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
                
                // Get modifiers
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

    /*
     * Ticket #85
     * get items from cache, and for those the player already has set the hasIt=1 , otherwise hasIt = 0
     */
    public function get_cache_items($player_id = 0, $where_arg = "")
    {
        $player_items = array();
        $player_items = $this->db->select("item_id")
            ->get_where("player_items", "player_id = $player_id")
            ->result_array();
        // if(count($player_items) > 0 ){
        // $items = $this->db->get_where("items","from_cache = 1" )->result();
        if ($where_arg != "") {
            // $items = $this->db->order_by($order_by)->where("from_cache = 1 and release_id = 1")
            $items = $this->db->where($where_arg)
                ->where("from_cache = 1 and release_id = 1")
                ->get("items")
                ->result();
        } else {
            $items = $this->db->where("from_cache = 1 and release_id = 1")
                ->get("items")
                ->result();
        }
        $id_player_items = array();
        foreach ($player_items as $p_i) {
            $id_player_items[] = $p_i["item_id"];
        }
        foreach ($items as $item) {
            
            if (in_array($item->id, $id_player_items)) {
                $item->hasIt = 1;
            } else {
                $item->hasIt = 0;
            }
        }
        
        return $items;
        
        // }else{
    }

    /*
     * Ticket #85
     * Generate cache item with appearence chance depending on rarity 5 is 10% , 4 - 20%, 3- 30% , 2 - 40%
     * if the player_id has already the generated item in its inventory, generate another item
     */
    public function generate_cache_item($player_id)
    {
        // $item_ids = $this->db->select("id")->get_where("items", "from_cache = 1 and release_id = 1");
        $rarity5 = $this->db->select("id")
            ->get_where("items", "from_cache = 1 and release_id = 1 and rarity_id = 5")
            ->result_array();
        $rarity4 = $this->db->select("id")
            ->get_where("items", "from_cache = 1 and release_id = 1 and rarity_id = 4")
            ->result_array();
        $rarity3 = $this->db->select("id")
            ->get_where("items", "from_cache = 1 and release_id = 1 and rarity_id = 3")
            ->result_array();
        $rarity2 = $this->db->select("id")
            ->get_where("items", "from_cache = 1 and release_id = 1 and rarity_id = 2")
            ->result_array();
        shuffle($rarity5);
        shuffle($rarity4);
        shuffle($rarity3);
        shuffle($rarity2);
        $items_list = array();
        for ($i = 0; $i < 10; $i ++) {
            $index = array_rand($rarity5);
            $items_list[$i] = $rarity5[$index]["id"];
        }
        for ($i = 10; $i < 30; $i ++) {
            $index = array_rand($rarity4);
            $items_list[$i] = $rarity4[$index]["id"];
        }
        for ($i = 30; $i < 60; $i ++) {
            $index = array_rand($rarity3);
            $items_list[$i] = $rarity3[$index]["id"];
        }
        for ($i = 60; $i < 100; $i ++) {
            $index = array_rand($rarity2);
            $items_list[$i] = $rarity2[$index]["id"];
        }
        shuffle($items_list);
       do{
           $cache_index = array_rand($items_list);
           $player_item = $this->db->get_where("player_items", "player_id = {$player_id} and item_id = {$items_list[$cache_index]}")->result();
       }while(count($player_item)>0);
        
        
        $item = $this->db->get_where("items", "id = {$items_list[$cache_index]}")->row();
        return $item;
    }
}

/* End of file cache_model.php */
/* Location: ./application/models/cache_model.php */