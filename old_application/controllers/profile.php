<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $this->output->enable_profiler(TRUE);
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);

        $this->load->model('players_model');
        $player = newPlayer();
        if($this->input->post('drop_id')) {
            $p_item = new PlayerItem($this->input->post('drop_id'));
            $player->drop($p_item);
        }
        $player_id = $player->id;
        $friends = $this->players_model->get_friends($player_id);

        //echo $player_id;
        $this->load->model('items_model');
        $items = $this->items_model->get_items($player_id);
        //var_dump($items['item_slots']);
        $this->load->model('modifiers_model');
        $inactive_modifiers = $player->getInactiveModifiers();

        $this->load->model('things_model');
        $things = $this->things_model->get_things($player_id);

        $this->load->model('collection_model');
        $collections = $this->collection_model->check_collections($player_id);

        $this->load->model('boosts_model');
        $boosts = $this->boosts_model->get_boosts($player_id);
        $header ['page_title'] = 'Player Profile for: ' . $player->account->first_name . ' ' . $player->account->last_name;

        //$data['slots'] = $slots;
        $data['account'] = $account;
        $data['player'] = $player;
        $data['friends'] = $friends;
        $data['items'] = $items;
        $data['inactive_modifiers'] = $inactive_modifiers;
        $data['things'] = $things;
        $data['collections'] = $collections;
        $data['boosts'] = $boosts;
        $header['data'] = $this->get_counters();

        $data['content'] = $this->load->view('profile', $data, TRUE);
        $this->load->view('header', $header);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */