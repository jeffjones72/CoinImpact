<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class API_Controller extends MY_Controller{
    public function check_isvalidated() {
        
       
        
        if (!$this->session->userdata('validated')) {
            $results=array("status"=>"NOT_LOGGED_IN");
        }
        if (!$this->session->userdata('passed_intro') && get_class($this) != 'Intro') {
            $results=array("status"=>"NOT_LOGGED_IN");
        } else {
            $results=array("status"=>"OK");       
        }
        
        $return=json_encode($results);
        return $return;   
    }
}