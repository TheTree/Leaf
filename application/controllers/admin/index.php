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

        $acp = $this->cache->model('admin_m','get_count', array(), 1800);
        $acp['api'] = $this->cache->model('admin_m', 'get_api_count', array(), 1800);

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

    function keys() {

        if ($this->input->post('submitted') != FALSE) {
            $this->form_validation->set_rules('user', 'User', 'required|xss_clean|max_length[32]|min_length[5]');

            if ($this->form_validation->run() === FALSE) {
                $this->form_validation->set_message('errors', validation_errors());
            } else {
                // add key
                $this->stat_m->insert_api($this->input->post('user'));
            }
        }

        $this->template
            ->set('key_msg', $this->session->flashdata('key_msg'))
            ->set('keys', $this->stat_m->get_keys())
            ->title("API Keys")
            ->build("pages/admin/keys");
    }

    function key_delete($key) {
        $this->stat_m->delete_key($key);
        $this->session->set_flashdata('key_msg', 'Key deleted');
        redirect(base_url('backstage/keys'));
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
        $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|max_length[28]');
        $this->form_validation->set_rules('article', 'Body', 'required|xss_clean');

        if ($this->input->post('submitted') != FALSE) {
            if ($this->form_validation->run() === FALSE) {
                $this->template->build("pages/admin/news/create");
            } else {

                // submit article
                $id = $this->news_m->add_news(array(
                                            'author'    => $this->input->post('author'),
                                            'text'      => $this->input->post('article'),
                                            'title'     => $this->input->post('title')));

                // update seo
                $this->news_m->update_slug($id, url_title($this->input->post('title'),'-',TRUE));

                redirect(base_url("backstage/news/list"));
            }
        }
        $this->template->build("pages/admin/news/create");
    }

    function badges_list($page = 0) {
        $config = array();
        $config['base_url'] = base_url() . "backstage/badges/list/";
        $config['total_rows'] = $this->stat_m->count_badges();
        $config['per_page'] = intval(6);
        $config['use_page_numbers'] = FALSE;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : $page;

        // run query again, cached w/ limit
        $news = $this->stat_m->get_badges($config['per_page'], $page);
        $this->pagination->initialize($config);

        $this->template
            ->set('badges', $news)
            ->set("badge_pagination", $this->pagination->create_links())
            ->title("Badge List")
            ->build("pages/admin/badges/list");
    }

    function badges_create() {
        $this->form_validation->set_rules('gamertag', 'Gamertag', 'required|xss_clean|callback_valid_gamertag|callback_gamertag_exists');
        $this->form_validation->set_rules('badge', 'Badge', 'required|xss_clean|max_length[28]');
        $this->form_validation->set_rules('type', 'Type', 'required|xss_clean|callback_valid_type');

        if ($this->input->post('submitted') != FALSE) {
            if ($this->form_validation->run() === FALSE) {
                $this->template->build("pages/admin/badges/create");
            } else {

                // insert badge
                $this->stat_m->insert_badge(array(
                    'SeoGamertag'       => $this->library->get_seo_gamertag($this->input->post('gamertag')),
                    'title'             => $this->input->post('badge'),
                    'colour'            => $this->input->post('type')
                ));

                redirect(base_url("backstage/badges/list"));
            }
        }
        $this->template->build("pages/admin/badges/create");
    }

    //----------------------------------------------------------------
    // START: Callback Functions
    //----------------------------------------------------------------

    function valid_gamertag($val) {
        $hashed = $this->library->get_hashed_seo_gamertag($this->library->get_seo_gamertag($val));

        if ($this->stat_m->account_exists($hashed) != FALSE) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_gamertag', 'This gamertag does not exist.');
            return FALSE;
        }
    }

    function gamertag_exists($val) {
        $seo = $this->library->get_seo_gamertag($val);

        if ($this->stat_m->badge_exists($seo) != FALSE) {
            $this->form_validation->set_message('gamertag_exists', '%s already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function valid_type($val) {
        $valid = ["important", "warning", "success", "info", "inverse"];

        if (in_array($val, $valid)) {
            return TRUE;
        }  else {
            $this->form_validation->set_message('valid_type', 'This is not a valid color type.');
            return FALSE;
        }
    }

    //----------------------------------------------------------------
    // END: Callback Functions
    //----------------------------------------------------------------

    function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
