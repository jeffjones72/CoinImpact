<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Action extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_isvalidated();
    }

    function travel()
    {
        // $this->load->model('players_model');
        // $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        // $player_id = $temp['player_id'];
        
        // $place_id = $this->input->post('place_id');
        // $this->load->model('action_model');
        $player = newPlayer();
        $result = $player->tryTravelTo(new Place($this->input->post('place_id')));
        // $this->action_model->travel($player_id, $place_id);
        
        redirect('explore');
    }

    function training_attack()
    {
        /*
         * /action/training_attack
         *
         * Attacks training target with X amount of stamina.
         *
         * POST
         * $stamina -> the amount of stamina to use on the training target
         *
         * response (json)
         * ok: (boolean) if everything was okay
         * error: (optional, string) if there was an error
         * 'bad_data': POST input for stamina is bad or nonexistent
         * 'no_stamina': player doesn't have enough stamina
         * 'unknown': unknown server error
         * damage: (optional, int) how much damage was dealt
         * xp: (optional, int) how much XP you gained
         */
        
        // Validate the data.
        $stamina = $this->input->post('stamina');
        if (empty($stamina) || ! ctype_digit($stamina)) {
            echo json_encode(array(
                'ok' => FALSE,
                'error' => 'bad_data'
            ));
            return;
        }
        $stamina = (int) $stamina;
        
        // Process the data.
        $user = newPlayer();
        $result = $user->getTrainingDummy()->attack($stamina);
        
        if ($result['status'] == "no_stamina") {
            echo json_encode(array(
                'ok' => FALSE,
                'error' => 'no_stamina'
            ));
        } else 
            if ($result['status'] == "bad_data") {
                echo json_encode(array(
                    'ok' => FALSE,
                    'error' => 'bad_data'
                ));
            } else 
                if ($result['status'] == "ok") {
                    echo json_encode(array(
                        'ok' => TRUE,
                        'damage' => $result['damage'],
                        'xp' => $result['xp']
                    ));
                } else {
                    // never supposed to happen
                    echo json_encode(array(
                        'ok' => FALSE,
                        'error' => 'unknown'
                    ));
                }
    }

    function explore()
    {
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];
        $place_id = $this->input->post('place_id');
        $energy = $temp['energy'];
        $roll = $this->roll(100);
        
        if ($roll > 40) {
            $rarity_level = $this->rarity_roll(5);
            $this->get_combatant($place_id, $rarity_level, $player_id);
        } elseif ($roll > 15) {
            $rarity_level = $this->rarity_roll(5);
            $this->get_trader($place_id, $rarity_level, $player_id);
        } else {
            $rarity_level = $this->rarity_roll(5);
            $this->get_event($place_id, $rarity_level, $player_id);
        }
        
        $this->load->model('action_model');
        $this->action_model->explore($player_id, $energy);
        
        redirect('explore');
    }

    function fight()
    {
        $player = newPlayer();
        if ($player->health < 10) {
            return false;
        }
        $p_combatant = new PlayerCombatant($this->input->post('player_combatant_id'));
        
        $dmg_to_combatant = $player->attack($p_combatant);
        $dmg_to_player = NULL;
        if (! $player->isFighting($p_combatant)) {
            $p_combatant->setFighting();
        }
        if (! $p_combatant->isDead()) {
            $dmg_to_player = $p_combatant->hit($player);
        }
        $player->takeStamina(1);
        ActionO::attackCombatant($p_combatant, $dmg_to_combatant, $dmg_to_player);
        
        redirect('explore');
    }

    public function confirm_combatant()
    {
        $player_combatant_id = $this->input->post('player_combatant_id');
        $date = date("Y-m-d H:i:s", time());
        $arr = array(
            'player_combatant_id' => $player_combatant_id,
            'completed' => $date,
            'active' => 0
        );
        
        $this->load->model('action_model');
        $this->action_model->confirm_combatant($arr);
        
        redirect('explore');
    }

    private function attribute_mod($quality, $attribute)
    {
        switch ($quality) {
            case 1:
                $rand = $this->roll(50, 26) / 100.0;
                $mod = ceil(($attribute * $rand) * - 1);
                break;
            case 2:
                $rand = $this->roll(25, 10) / 100.0;
                $mod = ceil(($attribute * $rand) * - 1);
                break;
            case 3:
                $rand = $this->roll(25, 10) / 100.0;
                $rand2 = $this->roll(2, 1);
                if ($rand2 == 1)
                    $mod = ceil(($attribute * $rand) * - 1);
                else
                    $mod = ceil($attribute * $rand);
                break;
            case 4:
                $rand = $this->roll(25, 10) / 100.0;
                $mod = ceil($attribute * $rand);
                break;
            case 5:
                $rand = $this->roll(50, 26) / 100.0;
                $mod = ceil($attribute * $rand);
                break;
        }
        return $mod;
    }

    function flee()
    {
        $player_combatant_id = $this->input->post('player_combatant_id');
        $this->load->model('action_model');
        
        $base_fail_rate = 10;
        $roll = $this->roll(100);
        
        if ($roll >= $base_fail_rate) {
            $this->action_model->flee($player_combatant_id);
        } else {
            // Flee failed!
        }
        redirect('explore');
    }

    function collect_combatant_item()
    {
        $player_combatant_item_id = $this->input->post('player_combatant_item_id');
        
        $this->load->model('action_model');
        
        $this->action_model->collect_combatant_item($player_combatant_item_id);
        
        redirect('explore');
    }

    function drop_combatant_item()
    {
        $player_combatant_item_id = $this->input->post('player_combatant_item_id');
        
        $this->load->model('action_model');
        
        $this->action_model->drop_combatant_item($player_combatant_item_id);
        
        redirect('explore');
    }

    function collect_combatant_thing()
    {
        $player_combatant_thing_id = $this->input->post('player_combatant_thing_id');
        
        $this->load->model('action_model');
        
        $this->action_model->collect_combatant_thing($player_combatant_thing_id);
        
        redirect('explore');
    }

    /*
     * Ticket #58
     * changed this function with squad members details
     *
     */
    function equip()
    {
        $this->loadGlobals($this, $data);
        $player_id = $this->session->userdata("player_id");
        $player_item_id = $this->input->post('player_item_id');
        $item_id = $this->input->post("item_id");
        $squad_player = explode("_", $this->input->post("squad_player_id"));
        $is_npc = $squad_player[0] == "member" ? 0 : 1;
        $this->load->model("Player_items_squad_model", "pis_model");
        
        $player = newPlayer($player_id);
        
       
        
        if (isset($squad_player[1]) && $squad_player[1] != "" && $squad_player[1] != $player_id) {
            
            $this->pis_model->tryEquip($player_item_id, $player_id, $squad_player[1], $is_npc);
        } else {
            $p_item = new PlayerItem($player_item_id);
            
            if (! $p_item->isValid()) {
                return false;
            }
            
            $player->tryEquip($p_item);
        }
        
        if (isset($squad_player[1])) {
            $squad_member_id = $squad_player[1];
            
            if ($player_id == $squad_member_id) {
//                 $data['squad_attack'] = $player->attack;
//                 $data['squad_defense'] = $player->defense;
                $data['vehicle'] = $player->getVehicle();
                $data['companion'] = $player->getCompanion();
            } else 
                if ($squad_player[0] == "member") {
                    $member_details = $this->db->get_where("players_team", "player_id = {$player_id} and team_player_id = {$squad_member_id} and is_NPC =0")->row();
//                     $data['squad_attack'] = $member_details->attack;
//                     $data['squad_defense'] = $member_details->defense;
                    $data['vehicle'] = $this->pis_model->getBySlotId($player_id, $squad_member_id, 14, 0); // slot_id for vehicle is 14
                    $data['companion'] = $this->pis_model->getBySlotId($player_id, $squad_member_id, 15, 0); // slot_id for companion is 15
                } else 
                    if ($squad_player[0] == "npc") {
                        $member_details = $this->db->get_where("players_team", "player_id = {$player_id} and team_player_id = {$squad_member_id} and is_NPC = 1")->row();
//                         $data['squad_attack'] = $member_details->attack;
//                         $data['squad_defense'] = $member_details->defense;
                        $data['vehicle'] = $this->pis_model->getBySlotId($player_id, $squad_member_id, 14, 1); // slot_id for vehicle is 14
                        $data['companion'] = $this->pis_model->getBySlotId($player_id, $squad_member_id, 15, 1); // slot_id for companion is 15
                    }
        } else {
            $squad_member_id = "";
        }
        
        $this->load->model('items_model');
        if ($player_id != $squad_member_id) {
            $squad_items = $this->pis_model->getItemsDetails($player_id, $squad_member_id);
            
            if ($squad_items != null) {
                $data['squad_items'] = $squad_items;
            } else {
                $data['equipment'] = new Equipment();
            }
        } else {
            
            // $data['squad_items'] = $this->items_model->get_items($player_id);
            $data['squad_items'] = $this->items_model->get_slot_items($player_id);
            $data['equipment'] = $player->getEquipment();
        }
        
        // $items = $this->load->model('items_model');
        $squad_numbers = $player->getSquadNumbers();
        $data['squad_attack'] = $squad_numbers['attack'];
        $data['squad_defense'] = $squad_numbers['defense'];
        
        $this->load->model('modifiers_model');
        
        $this->load->model('things_model');
        $things = $this->things_model->get_things($player_id);
        
        $this->load->model('collection_model');
        $collections = $this->collection_model->check_collections($player_id);
        
        $this->load->model('boosts_model');
        $boosts = $this->boosts_model->get_boosts($player_id);
        
        $this->load->model("Squad_model");
        $data['squad_members'] = $this->Squad_model->get_squad_members_profile($player_id);
        $data['squad_npc'] = $this->Squad_model->get_squad_npc_profile($player_id);
        
        $data['player'] = $player;
        $data['friends'] = $this->players_model->get_friends($player_id);
        $data['items'] = $this->items_model->get_items($player_id);
        $data['inactive_modifiers'] = $player->getInactiveModifiers();
        
        $data['things'] = $this->things_model->get_things($player_id);
        $data['collections'] = $this->collection_model->check_collections($player_id);
        $data['boosts'] = $this->boosts_model->get_boosts($player_id);
        $this->load->model('stats_model');
        $data['stats'] = $this->stats_model->get($player_id);
        // echo "ajax_profile";
        $this->load->view('ajax_profile', $data);
    }

    function loadProfile($squad_member = null)
    {
        
        /**
         * $this->load->model('things_model');
         * $things = $this->things_model->get_things($player_id);
         *
         * $this->load->model('collection_model');
         * $collections = $this->collection_model->check_collections($player_id);
         *
         * $this->load->model('boosts_model');
         * $boosts = $this->boosts_model->get_boosts($player_id);
         *
         * $this->load->model("Squad_model");
         * $data['squad_members'] = $this->Squad_model->get_squad_members_profile($player_id);
         * $data['squad_npc'] = $this->Squad_model->get_squad_npc_profile($player_id);
         *
         * $data['player'] = $player;
         * $data['friends'] = $this->players_model->get_friends($player_id);
         * $data['items'] = $this->items_model->get_items($player_id);
         * $data['inactive_modifiers'] = $player->getInactiveModifiers();
         *
         * $data['things'] = $this->things_model->get_things($player_id);
         * $data['collections'] = $this->collection_model->check_collections($player_id);
         * $data['boosts'] = $this->boosts_model->get_boosts($player_id);
         * // echo "ajax_profile";
         * $this->load->view('ajax_profile', $data);
         */
        
        // $post_data = explode("_", $this->input->post("user_id"));
        $player_id = $this->session->userdata("player_id");
        $squad_member_array = explode("_", $squad_member);
        $squad_member_id = $squad_member_array[1];
        $player = new Player($player_id);
        $this->load->model('items_model');
        if ($player_id == $squad_member_id) {
            
            $data['squad_items'] = $this->items_model->get_items($player_id);
            
            $data['equipment'] = $player->getEquipment();
            // redirect("profile");
        } 

        else { // if ($player_id != $squad_member_id)
            $this->load->model("Player_items_squad_model", "p_items_s_model");
            $squad_items = $this->p_items_s_model->getItemsDetails($player_id, $squad_member_id);
            
            if ($squad_items != null) {
                $item_ids = array();
                foreach ($squad_items as $item) {
                    $item_ids[] = $item->item_id;
                }
                $data['squad_items'] = $squad_items;
            } else {
                $data['equipment'] = new Equipment();
            }
        }
        
        $user_id = $this->session->userdata('id');
        $this->loadGlobals($this, $data);
        $this->load->model('accounts_model');
        $account = $this->accounts_model->get_account_info($user_id);
        
        $this->load->model('players_model');
        // if ($this->input->post('drop_id')) {
        // $p_item = new PlayerItem($this->input->post('drop_id'));
        // $player->drop($p_item);
        // }
        // $player_id = $player->id;
        $friends = $this->players_model->get_friends($player_id);
        
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
        $this->load->model("Squad_model");
        $data['squad_members'] = $this->Squad_model->get_squad_members_profile($player_id);
        $data['squad_npc'] = $this->Squad_model->get_squad_npc_profile($player_id);
        $data['account'] = $account;
        $data['player'] = $player;
        $data['friends'] = $friends;
        $data['items'] = $items;
        $data['inactive_modifiers'] = $inactive_modifiers;
        $data['things'] = $things;
        $data['collections'] = $collections;
        $data['boosts'] = $boosts;
        $header['data'] = $this->get_counters();
        // var_dump($data['squad_items']);
        $this->load->view('ajax_profile', $data);
    }

    function unequip()
    {
        $player_item_id = $this->input->post('player_items_squad_id'); // item id from player_items or player_items_squad table
        $squad_player_id = explode("_", $this->input->post('squad_player_id'));
        
        $player_id = $this->session->userdata("player_id");
        $player = newPlayer($player_id);
        
       
        
        // data for the view squad_attack and squad_defense
        $this->load->model("Player_items_squad_model", "pis_model");
        
        if ($player_id == $squad_player_id[1] && $squad_player_id[0] == "member") {
            $p_item = new PlayerItem($player_item_id);
            if (! $p_item->isValid()) {
                return false;
            }
            
            $player->unequip($p_item);
        } else {
            // unequip squad member and give back to item player inventory
            // remove stats from squad member
            
            // $squad_details = explode("_", $squad_player_id);
            if ($squad_player_id[0] == "member") {
                $is_npc = 0;
            } else {
                $is_npc = 1;
            }
            
            $set = array(
                "slot_id" => "null"
            );
            $squad_item = $this->pis_model->getById($player_item_id);
            if ($squad_item != null) {
                $this->pis_model->update_players_inventory($squad_item->player_item_id, $set);
                $unequip_result = $this->pis_model->unequip(array(
                    "id" => $player_item_id
                ));
                
                if ($unequip_result == false) {
                    echo "Error";
                    die();
                }
            }
        }
        
        if ($player_id == $squad_player_id[1]) {
//             $data['squad_attack'] = $player->attack;
//             $data['squad_defense'] = $player->defense;
            $data['vehicle'] = $player->getVehicle();
            $data['companion'] = $player->getCompanion();
        } elseif ($squad_player_id[0] == "member") {
            $member_details = $this->db->get_where("players_team", "player_id = {$player_id} and team_player_id = {$squad_player_id[1]} and is_NPC =0")->row();
//             $data['squad_attack'] = $member_details->attack;
//             $data['squad_defense'] = $member_details->defense;
            $data['vehicle'] = $this->pis_model->getBySlotId($player_id, $squad_player_id[1], 14, 0); // slot_id for vehicle is 14
            $data['companion'] = $this->pis_model->getBySlotId($player_id, $squad_player_id[1], 15, 0); // slot_id for companion is 15
        } elseif ($squad_player_id[0] == "npc") {
            $member_details = $this->db->get_where("players_team", "player_id = {$player_id} and team_player_id = {$squad_player_id[1]} and is_NPC = 1")->row();
//             $data['squad_attack'] = $member_details->attack;
//             $data['squad_defense'] = $member_details->defense;
            $data['vehicle'] = $this->pis_model->getBySlotId($player_id, $squad_player_id[1], 14, 1); // slot_id for vehicle is 14
            $data['companion'] = $this->pis_model->getBySlotId($player_id, $squad_player_id[1], 15, 1); // slot_id for companion is 15
        }
        
        // data for the view
        $this->load->model('items_model');
        if ($player_id != $squad_player_id[1]) {
            $this->load->model("Player_items_squad_model", "p_items_s_model");
            $squad_items = $this->pis_model->getItemsDetails($player_id, $squad_player_id[1]);
            
            if ($squad_items != null) {
                $data['squad_items'] = $squad_items;
            } else {
                $data['equipment'] = new Equipment();
            }
        } else {
            
            $data['squad_items'] = $this->items_model->get_slot_items($player_id);
            $data['equipment'] = $player->getEquipment();
        }
        
        // $items = $this->load->model('items_model');
        
        // var_dump($data['squad_items']);
        $this->load->model('modifiers_model');
        
        $this->load->model('things_model');
        
        $this->load->model('collection_model');
        
        $this->load->model('boosts_model');
        
        $this->load->model("Squad_model");
        $data['squad_members'] = $this->Squad_model->get_squad_members_profile($player_id);
        
        $squad_numbers = $player->getSquadNumbers();
        $data['squad_attack'] = $squad_numbers['attack'];
        $data['squad_defense'] = $squad_numbers['defense'];
        
        $data['squad_npc'] = $this->Squad_model->get_squad_npc_profile($player_id);
        
        $data['player'] = $player;
        $this->load->model('players_model');
        $data['friends'] = $this->players_model->get_friends($player_id);
        $data['items'] = $this->items_model->get_items($player_id);
        $data['inactive_modifiers'] = $player->getInactiveModifiers();
        
        $data['things'] = $this->things_model->get_things($player_id);
        $data['collections'] = $this->collection_model->check_collections($player_id);
        $data['boosts'] = $this->boosts_model->get_boosts($player_id);
        $this->load->model('stats_model');
        $data['stats'] = $this->stats_model->get($player_id);
        // echo "ajax_profile";
        $this->load->view('ajax_profile', $data);
    }

    function unequipslot($slot_id)
    {
        $this->load->model('action_model');
        $this->action_model->unequipslot($slot_id);
        redirect('profile');
    }

    function drop()
    {
        $player_item_id = $this->input->post('player_item_id');
        
        $this->load->model('action_model');
        $this->action_model->drop($player_item_id);
        
        redirect('profile');
    }

    function drop_thing()
    {
        $player_thing_id = $this->input->post('player_thing_id');
        
        $this->load->model('action_model');
        $this->action_model->drop_thing($player_thing_id);
        
        redirect('profile');
    }
    
    function sell_item()
    {
        $player_id = $this->input->post('player_id');
        $player_item_id = $this->input->post('player_item_id');
        
        $this->load->model('action_model');
        $amount = $this->action_model->sell_item($player_id,$player_item_id);
        
        if($amount > 0)
        {
            $this->action_model->add_balance($player_id, $amount); // Add amount to players table
            $this->drop($player_item_id);
        }
        
    }
    
    function store_item()
    {
        $player_id = $this->input->post('player_id');
        $player_item_id = $this->input->post('player_item_id');
        
        $this->load->model('action_model');
        $this->action_model->store_item($player_id,$player_item_id);
    }
    
    function retrieve_item()
    {
        $player_id = $this->input->post('player_id');
        $player_item_id = $this->input->post('player_item_id');
        
        $this->load->model('action_model');
        $this->action_model->retrieve_item($player_id,$player_item_id);
    }
    
    function sell_thing()
    {
        $player_id = $this->input->post('player_id');
        $player_thing_id = $this->input->post('player_thing_id');
        
        $this->load->model('action_model');
        $amount = $this->action_model->sell_thing($player_id,$player_thing_id);
        
        if($amount > 0)
        {
            $this->action_model->add_balance($player_id, $amount); // Add amount to players table
            $this->drop_thing($player_thing_id);
        }        
        
    }

    function enable_modifier()
    {
        $modifier_id = $this->input->post('modifier_id');
        $player_item_id = $this->input->post('player_item_id');
        
        $this->load->model('action_model');
        $this->action_model->enable_modifier($modifier_id, $player_item_id);
        
        redirect('profile');
    }

    function drop_modifier()
    {
        $modifier_id = $this->input->post('modifier_id');
        
        $this->load->model('action_model');
        $this->action_model->drop_modifier($modifier_id);
        
        redirect('profile');
    }

    function accept_trader()
    {
        // $this->output->enable_profiler(TRUE);
        $user_id = $this->session->userdata('id');
        
        $this->load->model('players_model');
        $player = $this->players_model->get_player_info($user_id);
        
        $player_id = $player['player_id'];
        
        $player_trader_id = $this->input->post('player_trader_id');
        $this->load->model('action_model');
        
        $this->action_model->accept_trader($player_trader_id, $player_id);
        
        $trader = $this->action_model->get_trader_details($player_trader_id);
        
        /**
         * CI:B0211
         * calculates the trust bar
         */
        
        $oop_player = new Player($player_id);
        $max_amount = 350; // the max amount when calculate the trust bar
        $data['percent'] = $oop_player->calcTrustProgress($trader['cost'], $max_amount);
        
        // $this->players_model ->updatePlayer($player_id, $update);
        redirect('explore', $data);
    }

    function ignore_trader()
    {
        $player_trader_id = $this->input->post('player_trader_id');
        $this->load->model('action_model');
        
        $this->action_model->ignore_trader($player_trader_id);
        
        redirect('explore');
    }

    function confirm_event()
    {
        $player_event_id = $this->input->post('player_event_id');
        $this->load->model('action_model');
        
        $this->action_model->confirm_event($player_event_id);
        
        redirect('explore');
    }

    function assemble_collection()
    {
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $player_id = $temp['player_id'];
        
        $collected_item_id = $this->input->post('item_id');
        
        $this->load->model('collection_model');
        $success = $this->collection_model->assemble($player_id, $collected_item_id);
        
        if ($success)
            redirect('profile');
        else
            redirect('profile');
    }

    private function get_combatant($place_id, $rarity_level, $player_id)
    {
        $this->load->model('action_model');
        $this->action_model->get_combatant($place_id, $rarity_level, $player_id);
    }

    function display()
    {
        $rarity1 = 0;
        $rarity2 = 0;
        $rarity3 = 0;
        $rarity4 = 0;
        $rarity5 = 0;
        $rolls = 100000;
        
        for ($i = 0; $i < $rolls; $i ++) {
            $rarity = $this->rarity_roll(5);
            
            switch ($rarity) {
                case 1:
                    $rarity1 ++;
                    break;
                case 2:
                    $rarity2 ++;
                    break;
                case 3:
                    $rarity3 ++;
                    break;
                case 4:
                    $rarity4 ++;
                    break;
                case 5:
                    $rarity5 ++;
                    break;
            }
        }
        // echo 'Rarity 1: ' . $rarity1 . ' (' . ($rarity1/$rolls) * 100 . '% and roughly ' . (($rarity1/$rolls) * 100) / 5 . '% chance of selection)<br />';
        // echo 'Rarity 2: ' . $rarity2 . ' (' . ($rarity2/$rolls) * 100 . '% and roughly ' . (($rarity2/$rolls) * 100) / 5 . '% chance of selection)<br />';
        // echo 'Rarity 3: ' . $rarity3 . ' (' . ($rarity3/$rolls) * 100 . '% and roughly ' . (($rarity3/$rolls) * 100) / 5 . '% chance of selection)<br />';
        // echo 'Rarity 4: ' . $rarity4 . ' (' . ($rarity4/$rolls) * 100 . '% and roughly ' . (($rarity4/$rolls) * 100) / 5 . '% chance of selection)<br />';
        // echo 'Rarity 5: ' . $rarity5 . ' (' . ($rarity5/$rolls) * 100 . '% and roughly ' . (($rarity5/$rolls) * 100) / 5 . '% chance of selection)<br /><br />';
        // echo 'Based on ' . $rolls . ' rolls.<br />';
        // echo anchor('action/display','Roll again...');
    }

    private function get_trader($place_id, $rarity_level, $player_id)
    {
        $this->load->model('action_model');
        $this->action_model->get_trader($place_id, $rarity_level, $player_id);
    }

    private function get_event($place_id, $rarity_level, $player_id)
    {
        $this->load->model('action_model');
        $this->action_model->get_event($place_id, $rarity_level, $player_id);
    }

    public function roll($sides, $start = 1)
    {
        return mt_rand($start, $sides);
    }

    private function rarity_roll($rarity)
    {
        return mt_rand(1, mt_rand(1, $rarity));
    }
}

/* End of file action.php */
/* Location: ./application/controllers/action.php */