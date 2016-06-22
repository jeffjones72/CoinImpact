<?php
class Exploration {
    private $player = null;
    public function __construct(Player $player) {
        $this->player = $player;
    }
    /*
     * CI:B0212
     * Trust bar should eventually reduce the amount of “events” the player experiences.
     * This means less bombs, as well as beggars, etc.  As the bar gets more full, the bombs should decrease. 
     * I would like the maximum amount of decrease to be 75% when the bar reaches 100%. 
     * So, the player should eventually only experience 25% as many bombs. 
     * Beggars should stop appearing when the bar is at 100%.
     */
    public function explore() {
        $cur_place = $this->player->getCurrentPlace();
        if($cur_place->place->energy > $this->player->energy) {
            return "not_enough_energy";
        }
        $roll = roll(100);
        $type = null;
        /**
         * if trust bar is full with 75% give to the player less bombs and events
         */
        if($this->player->trustProgress < 40 ){
            //normal fight
            $roll = roll(100);
          /** 15% events , 25% beggars, 60% combatants**/
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
        }else if ($this->player->trustProgress<60){
            //less bombs and events  with 45%
            /** 8% events , 13% beggars, 33% combatants**/
            $roll = roll(55);
            if ($roll >22) {
                $type = 'combatant';
            } else if ($roll >=9) {
                $type = 'trader';
            } else if ($roll <9){
                $type = 'event';
            }
        
            
        }else if ($this->player->trustProgress<80){
            //less bombs, events and combatants with 60%
            $roll = roll(40);
            if ($roll >24) {
                $type = 'combatant';
            } else if ($roll >=9) {
                $type = 'trader';
            } else if ($roll <9){
                $type = 'event';
            }
        
            
        }else if ($this->player->trustProgress<100){
            //less bombs, events reduced with 75%
            $roll = roll(25);
            if ($roll >9) {
                $type = 'combatant';
            } else if ($roll >=4) {
                $type = 'trader';
            } else if ($roll <4){
                $type = 'event';
            }
        
            
        }
        else if($this->player->trustProgress == 100){
            $roll = roll(25);
            //no more beggars
          if ($roll >5) {
                // Give player a combatant
                $type = 'combatant';
            } else if ($roll <=5) {
                // Give player a trader
                $type = 'event';
            } 
        }
        
        
//         if ($roll > 40) {
//             // Give player a combatant
//             $type = 'combatant';
//         } else if ($roll > 15) {
//             // Give player a trader
//             $type = 'trader';
//         } else {
//             // Give player an event
//             $type = 'event';
//         }
       
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