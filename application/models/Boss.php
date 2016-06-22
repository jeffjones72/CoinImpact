<?php
class Boss extends CI_Model {
    public static $ids = array('outlaw_guards' => 1);
    public static $max_skulls = 5;
    public function getSkulls() {
        /*$score = $this->health + (($this->attack + $this->defense) * 3);
        $skulls = 0;
        if ($score <= 175) {
            $skulls = 1;
        } elseif ($score <= 275) {
            $skulls = 2;
        } elseif ($score <= 350) {
            $skulls = 3;
        } elseif ($score <= 450) {
            $skulls = 4;
        } else {
            $skulls = 5;
        }
        return $skulls;*/
        return $this->skulls;
    }
    public function getDrop() {
        $drop = array();
        $this->db->select('item_id');
        $this->db->where('boss_id', $this->id);
        $this->db->from('boss_items');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $arr) {
            $drop[] = new Item($arr['item_id']);
        }
        
        return $drop;
    }
    public function getRequirements() {
        $requirements = array();
        
        $repo = Repo::getInstance();
        
        foreach(array('mission', 'item', 'place') as $thing) {
            $this->db->select($thing.'_id');
            $this->db->where('boss_id', $this->id);
            $this->db->from('bosses_required_'.$thing.'s');
            $query = $this->db->get();
            $result_arr = $query->result_array();
            $class = ucfirst($thing);
            
            foreach($result_arr as $result) {
                $requirements[] = $repo->getByID($class, $result[$thing.'_id']);
            }
        }
        return $requirements;
    }
    public static function getAll() {
        $db = get_instance()->db;
        $bosses = array();
        $db->select('*');
        $db->from('bosses');
        
        $query = $db->get();
        $result_arr = $query->result_array();
        
        foreach($result_arr as $result) {
            $bosses[] = new Boss($result);
        }
        return $bosses;
    }
//    public static function search() {
//        $CI = & get_instance();
//        $db = $CI->db;
//        $db->select('boss_id');
//        $db->from('player_available_bosses');
//        
//        $query = $db->get();
//        $arrs = $query->result_array();
//        
//        $bosses = array();
//        foreach($arrs as $boss) {
//            $bosses[] = new Boss($boss['boss_id']);
//        }
//        return $bosses;
//    }
}
?>