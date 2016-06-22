<?php
class Exploration {
    private $player = null;
    public function __construct(Player $player) {
        $this->player = $player;
    }
    public function explore() {
        $cur_place = $this->player->getCurrentPlace();
        if($cur_place->place->energy > $this->player->energy) {
            return "not_enough_energy";
        }
        $roll = roll(100);
        $type = null;
        if ($roll > 40) {
            // Give player a combatant
            $type = 'combatant';
        } else if ($roll > 15) {
            // Give player a trader
            $type = 'trader';
        } else {
            // Give player an event
            $type = 'event';
        }
        $obj = call_user_func_array(array(ucfirst($type), 'generate'), array($cur_place->place));
        if(!$obj) {
            return false;
        }
        $obj_ins = $obj->generatePlaceInstance($cur_place);
        $obj_ins->setActive(true);
        $obj_ins->save();
        $cur_place->player->takeEnergy($cur_place->place->energy);
        return true;
    }
}
?>