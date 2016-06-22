<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_isvalidated();
        // $sections = array(
        // 'config' => TRUE,
        // 'queries' => TRUE
        // );
        // $this->output->enable_profiler(TRUE);
        // $this->output->set_profiler_sections($sections);
    }

    public function index()
    {
        $this->output->enable_profiler(TRUE);
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);
        
        $this->load->model('players_model');
        $player = newPlayer();
        if ($this->input->post('drop_id')) {
            $p_item = new PlayerItem($this->input->post('drop_id'));
            $player->drop($p_item);
        }
        $player_id = $player->id;
        $friends = $this->players_model->get_friends($player_id);
        
        $this->load->model('items_model');
        $items = $this->items_model->get_items($player_id);
        $this->load->model('modifiers_model');
        $inactive_modifiers = $player->getInactiveModifiers();
        
        $this->load->model('things_model');
        $things = $this->things_model->get_things($player_id);
        
        $this->load->model('collection_model');
        $collections = $this->collection_model->check_collections($player_id);
        
        $this->load->model('boosts_model');
        $boosts = $this->boosts_model->get_boosts($player_id);
        $header['page_title'] = 'Player Profile for: ' . $player->account->first_name . ' ' . $player->account->last_name;
        
        // $data['slots'] = $slots;
        /*
         * CI:B0217 squad members
         */
        $squad_numbers = $player->getSquadNumbers();
        $data['squad_attack'] = $squad_numbers['attack'];
        $data['squad_defense'] = $squad_numbers['defense'];
//         $data['squad_attack'] = $player->attack;
//         $data['squad_defense'] = $player->defense;
        $this->load->model("Squad_model");
        $data['squad_members'] = $this->Squad_model->get_squad_members_profile($player_id);
        $data['squad_npc'] = $this->Squad_model->get_squad_npc_profile($player_id);
        $data['account'] = $account;
        $data['player'] = $player;
        $data['friends'] = $friends;
        $data['items'] = $items;
        $data['slot_items'] = $this->items_model->get_slot_items($player_id);
        $data['inactive_modifiers'] = $inactive_modifiers;
        $data['things'] = $things;
        $data['collections'] = $collections;
        $data['boosts'] = $boosts;
        $header['data'] = $this->get_counters();
        $this->load->model('stats_model');
        $data['stats'] = $this->stats_model->get($player_id);
        
        $data['content'] = $this->load->view('profile', $data, TRUE);
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }
    
    // Ticket #58
    // this function is just for test. Updates players stats so that you cand explore without flee
    // remove it when application is ready
    public function refresh()
    {
        $player_id = $this->session->userdata("player_id");
        $this->db->update("players", array(
            "energy" => "20",
            "stamina" => "20",
            "health" => "100",
            "balance" => "150"
        ), array(
            "id" => $player_id
        ));
    }

    public function ajax_squad_members()
    {
        $player_id = $this->session->userdata("player_id");
        
        $player = new Player($player_id);
        $this->load->model('items_model');
        $this->load->model("Player_items_squad_model", "p_items_s_model");
        if ($this->input->post("user_id")) {
            $post_data = explode("_", $this->input->post("user_id"));
        } else 
            if ($this->input->get("user_id")) {
                $post_data = explode("_", $this->input->get("user_id"));
            } else {
                // redirect(base_url() . "profile");
                // exit();
            }
        if (isset($post_data[1])) {
            $squad_member_id = $post_data[1];
            if ($player_id == $squad_member_id) {
                $data['vehicle'] = $player->getVehicle();
                $data['companion'] = $player->getCompanion();
            } else 
                if ($post_data[0] == "member") {
                    $data['vehicle'] = $this->p_items_s_model->getBySlotId($player_id, $squad_member_id, 14, 0); // slot_id for vehicle is 14
                    $data['companion'] = $this->p_items_s_model->getBySlotId($player_id, $squad_member_id, 15, 0); // slot_id for companion is 15
                    
                    $member_details = $this->db->get_where("players_team", "player_id = {$player_id} and team_player_id = {$squad_member_id} and is_NPC =0")->row();
                } else 
                    if ($post_data[0] == "npc") {
                        $data['vehicle'] = $this->p_items_s_model->getBySlotId($player_id, $squad_member_id, 14, 1); // slot_id for vehicle is 14
                        $data['companion'] = $this->p_items_s_model->getBySlotId($player_id, $squad_member_id, 15, 1); // slot_id for companion is 15
                        
                        $member_details = $this->db->get_where("players_team", "player_id = {$player_id} and team_player_id = {$squad_member_id} and is_NPC = 1")->row();
                    }
        } else {
            $squad_member_id = $player_id;
        }
        
        if ($player_id == $squad_member_id) {
            
            $data['squad_items'] = $this->items_model->get_slot_items($player_id);
            
            $data['equipment'] = $player->getEquipment();
        } else { // if ($player_id != $squad_member_id)
            
            $squad_items = $this->p_items_s_model->getItemsDetails($player_id, $squad_member_id);
            $data['equipment'] = new Equipment();
            if ($squad_items != null) {
                $data['squad_items'] = $squad_items;
            }
        }
        
        $squad_numbers = $player->getSquadNumbers();
        $data['squad_attack'] = $squad_numbers['attack'];
        $data['squad_defense'] = $squad_numbers['defense'];
        
        
        $this->load->model('modifiers_model');
        $this->load->model('things_model');
        $this->load->model('collection_model');
        $this->load->model('boosts_model');
        $this->load->model("Squad_model");
        $data['squad_members'] = $this->Squad_model->get_squad_members_profile($player_id);
        $data['squad_npc'] = $this->Squad_model->get_squad_npc_profile($player_id);
        $data['player'] = $player;
        $this->load->model('players_model');
        $data['friends'] = $this->players_model->get_friends($player_id);
        $data['items'] = $this->items_model->get_items($player_id);
        $data['inactive_modifiers'] = $player->getInactiveModifiers();
        
        $data['things'] = $this->things_model->get_things($player_id);
        $data['collections'] = $this->collection_model->check_collections($player_id);
        $data['boosts'] = $this->boosts_model->get_boosts($player_id);
        
        
        
        $this->load->view('ajax_profile', $data);
    }
}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */