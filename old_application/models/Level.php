<?php
class Level extends CI_Model {
    public static function getByXP($xp) {
        $db = get_instance()->db;
        $query = $db->query('SELECT * FROM levels WHERE '.$db->escape($xp).
                ' >= experience AND (id+1) IN (SELECT id FROM levels WHERE '
                . $db->escape($xp).' < experience)');
        $result = $query->row_array();
        return new Level($result);
    }
    public static function getByID($id) {
        $db = get_instance()->db;
        $db->select('*');
        $db->where('id', $id);
        $db->from('levels');
        
        $query = $db->get();
        $result = $query->row_array();
        
        return new Level($result);
    }
    public function getNextLevel() {
        $this->db->select('*');
        $this->db->where('id > '.$this->db->escape($this->id), null, false);
        $this->db->from('levels');
        $this->db->order_by('id');
        $this->db->limit(1);
        
        $query = $this->db->get();
        if($query->num_rows == 0) {
            return null;
        }
        
        return new Level($query->row_array());
    }
}
?>