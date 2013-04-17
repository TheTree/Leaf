<?php

class IBOT_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        // check debug
        if (ENVIRONMENT == "development") {
           //$this->output->enable_profiler(TRUE);
        }
        // load global header & footer
        $this->template->set_partial('head_header', '_partials/head_header');
        $this->template->set_partial('header', '_partials/header');
        $this->template->set_partial('footer', '_partials/footer');
    }

}
