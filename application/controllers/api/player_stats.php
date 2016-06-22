<?php
class PlayerStats extends API_Controller{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get($id){
        $this->db->select("attack, defense, energy, energy_limit, stamina, stamina_limit, health, health_limit, strike, dodge, luck, trustProgress");
        $this->db->where('id', $item_id);
        $query = $this->db->get('players');
        $results = $query->result();
        if($results==null){
            $data=array("status"=>"BAD_DATA", "error"=>"found no item for this id");
        
        }else{
            $data=array("status"=>"OK", "data"=>$results);
        }
        
        return json_encode($data);
    }
}