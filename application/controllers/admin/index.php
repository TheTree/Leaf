<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    function __construct() {
        parent::__construct();

        // gatekeeper, load models, setup template
        $this->library->backstage_gatekeeper();
        $this->load->model('admin_model', 'admin_m', TRUE);
        $this->load->model('news_model', 'news_m', TRUE);
        $this->load->model('stat_model', 'stat_m', TRUE);

        $this->template
            ->set_partial('second_nav','_partials/admin/second_nav')
            ->set_layout('backstage');
    }

    function index() {

        $acp['total_accounts'] = $this->cache->model('admin_m','get_count', array(), 3600);

        $this->template
            ->set('acp', $acp)
            ->title("Sandbox")
            ->build("pages/admin/index");
    }

    function flagged() {
        $flagged_users = $this->library->get_flagged($this->admin_m->get_flagged_users());

        $this->template
            ->title("Flagged Users")
            ->set('flagged_users', $flagged_users)
            ->build("pages/admin/flagged");
    }

    public function flagged_mod($seo_name = "", $status = 0) {
        // check for session
        if ($this->session->userdata('authenticated') != FALSE) {

            // delete all pending marks
            $this->stat_m->delete_pending_flagged_users($seo_name);

            // mark the person
            $this->stat_m->change_status($seo_name, $status);

            // flag person in csr_records
            $this->stat_m->change_csr_status($seo_name, $status);

            // redirect back 2 marking page
            redirect(base_url("backstage/flagged"));
        } else {

        }
    }

    function find() {
        $this->template
            ->build("pages/comingsoon");
    }

    function news_list($page = 0) {

        $config = array();
        $config['base_url'] = base_url() . "backstage/news/list/";
        $config['total_rows'] = $this->news_m->count_news();
        $config['per_page'] = intval(6);
        $config['use_page_numbers'] = FALSE;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : $page;

        // run query again, cached w/ limit
        $news = $this->news_m->get_news($config['per_page'], $page);
        $this->pagination->initialize($config);

        $this->template
            ->set('news', $news)
            ->set("news_pagination", $this->pagination->create_links())
            ->title("Leaf News")
            ->build("pages/admin/news/list");

    }

    function news_create() {
        $this->form_validation->set_rules('author', 'Author', 'required|xss_clean');
        $this->form_validation->set_rules('article', 'Body', 'required|xss_clean');

        if ($this->input->post('submitted') != FALSE) {
            if ($this->form_validation->run() === FALSE) {
                $this->template->build("pages/admin/news/create");
            } else {

                // submit article
                $this->news_m->add_news(array(
                                            'author' => $this->input->post('author'),
                                            'text' => $this->input->post('article')));

                redirect(base_url("backstage/news/list"));
            }
        }
        $this->template->build("pages/admin/news/create");
    }
}
