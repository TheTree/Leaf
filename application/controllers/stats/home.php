<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends H4_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->template->build("pages/comingsoon");
    }
    
    public function gt($gamertag = "") {
        echo $gamertag;
        
    }
    
}
?>