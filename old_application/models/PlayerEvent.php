<?php
class PlayerEvent extends EncountarableInstance {
    public function __construct($data = null) {
        parent::__construct($data);
        if($data === null) {
            return;
        }
        $this->player_place = new PlayerPlace($this->place_id);
    }
    public function setEvent(Event $event) {
        $this->event_id = $event->id;
        $this->event = $event;
    }
    public function setCompleted() {
        $this->db->set('completed', 'NOW()', false);
        $this->db->where('id', $this->id);
        $this->db->update('player_events');
        $this->completed = date(Globals::MYSQL_DATE_FORMAT);
    }
    public function setActive($active) {}
}
?>