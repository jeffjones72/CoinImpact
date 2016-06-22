<?php
class Dev extends CI_Controller {
    public function activate() {
        $this->db->set('id');
        $this->db->where('id NOT IN (SELECT player_id FROM player_places WHERE active=1)', null, false);
        $this->db->from('players');
        
        $query = $this->db->get();
        $result = $query->result_array();
        foreach($result as $id_arr) {
            $id = $id_arr['id'];
            echo $id.'<br>';
            $this->db->set('active', 1);
            $this->db->where('place_id', 2);
            $this->db->where('player_id', $id);
            $this->db->update('player_places');
        }
    }
    public function login() {
        $id = 36;
        AccountO::login($id);
    }
    public function index() {
        $stat_types = array('stamina_limit', 'energy_limit', 'health_limit', 'attack', 'defense');
        $players = Player::getAll();
        foreach($players as $player) {
            echo 'username='.$player->account->username.'<br>';
            foreach($stat_types as $stat_type) {
                $starting = $this->getStartingStat($stat_type);
                $skill = $player->$stat_type - $starting;
                if($stat_type == 'stamina_limit') {
                    $skill /= 2;
                }
                echo $stat_type.': '.$skill.'<br>';
            }
        }
    }
    private function getStartingStat($field) {
        switch($field) {
            case 'stamina_limit':
                return 20;
            case 'energy_limit':
                return 20;
            case 'health_limit':
                return 100;
            case 'attack':
                return 10;
            case 'defense':
                return 10;
        }
    }
    public function stats() {
        $players = Player::getAll();
        foreach($players as $player) {
            $stamina = Player::STARTING_STAMINA + $player->getStaminaFromItems();
            $health = Player::STARTING_HEALTH + $player->getHealthFromItems();
            $energy = Player::STARTING_ENERGY + $player->getEnergyFromItems();
            $attack = Player::STARTING_ATTACK + $player->getAttackFromItems();
            $defense = Player::STARTING_DEFENSE + $player->getDefenseFromItems();
            $strike = $player->getStrikeFromItems();
            $dodge = $player->getDodgeFromItems();
            $luck = $player->getLuckFromItems();
            foreach(array('stamina', 'health', 'energy') as $stat) {
                $this->set_stat($player, $stat.'_limit', $$stat);
            }
            foreach(array('stamina', 'health', 'energy', 'attack', 'defense', 'strike', 'dodge', 'luck') as $stat) {
                $this->set_stat($player, $stat, $$stat);
            }
        }
        $this->db->set('skill', 'level_id', false);
        $this->db->update('players');
        $this->db->where('id', 'id', false);
        $this->db->delete('spent_skills');
    }
    private function set_stat(Player $player, $stat, $val) {
        $this->db->set($stat, $val);
        $this->db->where('id', $player->id);
        $this->db->update('players');
    }
}
?>