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

        // start the list of data for affix
        $stats['Items'] = ["Total Kills", "Total Deaths", "Total Assists", "Total Headshots", "KD Ratio", "Time Played", "Total Medals", "Challenges Completed"];

        // get the actual data for the partials
        $stats['Data']['total_kills']            = $this->cache->model('stat_m', 'get_top_10', array('TotalKills', false), 3600);
        $stats['Data']['total_deaths']           = $this->cache->model('stat_m', 'get_top_10', array('TotalDeaths', false), 3600);
        $stats['Data']['kd_ratio']               = $this->cache->model('stat_m', 'get_top_10', array('KDRatio', false), 3600);
        $stats['Data']['time_played']            = $this->cache->model('stat_m', 'get_top_10', array('TotalGameplay', false), 3600);
        $stats['Data']['total_medals']           = $this->cache->model('stat_m', 'get_top_10', array('TotalMedalsEarned', false), 3600);
        $stats['Data']['challenges_completed']   = $this->cache->model('stat_m', 'get_top_10', array('TotalChallengesCompleted', false), 3600);
        $stats['Data']['total_assists']          = $this->cache->model('stat_m', 'get_top_10', array('TotalAssists', false), 3600);
        $stats['Data']['total_headshots']        = $this->cache->model('stat_m', 'get_top_10', array('TotalHeadshots', false), 3600);

        $this->library->description = "LeafApp .:. Leaderboards";
        $this->template->set("meta", $this->library->return_meta());

        // build w/ data
        $this->template
                ->set_partial('total_kills', '_partials/leaderboards/total_kills')
                ->set_partial('total_deaths', '_partials/leaderboards/total_deaths')
                ->set_partial('total_assists', '_partials/leaderboards/total_assists')
                ->set_partial('kd_ratio', '_partials/leaderboards/kd_ratio')
                ->set_partial('time_played', '_partials/leaderboards/time_played')
                ->set_partial('challenges_completed', '_partials/leaderboards/challenges_completed')
                ->set_partial('total_medals', '_partials/leaderboards/total_medals')
                ->set_partial('total_headshots', '_partials/leaderboards/total_headshots')
                ->title("Leaf .:. Leaderboards")
                ->set("stats", $stats)
                ->build("pages/leaderboard/home");
    }
    
    public function gt($gamertag = "") {
        
        // convert _ to %20 (space)
        $gamertag = str_replace("_", "%20", $gamertag);
        
        // lets load their data, check for 1hr expiration
        $data = $this->library->get_profile($gamertag);
        
        $data['SpartanURL']     = $this->library->return_spartan_url($gamertag, "Profile");
        $data['RankImage']      = $this->library->return_image_url("Rank", $data['RankImage'], "large");
        $data['MedalData']      = $this->library->return_medals($data['MedalData']);
        $data['SkillData']      = $this->library->return_csr($data['SkillData']);
        $data['SpecData']       = $this->library->return_spec($data['SpecData']);
        $data['FavoriteData']   = $this->library->return_favorite($data['FavoriteWeaponName'],
                                                                  $data['FavoriteWeaponDescription'],
                                                                  $data['FavoriteWeaponTotalKills'],
                                                                  $data['FavoriteWeaponUrl']);

        $this->library->description = "LeafApp .:. " . $data['Gamertag'] . " Halo 4 Stats";
        $this->template->set("meta", $this->library->return_meta());
        //  output gt, build template, set partials
        $this->template
                ->set_partial('block_photo', '_partials/profile/block_photo')
                ->set_partial('block_basicstats', '_partials/profile/block_basicstats')
                ->set_partial('block_progression', '_partials/profile/block_progression')
                ->set_partial('block_bestgame', '_partials/profile/block_bestgame')
                ->set_partial('block_medals','_partials/profile/block_medals')
                ->set_partial('block_favoriteweapon', '_partials/profile/block_favoriteweapon')
                ->set_partial('block_csr', '_partials/profile/block_csr')
                ->set_partial('block_specs', '_partials/profile/block_specdata')
                ->title("Leaf .:. " . urldecode($data['Gamertag']))
                ->set('msg', $this->session->flashdata("recache"))
                ->set('gamertag', $data['Gamertag'])
                ->set('data', $data)
                ->set('badge', $this->library->get_badge($data['Gamertag']))
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
            if (($data['Expiration'] - THREE_HOURS_IN_SECONDS + FIVEMIN_IN_SECONDS) < time() || ENVIRONMENT == "development") {
                $data = $this->library->get_profile($gamertag, false, true, $seo_gamertag);
                
                // set cache
                $this->session->set_flashdata("recache", "enabled");
            } else {
                $this->session->set_flashdata("recache", "disabled");
            }
            
            // redirect out of here
            redirect(base_url("gt/" . str_replace("%20", "_",$gamertag)));
        } else {
            $this->library->throw_error("NO_GAMERTAG_STORED");
        }
    }

    public function metadata() {
        $this->library->get_metadata();
    }
    
}
