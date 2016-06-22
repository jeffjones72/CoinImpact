<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Squad_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function team_player_favorite($player_id, $teamplayer_id)
    {
        $where = "player_id = {$player_id} and team_player_id = {$teamplayer_id}";
        $result = $this->db->get_where("players_team", $where)->row();
        $reccord_id = $result->id;
        
        if (count($result)) {
            if ($result->favorite == "0") {
                $fav = 1;
            } else 
                if ($result->favorite == "1") {
                    $fav = 0;
                }
            $this->db->where("id", $reccord_id);
            $this->db->update("players_team", array(
                "favorite" => $fav
            ));
            
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function get_free_slots($player_id)
    {
        $available_slots = array(
            "1",
            "2",
            "3",
            "4",
            "5"
        );
        $this->db->select("slot_number");
        
        $this->db->order_by("slot_number  asc");
        
        $query = $this->db->get_where("players_squad", array(
            "player_id" => $player_id
        ));
        $squads_slots = $query->result_array();
        $occupied_slots = array();
        foreach ($squads_slots as $slot) {
            $occupied_slots[] = $slot["slot_number"];
        }
        $free_slots = array_diff($available_slots, $occupied_slots);
        return $free_slots;
    }

    public function count_slots($player_id)
    {
        return $this->db->from("players_team")
            ->where(array(
            "player_id" => $player_id,
            "squad_id != " => "0"
        ))
            ->count_all_results();
    }

    public function try_add_squad($player_id, $team_player_id, $is_npc = null)
    {
        $occupied_slots = $this->count_slots($player_id);
        if ($occupied_slots >= 5) {
            return - 1;
        } else {
            $slot_id = $occupied_slots + 1;
            $date = date('Y-m-d H:i:s');
            
            $update_array = array(
                "squad_id" => "1",
                "squad_create_date" => $date
            );
            if (isset($is_npc) && $is_npc != 0) {
                $update_array["is_NPC"] = "1";
            }
            $where = array(
                "player_id" => $player_id,
                "team_player_id" => $team_player_id
                
            );
            $this->db->update("players_team", $update_array, $where);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function remove_from_squad($player_id, $team_player_id)
    {
        $update_array = array(
            "squad_id" => 0,
            "squad_create_date" => "",
            "health"=>0, 
            "energy" => 0,
            "stamina"=> 0,
            "attack"=> 0,
            "defense"=>0,
            "luck" =>0, 	
            "dodge"=>0, 	
            "strike" => 0, 	
            "strike_boost" => 0, 	
            "damage_boost"=>0
        );
        $where = array(
            "player_id" => $player_id,
            "team_player_id" => $team_player_id
        );
        $this->db->update("players_team", $update_array, $where);
        
        if ($this->db->affected_rows()) {
            
            // get all items from $team_player_id and give back to $player_id 's inventory
            $where = array(
                "player_id" => $player_id,
                "squad_player_id" => $team_player_id
            );
            $player_items = $this->db->get_where("player_items_squad", $where)->result();
            
            $update_items = array();
            foreach ($player_items as $item) {
                $update_items[] = $item->player_item_id;
                $this->db->delete("player_items_squad", array(
                    "id" => $item->id
                ));
            }
            if (count($update_items)) {
                $items = implode(", ", $update_items);
                $this->db->update("player_items", "slot_id = null", "id in ({$items})");
            }
            return true;
        } else {
            return false;
        }
    }

    public function get_squad_members($player_id)
    {
        $now = new DateTime() ;
        //var_dump($now);
        
        $where = array(
            "player_id" => $player_id,
            "activation_key" => "",
            "squad_id != " => "0"
        );
        $query = $this->db->get_where("players_team", $where);
        $results = $query->result();
        $team_players_id = array();
        if (count($results) == 0) {
            return array();
        }
        foreach ($results as $i => $team_player) {
            
            $team_players_id[] = $team_player->team_player_id;
        }
        $id_string = implode(", ", $team_players_id);
        
        $this->db->_protect_identifiers = false;
        
        $this->db->select("players.* , players.id as p_id, players.attack as p_attack, players.defense as p_defense, players.energy as p_energy,  accounts.*, accounts.id as acc_id, accounts.username as acc_username,  players_team.*, ranks.*, ranks.id as r_id,  (players.attack+ players.defense*0.25)  as power ");
        
        $this->db->where("players.id in ({$id_string})");
        $this->db->join("accounts", "players.account_id = accounts.id", "left");
        $this->db->join("ranks", "players.rank_id = ranks.id", "left");
        $this->db->join("players_team", "players.id = players_team.team_player_id ", "left");
        $this->db->group_by("p_id");
        
        $query = $this->db->get("players");
        
        $players_team = $query->result();
        
        foreach ($results_players as $player){
            $player->diff = $now->diff(new DateTime($player->squad_create_date));
        }
        
        
        return $players_team;
    }

    public function get_squad_members_profile($player_id)
    {
        
        // get players
        //squad_create_date < 5 days
       
        $where = array(
            "player_id" => $player_id,
            "activation_key" => "",
            "squad_id != " => "0",
            "is_NPC" => "0",
            
        );
//         $datetime1 = date_create('2009-10-11');
//         $datetime2 = date_create('2009-10-13');
        
        
        
        $this->db->select("players.*, players.id as p_id ,accounts.*, accounts.id as account_id, accounts.username as account_username");
        $this->db->join("players", "players_team.team_player_id = players.id");
        $this->db->join("accounts", "accounts.id = players.account_id");
        $query = $this->db->get_where("players_team", $where);
        $results_players = $query->result();
       
        return $results_players;
    }

    public function get_squad_npc_profile($player_id)
    {
        // get NPC
        $where = array(
            "player_id" => $player_id,
            "activation_key" => "",
            "squad_id != " => "0",
            "is_NPC" => "1"
        );
        $this->db->select("squad_NPC.*, squad_NPC.acc_username as  account_username ");
        $this->db->join("squad_NPC", "players_team.team_player_id = squad_NPC.id");
        $query = $this->db->get_where("players_team", $where);
        $results_npc = $query->result();
        return $results_npc;
    }

    public function add_squad_npc($player_id, $team_player_id)
    {
        $occupied_slots = $this->count_slots($player_id);
        if ($occupied_slots >= 5) {
            return false;
        } else {
            $slot_id = $occupied_slots + 1;
            $date = date('Y-m-d H:i:s');
            // test if this NPC is already added
            $where = array(
                "player_id" => $player_id,
                "team_player_id" => $team_player_id,
                "squad_id != " => "0"
            );
            $query = $this->db->get_where("players_team", $where);
            if (count($query->result())) {
                return false;
            }
            
            $data = array(
                "player_id" => $player_id,
                "team_player_id" => $team_player_id,
                "is_NPC" => "1",
                "squad_create_date" => $date,
                "squad_id" => 1
            );
            $this->db->insert("players_team", $data);
            if ($this->db->insert_id()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * used to add npc to squad when it appears in $part_id (e.g.
     * part_id = 1 is intro, part_id = 2 in town 2 and so on)
     * 
     * @param unknown $part_id
     *            part number where NPC appears
     * @param unknown $player_id            
     */
    public function add_npc_squad_part($part_id, $player_id)
    {
        $occupied_slots = $this->count_slots($player_id);
        if ($occupied_slots >= 5) {
            return false;
        } else {
            $slot_id = $occupied_slots + 1;
            $date = date('Y-m-d H:i:s');
            // test if this NPC is already added
            // get npc from squad_NPC available for part_id
            $ids_array = $this->db->select("id")
                ->from("squad_NPC")
                ->where("level_id = {$part_id} and alive = 1")
                ->get()
                ->result();
      //      var_dump($ids_array);
            foreach ($ids_array as $id) {
                //var_dump($id);
                // $ids_list = implode(", ", $id);
                // var_dump($id->id);die();
                $team_player_id = $id->id;
                $where = "player_id = {$player_id} and team_player_id = {$team_player_id} and squad_id != 0 and is_NPC = 1";
                $query = $this->db->get_where("players_team", $where)->result();
                if (! count($query)) {
                    $data = array(
                        "player_id" => $player_id,
                        "team_player_id" => $team_player_id,
                        "is_NPC" => "1",
                        "squad_create_date" => $date,
                        "squad_id" => 1
                    );
                    $this->db->insert("players_team", $data);
                    if($this->db->insert_id() == 0 || $this->db->insert_id() == false){
                        return false;
                    }
                }
            }
            return true;
        }
    }
}