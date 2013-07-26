<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('h4/stat_model', 'stat_m', true);
    }

    public function star($gamertag = "") {
        $this->load->helper('cookie');
        $this->input->set_cookie(array(
            'name' => 'starred',
            'value' => $gamertag,
            'expire' => 865000
        ));

        redirect(base_url('h4/' . $gamertag));
    }

    public function removefreeze($seo_gamertag = "") {

        // pull record based on seo_gamertag
        $gt = $this->stat_m->get_unfreeze_data($seo_gamertag);

        // check
        if ($gt == FALSE) {
            $this->library->throw_error("NO_GAMERTAG_STORED");
        } else if ($gt['InactiveCounter'] < INACTIVE_COUNTER) {
            $this->library->throw_error("INACTIVECOUNTER_LESSTHAN_X");
        } else {
            // grab new acc
            $acc = $this->library->grab_profile_data($gt['Gamertag'], TRUE, $gt['SeoGamertag']);

            // check to see if there games are the same
            if ($acc['TotalGamesStarted'] == $gt['TotalGamesStarted']) {
                $this->library->throw_error("UNFREEZE_NO_CHANGE");
            } else {
                $this->session->set_flashdata("recache", "enabled");
                redirect(base_url('/gt/' . $acc['SeoGamertag']));
            }
        }
    }
    
    public function gt($gamertag = "") {
        
        // convert _ to %20 (space)
        $gamertag = str_replace("_", "%20", $gamertag);
        
        // lets load their data, check for 1hr expiration
        $data = $this->h4_lib->get_profile($gamertag);

        $data['BranchGamertag'] = $this->h4_lib->make_branch_gt($data[H4::GAMERTAG]);
        $data['SpartanURL']     = $this->h4_lib->return_spartan_url($gamertag, "Profile");
        $data[H4::MEDAL_DATA]   = $this->h4_lib->return_medals($data[H4::MEDAL_DATA]);
        $data['CSRPlaylist']    = $this->h4_lib->return_csr_v2($this->cache->model('stat_m','get_unique_csr_position', array($data[H4::SEO_GAMERTAG]), 300),
                                                                $data[H4::SKILL_DATA]);
        $data[H4::SKILL_DATA]   = $this->h4_lib->return_csr($data[H4::SKILL_DATA]);
        $data[H4::SPEC_DATA]    = $this->h4_lib->return_spec($data[H4::SPEC_DATA]);
        $data['FavoriteData']   = $this->h4_lib->return_favorite($data[H4::FAVORITE_WEAPON_NAME],
                                                                  $data[H4::FAVORITE_WEAPON_DESCRIPTION],
                                                                  $data[H4::FAVORITE_WEAPON_TOTAL_KILLS],
                                                                  $data[H4::FAVORITE_WEAPON_URL]);

        $this->utils->description = "LeafApp .:. " . $data[H4::GAMERTAG] . " Halo 4 Stats";

        //  output gt, build template, set partials
        $this->template
                ->set("meta", $this->utils->return_meta())
                ->set_partial('block_photo', '_partials/h4/profile/block_photo')
                ->set_partial('block_basicstats', '_partials/h4/profile/block_basicstats')
                ->set_partial('block_progression', '_partials/h4/profile/block_progression')
                ->set_partial('block_bestgame', '_partials/h4/profile/block_bestgame')
                ->set_partial('block_medals','_partials/h4/profile/block_medals')
                ->set_partial('block_favoriteweapon', '_partials/h4/profile/block_favoriteweapon')
                ->set_partial('block_csr', '_partials/h4/profile/block_csr')
                ->set_partial('block_specs', '_partials/h4/profile/block_specdata')
                ->set_partial('block_inactivetest', '_partials/h4/profile/block_inactivetest')
                ->set_partial('block_cheatertest', '_partials/h4/profile/block_cheatertest')
                ->set_partial('block_social', '_partials/h4/profile/block_social')
                ->title("Leaf .:. " . urldecode($data[H4::GAMERTAG]))
                ->set('msg', $this->session->flashdata("recache"))
                ->set('general_msg', $this->session->flashdata('general_msg'))
                ->set('gamertag', $data[H4::GAMERTAG])
                ->set('data', $data)
                ->build("pages/h4/profile/view");
        
    }
    
    public function recache_gt($gamertag = "") {
        
         // convert _ to %20 (space)
        $gamertag = str_replace("_", "%20", $gamertag);
        $seo_gamertag = $this->h4_lib->get_seo_gamertag($gamertag);
        $hashed = $this->h4_lib->get_hashed_seo_gamertag($seo_gamertag);
        
        // lets see if they need a recache.
        $data = $this->stat_m->get_expiration_date($hashed);
        
        if (is_array($data)) {
            if (($data[H4::EXPIRATION] - SEVENDAYS_IN_SECONDS + FIVEMIN_IN_SECONDS) < time() || ENVIRONMENT == "development") {
                $data = $this->h4_lib->get_profile($gamertag, false, true, $seo_gamertag);
                
                // set cache
                $this->session->set_flashdata("recache", "enabled");
            } else {
                $this->session->set_flashdata("recache", "disabled");
            }
            
            // redirect out of here
            redirect(base_url("h4/" . $data['SeoGamertag']));
        } else {
            $this->utils->throw_error("NO_GAMERTAG_STORED");
        }
    }

    public function metadata() {
        $this->utils->get_metadata();
    }

    public function redo_playlists() {
        $this->h4_lib->get_playlists();
    }
    
}
