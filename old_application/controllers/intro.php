<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Intro extends MY_Controller {

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
        $player = $this->players_model->get_player_info($user_id);

        $player_id = $player['player_id'];

        $data['inventory_count'] = $player['inventory_count'];
        $data['inventory_capacity'] = $player['inventory_capacity'];

        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);

        $data ['page_title'] = 'Intro';
        $data['data'] = $this->get_counters();

        $data['account'] = $account;
        //$data['player'] = $player;

        if($this->input->get('warn')) {
            $data['game_error'] = 'You must pass the intro in order to see other pages.';
        }
        $this->load->view('header', $data);
        $data['content'] = $this->load->view('intro', $data, TRUE);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

    public function upd_progress() {
        $page = $this->input->post('page'); 
        $player = newPlayer();
        
        if($page > 8) {
            return;
        }
       
        $this->db->set('page', $page);
        $this->db->where('player_id', $player->id);
        $this->db->update('intro_info');
    }
    private function equip($player_item_id, $slot_it) {
        $this->load->model('action_model');

        $this->action_model->equip($player_item_id, $slot_id);
    }

    function equip_first_gear() {
        $item_ids = array(
            36 => 12, 
            57 => 6, 
            65 => null, 
            68 => 9, 
            69 => null, 
            76 => null
        );
        $player = newPlayer();
        if($player->hasIntroPassed()) {
            redirect('profile');
            return;
        }
        foreach($item_ids as $id => $slot) {
            $item = new Item($id);
            if($slot) {
                $player->tryAdd($item, $slot);
            } else {
                $player->tryAdd($item);
            }
        }
        $player->tryAdd(new Boost(9));
        $player->setIntroPassed();
        redirect('profile');
    }

}

/* End of file intro.php */
/* Location: ./application/controllers/intro.php */