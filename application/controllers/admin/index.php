<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    function __construct() {
        parent::__construct();

        // gatekeeper, load models, setup template
        $this->library->backstage_gatekeeper();
        $this->load->model('admin_model', 'admin_m', true);

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
        $this->template
            ->build("pages/comingsoon");
    }

    function find() {
        $this->template
            ->build("pages/comingsoon");
    }
}
