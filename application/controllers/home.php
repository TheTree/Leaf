<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
        $this->load->model('stat_model', 'stat_m', true);
    }

    function about() {
        $this->template
                ->build("pages/about");
    }

    function index() {

        // rules
        $this->form_validation->set_rules('gamertag', 'Gamertag', 'required|max_length[15]|xss_clean');

        // run it
        if ($this->form_validation->run() == FALSE) {
            $this->template->build('pages/home');
        } else {
            redirect(base_url("/gt/" . str_replace(" ", "_",$this->input->post('gamertag'))));
        }
        $this->template
                ->title("Leaf .:. Halo 4 Stats")
                ->set_partial("challenges", "_partials/homepage/challenges")
                ->set_partial("news_block", "_partials/homepage/news_block")
                ->set_partial("recent_gamertags", "_partials/homepage/recent_gamertags")
                ->set("recent_players", $this->cache->model("stat_m", 'get_last_5', array()), 1800)
                ->set("recent_news", $this->news_m->get_newest_article())
                ->set('challenges', $this->library->get_challenges())
                ->build('pages/home');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */