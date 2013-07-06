<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    public $_output = [];
    public $_incoming;

    public $_error = "";

    function __construct() {
        parent::__construct();

        // grab the incoming response
        $this->_incoming = @json_decode(file_get_contents("php://input"), TRUE);
        $this->output->enable_profiler(FALSE);

        // load model
        $this->load->model('stat_model', 'stat_m', true);

        // check for valid auth
        $this->_setup();
    }

    function __destruct() {

        if (strlen($this->_error) > 5) {
            $this->_output['status']['error'] = TRUE;
            $this->_output['status']['msg'] = $this->_error;
        } else {
            $this->_output['status']['error'] = FALSE;
            $this->_output['status']['msg'] = 'Ok';
        }

        // add timestamp
        $this->add_key('timestamp', time());

        // bye bye
        echo json_encode($this->_output);
    }

    private function _setup() {
        if (isset($this->_incoming['auth'])) {

            // check for valid user/pass
            if (isset($this->_incoming['auth']['user']) && isset($this->_incoming['auth']['pass'])) {

                // check for user
                $_tmp = $this->stat_m->validate_api($this->_incoming['auth']['user'], $this->_incoming['auth']['pass']);

            } else {
                $this->_error = "No user/pass block passed. (EO2)";
                return;
            }
        } else {
            $this->_error = "No authentication block passed. (EO1)";
            return;
        }
    }

    public function add_key($key, $value) {
        $this->_output[$key] = $value;
    }

    public function index() {

    }

}
