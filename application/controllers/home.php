<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
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
        $this->template->title("Leaf .:. Halo 4 Stats");
        $this->template->set("recent_news", $this->news_m->get_newest_article());
        $this->template->set('challenges', $this->library->get_challenges());
        $this->template->build('pages/home');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */