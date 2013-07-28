<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gate extends IBOT_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model', 'admin_m', true);

        if (($this->utils->backstage_gatekeeper(FALSE))) {
            redirect(base_url('backstage/index'));
        }
    }

    public function index() {

        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean|callback_check_pass');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
        } else {
            // @todo finish login
            redirect(base_url("backstage/index"));
        }

        $this->template
            ->title("Sandbox Entry")
            ->build("pages/admin/gate");
    }

    function check_pass($pass) {

        // grab response
        $resp = $this->admin_m->login(seo_name($this->input->post('username')), $pass);

        // check if we got record.
        if ($resp === FALSE) {
            $this->form_validation->set_message('check_pass', 'The %s field is wrong. Go home kid.');
            return FALSE;
        } else {

            // make a session
            $this->session->set_userdata(array(
                'username'          => $resp['username'],
                'id'                => $resp['id'],
                'seo_username'      => $resp['seo_username'],
                'expire'            => intval(time() + HOUR_IN_SECONDS),
                'authenticated'     => intval(1)
            ));
            return TRUE;
        }
    }
}
