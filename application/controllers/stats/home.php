<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends H4_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('stat_model', 'stat_m', true);
    }
    
    public function index() {
        $this->template->build("pages/comingsoon");
    }
    
    public function gt($gamertag = "") {
        
        // lets load their data, check for 1hr expiration
        $data = $this->library->get_profile($gamertag);
        
        //  output gt, build template
        $this->template->title("Leaf .:. " . urldecode($gamertag));
        $this->template->set('gamertag', $gamertag);
        $this->template->build("pages/profile/view");
        
    }
    
}
?>