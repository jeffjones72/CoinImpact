<?php

class Squad_npc_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_NPC_by_intro_part($part)
    {
        if ($part == 1) {
            // get 1st 3 NPC's
            $ids_list = "1, 2, 3";
        } else 
            if ($part == 2) {
                $ids_list = "4";
            } else 
                if ($part == 3) {
                    $ids_list = "5";
                }
        
        $where = "id in ({$ids_list})";
        $npc = $this->get_where($where);
        
        return $npc;
    }

    public function get_NPC($id)
    {
        $query = $this->db->get("squad_NPC", array(
            "id" => $id
        ));
        return $query->row();
    }

    public function get_where($where)
    {
        $query = $this->db->get_where("squad_NPC", $where);
        return $query->result();
    }
    
   
    
    
    
}
