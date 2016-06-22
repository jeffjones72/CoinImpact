<?php
/*
 * Ticket #58
 */
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Team extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_isvalidated();
//         $sections = array(
//             'config' => TRUE,
//             'queries' => TRUE
//         );
//         $this->output->enable_profiler(TRUE);
//         $this->output->set_profiler_sections($sections);
    }

    public function index()
    {
        $this->loadGlobals($this, $data);
        $this->load->library('pagination');
        $player = new Player($data['player']->id);
        
        $data['squad_members'] = $player->get_squad_members();
        
        
        $where = "id !={$data['player']->id}";
        $count_players = count($player->get_team_players());

        $rsegment_array = $this->uri->ruri_to_assoc();

        if (isset($rsegment_array['order_team'])) {

            $order_team = "";
            switch ($rsegment_array['order_team']) {
                case "favorite":
                    $order_team = "favorite desc ";
                    break;
                case "power":
                    $order_team = "power desc";
                    break;
                case "newest":
                    $order_team = "create_date desc";
                    break;
                case "level":
                    $order_team = "level_id desc ";
                    break;
                default:
                    $order_team = "create_date desc";
                    break;
            }
        }

        $data['count_players'] = $count_players;

        if (isset($rsegment_array['order_team'])) {
            $pag['uri_segment'] = 4;
            $pag['base_url'] = base_url("team") . "/order_team/{$rsegment_array['order_team']}";
            $page = $this->uri->segment(4, 1);
        } else {
            $pag['uri_segment'] = 2;
            $pag['base_url'] = base_url("team");
            $page = $this->uri->segment(2, 1);
        }
        // $pag['uri_segment'] = 4;
        $pag['total_rows'] = $count_players;
        $pag['per_page'] = 8;

        $pag['display_pages'] = false;
        $pag['full_tag_open'] = "<div class='page-button-container'>";
        $pag['full_tag_close'] = "</div >";

        $pag['first_link'] = false;
        $pag['last_link'] = false;
        $pag['prev_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowLeft.png'>";
        $pag['next_link'] = "<img class='page-button' src='" . base_url() . "_images/arrowRight.png'>";
        $this->pagination->initialize($pag);
        $data['pagination'] = $this->pagination->create_links();

        $data['start_item'] = $page == 1 ? 0 : $page + 1;
        $data['last_item'] = ($pag['per_page'] + $page) > $count_players ? $count_players : ($pag['per_page'] + $page);

        if (isset($rsegment_array['order_team'])) {

            $all_players_team = $player->get_team_players($order_team); // , $pag['per_page'], $page -1);
        } else {
            $all_players_team = $player->get_team_players("create_date desc"); // , $pag['per_page'], $page -1);
        }
        // echo "<pre>";
        // var_dump($page);

        // echo "</pre>";
        if ($page == 1) {
            $start = 0;
        } else {
            $start = $page;
        }
        $players_team = array_slice($all_players_team, $start, $pag['per_page']);

        if ($players_team == 0) {
            $data['players'] = array();
        } else {
            $data['players'] = $players_team;
            $this->load->model("Items_model");
            foreach ($data['players'] as $i => $one) {
                $data['players'][$i]->itemsI = $this->Items_model->get_players_items($one->p_id);
            }
        }

        // team requests
        if (isset($rsegment_array['sort'])) {
            $order_requests_by = $rsegment_array['sort'];
        } else {
            $order_requests_by = "create_date desc";
        }
        switch ($order_requests_by) {
            case "date_asc":
                $order_by = "create_date asc";
                break;
            case "date_desc":
                $order_by = "create_date desc";
                break;
            case "attack_asc":
                $order_by = "attack asc";
                break;
            case "attack_desc":
                $order_by = "attack desc";
                break;
            case "defense_asc":
                $order_by = "defense asc";
                break;
            case "defense_desc":
                $order_by = "defense desc";
                break;
            default:
                $order_by = "create_date desc";
                break;
        }
        $data['team_request'] = $player->get_team_requests($order_by);

        // squad_team members
        // $data['squad_members'] = $player->get_team_players(null, null, null, "players_team.squad_id != 0");

       
        // print "<pre>";
        // print_r($data['squad_members'] );
        // die();
        // var_dump($data['squad_members']);
        // team invite

        $data['has_requests'] = $player->has_team_requests();

        $data['player_code'] = $player->get_share_id();

        $data['content'] = $this->load->view('team', $data, TRUE);
        $header['page_title'] = 'Team';
        $header['data'] = $this->get_counters();
        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

    public function page_team()
    {
        $this->loadGlobals($this, $data);

        $this->load->view('team_page_team', $data);
    }

    /*
     * Ticket #58
     * accept team request
     */
    public function add_in_team()
    {
        $id = $this->input->post("id");
        if ($id == false) {
            redirect("team");
        }
        $this->loadGlobals($this, $data);
        // $activation_code = generateActivationCode($data['player']->id);
        // $data['add_to_squad_link'] = base_url() . "?code={$activation_code}";

        try {
            $player = new Player($data['player']->id);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $result_add = $player->accept_team_request($id);

        return $result_add;
    }

    /*
     * Ticket #58
     */
    public function remove_from_team()
    {
        $team_player_id = $this->uri->segment(3, 0);
        if ($team_player_id == 0) {
            redirect("team");
        }
        $this->loadGlobals($this, $data);
        $player = new Player($data['player']->id);
        $result = $player->remove_from_team($team_player_id);
        if ($result) {
            redirect("team");
        }
    }

    /*
     * Ticket #58
     */
    public function add_to_squad()
    {
        $this->loadGlobals($this, $data);
        $team_player_id = $this->input->post("id");
        $this->load->model("Squad_model");
        $slots = $this->Squad_model->count_slots($data['player']->id);

        $message = "";

        $occupied_slots = $this->Squad_model->count_slots($data['player']->id);
        if ($occupied_slots >= 5) {
            $message .= "All 5 squad slots are occupied. You first have to remove a player and then add another one. ";
        }  // $free_slots=$this->Squad_model->try_add_squad($data['player']->id, $team_player_id);
else
            if ($team_player_id != 0) {
                $insert_id = $this->Squad_model->try_add_squad($data['player']->id, $team_player_id);
                // var_dump($insert_id);die();
                if (is_bool($insert_id) && $insert_id == true) {
                    $message .= "Succesfully added to your squad";
                } else {
                    $message .= "You can't add this player";
                }
            } else {
                $message .= "Incorrect team player id";
            }
        echo $message;
    }

    /*
     * Ticket #58
     */
    public function remove_from_squad()
    {
        $team_player_id = $this->uri->segment(3, 0);
        $player_id = $this->session->userdata('player_id');
        if ($team_player_id == 0) {
            redirect("team");
        }
     
        $this->load->model("Squad_model");
      if(  $this->Squad_model->remove_from_squad($player_id, $team_player_id)){
          redirect("team");
      }else{
         $message = "Ooops! There is an error...";
         echo $message;
      }
      //echo $message;
    }

    public function team_favorite()
    {
        $team_player_id = $this->input->post("user_id", true);
        $fav = $this->input->post("fav", true);
        $this->loadGlobals($this, $data);

        $this->load->model("Squad_model");

        $res = $this->Squad_model->team_player_favorite($data['player']->id, $team_player_id);
        $str = $this->db->last_query();

        if ($res) {
            if ($fav == 1) {
                return false;
            } else
                if ($fav == 0) {
                    return true;
                }
        }
    }

    public function team_invite()
    {
        $this->loadGlobals($this, $data);
        $player = new Player($data['player']->id);

        $data['has_requests'] = $player->has_team_requests();
        $data['players'] = $player->get_free_players();
        $data['content'] = $this->load->view('team_invite', $data, TRUE);
        $header['page_title'] = 'Add team players';
        $this->load->view('header', $header);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

    public function ajax_team_invite()
    {
        $this->loadGlobals($this, $data);
        $player = new Player($data['player']->id);

        $user_name = $this->input->post("user_name", true);
        $user_id = $this->input->post("user_id", true);
        if ($user_id != false) {
            $result = $player->try_add_team_player($user_id);
        }
        $data['has_requests'] = $player->has_team_requests();
        $data['players'] = $player->get_free_players();

        $this->load->view('ajax_team_invite', $data);
    }

    public function send_request()
    {
        //$this->loadGlobals($this, $data);
        $player_id = $this->session->userdata('player_id');
        $player = new Player($data['player']->id);
        $share_id = $this->input->get("share_id");

        // $user_name=explode(" ", $this->input->get("name"));
        // var_dump($user_name);
        // $level_id=$this->input->get("level");
        // echo $share_id." {$user_name} {$level_id}";
        // eam_player_id=$player->getArrById($id)
        // $where= array("share_id"=>$share_id, "players.first_name"=>$user_name[0], "players.last_name"=>$user_name[1]);

       //var_dump($share_id);die();
        if ( $share_id != FALSE) {
//             $where = array(
//                 "share_id like" => $share_id
//             );
            $where = "share_id like '%{$share_id}%'";
            $team_player = $player->get_where($where);
            $name = $player->getUsernameByID($team_player[0]->id);

            if ($name == "") {
                $name = "{$team_player[0]->first_name} {$team_player[0]->last_name}";
            }
            if (count($team_player)) {
                $team_player_id = $team_player[0]->id;
                $result_add = $player->try_add_team_player($team_player_id);
                if ($result_add != null) {
                    $data['message'] = "You have successfully sent an invite request to  {$name} .";
                } else {
                    $data['message'] = " {$name} was already on your team or you have a pending request.";
                }
            } else {
                $data["message"] = "This code is not correct. Try again.";
            }
        }else{
            $data['message']="You must type the code first.";
        }
        // var_dump($team_player);
        $data['content'] = $this->load->view('send_request', $data, TRUE);
        $header['page_title'] = 'Add team players';
        $this->load->view('header', $header);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

    public function ajax_team_request()
    {
        $this->loadGlobals($this, $data);
        $player = new Player($data['player']->id);

        $share_id = $this->input->post("share_id");

        // $user_name=explode(" ", $this->input->post("name"));
        // $level_id=$this->input->post("level");

        if($share_id==false){
            echo "You have to add a code first";
            exit();
        }
        $where = array(
            "share_id" => $share_id
        );

        $team_player = $player->get_where($where);

        $name = $player->getUsernameByID($team_player[0]->id);

        if ($name == "") {
            $name = "{$team_player[0]->first_name} {$team_player[0]->last_name}";
        }

        if (count($team_player)) {
            $team_player_id = $team_player[0]->id;
            $result_add = $player->try_add_team_player($team_player_id);
            if ($result_add != null) {
                echo "You have successfully sent an invite request to {$name}.";
            } else {
                echo "{$name} was already on your team or you have a pending request.";
            }
        } else {
            echo "This code is not correct. Try again.";
        }
    }

    /**
     * ALTER TABLE `players` ADD UNIQUE (`share_id`)
     */
    public function generate_share()
    {
        $players = $this->db->get("players")->result();
        foreach ($players as $player) {
            $share_id = generateRandomString();
            $this->db->update("players", array(
                "share_id" => $share_id
            ), "id = {$player->id}");
        }
    }
}

/* End of file team.php */
;
/* Location: ./application/controllers/team.php */