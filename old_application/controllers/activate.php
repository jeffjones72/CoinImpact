<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Activate extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $acc = $this->input->get('acc');
        $key = $this->input->get('get');
        $result = AccountO::tryActivate($acc, $key);
        if($result) {
            redirect('/');
            return;
        }
        $data = array();
        $data['result'] = $result;
        $header = array('title' => 'Activate');
        $this->load->view('header', $header);
        $data['content'] = $this->load->view('main', $data, TRUE);
        $this->load->view('activate', $data);
        $this->load->view('footer', $data);
    }
}
?>