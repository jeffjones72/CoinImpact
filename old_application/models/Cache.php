<?php
class Cache extends CI_Model {
    public static function getByID($id) {
        $db = get_instance()->db;
        $db->select('*');
        $db->where('id', $id);
        $db->from('caches');
        
        $query = $db->get();
        if($query->num_rows == 0) {
            return null;
        }
        
        $result = $query->row_array();
        return new Cache($result);
    }
}
?>