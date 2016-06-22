<?php
class PlayerPlace extends CI_Model {
    public $place;
    
    public function __construct($data = null) {
        parent::__construct($data);
        $this->place = new Place($this->place_id);
        if(!$this->place->isValid()) {
            throw new Exception('Invalid Place for PlayerPlace');
        }
    }
    
    public function tryActivateBosses() {
        if($this->progress != 100) {
            return;
        }
        $this->db->select('boss_id');
        $this->db->where('place_id', $this->place->id);
        $this->db->from('bosses_required_places');
        
        $query = $this->db->get();
        $result_arr = $query->result_array();
        
        foreach($result_arr as $result) {
            $this->player->tryActivate(new Boss($result['boss_id']));
        }
    }
    
    public function addProgress($amount) {
        $this->progress += $amount;
        
        if($this->progress > 100) {
            $this->progress = 100;
        }
        
        $this->db->set('progress', $this->progress);
        $this->db->where('id', $this->id);
        $this->db->update('player_places');
        
        if($this->progress == 100) {
            $this->tryActivateBosses();
        }
    }
}
?>