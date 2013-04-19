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

        // load global header & footer
        $this->template->set_partial('head_header', '_partials/head_header');
        $this->template->set_partial('header', '_partials/header');
        $this->template->set_partial('footer', '_partials/footer');
    }

}
