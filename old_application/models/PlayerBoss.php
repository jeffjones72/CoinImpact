<?php
class PlayerBoss extends CI_Model {
    
    public $combatants = null;
    CONST SCORE_LIST_NUM = 10;
    public function __construct($data = null) {
        parent::__construct($data);
        $this->generated = strtotime($this->generated);
        if($this->completed !== null) {
            $this->completed = strtotime($this->completed);
        }
    }
    public function hit(Player $player) {
        $dmg = round($this->boss->attack * ((100 - $player->defense) / 100));
        $player->recieveDamage($dmg);
        return $dmg;
    }
    public function getPlaceFor(Player $player) {
        $dmg = $this->getDamageFor($player);
        $this->db->select('COUNT(*) as count');
        $this->db->where('player_boss_id', $this->id);
        $this->db->where('player_id', $this->id);
        $this->db->where('damage < '.$this->db->escape($dmg), null, false);
        $this->db->from('player_boss_combatants');
        
        $result = $this->db->get()->row_array();
        return $result['count']+1;
    }
    public function getDamageFor(Player $player) {
        $this->db->select('damage');
        $this->db->where('player_id', $player->id);
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_combatants');
        
        $query = $this->db->get();
        if(!$query->num_rows) {
            return 0;
        }
        
        $result = $query->row_array();
        return $result['damage'];
    }
    public function getTopPlayers($num = self::SCORE_LIST_NUM) {
        $this->db->select('player_id, damage');
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_combatants');
        $this->db->order_by('damage', 'desc');
        $this->db->limit($num);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $scores = array();
        
        foreach($result as $data) {
            $scores[] = array('damage' => $data['damage'], 'player' => newPlayer($data['player_id']));
        }
        
        return $scores;
    }
    
    public function log($dmg_to_player, $dmg_to_boss, Player $player) {
        $this->db->set('damage_to_boss', $dmg_to_boss);
        $this->db->set('damage_to_player', $dmg_to_player);
        $this->db->set('player_boss_id', $this->id);
        $this->db->set('player_id', $player->id);
        $this->db->set('time', 'now()', false);
        $this->db->insert('boss_combat_log');
    }
    public function getLog() {
        $this->db->select('*');
        $this->db->where('player_boss_id', $this->id);
        $this->db->order_by('time DESC');
        $this->db->from('boss_combat_log');
        
        $query = $this->db->get();
        $result = $query->result_array();
        for($i=0;$i<sizeof($result);++$i) {
            $result[$i]['player_name'] = Player::getUsernameByID($result[$i]['player_id']);
        }
        
        return $result;
    }
    public function getHealthPercent() {
        if($this->boss->health === null) {
            return 100;
        }
        return $this->health*100/$this->boss->health;
    }
    public function getRemainingTime() {
        return $this->boss->timeout-(time()-$this->generated);
    }
    public function getEndTime() {
        return $this->generated+
                $this->boss->timeout;
    }
    public function getCombatantsCount() {
        $this->db->select('COUNT(id) as count');
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_combatants');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['count'];
    }
    public function getShareLink() {
        return base_url().'bosses?share_id='.$this->share_id.'&name='.urlencode($this->boss->name).'&level='.$this->boss->level;
    }
    public function isDead() {
        return $this->health <= 0 && $this->boss->health !== NULL;
    }
    public function recieveDamage($dmg) {
        if($dmg > $this->health) {
            $dmg = $this->health;
        }
        $this->health -= $dmg;
        $this->db->set('health', 'health-'.$this->db->escape($dmg), false);
        $this->db->where('id', $this->id);
        $this->db->update('player_bosses');
        
        if($this->isDead()) {
            $this->markAsCompleted();
            $this->giveCredits();
            $this->generateLoot();
        }
    }
    public function markAsCompleted() {
        $this->db->set('completed', 'NOW()', false);
        $this->db->where('id', $this->id);
        $this->db->update('player_bosses');
    }
    public function getCombatants() {
        $combatants = array();
        
        $this->db->select('*');
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_combatants');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $comb) {
            $combatants[] = new PlayerBossCombatant($comb);
        }
        
