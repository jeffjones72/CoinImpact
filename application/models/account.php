<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->check_isvalidated();
    }

    public function index() {
        $this->loadGlobals($this, $data);
        $this->load->helper('string');
        $this->load->model('accounts_model');
        $temp = $this->accounts_model->get_account_info($this->session->userdata('id'));
        $acc = newAccount();
        $data['account'] = $acc;
        if ($temp['invitation_account_id'])
        {
            $temp = $this->accounts_model->get_account_info($temp['invitation_account_id']);
        }
        else
        {
            $temp = null;
        }
        $data['invitation_account'] = $temp;
        $temp = $this->accounts_model->get_recruit_count($this->session->userdata('id'));
        $data['recruit_count'] = $temp;
        $this->load->model('players_model');
        $temp = $this->players_model->get_player_info($this->session->userdata('id'));
        $header ['page_title'] = 'Account Info';
        $data['page_title'] = 'Account Info';
        $data['data'] = $this->get_counters();

        $this->load->view('header', $data);
        $data['content'] = $this->load->view('account', $data, TRUE);
        $player_id = AccountO::getPlayerIdByAccountId($acc->id);
        $this->load->model('items_model');
        $data['items'] = $this->items_model->get_items($player_id);
        $this->load->view('header', $header);
        $this->load->view('base', $data);
        $this->load->view('footer', $data);
    }

    public function load_invitation() {
        $id = $this->uri->segment(3, 0);
        $data['invitation_account_o'] = AccountO::getByID($id);
        $this->load->view('account_load_invitation', $data);
    }

    public function save_invite_email() {

        $this->loadGlobals($this, $data);
        $account_o = $data['account_o'];

        $invitation_account_id = (int)$this->input->post('invitation_account_id');
        $is_saved = $account_o->saveInvitationAccountId($invitation_account_id);
        if (!$is_saved)
        {
             $this->session->set_flashdata('message', 'Account not found by id!');
        }

        redirect('account');
    }

}

/* End of file account.php */
/* Location: ./application/controllers/account.php */