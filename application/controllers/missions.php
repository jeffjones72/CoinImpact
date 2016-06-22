<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Missions extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);

        $this->load->model('players_model');
        $player = newPlayer();


        $player_id = $player->id;

        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);


        $this->load->model('missions_model');
        $data['missions'] = $this->missions_model->get_missions($player_id);

        $header ['page_title'] = 'Missions';
        $header['data'] = $this->get_counters();

        $data['account'] = $account;
        $data['player'] = $player;

        //echo '<pre>';
        //print_r($data);
        //echo '</pre>';

        $data['content'] = $this->load->view('missions', $data, TRUE);

        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

}

/* End of file missions.php */
/* Location: ./application/controllers/missions.php */