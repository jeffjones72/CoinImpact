<?php
/*
    DamageCalculator

    The model for the instance of the calculator
    of the damage of an combatant by the player.
*/
/*
    sq10 | CI:B0101
    Split the TrainingDummy damage algorithm into its own model.
*/
class DamageCalculator extends CI_Model {
    private $player = null;
    private $dodge = -1;
    public function __construct($data) {
        /*
            Constructs the TrainingDummy for each player.

            returns nothing

            $data (array)
                $player (Player):
                    the player instance
                $dodge (int):
                    dodge attribute of attacker

        */
        $this->player = $data['player'];
        $this->dodge = $data['dodge'];
    }

    /*
        Getters+Setters
    */
    //player (Player)
    public function getPlayer(){
        return $this->player;
    }
    public function setPlayer($player){
        $this->player = $player;
    }
    //dodge (int)
    public function getDodge(){
        return $this->dodge;
    }
    public function setDodge($dodge){
        $this->dodge = $dodge;
    }
    /*
        End of Getters+Setters
    */

    public function attackRoll($rolls = 1){
        /*
            Calculates attack stats for $rolls amount of rolls.
            If $rolls isn't specified, then $rolls = 1.

            returns (array)
                damage (int):
                    the amount of damage dealt
                xp (int):
                    the amount of XP awarded
                special (string):
                    if a special attack event happened (e.g. 'dodge', 'critical')
                    'no' is default value
        */

        $stats = array(
            'damage' => 0,
            'xp' => 0,
            'special' => 'no'
        );

        //Using the Damage Output Computation from the COIN documentation

        //Calculate total damage of the rolls
        for ($x=0; $x<$rolls; $x++){
            $stats['damage'] += $this->damageRoll();
            $stats['xp'] += $this->xpRoll();
        }

        //Determine critical damage amount
        $critChance = mt_rand(0, 100);
        $critMultiplier = 1;
        if ($this->player->luck >= $critChance){
            $critMultiplier = 1.5;
            if ($critChance == 99){
                $critMultiplier = 2;
            }
            if ($critChance == 100){
                $critMultiplier = 3;
            }
            $stats['special'] = 'critical';
        }

        $stats['damage'] = (int)($stats['damage']*$critMultiplier);

        //Calculate dodge
        $combatantDodge = $this->dodge;
        $dodgeChance = mt_rand(0, 100); //0 = highest probability of dodging
        if ($dodgeChance <= $combatantDodge){
            $stats['damage'] = 0;
            $stats['special'] = 'dodge';
        }

        return $stats;
    }

    public function attackRollTraining($rolls = 1){
        /*
            Calculates training attack stats for $rolls amount of rolls.
            If $rolls isn't specified, then $rolls = 1.

            returns (array)
                damage (int):
                    the amount of damage dealt
                xp (int):
                    the amount of XP awarded
                special (string):
                    if a special attack event happened (e.g. 'dodge', 'critical')
                    'no' is default value
        */

        $stats = array(
            'damage' => 0,
            'xp' => 0,
            'special' => 'no'
        );

        //Using the Damage Output Computation from the COIN documentation

        //Calculate total damage of the rolls
        for ($x=0; $x<$rolls; $x++){
            $stats['damage'] += $this->damageRoll();
            $stats['xp'] += $this->xpRollTraining();
        }

        //Determine critical damage amount
        $critChance = mt_rand(0, 100);
        $critMultiplier = 1;
        if ($this->player->luck >= $critChance){
            $critMultiplier = 1.5;
            if ($critChance == 99){
                $critMultiplier = 2;
            }
            if ($critChance == 100){
                $critMultiplier = 3;
            }
            $stats['special'] = 'critical';
        }

        $stats['damage'] = (int)($stats['damage']*$critMultiplier);

        //Calculate dodge
        $combatantDodge = $this->dodge;
        $dodgeChance = mt_rand(0, 100); //0 = highest probability of dodging
        if ($dodgeChance <= $combatantDodge){
            $stats['damage'] = 0;
            $stats['special'] = 'dodge';
        }

        return $stats;
    }
    private function xpRoll() {
        /*
            Calculates how much XP you earn for exactly 1 roll.

            returns (int) xp
        */
        return mt_rand(1, 5);
    }
    private function xpRollTraining() {
        /*
            Calculates how much XP you earn for exactly 1 roll.

            returns (int) xp
        */
        return mt_rand(1, 2);
    }
    private function damageRoll() {
        /*
            Calculates how much damage you make for exactly 1 roll

            returns (int) damage
        */

        //Using the Damage Output Computation from the COIN documentation (cont.)

        //Calculate base attack
        $baseDmg =
            ($this->player->attack)+ //TODO: Current item attack bonus
            (0.25*($this->player->defense))+ //TODO: Current item defense bonus
            (0)+ //TODO: Team attack
            (0); //TODO: Boosts

        //Attack variation, ±10%
        $dmgVariation =
            $baseDmg*
            (//generate a number between -0.1 and 0.1
                (((float)mt_rand()/(float)mt_getrandmax())*10)*
                ((bool)mt_rand(0,1)?-1:1)/100
            );

        //Put it all together
        /*
            sq10 | CI:B0100
            Wrap all the numbers in parentheses so the (int) type applies to the resulting number.
        */
        return (int)($baseDmg + $dmgVariation);
    }
}
?>
