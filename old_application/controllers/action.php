<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Action extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    function travel() {
        //$this->load->model('players_model');
        //$temp = $this->players_model->get_player_info($this->session->userdata('id'));
        //$player_id = $temp['player_id'];

        //$place_id = $this->input->post('place_id');
        //$this->load->model('action_model');
        $player = newPlayer();
        $result = $player->tryTravelTo(new Place($this->input->post('place_id')));
        //$this->action_model->travel($player_id, $place_id);

        redirect('explore');
    }

    function training_attack() {
        /*
            /action/training_attack
            
            Attacks training target with X amount of stamina.

            POST
            $stamina -> the amount of stamina to use on the training target
            
            response (json)
                ok: (boolean) if everything was okay
                error: (optional, string) if there was an error
                    'bad_data': POST input for stamina is bad or nonexistent
                    'no_stamina': player doesn't have enough stamina
                    'unknown': unknown server error
                damage: (optional, int) how much damage was dealt
                xp: (optional, int) how much XP you gained
        */

        //Validate the data.
        $stamina = $this->input->post('stamina');
        if (
            empty($stamina) ||
            !ctype_digit($stamina)
        ) {
            echo json_encode(array(
                'ok' => FALSE,
                'error' => 'bad_data'
            ));
            return;
        }
        $stamina = (int)$stamina;

        //Process the data.
        $user = newPlayer();
        $result = $user->getTrainingDummy()->attack($stamina);

        if ($result['status'] == "no_stamina"){
            echo json_encode(array(
                'ok' => FALSE,
                'error' => 'no_stamina'
            ));
        }
        else if ($result['status'] == "bad_data"){
            echo json_encode(array(
                'ok' => FALSE,
                'error' => 'bad_data'
            ));
        }
        else if ($result['status'] == "ok"){
            echo json_encode(array(
                'ok' => TRUE,
                'damage' => $result['damage'],
                'xp' => $result['xp']
            ));
        }
        else{
            //never supposed to happen
            echo json_encode(array(
                'ok' => FALSE,
                'error' => 'unknown'
            ));
        }
    }

    function explore() {
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];
        $place_id = $this->input->post('place_id');
        $energy = $temp['energy'];
        $roll = $this->roll(100);

        if ($roll > 40) {
            $rarity_level = $this->rarity_roll(5);
            $this->get_combatant($place_id, $rarity_level, $player_id);
        } elseif ($roll > 15) {
            $rarity_level = $this->rarity_roll(5);
            $this->get_trader($place_id, $rarity_level, $player_id);
        } else {
            $rarity_level = $this->rarity_roll(5);
            $this->get_event($place_id, $rarity_level, $player_id);
        }

        $this->load->model('action_model');
        $this->action_model->explore($player_id, $energy);

        redirect('explore');
    }

    function fight() {
        $player = newPlayer();
        if($player->health < 10) {
            return false;
        }
        $p_combatant = new PlayerCombatant($this->input->post('player_combatant_id'));

        $dmg_to_combatant = $player->attack($p_combatant);
        $dmg_to_player = NULL;
        if(!$player->isFighting($p_combatant)) {
            $p_combatant->setFighting();
        }
        if(!$p_combatant->isDead()) {
            $dmg_to_player = $p_combatant->hit($player);
        }
        $player->takeStamina(1);
        ActionO::attackCombatant($p_combatant, $dmg_to_combatant, $dmg_to_player);

        redirect('explore');
    }

    public function confirm_combatant() {
        $player_combatant_id = $this->input->post('player_combatant_id');
        $date = date("Y-m-d H:i:s", time());
        $arr = array(
            'player_combatant_id' => $player_combatant_id,
            'completed' => $date,
            'active' => 0
        );

        $this->load->model('action_model');
        $this->action_model->confirm_combatant($arr);

        redirect('explore');
    }

    private function attribute_mod($quality, $attribute) {
        switch ($quality) {
            case 1:
                $rand = $this->roll(50, 26) / 100.0;
                $mod = ceil(($attribute * $rand) * -1);
                break;
            case 2:
                $rand = $this->roll(25, 10) / 100.0;
                $mod = ceil(($attribute * $rand) * -1);
                break;
            case 3:
                $rand = $this->roll(25, 10) / 100.0;
                $rand2 = $this->roll(2, 1);
                if ($rand2 == 1)
                    $mod = ceil(($attribute * $rand) * -1);
                else
                    $mod = ceil($attribute * $rand);
                break;
            case 4:
                $rand = $this->roll(25, 10) / 100.0;
                $mod = ceil($attribute * $rand);
                break;
            case 5:
                $rand = $this->roll(50, 26) / 100.0;
                $mod = ceil($attribute * $rand);
                break;
        }
        return $mod;
    }

    function flee() {
        $player_combatant_id = $this->input->post('player_combatant_id');
        $this->load->model('action_model');

        $base_fail_rate = 10;
        $roll = $this->roll(100);

        if ($roll >= $base_fail_rate) {
            $this->action_model->flee($player_combatant_id);
        } else {
            //Flee failed!
        }
        redirect('explore');
    }

    function collect_combatant_item() {
        $player_combatant_item_id = $this->input->post('player_combatant_item_id');

        $this->load->model('action_model');

        $this->action_model->collect_combatant_item($player_combatant_item_id);

        redirect('explore');
    }

    function drop_combatant_item() {
        $player_combatant_item_id = $this->input->post('player_combatant_item_id');

        $this->load->model('action_model');

        $this->action_model->drop_combatant_item($player_combatant_item_id);

        redirect('explore');
    }

    function collect_combatant_thing() {
        $player_combatant_thing_id = $this->input->post('player_combatant_thing_id');

        $this->load->model('action_model');

        $this->action_model->collect_combatant_thing($player_combatant_thing_id);

        redirect('explore');
    }

    function equip() {
        $player_item_id = $this->input->post('player_item_id');
        $p_item = new PlayerItem($player_item_id);
        if(!$p_item->isValid()) {
          //  var_dump("$p_item is invalid");
            return false;
        }
        $player = newPlayer();        
//         echo "<pre> player is <br>";
//         var_dump($player); 
//         echo "item is <hr>";
//         var_dump($p_item); die();
               
        $player->tryEquip($p_item);
        redirect('profile');
    }

    function unequip() {
        $player_item_id = $this->input->post('player_item_id');
        $p_item = new PlayerItem($player_item_id);
        if(!$p_item->isValid()) {
            return false;
        }
        $player = newPlayer();
       //CI:B0202 it doesn't unequip the items
        $player->unequip($p_item);

        redirect('profile');
    }

    function unequipslot($slot_id) {
        $this->load->model('action_model');
        $this->action_model->unequipslot($slot_id);
        redirect('profile');
    }

    function drop() {
        $player_item_id = $this->input->post('player_item_id');

        $this->load->model('action_model');
        $this->action_model->drop($player_item_id);

        redirect('profile');
    }

    function enable_modifier() {
        $modifier_id = $this->input->post('modifier_id');
        $player_item_id = $this->input->post('player_item_id');

        $this->load->model('action_model');
        $this->action_model->enable_modifier($modifier_id, $player_item_id);

        redirect('profile');
    }

    function drop_modifier() {
        $modifier_id = $this->input->post('modifier_id');

        $this->load->model('action_model');
        $this->action_model->drop_modifier($modifier_id);

        redirect('profile');
    }

    function accept_trader() {
       // $this->output->enable_profiler(TRUE);

        $user_id = $this->session->userdata('id');

        $this->load->model('players_model');
        $player = $this->players_model->get_player_info($user_id);

        $player_id = $player['player_id'];

        $player_trader_id = $this->input->post('player_trader_id');
        $this->load->model('action_model');

        $this->action_model->accept_trader($player_trader_id, $player_id);

        $trader=$this->action_model->get_trader_details($player_trader_id);

/**
 * CI:B0211
 * calculates the trust bar
 */
        
        $oop_player = new Player($player_id);
        $max_amount = 350; //the max amount when calculate the trust bar
        $data['percent'] = $oop_player->calcTrustProgress($trader['cost'], $max_amount);

        //$this->players_model ->updatePlayer($player_id, $update);
        redirect('explore', $data);
    }

    function ignore_trader() {
        $player_trader_id = $this->input->post('player_trader_id');
        $this->load->model('action_model');

        $this->action_model->ignore_trader($player_trader_id);

        redirect('explore');
    }

    function confirm_event() {
        $player_event_id = $this->input->post('player_event_id');
        $this->load->model('action_model');

        $this->action_model->confirm_event($player_event_id);

        redirect('explore');
    }

    function assemble_collection() {
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];

        $collected_item_id = $this->input->post('item_id');

        $this->load->model('collection_model');
        $success = $this->collection_model->assemble($player_id, $collected_item_id);

        if ($success)
            redirect('profile');
        else
            redirect('profile');
    }

    private function get_combatant($place_id, $rarity_level, $player_id) {
        $this->load->model('action_model');
        $this->action_model->get_combatant($place_id, $rarity_level, $player_id);
    }

    function display() {
        $rarity1 = 0;
        $rarity2 = 0;
        $rarity3 = 0;
        $rarity4 = 0;
        $rarity5 = 0;
        $rolls = 100000;

        for ($i = 0; $i < $rolls; $i++) {
            $rarity = $this->rarity_roll(5);

            switch ($rarity) {
                case 1:
                    $rarity1++;
                    break;
                case 2:
                    $rarity2++;
                    break;
                case 3:
                    $rarity3++;
                    break;
                case 4:
                    $rarity4++;
                    break;
                case 5:
                    $rarity5++;
                    break;
            }
        }
        //echo 'Rarity 1: ' . $rarity1 . ' (' . ($rarity1/$rolls) * 100 . '% and roughly ' . (($rarity1/$rolls) * 100) / 5 . '% chance of selection)<br />';
        //echo 'Rarity 2: ' . $rarity2 . ' (' . ($rarity2/$rolls) * 100 . '% and roughly ' . (($rarity2/$rolls) * 100) / 5 . '% chance of selection)<br />';
        //echo 'Rarity 3: ' . $rarity3 . ' (' . ($rarity3/$rolls) * 100 . '% and roughly ' . (($rarity3/$rolls) * 100) / 5 . '% chance of selection)<br />';
        //echo 'Rarity 4: ' . $rarity4 . ' (' . ($rarity4/$rolls) * 100 . '% and roughly ' . (($rarity4/$rolls) * 100) / 5 . '% chance of selection)<br />';
        //echo 'Rarity 5: ' . $rarity5 . ' (' . ($rarity5/$rolls) * 100 . '% and roughly ' . (($rarity5/$rolls) * 100) / 5 . '% chance of selection)<br /><br />';
        //echo 'Based on ' . $rolls . ' rolls.<br />';
        //echo anchor('action/display','Roll again...');
    }

    private function get_trader($place_id, $rarity_level, $player_id) {
        $this->load->model('action_model');
        $this->action_model->get_trader($place_id, $rarity_level, $player_id);
    }

    private function get_event($place_id, $rarity_level, $player_id) {
        $this->load->model('action_model');
        $this->action_model->get_event($place_id, $rarity_level, $player_id);
    }

    public function roll($sides, $start = 1) {
        return mt_rand($start, $sides);
    }
    private function rarity_roll($rarity) {
        return mt_rand(1, mt_rand(1, $rarity));
    }
}

/* End of file action.php */
/* Location: ./application/controllers/action.php */