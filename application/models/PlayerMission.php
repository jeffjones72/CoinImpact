<?php
class PlayerMission extends CI_Model {
    private $completed_combatants_count = null;
    private $required_combatants_count = null;
    private $completed_items_count = null;
    private $required_items_count = null;
    private $completed_things_count = null;
    private $required_things_count = null;
    private $completed_events_count = null;
    private $required_events_count = null;
    public function getProgress() {
        return sprintf('%.2f', $this->getCompletedObjectivesCount()*100/$this->getObjectivesCount());
    }
    public function getCompletedObjectivesCount() {
        return $this->getCompletedCombatantObjectivesCount() + $this->getCompletedEventObjectivesCount() + 
                $this->getCompletedItemObjectivesCount() + $this->getCompletedThingObjectivesCount();
    }
    public function getObjectivesCount() {
        return $this->getCombatantObjectivesCount() + $this->getEventObjectivesCount() + 
                $this->getItemObjectivesCount() + $this->getThingObjectivesCount();
    }
    public function getCompletedCombatantObjectivesCount() {
        if($this->completed_combatants_count !== null) {
            return $this->completed_combatants_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('combatant_id IN (SELECT combatant_id FROM player_combatants WHERE not(completed is null) AND '
                . 'place_id IN (SELECT id FROM player_places WHERE player_id="'.$this->db->escape($this->player_id).'"))');
        $this->db->from('missions_required_combatants');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->completed_combatants_count = $result['count'];
        return $result['count'];
    }
    public function getCompletedEventObjectivesCount() {
        if($this->completed_events_count !== null) {
            return $this->completed_events_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('event_id IN (SELECT event_id FROM player_events WHERE not(completed is null) AND '
                . 'place_id IN (SELECT id FROM player_places WHERE player_id="'.$this->db->escape($this->player_id).'"))');
        $this->db->from('missions_required_events');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->completed_events_count = $result['count'];
        return $result['count'];
    }
    public function getCompletedItemObjectivesCount() {
        if($this->completed_items_count !== null) {
            return $this->completed_items_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('item_id IN (SELECT item_id FROM player_items WHERE not(completed is null) '
                . 'AND player_id="'.$this->db->escape($this->player_id).'")');
        $this->db->from('missions_required_items');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->completed_items_count = $result['count'];
        return $result['count'];
    }
    public function getCompletedThingObjectivesCount() {
        if($this->completed_things_count !== null) {
            return $this->completed_things_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('thing_id IN (SELECT thing_id FROM player_things WHERE not(completed is null) '
                . 'AND player_id="'.$this->db->escape($this->player_id).'")');
        $this->db->from('missions_required_things');
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->completed_things_count = $result['count'];
        return $result['count'];
    }
    public function getCombatantObjectivesCount() {
        if($this->required_combatants_count !== null) {
            return $this->required_combatants_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->from('missions_required_combatants');
        $this->db->where('mission_id', $this->mission->id);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->required_combatants_count = $result['count'];
        return $result['count'];
    }
    public function getEventObjectivesCount() {
        if($this->required_events_count !== null) {
            return $this->required_events_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->from('missions_required_events');
        $this->db->where('event_id', $this->mission->id);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->required_events_count = $result['count'];
        return $result['count'];
    }
    public function getItemObjectivesCount() {
        if($this->required_items_count !== null) {
            return $this->required_items_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->from('missions_required_items');
        $this->db->where('mission_id', $this->mission->id);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->required_items_count = $result['count'];
        return $result['count'];
    }
    public function getThingObjectivesCount() {
        if($this->required_things_count !== null) {
            return $this->required_things_count;
        }
        $this->db->select('COUNT(*) as count');
        $this->db->from('missions_required_things');
        $this->db->where('mission_id', $this->mission->id);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        $this->required_things_count = $result['count'];
        return $result['count'];
    }
    public function tryComplete() {
        if($this->completed) {
            return false;
        }
        if(!$this->hasCompletedAllEvents()) {
            return false;
        }
        if(!$this->hasKilledAllCombatants()) {
            return false;
        }
        if(!$this->hasCollectedAllItems()) {
            return false;
        }
        if(!$this->hasCollectedAllThings()) {
            return false;
        }
        return true;
    }
    public function hasCompletedAllThings() {
        $this->db->select('count(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('thing_id not in (select thing_id from player_things where player_id="'.
                $this->db->escape($this->player_id).'")');
        $this->db->from('player_combatant_things');
        
        $query = $this->db->get();
        $result = $query->row_array();
        if($result['count']) {
            return false;
        }
        return true;
    }
    public function hasCollectedAllItems() {
        $this->db->select('count(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('item_id not in (select item_id from player_items where player_id="'.
                $this->db->escape($this->player->id).'")');
        $this->db->from('missions_required_items');
        
        $query = $this->db->get();
        $result = $query->row_array();
        if($result['count']) {
            return false;
        }
        return true;
    }
    public function hasKilledAllCombatants() {
        $this->db->select('count(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('combatant_id not in (select combatant_id from player_combatants where place_id in (select id '
                . 'from player_combatants where player_id="'.$this->db->escape($this->player_id).'"))');
        $this->db->from('missions_required_combatants');
        
        $query = $this->db->get();
        $result = $query->row_array();
        if($result['count']) {
            return false;
        }
        return true;
    }
    public function hasCompletedAllEvents() {
        $this->db->select('count(*) as count');
        $this->db->where('mission_id', $this->mission->id);
        $this->db->where('event_id not in (select event_id from player_events where place_id in (select id from player_places where player_id="'.
                $this->db->escape($this->player_id).'"))');
        $this->db->from('missions_required_events');
        $query = $this->db->get();
        $result = $query->row_array();
        if($result['count']) {
            return false;
        }
        return true;
    }
}
?>