<?php
class Place extends CI_Model {
    public static $ids = array('base' => 2, 'enemy_safehouse' => 1);
    public function getMainBoss() {
        $this->db->select('boss_id');
        $this->db->where('place_id', $this->id);
        $this->db->from('bosses_required_places');
        
        $query = $this->db->get();
        if(!$query->num_rows) {
            return null;
        }
        $result = $query->row_array();
        return new Boss($result['boss_id']);
    }
    public static function getAll() {
        $places = array();
        
        $db = get_instance()->db;
        $db->select('*');
        $db->where('release_id', 1);
        $db->from('places');
        $query = $db->get();
        
        foreach($query->result_array() as $row) {
            $places[] = new Place($row);
        }
        
        return $places;
    }
}
?>