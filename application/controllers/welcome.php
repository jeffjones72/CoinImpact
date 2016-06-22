<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $this->load->model('user');
        $user = $this->user->get_user_info($this->session->userdata('id'));
        //$users = $this->accounts->get_users();

        $this->load->view('header', $user);
        $this->load->view('index', $user);
        $this->load->view('footer', $user);
    }

    private function check_isvalidated() {
        if (!$this->session->userdata('validated')) {
            redirect('login');
        } else {
            $this->load->model('user');
            $user = $this->user->get_user_info($this->session->userdata('id'));
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */