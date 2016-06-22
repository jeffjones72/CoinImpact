<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_account_info($account_id) {
        $this->db->select('id, username, first_name, last_name, email, date_joined, last_login, facebook_id, invitation_account_id');
        $this->db->from('accounts');
        $this->db->where('id', $account_id);
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_recruit_count($account_id) {
        $this->db->select('count(*) as cnt');
        $this->db->from('accounts');
        $this->db->where('invitation_account_id', $account_id);
        $query = $this->db->get();
        $row = $query->row(); 
        return $row->cnt;
    }

    function insert_account() {
        $username = $this->input->post('username');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));

        $date = date("Y-m-d H:i:s", time());

        $data = array();
        $data['username'] = $username;
        $data['first_name'] = $first_name;
        $data['last_name'] = $last_name;
        $data['email'] = $email;
        $data['password'] = $password;
        $data['date_joined'] = $date;
        $data['is_staff'] = 0;

        $this->db->insert('accounts', $data);

        $account_id = $this->db->insert_id();

        $data2 = array();
        $data2['first_name'] = $first_name;
        $data2['last_name'] = $last_name;
        $data2['account_id'] = $account_id;
        $data2['created'] = $date;
        $data2['attack'] = Player::STARTING_ATTACK;
        $data2['defense'] = Player::STARTING_DEFENSE;
        $data2['energy'] = Player::STARTING_ENERGY;
        $data2['energy_limit'] = Player::STARTING_ENERGY;
        $data2['stamina'] = Player::STARTING_STAMINA;
        $data2['stamina_limit'] = Player::STARTING_STAMINA;
        $data2['health'] = Player::STARTING_HEALTH;
        $data2['health_limit'] = Player::STARTING_HEALTH;
        $data2['skill'] = 0;
        $data2['damage_boost'] = Player::STARTING_DAMAGE_BOOST;
        $data2['strike'] = Player::STARTING_STRIKE;
        $data2['strike_boost'] = Player::STARTING_STRIKE_BOOST;
        $data2['luck'] = Player::STARTING_LUCK;
        $data2['dodge'] = Player::STARTING_DODGE;
        $data2['storage_cap'] = Player::STARTING_STORAGE_CAP;
        $data2['experience'] = 0;
        $data2['level_id'] = 1;
        $data2['balance'] = 100;
        $data2['premium_balance'] = 30;
        $data2['rank_id'] = 1;
        $data2['path'] = 0;
        //$data2->user_agent = $base->user_agent;
        //$data2->remote_address = $base->remote_address;
        //$data2->location_id = $base->location_id;
        $data2['energy_rate'] = 300;
        $data2['health_rate'] = 180;
        $data2['stamina_rate'] = 300;
        $data2['energy_refill'] = $date;
        $data2['health_refill'] = $date;
        $data2['stamina_refill'] = $date;

        $this->db->insert('players', $data2);

        $player_id = $this->db->insert_id();

        $this->db->select('id');
        $this->db->where('release_id', 1);
        $this->db->order_by('id');
        $places = $this->db->get('places');

        if ($places->num_rows > 0) {
            foreach ($places->result_array() as $place) {
                $this->db->set('date', $date);
                $this->db->set('player_id', $player_id);
                $this->db->set('place_id', $place['id']);
                $this->db->set('progress', 0);
                if ($place['id'] == 2)
                    $this->db->set('active', 1);
                else {
                    $this->db->set('active', 0);
                }
                $this->db->insert('player_places');
            }
        }

        $this->db->select('id');
        $this->db->order_by('id');
//        $missions = $this->db->get('missions');
//
//        if ($missions->num_rows > 0) {
//            foreach ($missions->result_array() as $mission) {
//                $this->db->set('date', $date);
//                $this->db->set('player_id', $player_id);
//                $this->db->set('mission_id', $mission['id']);
//                $this->db->insert('player_missions');
//            }
//        }
        
        $this->db->set('player_id', $player_id);
        $this->db->set('page', 1);
        $this->db->insert('intro_info');
        
        return $account_id;
    }

    function update_last_login($user_id) {
        $date = date("Y-m-d H:i:s", time());

        $this->db->set('last_login', $date);
        $this->db->where('id', $user_id);
        $this->db->update('accounts');
    }

    function update_user_password() {
        $data->password = sha1($_POST['password']);

        $this->db->where('id', $_POST['id']);
        $this->db->update('accounts', $data);
    }

    function update_user() {
        $date = time();
        $data->first_name = $_POST['first_name'];
        $data->last_name = $_POST['last_name'];
        $data->email = $_POST['email'];
        if (isset($_POST['password'])) {
            $data->password = sha1($_POST['password']);
        }
        //$data->timezone = $_POST['timezone'];

        $this->db->where('id', $_POST['id']);
        $this->db->update('accounts', $data);
    }

    function update_invitation_account_id($account_id, $invitation_account_id) {
        $data = array();
        $data['invitation_account_id'] = $invitation_account_id;
        $this->db->where('id', $account_id);
        $this->db->update('accounts', $data);
    }

}

/* End of file accounts_model.php */
/* Location: ./application/models/accounts_model.php */