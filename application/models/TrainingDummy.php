<?php
/*
    TrainingDummy

    The model for the instance of the dummy
    that is encountered during training.
*/
class TrainingDummy extends CI_Model {
    private $player = null;
    private $damageCalculator = null;
    public function __construct($player) {
        /*
            Constructs the TrainingDummy for each player.

            returns nothing

            $player (Player)
                the player instance
        */
        $this->player = $player;
        /*
            sq10 | CI:B0102 | 1/2
            Use the new DamageCalculator model.
        */
        $this->damageCalculator = new DamageCalculator(array(
            'player' => $this->player,
            'dodge' => -1 //static item, can't dodge
        ));
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
                special: (int) if there was a special attack event (e.g. 'critical')

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

        /*
            sq10 | CI:B0102 | 2/2
            Refactor this segment to work with the new DamageCalculator model.
        */

        $roll = $this->damageCalculator->attackRollTraining($stamina);

        $damage = $roll['damage'];
        $xp = $roll['xp'];
        $special = $roll['special'];

        $this->player->takeStamina($stamina);
        $this->player->awardXP($xp);

        return array(
            'status' => 'ok',
            'damage' => $damage,
            'xp' => $xp,
            'special' => $special
        );
    }
}
?>
