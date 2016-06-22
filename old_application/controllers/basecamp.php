<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Basecamp extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index()
    {
        $user_id = $this->session->userdata('id');
        $player = newPlayer();
        if ($this->input->post('enable_id')) {
            $p_item = new PlayerItem($this->input->post('enable_id'));
            $player->tryEquip($p_item);
        }
        if ($this->input->post('drop_id')) {
            $p_item = new PlayerItem($this->input->post('drop_id'));
            $player->drop($p_item);
        }
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);
        
        $this->load->model('players_model');
        $data['missions'] = $player->queryMissions();
//         var_dump($data['missions']);
//         die();
        //  $player = $this->players_model->get_player_info($user_id);
        // $player_id = $player['player_id'];
        //
        // $player_places = $this->players_model->get_player_places($player_id);
        $data['page_title'] = 'Base Camp';
        
        // $data['slots'] = $slots;
        $data['account'] = $account;
        // $data['player'] = $player;
        // $data['player_places'] = $player_places;
        $data['inventory_items'] = $player->getItemsInInventory();
        // $data['items'] = $items;
        // $data['modifiers'] = $modifiers;
        // $data['things'] = $things;
        // $data['boosts'] = $boosts;
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        
        $data['content'] = $this->load->view('basecamp', $data, TRUE);
        
        $this->load->view('header', $data);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }
}

/* End of file basecamp.php */
/* Location: ./application/controllers/basecamp.php */