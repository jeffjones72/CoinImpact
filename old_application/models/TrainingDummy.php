<?php
/*
    TrainingDummy

    The model for the instance of the dummy
    that is encountered during training.
*/
class TrainingDummy extends CI_Model {
    private $player = null;
    public function __construct($player) {
        /*
            Constructs the TrainingDummy for each player.

            returns nothing

            $player (Player)
                the player instance
        */
        $this->player = $player;
    }
    public function attack($stamina) {
        /*
            Has the player attack the dummy for X amount of stamina.

            returns (array)
                status: (string) the return status of this function
                    'ok': everything went well
                    'no_stamina': the player doesn't have enough stamina
                    'bad_data': stamina wasn't either 1, 5, or 20
                damage: (int) the amount of damage dealt
                xp: (int) the amount of xp earned

            $stamina (int)
                amount of stamina used
                allowed: 1, 5, 20

        */

        $allowedStamina = array(1, 5, 20);
        if (!in_array($stamina, $allowedStamina)){
            return array('status' => 'bad_data');
        }

        if ($this->player->stamina < $stamina){
            return array('status' => 'no_stamina');
        }

        $damage = 0;
        $xp = 0;

        for ($x = 0; $x < $stamina; $x++) {
            $stats = $this->attackRoll();
            $damage += $stats['damage'];
            $xp += $stats['xp'];
        }

        $this->player->takeStamina($stamina);
        $this->player->awardXP($xp);

        return array(
            'status' => 'ok',
            'damage' => $damage,
            'xp' => $xp
        );
    }
    private function attackRoll() {
        /*
            Calculates attack stats for exactly 1 stamina.

            returns (array)
                damage
                xp
        */

        return array(
            'damage' => $this->damageRoll(),
            'xp' => $this->xpRoll()
        );
    }
    private function xpRoll() {
        /*
            Calculates how much XP you earn for exactly 1 stamina.

            returns (int) stamina
        */
        return rand(1, 5);
    }
    private function damageRoll() {
        /*
            Calculates how much damage you make for exactly 1 stamina
            
            returns (int) damage
        */
        
        //Using the Damage Output Computation from the COIN documentation
        
        //1. Calculate dodge
        $combatantDodge = -1; //static item, can't dodge
        $dodgeChance = rand(0, 100); //0 = highest probability of dodging
        if ($dodgeChance <= $combatantDodge){
            return 0;
        }

        //2. Calculate base attack
        $baseDmg = 
            ($this->player->attack)+ //TODO: Current item attack bonus
            (0.25*($this->player->defense))+ //TODO: Current item defense bonus
            (0)+ //Team attack, which doesn't apply to the training target
            (0); //TODO: Boosts

        //3. Attack variation, ±10%
        $dmgVariation = 
            $baseDmg*
            (//generate a number between -0.1 and 0.1
                (((float)rand()/(float)getrandmax())*10)*
                ((bool)rand(0,1)?-1:1)/100
            );

        //4. Determine critical damage amount
        $critChance = rand(0, 100);
        $critMultiplier = 1;
        if ($this->player->luck >= $critChance){
            $critMultiplier = 1.5;
            if ($critChance == 99){
                $critMultiplier = 2;
            }
            if ($critChance == 100){
                $critMultiplier = 3;
            }
        }

        //5. Put it all together
        return (int)($baseDmg + $dmgVariation)*$critMultiplier;
    }
}
?>