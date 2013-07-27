<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leaderboards extends IBOT_Controller {

    public $gt;
    public $my;
    public $playlists;
    public $default_playlist = "100_I";

    public function __construct() {
        parent::__construct();
        $this->load->model('h4/stat_model', 'stat_m', true);

        // check for "starred" gt
        $this->load->helper('cookie');

        // get gamertag and icon, send 2 template
        $_tmp = get_cookie('starred',TRUE);
        if ($_tmp != FALSE) {
            $this->gt = $this->stat_m->get_name_and_emblem($_tmp);
        }

        // load playlists
        $this->playlists = $this->h4_lib->get_playlists();
    }

    public function top_10() {

        // lets start grabbing data w/ caches
        $stats = array();

        // start the list of data for affix
        $stats['Items'] = ["KD Ratio", "Kills per Game", "Deaths per Game", "Assists per Game", "Headshots per Game", "Medals per Game", "Time Played", "Challenges Completed"];

        // get the actual data for the partials
        $stats['Data']['kills_per_game']            = $this->cache->model('stat_m', 'get_top_10', array(H4::KILLS_PER_GAME_RATIO, 'DESC'), 1800);
        $stats['Data']['deaths_per_game']           = $this->cache->model('stat_m', 'get_top_10', array(H4::DEATHS_PER_GAME_RATIO, 'ASC'), 1800);
        $stats['Data']['kd_ratio']                  = $this->cache->model('stat_m', 'get_top_10', array(H4::KD_RATIO, 'DESC'), 1800);
        $stats['Data']['time_played']               = $this->cache->model('stat_m', 'get_top_10', array(H4::TOTAL_GAMEPLAY, 'DESC'), 1800);
        $stats['Data']['medals_per_game']           = $this->cache->model('stat_m', 'get_top_10', array(H4::MEDALS_PER_GAME_RATIO, 'DESC'), 1800);
        $stats['Data']['challenges_completed']      = $this->cache->model('stat_m', 'get_top_10', array(H4::TOTAL_CHALLENGES_COMPLETED, 'DESC'), 1800);
        $stats['Data']['assists_per_game']          = $this->cache->model('stat_m', 'get_top_10', array(H4::ASSISTS_PER_GAME_RATIO, 'DESC'), 1800);
        $stats['Data']['headshots_per_game']        = $this->cache->model('stat_m', 'get_top_10', array(H4::HEADSHOTS_PER_GAME_RATIO,'DESC'), 1800);

        $this->utils->description = "LeafApp .:. Leaderboards";

        // build w/ data
        $this->template
            ->set_partial('kills_per_game', '_partials/h4/leaderboards/total_kills')
            ->set_partial('deaths_per_game', '_partials/h4/leaderboards/total_deaths')
            ->set_partial('kd_ratio', '_partials/h4/leaderboards/kd_ratio')
            ->set_partial('time_played', '_partials/h4/leaderboards/time_played')
            ->set_partial('medals_per_game', '_partials/h4/leaderboards/total_medals')
            ->set_partial('challenges_completed', '_partials/h4/leaderboards/challenges_completed')
            ->set_partial('assists_per_game', '_partials/h4/leaderboards/total_assists')
            ->set_partial('headshots_per_game', '_partials/h4/leaderboards/total_headshots')
            ->set("meta", $this->utils->return_meta())
            ->title("Leaf .:. Leaderboards")
            ->set("stats", $stats)
            ->build("pages/h4/leaderboard/top_ten");
    }

    private function _load_pagination($playlist, $page = 0) {

        // load pagination stuff
        $this->load->library('pagination');
        $csr = $this->h4_lib->get_playlist_csr();

        // check if this playlist exists
        if (!in_array($playlist, $csr)) {
            $this->utils->throw_error("PLAYLIST_NOT_FOUND");
            return FALSE;
        }

        $config = array();
        $config['base_url'] = base_url() . "h4/csr_leaderboards/" . $playlist . "/";
        $config['total_rows'] = $this->stat_m->count_csr($playlist);
        $config['per_page'] = intval(15);
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        // run query again, cached w/ limit
        $_tmp = $this->stat_m->get_playlist($playlist, $config['per_page'], $page);
        $this->pagination->initialize($config);
        return $_tmp;
    }

    private function _load_personal() {
        if (is_array($this->gt)) {
            $this->my = $this->cache->model('stat_m','get_unique_csr_position', array($this->gt[H4::SEO_GAMERTAG]), 300);
        }
    }

    function panel($playlist = "", $page = 0) {
        $this->_load_personal();
        $resp = $this->_load_pagination($playlist, $page);
        $name = $this->playlists[substr($playlist,0, -2)]['Name'];

        // add playlist name into this.
        $this->utils->description = "LeafApp .:. CSR Halo 4 " . $name . " Leaderboards";

        // build w/ data
        $this->template
            ->set("meta", $this->utils->return_meta())
            ->set('pagination', $this->pagination->create_links())
            ->set('leaderboards', $resp)
            ->set('playlist_name', $name)
            ->title("Leaf .:. CSR Halo 4 " . $name . " Leaderboards");
        $this->_build();

    }

    private function _build() {

        // get page if one
        $page = $this->uri->segment(4);
        if ($page == FALSE) {
            $page = 1;
        }  else {
            $page = (($page - 1) * 15 + 1); #$config['per_page']
        }

        // check 4 playlist
        $playlist = $this->uri->segment(3);
        if ($playlist == FALSE) {
            $playlist = $this->default_playlist;
        }

        //output needed template stuff
        $this->template
            ->set("playlist", $playlist)
            ->set("my", $this->my)
            ->set("page", intval($page))
            ->set("csr_team", $this->config->item('h4_team_csr'))
            ->set("csr_ind", $this->config->item('h4_individual_csr'))
            ->set("playlists", $this->playlists)
            ->build("pages/h4/leaderboard/csr");
    }
}