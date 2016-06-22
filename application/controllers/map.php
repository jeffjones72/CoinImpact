<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $header);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);

        $this->load->model('players_model');
        $player = newPlayer();
        $player_id = $player->id;
        $this->load->model('items_model');
        $items = $this->items_model->get_items($player_id);

        $player_places = $this->players_model->get_player_places($player_id);
        $header ['page_title'] = 'Map';
        $header['data'] = $this->get_counters();
        $data['account'] = $account;
        $data['player'] = $player;
        $data['items'] = $items;
        $data['places'] = Place::getAll();
        $data['player_places'] = $player_places;

        $data['content'] = $this->load->view('map', $data, TRUE);

        if($player->isInCombat()) {
            $data['game_error'] = 'You can\'t travel while in combat.';
        }
        
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

}

/* End of file map.php */
/* Location: ./application/controllers/map.php */