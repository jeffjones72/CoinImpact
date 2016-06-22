<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $this->loadGlobals($this, $data);
        $this->load->model('players_model');
        $this->load->model('items_model');
        $this->load->model('modifiers_model');
        $this->load->model('things_model');
        $this->load->model('boosts_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $data['player'] = $temp;
        $items = $this->items_model->get_items();
        $item_count = $this->items_model->get_item_count();
        $modifiers = $this->modifiers_model->get_modifiers();
        unset($modifiers['unassigned']);
        $modifier_count = $this->modifiers_model->get_modifier_count();
        $things = $this->things_model->get_things();
        $thing_count = $this->things_model->get_thing_count();
        $boosts = $this->boosts_model->get_boosts();
        $boost_count = $this->boosts_model->get_boost_count();

        $data ['page_title'] = 'Items';

        $data['item_count'] = $item_count;
        $data['modifier_count'] = $modifier_count;
        $data['thing_count'] = $thing_count;
        $data['boost_count'] = $boost_count;
        $data['items'] = $items;
        $data['modifiers'] = $modifiers;
        $data['things'] = $things;
        $data['boosts'] = $boosts;

        //print '<pre>';
        //print_r($data);
        //print '<pre>';

        $data['content'] = $this->load->view('items', $data, TRUE);

        $this->load->view('header', $data);
        $this->load->view('index', $data);
        $this->load->view('footer', $data);
    }

    private function check_isvalidated() {
        if (!$this->session->userdata('validated')) {
            redirect('login');
        } else {
            $this->load->model('accounts_model');
            $user = $this->accounts_model->get_account_info($this->session->userdata('id'));
            $result = $this->accounts_model->update_last_login($user);
        }
    }

}

/* End of file items.php */
/* Location: ./application/controllers/items.php */