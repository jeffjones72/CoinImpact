<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Base extends MY_Controller
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
        if ($this->input->post('sell_id')) {
            $p_item = new PlayerItem($this->input->post('sell_id'));
            $player->sell($p_item);
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
        $data['stored_items'] = $player->getStoredItems();
        
        $this->load->model('things_model');
        $data['inventory_things'] = $player->getThingsInInventory();
        
        //Get ccollections
        $this->load->model('collection_model');
        $data['collections'] = $this->collection_model->check_collections($player->id);
        // $data['items'] = $items;
        // $data['modifiers'] = $modifiers;
        // $data['things'] = $things;
        // $data['boosts'] = $boosts;
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        
        $data['content'] = $this->load->view('base', $data, TRUE);
        $header['data'] = $this->get_counters();
        
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }
    
    public function seen_base_intro(){
        $player_id = $this->input->post('player_id');
        $this->load->model('players_model');
        $this->players_model->seen_base_intro($player_id);
    }
}
/* End of file base.php */
/* Location: ./application/controllers/base.php */