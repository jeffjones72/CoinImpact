<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Skill extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $this->loadGlobals($this, $data);
        $data['stamina_rate'] = .5;
        $data['energy_rate'] = 1;
        $data['health_rate'] = 5;
        $data['attack_rate'] = 1;
        $data['defense_rate'] = 1;
        $data['confirm'] = FALSE;

        $data['stat'] = $this->input->post('stat');
        $data['skill_points'] = $this->input->post('points');
        if ($data['stat'])
            $data['confirm'] = TRUE;

        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));

        $player_id = $data['player']->id;
        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);
        $header['data'] = $this->get_counters();

        $data['page_title'] = 'Allocate Skills';

        //echo '<pre>';
        //print_r($data);
        //echo '</pre>';

        $data['content'] = $this->load->view('skill', $data, TRUE);

        $this->load->view('header', $header);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

    public function apply_skill() {
        $player = newPlayer();

        $stat = $this->input->post('stat');
        $points = $this->input->post('points');

        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $data['player'] = $temp;
        $player->spendPoints($stat, $points);
        
        redirect('skill');
    }

}

/* End of file skill.php */
/* Location: ./application/controllers/skill.php */