<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function validate() {

        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));

        $this->db->select('a.*, p.id as player_id, p.passed_intro as passed_intro');
        $this->db->from('accounts a');
        $this->db->join('players p', 'a.id = p.account_id');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get();

        if ($query->num_rows == 1) {
            $date = date("Y-m-d H:i:s", time());

            $row = $query->row();
            $data = array(
                'id' => $row->id,
                'player_id' => $row->player_id,
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'email' => $row->email,
                'date_joined' => $row->date_joined,
                'last_login' => $date,
                'facebook_id' => $row->facebook_id,
                'timezone' => $row->timezone,
                'is_staff' => $row->is_staff,
                'passed_intro' => $row->passed_intro,
                'validated' => true
            );

            $this->session->set_userdata($data);

            $update = array(
                'last_login' => $date
            );

            $this->db->where('id', $data['id']);
            $this->db->update('accounts', $update);

            return true;
        }

        return false;
    }

}
