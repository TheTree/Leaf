<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('stat_model', 'stat_m', true);
    }
    
    public function index() {
        
        // lets start grabbing data w/ caches
        $stats = array();
        
        $stats['TotalKills'] = $this->cache->model('stat_m','get_top_5', array('TotalKills', false), 3600);
        $stats['TotalDeaths'] = $this->cache->model('stat_m', 'get_top_5', array('TotalDeaths', false), 3600);
        $stats['KDRatio'] = $this->cache->model('stat_m', 'get_top_5', array('KDRatio', false), 3600);
        $stats['TimePlayed'] = $this->cache->model('stat_m', 'get_top_5', array('TotalGameplay', false), 3600);
        $stats['TotalMedals'] = $this->cache->model('stat_m', 'get_top_5', array('TotalMedalsEarned', false), 3600);
        $stats['ChallengesCompleted'] = $this->cache->model('stat_m', 'get_top_5', array('TotalChallengesCompleted', false), 3600);
        
        // build w/ data
        $this->template
                ->set_partial('total_kills', '_partials/leaderboards/total_kills')
                ->set_partial('total_deaths', '_partials/leaderboards/total_deaths')
                ->set_partial('kd_ratio', '_partials/leaderboards/kd_ratio')
                ->set_partial('time_played', '_partials/leaderboards/time_played')
                ->set_partial('challenges_completed', '_partials/leaderboards/challenges_completed')
                ->set_partial('total_medals', '_partials/leaderboards/total_medals')
                ->title("Leaf .:. Leaderboards")
                ->set("stats", $stats)
                ->build("pages/leaderboard/home");
    }
    
    public function gt($gamertag = "") {
        
        // convert _ to %20 (space)
        $gamertag = str_replace("_", "%20", $gamertag);
        
        // lets load their data, check for 1hr expiration
        $data = $this->library->get_profile($gamertag);
        
        $data['SpartanURL'] = $this->library->return_spartan_url($data['HashedGamertag'], $gamertag);
        $data['RankImage'] = $this->library->return_image_url("Rank", $data['RankImage'], "large");
        $data['MedalData'] = $this->library->return_medals($data['MedalData']);
        $data['SkillData'] = $this->library->return_csr($data['SkillData']);
        
        //  output gt, build template, set partials
        $this->template
                ->set_partial('block_photo', '_partials/profile/block_photo')
                ->set_partial('block_basicstats', '_partials/profile/block_basicstats')
                ->set_partial('block_progression', '_partials/profile/block_progression')
                ->set_partial('block_medals','_partials/profile/block_medals')
                ->set_partial('block_csr', '_partials/profile/block_csr')
                ->title("Leaf .:. " . urldecode($gamertag))
                ->set('msg', $this->session->flashdata("recache"))
                ->set('gamertag', $data['Gamertag'])
                ->set('data', $data)
                ->build("pages/profile/view");
        
    }
    
    public function recache_gt($gamertag = "") {
        
         // convert _ to %20 (space)
        $gamertag = str_replace("_", "%20", $gamertag);
        $seo_gamertag = $this->library->get_seo_gamertag($gamertag);
        $hashed = $this->library->get_hashed_seo_gamertag($seo_gamertag);
        
        // lets see if they need a recache.
        $data = $this->stat_m->get_expiration_date($hashed);
        
        if (is_array($data)) {
            if (($data['Expiration'] - THREE_HOURS_IN_SECONDS + TENMIN_IN_SECONDS) < time() || ENVIRONMENT == "development") {
                $data = $this->library->get_profile($gamertag, false, true, $seo_gamertag);
                
                // set cache
                $this->session->set_flashdata("recache", "enabled");
            } else {
                $this->session->set_flashdata("recache", "disabled");
            }
            
            // redirect out of here
            redirect(base_url("gt/" . str_replace("%20", "_",$gamertag)));
        } else {
            mail("ibotpeaches@gmail.com",'error', 'Below account could not verify existance before recaching');
            mail("ibotpeaches@gmail.com",'error', $seo_gamertag);
            mail("ibotpeaches@gmail.com",'error', $hashed);
            show_error("This isn't a gamertag.");
        }
    }
    
}
