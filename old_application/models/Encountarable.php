<?php
class Encountarable extends CI_Model{
    private static $allowed_types = array('combatant', 'trader', 'event');
    public static function generateByType(Place $place, $type) {
        $db = get_instance()->db;
        if(!in_array($type, self::$allowed_types)) {
            throw new Exception('Not allowed type');
        }
        $rarity_level = rarity_roll(5);
        $query = $db->query('select o.id as id from '.$type.'s o 
				inner join '.$type.'_sections s on o.section_id = s.id 
				where rarity_id <= ' . $rarity_level . ' 
				and (exists (select '.$type.'section_id from places_allowed_'.$type.'_sections where '.$type.'section_id = s.id) 
					 or exists (select '.$type.'_id from places_allowed_'.$type.'s where '.$type.'_id = o.id)) 
				and not exists (select '.$type.'_id from places_forbidden_'.$type.'s where '.$type.'_id = o.id and place_id = ' . $place->id . ') 
				and not exists (select '.$type.'section_id from places_forbidden_'.$type.'_sections where '.$type.'section_id = s.id and place_id = ' . $place->id . ') 
				order by RAND() 
				LIMIT 1');
        if($query->num_rows === 0) {
            return null;
        }
        $id_arr = $query->row_array();
        $enc_id = $id_arr['id'];
        $class = ucfirst($type);
        $enc = new $class($enc_id);
        return $enc;
    }
}
?>