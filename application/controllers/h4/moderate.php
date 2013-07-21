<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stat_model', 'stat_m', true);
        $this->load->model('admin_model', 'admin_m', true);
    }

    public function index() {
        // get last 5 banned users
        $banned_users = $this->stat_m->get_cheating_users();

        $this->library->description = "LeafApp .:. Manage Cheating Accounts";
        $this->template->set("meta", $this->library->return_meta());

        $this->template
            ->set("Leaf .:. 343 Guilty Spark")
            ->set("banned_users", $banned_users)
            ->build("pages/moderate/index");

    }

    public function flagged($seo_name = "") {

        // check if GT exists
        if (($gt = $this->stat_m->get_gamertag_data($this->library->get_hashed_seo_gamertag($seo_name))) != FALSE) {

            // check if record exists
            if (($_tmp = $this->stat_m->check_for_flag($seo_name, $this->input->ip_address())) != FALSE) {
                $this->library->throw_error("ALREADY_FLAGGED_BY_YOU");

            } else {
                // insert that flag
                $this->stat_m->insert_flag($gt, $this->input->ip_address());

                // setup tmp var for error msg, same as recache
                $this->session->set_flashdata('general_msg', TRUE);
                redirect(base_url('gt/' . $seo_name));
            }
        } else {
            $this->library->throw_error("NOT_XBL_ACCOUNT");
        }
    }
}