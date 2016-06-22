<?php
class Event extends Encountarable {
    public static function generate(Place $place) {
        return Encountarable::generateByType($place, 'event');
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