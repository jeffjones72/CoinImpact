<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Action extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    function travel() {
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];

        $place_id = $this->input->post('place_id');
        $this->load->model('action_model');
        $this->action_model->travel($player_id, $place_id);


        redirect('explore');
    }

    function explore() {
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];

        $place_id = $this->input->post('place_id');
        $roll = $this->roll(100);

        if ($roll > 40) {
            $rarity_level = $this->rarity_roll(5);
            $this->get_combatant($place_id, $rarity_level, $player_id);
        } elseif ($roll > 15) {
            $rarity_level = $this->rarity_roll(5);
            $this->get_trader($place_id, $rarity_level);
        } else {
            $rarity_level = $this->rarity_roll(3);
            $this->get_event($place_id, $rarity_level);
        }

        echo '<br /><br />';
        echo 'Roll is: ' . $roll . '<br />';
        echo 'Rarity roll is: ' . $rarity_level . '<br />';
    }

    private function get_combatant($place_id, $rarity_level, $player_id) {
        $this->load->model('action_model');
        $this->action_model->get_combatant($place_id, $rarity_level, $player_id);
        redirect('explore');
    }

    private function get_trader($place_id, $rarity_level) {
        $this->load->model('action_model');
        $this->action_model->get_trader($place_id, $rarity_level);
        echo 'Trader!';
    }

    private function get_event($place_id, $rarity_level) {
        $this->load->model('action_model');
        $this->action_model->get_event($place_id, $rarity_level);
        echo 'Event!';
    }

    private function roll($sides) {
        return mt_rand(1, $sides);
    }

    private function rarity_roll($rarity) {
        return mt_rand(1, mt_rand(1, $rarity));
    }

    private function check_isvalidated() {
        if (!$this->session->userdata('validated')) {
            redirect('login');
        } else {
            $this->load->model('accounts_model');
            $user = $this->accounts_model->get_account_info($this->session->userdata('id'));
            $result = $this->accounts_model->update_last_login($user['id']);
        }
    }

}

/* End of file action.php */
/* Location: ./application/controllers/action.php */