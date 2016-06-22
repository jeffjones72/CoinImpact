<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('places_model');
        $data['base_place'] = $this->places_model->get_place_by_id($this->places_model->base_place);
    }
    public function loadGlobals(&$model, &$data) {
        $model->load->model('places_model');
        //$model->load->model('account');
        $repo = Repo::getInstance();
        $data['lang'] = Lang::getInstance();
        $data['base_place'] = $repo->getByID('Place', Place::$ids['base']);
        $data['account_o'] = new AccountO($this->session->userdata('id'));
        $player = newPlayer();
        $player->handleRefill();
        //$player->save();
        $data['current_place'] = $player->getCurrentPlace();
        //echo $data['current_place'];
        $data['player'] = $player;
    }
    public function get_counters() {
        $this->output->enable_profiler(TRUE);
        $account_id = $this->session->userdata('id');
        
        $this->load->model('players_model');
        $player = $this->players_model->get_player_info($account_id);
        $player_id = $player['player_id'];
        $this->load->model('items_model');
        $items = $this->items_model->get_items($player_id);
        $deltas = $items['item_deltas'][0];

        $data['attack'] = $player['attack'] + $deltas['attack_delta'];
        $data['defense'] = $player['defense'] + $deltas['defense_delta'];
        $data['strike'] = $player['strike'] + $deltas['strike_delta'];
        $data['strike_boost'] = $player['strike_boost'] + $deltas['strike_boost_delta'];
        $data['damage_boost'] = $player['damage_boost'] + $deltas['damage_boost_delta'];
        $data['dodge'] = $player['dodge'] + $deltas['dodge_delta'];
        $data['luck'] = $player['luck'] + $deltas['luck_delta'];

        $data['experience'] = $player['experience'];
        $data['next_level_xp'] = $player['next_level_xp'];
        $data['current_level_xp'] = $player['current_level_xp'];
        $data['player_now'] = $player['player_now'];

        // Current stats (counters)
        /*$data['energy'] = $player['energy'];
        $data['health'] = $player['health'];
        $data['stamina'] = $player['stamina'];

        // Refill rates
        $data['energy_rate'] = $player['energy_rate'];
        $data['health_rate'] = $player['health_rate'];
        $data['stamina_rate'] = $player['stamina_rate'];

        // Time of last refill
        $data['energy_refill'] = $player['energy_refill'];
        $data['health_refill'] = $player['health_refill'];
        $data['stamina_refill'] = $player['stamina_refill'];

        // Max refill levels (including buffs).
        $data['stamina_limit'] = $player['stamina_limit'] + $deltas['stamina_delta'];
        $data['energy_limit'] = $player['energy_limit'] + $deltas['energy_delta'];
        $data['health_limit'] = $player['health_limit'] + $deltas['health_delta'];

        // Time difference since last refill (in seconds)
        $data['energy_diff'] = $player['server_time'] - $player['energy_refill'];
        $data['health_diff'] = $player['server_time'] - $player['health_refill'];
        $data['stamina_diff'] = $player['server_time'] - $player['stamina_refill'];

        $data['energy_refill_amount'] = 0;
        $data['health_refill_amount'] = 0;
        $data['stamina_refill_amount'] = 0;

        // Calulate amount to refill based on the elapsed time.
        if ($data['energy'] < $data['energy_limit']) {
            $data['max_refill'] = $data['energy_limit'] - $data['energy'];
            $data['refill_amount'] = floor($data['energy_diff'] / $player['energy_rate']);
            $data['energy_refill_amount'] = $data['refill_amount'] <= $data['max_refill'] ? $data['refill_amount'] : $data['max_refill'];
        }
        if ($data['health'] < $data['health_limit']) {
            $data['max_refill'] = $data['health_limit'] - $data['health'];
            $data['refill_amount'] = floor($data['health_diff'] / $player['health_rate']);
            $data['health_refill_amount'] = $data['refill_amount'] <= $data['max_refill'] ? $data['refill_amount'] : $data['max_refill'];
        }
        if ($data['stamina'] < $data['stamina_limit']) {
            $data['max_refill'] = $data['stamina_limit'] - $data['stamina'];
            $data['refill_amount'] = floor($data['stamina_diff'] / $player['stamina_rate']);
            $data['stamina_refill_amount'] = $data['refill_amount'] <= $data['max_refill'] ? $data['refill_amount'] : $data['max_refill'];
        }

        $data['energy'] = $player['energy'] + floor($data['energy_refill_amount']);
        $data['health'] = $player['health'] + floor($data['health_refill_amount']);
        $data['stamina'] = $player['stamina'] + floor($data['stamina_refill_amount']);
        $this->players_model->counter_increment($player_id, $data['energy_refill_amount'], $data['health_refill_amount'], $data['stamina_refill_amount']);*/
        /*$player = new Player();
        $player->handleRefill();
        $player->save();*/
        
        return $data;
    }

    public function check_isvalidated() {
        if (!$this->session->userdata('validated')) {
            redirect('login');
        }
        if (!$this->session->userdata('passed_intro') && get_class($this) != 'Intro') {
            redirect('intro?warn=true');
        } else {
            $this->load->model('accounts_model');
            $user = $this->accounts_model->get_account_info($this->session->userdata('id'));
            $result = $this->accounts_model->update_last_login($user['id']);
        }
    }

}

?>