<?php
class Trader extends Encountarable {
    public static function generate(Place $place) {
        $CI =& get_instance();
        $rarity_level = rarity_roll(5);
        
        $CI->db->select('t.id');
        $CI->db->from('traders t');
        $CI->db->join('trader_sections s', 'c.section_id = s.id');
        $CI->db->join('places_allowed_trader_sections pacs', 't.section_id = pacs.tradersection_id');
        $CI->db->where('pacs.place_id', $place->id);
        $CI->db->where('rarity_id <=', $rarity_level);
        $CI->db->order_by('rand()');
        $CI->db->limit(1);

        $query = $CI->db->get();

        if($query === false){
            redirect('/explore/');
        }
        $trader = $query->result()[0];
        $obj = new Trader($trader->id);

        return $obj;
    }
    public function generatePlaceInstance(PlayerPlace $p_place) {
        $p_trader = new PlayerTrader();

        $p_trader->setPlace($p_place);
        $p_trader->setTrader($this);
        $p_trader->setActive(false);

        return $p_trader;
    }

}
?>