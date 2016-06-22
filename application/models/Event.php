<?php
class Event extends Encountarable {
    public static function generate(Place $place) {
        $CI =& get_instance();
        $rarity_level = rarity_roll(3);
        
        $CI->db->select('e.id');
        $CI->db->from('events e');
        $CI->db->join('event_sections s', 'e.section_id = s.id');
        $CI->db->join('places_allowed_event_sections paes', 'e.section_id = paes.eventsection_id');
        $CI->db->where('paes.place_id', $place->id);
        $CI->db->where('rarity_id <=', $rarity_level);
        $CI->db->order_by('rand()');
        $CI->db->limit(1);

        $query = $CI->db->get();

        if($query === false){
            redirect('/explore/');
        }

        $event = $query->result()[0];
        $obj = new Event($event->id);

        return $obj;
    }
    public function generatePlaceInstance(PlayerPlace $p_place) {
        $p_event = new PlayerEvent();
        
        $p_event->setPlace($p_place);
        $p_event->setEvent($this);
        $p_event->setActive(false);
        
        return $p_event;
    }
}
?>