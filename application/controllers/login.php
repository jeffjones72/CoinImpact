<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['page_title'] = 'Login';
        $data['just_registered'] = $this->session->userdata("just_registered");
        $data['login_error'] = $this->session->userdata("login_error");
        $this->session->unset_userdata("just_registered");
        $this->session->unset_userdata("login_error");
        $this->load->view('header_no_user', $data);
        $data['content'] = $this->load->view('login', '', TRUE);
        $this->load->view('index_no_user', $data);
        $this->load->view('footer');
    }

    public function process() {
        $this->load->model('login_model');
        $result = $this->login_model->validate();
        if (!$result) {
            $this->session->set_userdata(array("login_error" => true));
            redirect('login');
        } else {
            redirect('index');
        }
    }

    function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */