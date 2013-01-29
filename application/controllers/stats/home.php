<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('stat_model', 'stat_m', true);
    }
    
    public function index() {
        $this->template->build("pages/comingsoon");
    }
    
    public function gt($gamertag = "") {
        
        // convert _ to %20 (space)
        $gamertag = str_replace("_", "%20", $gamertag);
        
        // lets load their data, check for 1hr expiration
        $data = $this->library->get_profile($gamertag);
        
        $data['SpartanURL'] = $this->library->return_spartan_url($data['HashedGamertag'], $gamertag);
        $data['RankImage'] = $this->library->return_image_url("Rank", $data['RankImage'], "large");
        $data['MedalData'] = $this->library->return_medals($data['MedalData']);
        
        //  output gt, build template, set partials
        $this->template
                ->set_partial('block_photo', '_partials/profile/block_photo')
                ->set_partial('block_basicstats', '_partials/profile/block_basicstats')
                ->set_partial('block_progression', '_partials/profile/block_progression')
                ->set_partial('block_medals','_partials/profile/block_medals')
                ->title("Leaf .:. " . urldecode($gamertag))
                ->set('gamertag', $gamertag)
                ->set('data', $data)
                ->build("pages/profile/view");
        
    }
    
}
?>