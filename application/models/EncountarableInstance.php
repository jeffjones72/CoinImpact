<?php
class EncountarableInstance extends CI_Model {
    public function __construct($data = null) {
        parent::__construct($data);
        if(!isset($data['generated'])) {
            $this->generated = date(Globals::MYSQL_DATE_FORMAT);
        }
        if(!isset($data['completed'])) {
            $this->completed = null;
        }
    }
    public function setPlace(PlayerPlace $p_place) {
        $this->place_id = $p_place->id;
        $this->player_place = $p_place;
    }
}
?>