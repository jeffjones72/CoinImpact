<?php
class Encountarable extends CI_Model{
    private static $allowed_types = array('combatant', 'trader', 'event');
    public static function generateByType(Place $place, $type) {
        $db = get_instance()->db;
        if(!in_array($type, self::$allowed_types)) {
            throw new Exception('Not allowed type');
        }
        /*
        $sql = 'select c.id combatant_id, c.health, 1 active
                from combatants c 
                inner join combatant_sections s
                on c.section_id = s.id
                join places_allowed_combatant_sections pacs
                on c.section_id = pacs.combatantsection_id
                where pacs.place_id  = ' . $place_id . '
                order by rand();';
        */
        $rarity_level = rarity_roll(5);
        $query = $db->query('select o.id as id from '.$type.'s o 
				join '.$type.'_sections s on o.section_id = s.id 
                join places_allowed_' . $type . '_sections pas on o.section_id = pas.' . $type . 'section_id
				where rarity_id <= ' . $rarity_level . ' 
				where pas.place_id = ' . $place->id . ') 
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