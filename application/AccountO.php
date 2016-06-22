<?php
class AccountO extends CI_Model {
    public $player_id = null;
    public function __construct($data = null) {
        if($data === null) {
            self::__construct($this->session->userdata('id'));
            return;
        }
        parent::__construct($data);
        $this->player_id = AccountO::getPlayerIdByAccountId($this->id);
    }
    public static function getPlayerIdByAccountId($id) {
        $db = get_instance()->db;
        $db->select('id');
        $db->where('account_id', $id);
        $db->from('players');
        
        $query = $db->get();
        $result = $query->row_array();
        
        return $result['id'];
    }
    public static function getAll() {
        $db = get_instance()->db;
        
        $db->select('*');
        $db->from('accounts');
        
        $accounts_arr = $db->get()->result_array();
        
        $accounts = array();
        foreach($accounts_arr as $acc_arr) {
            $accounts[] = new AccountO($acc_arr);
        }
        return $accounts;
    }
    public static function tryActivate($acc_id, $key) {
        $db = get_instance()->db;
        $db->select('id');
        $db->where('account_id', $acc_id);
        $db->where('act_key', $key);
        $db->from('account_activation');
        
        $query = $db->get();
        $result = $query->row_array();
        
        if(!$result) {
            return false;
        }
        
        $db->select('activated');
        $db->where('id', $acc_id);
        $db->from('accounts');
        
        $query = $db->get();
        $result = $query->row_array();
        
        if($result['activated']) {
            return "already_activated";
        }
        
        $db->where('id', $acc_id);
        $db->set('activated', true);
        $db->update('accounts');
        return true;
    }
    public static function login($user_id) {
        $db = get_instance()->db;
        $session = get_instance()->session;
        $session->userdata('validated', true);
        $db->select('id, first_name, last_name, email, date_joined, '
                . 'facebook_id, timezone, is_staff');
        $db->where('id', $user_id);
        $db->from('accounts');
        
        $query = $db->get();
        $result = $query->row_array();
        
        if(!$result) {
            return;
        }
        
        $data = $result;
        
        $db->select('id, passed_intro');
        $db->where('account_id', $result['id']);
        $db->from('players');
        
        $query = $db->get();
        $result = $query->row_array();
        
        if(!$result) {
            throw new Exception('No player found for account');
        }
        
        $data['player_id'] = $result['id'];
        $data['passed_intro'] = $result['passed_intro'];
                
        $data['last_login'] = date(Globals::MYSQL_DATE_FORMAT);
        $data['validated'] = true;
        $session->set_userdata($data);
    }
    public static function tryJoin($data) {
        // To-Do
    }
    public static function getByID($id) {
        $db = get_instance()->db;
        $db->select('*');
        $db->where('id', $id);
        $db->from('accounts');
        
        $query = $db->get();
        if($query->num_rows == 0) {
            return null;
        }
        $result = $query->row_array();
        
        return new AccountO($result);
    }
    public function delete() {
        $this->db->where('account_id', $this->id);
        $this->db->delete('account_activation');
        
        $this->db->where('id', $this->id);
        $this->db->delete('accounts');
        
        $this->db->select('*');
        $this->db->where('account_id', $this->id);
        $this->db->from('players');
        $query = $this->db->get();
        $result = $query->row_array();
        
        $player = newPlayer($result);
        $player->delete();
        // and so on and so forth ;)
    }

    function getRecruitCount() {
        $this->db->select('count(*) as cnt');
        $this->db->from('accounts');
        $this->db->where('invitation_account_id', $this->id);
        $query = $this->db->get();
        $row = $query->row(); 
        return $row->cnt;
    }

    function saveInvitationAccountId($invitation_account_id) {

        $this->db->where('id',$invitation_account_id);
        $query = $this->db->get('accounts');
        if ($query->num_rows() == 0){
            return false;
        }

        $data = array();
        $data['invitation_account_id'] = $invitation_account_id;
        $this->db->where('id', $this->id);
        $this->db->update('accounts', $data);
        return true;
    }

}
?>