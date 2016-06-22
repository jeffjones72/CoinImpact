<?php
class Repo {
    private static $repo = null;
    private $idCache = array();
    private $db = null;
    private function __construct() {
        $this->db = get_instance()->db;
        //register_shutdown_function(array($this, "save"));
    }
    public function newAccount() {
        return $this->getByID('Account', $this->session->userdata('id'));
    }
    public function getByID($what, $id) {
        if(!isset($this->idCache[$what])) {
            $this->idCache[$what] = array();
        }
        if(!isset($this->idCache[$what][$id])) {
            $this->db->select("*");
            $this->db->where("id", $id);
            $table = getTableFromClass($what);
            $this->db->from($table);
            $query = $this->db->get();
            if(!$query) {
                throw new Exception('No query for '.$what.' and id '.$id);
            }
            $result = $query->row_array();
            if($what == 'Player') {
                $obj = newPlayer($result);
            } else {
                $obj = new $what($result);
            }
            $this->idCache[$what][$id] = $obj;
        }
        return $this->idCache[$what][$id];
    }
    public function newPlayer($data = null) {
        $ins = &get_instance();
        if(!$data) {
            $ins->load->model("players_model");
            $data = $ins->players_model->get_player();
        } else if(is_numeric($data)) {
            if(isset($this->idCache['Player']) && isset($this->idCache['Player'][$data])) {
                return $this->idCache['Player'][$data];
            }
            $this->idCache['Player'][$data] = Player::getArrById($data);
            $data = Player::getArrById($data);
        }
        if(!$data) {
            return null;
        }
        $data['account'] = new AccountO($data['account_id']);
        $player = null;
        if($data['account']->is_dev) {
            $player = new Developer($data);
        } else {
            $player = new Player($data);
        }
        $this->idCache['Player'][$player->id] = $player;
        return $player;
    }
    public function store($obj) {
        $this->idCache[get_class($obj)][$obj->id] = $obj;
    }
    /*public function getByProp($what, $prop, $val) {
        $this->db->select("*");
        $this->db->where($prop, $id);
        $this->db->from($what."s");
        $query = $this->db->get();
        $result = $query->row_array();
        if(isset($this->idCache[$what][$result['id']])) {
            return $this->idCache[$what][$result['id']];
        }
        return $result;
    }*/
    public static function getInstance() {
        if(self::$repo == null) {
            self::$repo =  new Repo();
        }
        return self::$repo;
    }
    private function save() {
        foreach($this->idCache as $what => $arr) {
            foreach($arr as $obj) {
                $obj->save();
            }
        }
    }
    public function finish() {
        $this->save();
        $this->idCache = array();
    }
    private function saveObj($obj) {
        $vars = get_object_vars($obj);
        foreach($vars as $var => $val) {
            $this->db->set($var, $val);
        }
        $this->db->replace(strtolower(get_class($obj)).'s');
    }
}
?>