        return $combatants;
    }
    public function getPlayerBossCombatant() {
        $player = newPlayer();
        $this->db->select('*');
        $this->db->where('player_id', $player->id);
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_combatants');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return new PlayerBossCombatant($result);
    }
    public function giveCredits() {
        if($this->combatants === null) {
            $this->combatants = $this->getCombatants();
        }
        
        foreach($this->combatants as $combatant) {
            $combatant->giveCredit();
        }        
    }
    public function generateLoot() {
        if($this->combatants === null) {
            $this->combatants = $this->getCombatants();
        }
        
        foreach($this->combatants as $combatant) {
            $combatant->generateLoot();
        }
    }
    public function getAllPlayerBossItems() {
        $this->db->select('*');
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_items');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $p_b_items = array();
        
        foreach($result as $p_b_i_arr) {
            $p_b_items[] = new PlayerBossItem($p_b_i_arr);
        }
        
        return $p_b_items;
    }
    public function getAllPlayerBossThings() {
        $this->db->select('*');
        $this->db->where('player_boss_id', $this->id);
        $this->db->from('player_boss_things');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $p_b_things = array();
        
        foreach($result as $p_b_t_arr) {
            $p_b_things[] = new PlayerBossThing($p_b_t_arr);
        }
        
        return $p_b_things;
    }
    public function getAllPlayerBossCombatants() {
        $this->db->select('*');
        $this->db->where('player_id', $this->id);
        $this->db->from('player_boss_combatants');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        $p_b_combatants = array();
        
        foreach($result as $p_b_c_arr) {
            $p_b_combatants[] = new PlayerBossCombatant($p_b_c_arr);
        }
        
        return $p_b_combatants;
    }
    public function getSpeed() {
        if(!$this->completed) {
            return '';
        }
        return format($this->completed - $this->generated);
    }
    public function delete() {
        $p_b_items = $this->getAllPlayerBossItems();
        foreach($p_b_items as $p_b_item) {
            $p_b_item->delete();
        }
        
        $p_b_things = $this->getAllPlayerBossThings();
        foreach($p_b_things as $p_b_thing) {
            $p_b_thing->delete();
        }
        
        $p_b_combatants = $this->getAllPlayerBossCombatants();
        foreach($p_b_combatants as $p_b_combatant) {
            $p_b_combatant->delete();
        }
        
        $this->db->where('id', $this->id);
        $this->db->delete('player_bosses');
    }
    public static function search() {
        $CI = &get_instance();
        $db = $CI->db;
        $db->select('*');
        $db->where('is_public', true);
        $db->from('player_bosses');
        $db->limit(10);
        $query = $db->get();
        
        $arrs = $query->result_array();
        $bosses = array();
        foreach($arrs as $arr) {
            $bosses[] = new PlayerBoss($arr);
        }
        return $bosses;
    }
    public static function getAllUnhandledInsects() {
        $p_bosses = array();
        $query = $this->db->query('SELECT * FROM player_bosses pb JOIN bosses b ON pb.boss_id=b.id'
                . ' WHERE b.health is null AND '
                . 'b.timeout+pb.generated<NOW() AND ' // and those who have expired
                . 'pb.id NOT IN (SELECT player_boss_id FROM handled_insects)');
        $result = $query->get();
        foreach($result->row_array() as $arr) {
            $correct_arr = array();
            foreach($arr as $key => $val) {
                if(substr($key, 0, 3) == 'pb.') {
                    continue;
                }
                $correct_arr[substr($key, 3)] = $val;
            }
            $p_bosses[] = new PlayerInsectBoss($correct_arr);
        }
        return $p_bosses;
    }
}
?>