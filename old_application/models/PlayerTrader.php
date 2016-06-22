<?php
class PlayerTrader extends EncountarableInstance {
    public function getAllPlayerTraderThings() {
        $this->db->select('*');
        $this->db->where('trader_id', $this->id);
        $this->db->from('player_trader_things');

        $query = $this->db->get();
        $result = $query->result_array();

        $p_t_things = array();

        foreach($result as $p_t_t_arr) {
            $p_t_things[] = new PlayerTraderThing($p_t_t_arr);
        }

        return $p_t_things;
    }
    public function setTrader(Trader $trader) {
        $this->trader_id = $trader->id;
        $this->trader = $trader;
    }


    public function setActive($active) {
        $this->active = $active;
    }
    public function getAllPlayerTraderItems() {
        $this->db->select('*');
        $this->db->where('trader_id', $this->id);
        $this->db->from('player_trader_items');

        $query = $this->db->get();
        $result = $query->result_array();

        $p_t_items = array();

        foreach($result as $p_t_i_arr) {
            $p_t_items[] = new PlayerItemThing($p_t_i_arr);
        }

        return $p_t_items;
    }
    public function delete() {
        $p_t_items = $this->getAllPlayerTraderItems();
        $p_t_things = $this->getAllPlayerTraderThings();

        foreach($p_t_items as $p_t_item) {
            $p_t_item->delete();
        }

        foreach($p_t_things as $p_t_thing) {
            $p_t_thing->delete();
        }
    }
}
?>