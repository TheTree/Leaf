<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
        $this->load->model('h4/stat_model', 'stat_m', true);
    }

    function about() {

        $this->library->description = "LeafApp .:. About Us";
        $this->template->set("meta", $this->library->return_meta());

        $this->template
                ->title("About")
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
                ->set("recent_players", $this->cache->model("stat_m", 'get_last_5', array(), 120))
                ->set("recent_news", $this->news_m->get_newest_article())
                ->set('challenges', $this->library->get_challenges())
                ->build('pages/home');
    }

    function error() {
        // get the flash data error
        if (($_tmp = $this->session->flashdata('error_msg')) == FALSE) {
            $_tmp = "hi. this is the error page, but no error was thrown. happy?";
        }

        // set the template and page
        $this->template
            ->title("Leaf .:. Error Occurred")
            ->set("error_msg", $_tmp)
            ->build("pages/error");
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */