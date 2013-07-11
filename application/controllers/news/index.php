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

        $this->library->description = "LeafApp .:. News Articles";
        $this->template->set("meta", $this->library->return_meta());

        $this->template
                ->set('pagination', $this->pagination->create_links())
                ->set('news', $news)
                ->title("Leaf News")
                ->build("pages/news");
    }

    function view($id) {
        $this->template->set("meta", $this->library->return_meta());

        $article = $this->news_m->get_article($id);
        if ($article == FALSE) {
            $article = $this->news_m->get_article_via_slug($id);
        }

        $this->library->description = "LeafApp .:. News Article " . $id;

        $this->template
                ->set('article', $article)
                ->title("Leafapp Article: " . intval($id) . " ")
                ->build("pages/article");
    }
}