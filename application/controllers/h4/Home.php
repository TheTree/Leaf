<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
        $this->load->model('h4/stat_model', 'stat_m', true);
    }

    function index() {
        $this->form_validation->set_rules('gamertag', 'Gamertag', 'required|max_length[15]|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
        } else {
            redirect(base_url("/h4/record/" . str_replace(" ", "_",$this->input->post('gamertag'))));
        }

        $this->template
                ->title("Leaf .:. Halo 4 Stats")
                ->set_partial("challenges", "_partials/h4/homepage/challenges")
                ->set_partial("gamertag_add", "_partials/h4/homepage/gamertag_add")
                ->set_partial("recent_gamertags", "_partials/h4/homepage/recent_gamertags")
                ->set_partial("news_block", "_partials/globals/homepage/news_block")
                ->set_partial("last_compared", "_partials/h4/homepage/last_compared")
                ->set("recent_players", $this->cache->model("stat_m", 'get_last_5', array(), 120))
                ->set("last_compared", $this->stat_m->get_last_comparison())
                ->set("recent_news", $this->news_m->get_newest_article())
                ->set('challenges', $this->h4_lib->get_challenges())
                ->build('pages/home');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */