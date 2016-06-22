<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Team extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $this->loadGlobals($this, $data);
        $header['page_title'] = 'Team';

        $data['content'] = $this->load->view('team', $data, TRUE);

        $this->load->view('header', $header);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

    public function page_team() {
        $this->loadGlobals($this, $data);
        $this->load->view('team_page_team', $data);
    }

    public function pagination_team() {
        $this->loadGlobals($this, $data);
        $this->load->view('team_pagination_team', $data);
    }

}

/* End of file team.php */
/* Location: ./application/controllers/team.php */