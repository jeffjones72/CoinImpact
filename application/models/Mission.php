<?php
class Mission extends CI_Model {
    public function getRequirements() {
        $requirements = array_merge($this->getCombatantObjectives(), $this->getEventObjectives(),
                $this->getItemObjectives(), $this->getThingObjectives());
        return $requirements;
    }
    public function getCombatantObjectives() {
        $combatants = array();
        $this->db->select('*');
        $this->db->from('missions_required_combatants');
        $this->db->where('mission_id', $this->id);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $arr) {
            $combatants[] = new MissionsRequiredCombatant($arr);
        }
        
        return $combatants;
    }
    public function getEventObjectives() {
        $events = array();
        $this->db->select('*');
        $this->db->from('missions_required_events');
        $this->db->where('mission_id', $this->id);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $arr) {
            $events[] = new MissionsRequiredEvent($arr);
        }
        
        return $events;
    }
    public function getItemObjectives() {
        $items = array();
        $this->db->select('*');
        $this->db->from('missions_required_items');
        $this->db->where('mission_id', $this->id);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $arr) {
            $items[] = new MissionsRequiredItem($arr);
        }
        
        return $items;
    }
    public function getThingObjectives() {
        $things = array();
        $this->db->select('*');
        $this->db->from('missions_required_things');
        $this->db->where('mission_id', $this->id);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $arr) {
            $things[] = new MissionsRequiredThing($arr);
        }
        
        return $things;
    }    
}
?>