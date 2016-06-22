<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Join extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $username = $this->input->post('username');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $password = $this->input->post('email');
        $password2 = $this->input->post('password');
        $email = $this->input->post('password2');

        $this->form_validation->set_rules('username', 'Username', 'trim|xss_clean|required|callback_custom_alpha|is_unique[accounts.username]');
        $this->form_validation->set_rules('first_name', 'First name', 'trim|xss_clean|required|callback_custom_alpha');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|xss_clean|required|callback_custom_alpha');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required|min_length[6]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|xss_clean|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|required|valid_email|is_unique[accounts.email]');

        if ($this->form_validation->run() == FALSE) {

            $data['page_title'] = 'Join';
            $this->load->view('header_no_user', $data);
            $data['content'] = $this->load->view('join', '', TRUE);
            $this->load->view('base_no_user', $data);
            $this->load->view('footer');
        } else {

            $this->load->model('accounts_model');
            $account_id = $this->accounts_model->insert_account();
            AccountO::login($account_id);
            $this->session->set_userdata(array("just_registered" => true));
            redirect('intro');
        };
    }

    function custom_alpha($str) {
        $this->form_validation->set_message('custom_alpha', 'The %s field must contain only alphabetic characters, and an optional hyphen or space.');

        return (!preg_match('/^([a-zA-Z])+([\s-])?([a-zA-Z])+$/', $str)) ? FALSE : TRUE;
    }

}

/* End of file join.php */
/* Location: ./application/controllers/join.php */