<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Template extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->load->view('template');
    }

}

/* End of file template.php */
/* Location: ./application/controllers/template.php */