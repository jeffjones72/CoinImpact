<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Base extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $data = array();
        $account = new AccountO($this->session->userdata('id'));
        $player = Player::getByAccountID($this->session->userdata('id'));
        $this->loadGlobals($this, $header);
        $data = $header;
        $header ['page_title'] = 'Account Info';
        $header['data'] = $this->get_counters();

        //Place holder for featured premium items
        $data['premium'] = array(
            'id' => 67,
            'name' => 'Chesty Puller\'s KA-BAR',
            'description' => 'The fighting knife used by a Legendary Marine',
            'premium_price' => 25
        );
        $data['cache'] = new Cache(1);
        $data['account'] = $account;
        $header['player'] = $data['player'] = $player;
        $this->load->view('header', $header);
        $data['content'] = $this->load->view('main', $data, TRUE);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

}

/* End of file base.php */
/* Location: ./application/controllers/base.php */