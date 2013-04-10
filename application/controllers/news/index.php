<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
    }

    function index($page = 0) {

        // load pagination stuff
        $this->load->library('pagination');

        $config = array();
        $config['base_url'] = base_url() . "news/";
        $config['total_rows'] = $this->news_m->count_news();
        $config['per_page'] = intval(4);
        $config['use_page_numbers'] = FALSE;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        // run query again, cached w/ limit
        $news = $this->news_m->get_news($config['per_page'], $page);
        $this->pagination->initialize($config);

        $this->template
                ->set('pagination', $this->pagination->create_links())
                ->set('news', $news)
                ->title("Leaf News")
                ->build("pages/news");
    }

    function view($id) {
        
        $this->template
                ->set('article', $this->news_m->get_article($id))
                ->title("Leafapp Article: " . intval($id) . " ")
                ->build("pages/article");
    }

    function create() {

        $this->form_validation->set_rules('author', 'Author', 'required|xss_clean');
        $this->form_validation->set_rules('article', 'Body', 'required|xss_clean');
        $this->form_validation->set_rules('pass', '老黄历', 'required|xss_clean|callback_check_pass');

        if ($this->form_validation->run() === FALSE) {
            $this->template->build("pages/create");
        } else {

            // submit article
            $this->news_m->add_news(array(
                'author' => $this->input->post('author'),
                'text' => $this->input->post('article')));

            redirect(base_url("news"));
        }
    }

    function check_pass($val) {

        if (md5($val . "yGefF") != "8fe6db8a802655d499206b18b2c00dce") {
            $this->form_validation->set_message('check_pass', 'The %s field is wrong. Go home kid.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}