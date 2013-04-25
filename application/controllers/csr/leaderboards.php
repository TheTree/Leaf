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
        $this->load->model('stat_model', 'stat_m', true);
        $this->config->set_item('csrf_protection', TRUE);

        // check for "starred" gt
        $this->load->helper('cookie');

        // get gamertag and icon, send 2 template
        $_tmp = get_cookie('starred',TRUE);
        if ($_tmp != FALSE) {
            $this->gt = $this->stat_m->get_name_and_emblem($_tmp);
        }

        // load playlists
        $this->playlists = $this->library->get_playlists();
    }

    private function _load_pagination($playlist, $page) {

        // load pagination stuff
        $this->load->library('pagination');

        // get arrays of playlists
        $ind = $this->config->item('individual_csr');
        foreach ($ind as $item) {
            $csr[] = $item . "_I";
        }

        $team = $this->config->item('team_csr');
        foreach ($team as $item) {
            $csr[] = $item . "_T";
        }
        unset($ind);
        unset($team);

        // check if this playlist exists
        if (!in_array($playlist, $csr)) {
            $this->library->throw_error("PLAYLIST_NOT_FOUND");
            return FALSE;
        }

        $config = array();
        $config['base_url'] = base_url() . "csr_leaderboards/" . $playlist . "/";
        $config['total_rows'] = $this->stat_m->count_csr($playlist);
        $config['per_page'] = intval(15);
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // run query again, cached w/ limit
        $_tmp = $this->stat_m->get_playlist($playlist, $config['per_page'], $page);
        $this->pagination->initialize($config);
        return $_tmp;
    }

    private function _load_personal() {
        if (is_array($this->gt)) {
            $this->my = $this->cache->model('stat_m','get_unique_csr_position', array($this->gt['SeoGamertag']), 300);
        } else {
            // @todo pop message saying to "star" a profile to mark it as your own
        }
    }

    private function _build() {

        // lets grab each playlist via config
        $team = $this->config->item('team_csr');
        $ind = $this->config->item('individual_csr');

        // get page if one
        $page = $this->uri->segment(3);
        if ($page == FALSE) {
            $page = 1;
        }  else {
            $page = (($page - 1) * 15 + 1); #$config['per_page']
        }

        // check 4 playlist
        $playlist = $this->uri->segment(2);
        if ($playlist == FALSE) {
            $playlist = $this->default_playlist;
        }

        //output needed template stuff
        $this->template
            ->set("playlist", $playlist)
            ->set("my", $this->my)
            ->set("page", intval($page))
            ->set("csr_team", $team)
            ->set("csr_ind", $ind)
            ->set("playlists", $this->playlists)
            ->build("pages/leaderboard/csr");
    }

    function index() {
        $this->_load_personal();

        // we are on the default playlist, so load it
        $resp = $this->_load_pagination($this->default_playlist, 0);
        $name = $this->playlists[substr($this->default_playlist, 0, -2)]['Name'];

        $this->library->description = "LeafApp .:. CSR Halo 4 " . $name . " Leaderboards";
        $this->template->set("meta", $this->library->return_meta());

        // build w/ data
        $this->template
            ->set('pagination', $this->pagination->create_links())
            ->set('leaderboards', $resp)
            ->title("Leaf .:. CSR Halo 4 " . $name . " Leaderboards");
        $this->_build();
    }

    function leaderboard($playlist = "", $page = 0) {
        $this->_load_personal();
        $resp = $this->_load_pagination($playlist, $page);
        $name = $this->playlists[substr($playlist,0, -2)]['Name'];

        // @todo add playlist name into this.
        $this->library->description = "LeafApp .:. CSR Halo 4 " . $name . " Leaderboards";
        $this->template->set("meta", $this->library->return_meta());

        // build w/ data
        $this->template
            ->set('pagination', $this->pagination->create_links())
            ->set('leaderboards', $resp)
            ->title("Leaf .:. CSR Halo 4 " . $name . " Leaderboards");
        $this->_build();

    }
}