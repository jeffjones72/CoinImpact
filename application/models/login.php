<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function validate() {
        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));

        //$this->output->enable_profiler(TRUE);
        $this->db->select('a.*, p.player_id');
        $this->db->from('accounts a');
        $this->db->join('players p', 'a.id = p.account_id');
        $this->db->where('email', $email);
        $this->db->where('password', sha1($password));
        $query = $this->db->get();

        if ($query->num_rows == 1) {
            $date = date("Y-m-d H:i:s", time());
            echo $date;
            $row = $query->row();
            $data = array(
                'id' => $row->id,
                'player_id' => $row->player_id,
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'email' => $row->email,
                'date_joined' => $row->date_joined,
                'last_login' => $row->$date,
                'facebook_id' => $row->facebook_id,
                'timezone' => $row->timezone,
                'is_staff' => $row->is_staff,
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

?>