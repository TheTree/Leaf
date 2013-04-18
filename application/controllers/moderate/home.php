<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stat_model', 'stat_m', true);
        $this->load->model('admin_model', 'admin_m', true);
        $this->config->set_item('csrf_protection', TRUE);
    }

    public function index() {

        // get flagged users @todo cache for 5 minutes
        $flagged_users = $this->stat_m->get_flagged_users();

        // remove all that are less than 25 flags
        if (is_array($flagged_users)) {
            foreach ($flagged_users as $key => $user) {
                if ($user['amt'] < 25) {
                    unset($flagged_users[$key]);
                }
            }
        }

        // if session authenticated
        if ($this->session->userdata('authenticated') != FALSE) {
            $mod = TRUE;
        } else {
            $mod = FALSE;
        }

        // get last 5 banned users
        $banned_users = $this->stat_m->get_cheating_users();

        $this->library->description = "LeafApp .:. Manage Cheating Accounts";
        $this->template->set("meta", $this->library->return_meta());

        $this->template
            ->set("Leaf .:. 343 Guilty Spark")
            ->set("flagged_users", $flagged_users)
            ->set("banned_users", $banned_users)
            ->set("mod", $mod)
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

    public function login() {

        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean|callback_check_pass');

        if ($this->form_validation->run() === FALSE) {
            $this->template->build("pages/moderate/index");
        } else {
            redirect(base_url("guilty_spark"));
        }

        $this->library->description = "LeafApp .:. Sign In";
        $this->template->set("meta", $this->library->return_meta());

        $this->template
            ->set("Leaf .:. Sign In")
            ->build("pages/moderate/signin");

    }

    public function marked($seo_name = "", $status = 0) {

        // check for session
        if ($this->session->userdata('authenticated') != FALSE) {

            // delete all pending marks
            $this->stat_m->delete_pending_flagged_users($seo_name);

            // mark the person
            $this->stat_m->change_status($seo_name, $status);

            // redirect back 2 marking page
            redirect(base_url("guilty_spark"));
        } else {
           redirect(base_url());
        }
    }

    function check_pass($val) {

        // grab response
        $resp = $this->admin_m->login($this->input->post('username'), $val);

        // check if we got record.
        if ($resp == FALSE) {
            $this->form_validation->set_message('check_pass', 'The %s field is wrong. Go home kid.');
            return FALSE;
        } else {

            // make a session
            $this->session->set_userdata(array(
                'username' => $resp['username'],
                'authenticated' => intval(1)
            ));
            return TRUE;
        }
    }
}