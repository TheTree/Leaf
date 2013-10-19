<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Moderate extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('h4/stat_model', 'stat_m', true);
        $this->load->model('admin_model', 'admin_m', true);
    }

    public function flagged($seo_name = "") {

        // check if GT exists
        if (($gt = $this->stat_m->get_gamertag_data($seo_name)) != FALSE) {

            // check if record exists
            if (($_tmp = $this->stat_m->check_for_flag($seo_name, $this->input->ip_address())) != FALSE) {
                $this->utils->throw_error("ALREADY_FLAGGED_BY_YOU");

            } else {
                $this->stat_m->insert_flag($gt, $this->input->ip_address());

                // setup tmp var for error msg, same as recache
                $this->session->set_flashdata('general_msg', TRUE);
                redirect(base_url('h4/record/' . $seo_name));
            }
        } else {
            $this->utils->throw_error("NOT_XBL_ACCOUNT");
        }
    }
}