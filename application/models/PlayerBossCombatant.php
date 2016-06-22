<?php
class PlayerBossCombatant extends CI_Model {
    public $player_boss;
    public function __construct($data) {
        parent::__construct($data);
        $repo = Repo::getInstance();
        $this->player_boss = $repo->getByID('PlayerBoss', $this->player_boss_id);
    }
    public function giveCredit() {
        $max_players = $this->player_boss->boss->max_players;
        $health = $this->player_boss->boss->health;
        $fair_share = $health/$max_players;
        
        $this->db->select('id');
        $this->db->where('player_id', $this->player_id);
        $this->db->where('boss_id', $this->player_boss->boss_id);
        $this->db->from('boss_kill_counts');
        
        $query = $this->db->get();
        $id = 0;
        
        if(!$query->num_rows) {
            $this->db->set('boss_id', $this->player_boss->boss_id);
            $this->db->set('player_id', $this->player_id);
            $this->db->set('count', 0);
            $this->db->insert('boss_kill_counts');
            $id = $this->db->insert_id();
        } else {
            $result = $query->row_array();
            $id = $result['id'];
        }
        
        if($this->damage < $fair_share) {
            return;
        }
        
        $this->db->set('count', 'count+1', false);
        $this->db->where('id', $id);
        $this->db->update('boss_kill_counts');
    }
    public function generateLoot() {
        foreach(array('item', 'thing') as $type) {
            $this->db->select('COUNT( i.rarity_id ) as count, i.rarity_id as rarity_id');
            $this->db->from('boss_'.$type.'s bi');
            $this->db->join($type.'s i', 'bi.'.$type.'_id = i.id');
            $this->db->where('bi.boss_id', $this->player_boss->boss->id);
            $this->db->group_by('i.rarity_id');

            $query = $this->db->get();
            $counts = $query->result_array();

            foreach($counts as $count) {
                $dmg = $this->damage;
                if($dmg >= $this->player_boss->boss->max_awards_at_dmg) {
                    $dmg = $this->player_boss->boss->max_awards_at_dmg;
                }
                $max_items_count = (int)($dmg*$count['count']/$this->player_boss->boss->max_awards_at_dmg);
                if($max_items_count > $this->player_boss->boss->max_awards_at_dmg) {
                    $max_items_count = $this->player_boss->boss->max_awards_at_dmg;
                }
                $item_count = roll($max_items_count,0);
                for($i=0;$i<$item_count;++$i) {
                    $data = $this->generateItem($count['rarity_id'], $type);
                    $this->handleAddItem($data);
                }
            }
        }
    }
    private function handleAddItem($data) {
        $type = $data['type'];
        unset($data['type']);
        foreach($data as $k => $v) {
            $this->db->set($k, $v);
        }
        $this->db->insert('player_boss_'.$type.'s');
    }
    public function generateItem($rarity_id, $type) {
        if(!in_array($type, array('thing', 'item'))) {
            return false;
        }
        $this->db->select('i.id AS id');
        $this->db->from('boss_'.$type.'s bi');
        $this->db->join($type.'s i', 'bi.'.$type.'_id=i.id');
        $this->db->where('from_bosses', true);
        $this->db->where('bi.boss_id', $this->player_boss->boss->id);
        $this->db->where('i.rarity_id', $rarity_id);
        $this->db->order_by('RAND()');
        $this->db->limit(1);
        
        $query = $this->db->get();
        $counts = $query->row_array();
        
        $id = $counts['id'];
        
        $item = new Item($id);
        $data = array();
        $data['type'] = $type;
        $data['player_boss_combatant_id'] = $this->id;
        $data[$type.'_id'] = $id;
        if($type == 'item' && $item->has_quality) {
            $quality = quality_roll();
            $attack = Item::getAttributeMod($quality, $item->attack);
            $defense = Item::getAttributeMod($quality, $item->defense);
            $data['attack'] = $attack;
            $data['defense'] = $defense;
            $data['quality'] = $quality;
        }
        return $data;
    }
    public function getAwards() {
        $awards = array();
        
        $this->db->select('*');
        $this->db->where('player_boss_combatant_id', $this->id);
        $this->db->from('player_boss_things');
        
        $query = $this->db->get();
        $result = $query->result_array();

        foreach($result as $arr) {
            $awards[] = new PlayerBossThing($arr);
        }
        
        $this->db->select('*');
        $this->db->where('player_boss_combatant_id', $this->id);
        $this->db->from('player_boss_items');
        
        $query = $this->db->get();
        $result = $query->result_array();

        foreach($result as $arr) {
            $awards[] = new PlayerBossItem($arr);
        }
        
        return $awards;
    }
    public function tryCollect($p_b_item) {
        $item = null;
        if($p_b_item->player_boss_combatant->player_id !== $this->player_id) {
            return false;
        }
        if($p_b_item instanceof PlayerBossItem) {
            $item = $p_b_item->item;
        } else {
            $item = $p_b_item->thing;
        }
        $type = $item->getClassification();
        if(!in_array($type, array('item', 'thing'))) {
            return false;
        }
        if($type == 'thing' && $this->player->isInventoryFull()) {
            return 'inventory_full';
        }
        $this->db->where('id', $p_b_item->id);
        if($type == 'item') {
            $this->db->delete('player_boss_items');
        } else {
            $this->db->delete('player_boss_things');
        }

        if(!$this->db->affected_rows()) {
            return false;
        }
        $this->player->tryAdd($item);
        return true;
    }
    public function discard($p_b_item) {
        if($p_b_item->player_boss_combatant->player_id !== $this->player_id) {
            return false;
        }
        $item = null;
        if($p_b_item instanceof PlayerBossItem) {
            $item = $p_b_item->item;
        } else {
            $item = $p_b_item->thing;
        }
        $this->db->where('id', $p_b_item->id);
        if($p_b_item->getClassification() == 'item') {
            $this->db->delete('player_boss_items');
        } else {
            $this->db->delete('player_boss_things');
        }
        return true;
    }
    public function getPercentDMGDone() {
        return round($this->damage*100/$this->player_boss->boss->health);
    }
}
?>