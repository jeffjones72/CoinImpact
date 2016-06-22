<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Combatant extends CI_Model {

    public function getSkulls() {
        $pow = floor($this->health + (($this->attack + $this->defense) * 3));
        if ($pow <= 175) {
            return 1;
        }
        if ($pow <= 275) {
            return 2;
        } 
        if ($pow <= 350) {
            return 3;
        } 
        if ($pow <= 450) {
            return 4;
        }
        return 5;
    }    
    public static function generate(Place $place) {
        $CI =& get_instance();
        $rarity_level = rarity_roll(5);
        
        $CI->db->select('c.id');
        $CI->db->from('combatants c');
        $CI->db->join('combatant_sections s', 'c.section_id = s.id');
        $CI->db->join('places_allowed_combatant_sections pacs', 'c.section_id = pacs.combatantsection_id');
        $CI->db->where('pacs.place_id', $place->id);
        $CI->db->where('rarity_id <=', $rarity_level);
        $CI->db->order_by('rand()');
        $CI->db->limit(1);

        $query = $CI->db->get();

        if($query === false){
            redirect('/explore/');
        }

        $combatant = $query->result()[0];
        $obj = new Combatant($combatant->id);

        return $obj;
    }
    public function generatePlaceInstance(PlayerPlace $p_place) {
        $p_combatant = new PlayerCombatant();
        
        $p_combatant->setPlace($p_place);
        $p_combatant->setCombatant($this);
        $p_combatant->setHealth($this->health);
        $p_combatant->setActive(false);
        $p_combatant->setFighting(false);
        
        return $p_combatant;
    }
}
?>