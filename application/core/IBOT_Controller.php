<?php

class IBOT_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        // check debug
        if (ENVIRONMENT == "development") {
           //$this->output->enable_profiler(TRUE);
        }

        // lets try and load the cookie
        $this->load->helper('cookie');

        // get gamertag and icon, send 2 template
        $_tmp = get_cookie('starred',TRUE);

        if ($_tmp == FALSE) {
            $this->template->set('starred', FALSE);
        } else {
            $resp = $this->stat_m->get_name_and_emblem($_tmp);
            $resp['Emblem'] = $this->library->return_image_url("Emblem", $resp['Emblem'], 40);
            $resp['SeoGamertag'] = $_tmp;
            $this->template->set('starred', $resp);
        }

        // fix the fucking Chrome browser
        $this->output->set_header('Content-Type: text/html; charset=utf-8');
        $this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Expires: 0');

        // load global header & footer
        $this->template
            ->set_partial('global_js_vars', '_partials/global_js_vars')
            ->set('csrf_token', $this->security->get_csrf_token_name())
            ->set('csrf_hash', $this->security->get_csrf_hash())
            ->set_partial('head_header', '_partials/head_header')
            ->set_partial('header', '_partials/header')
            ->set_partial('footer', '_partials/footer');
    }

}
