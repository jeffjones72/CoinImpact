<?php
class Trader extends Encountarable {
    public static function generate(Place $place) {
        return Encountarable::generateByType($place, 'trader');
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