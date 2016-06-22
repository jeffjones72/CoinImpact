<?php

class GetItem extends API_Controller
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function get($item_id)
    {
        $items_model=$this->load->model("Items_model");
        $results=$items_model->get_item($item_id);
        if($results==null){
            $data=array("status"=>"BAD_DATA", "error"=>"found no item for this id");
            
        }else{
            $data=array("status"=>"OK", "data"=>$results);
        }
        
        return json_encode($data);
    }
}

?>