<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->output->enable_profiler(TRUE);
        $account_id = $this->session->userdata('id');
        $counters = $this->get_counters();

        $this->load->model('players_model');
        $player = $this->players_model->get_player_info($account_id);
        $player_id = $player['player_id'];

        $item_id = 41;
        $this->load->model('collection_model');
        $success = $this->collection_model->assemble($player_id, $item_id);
    }

}

?>