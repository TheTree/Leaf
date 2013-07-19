<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    public $_output = [];
    public $_incoming;

    public $_error = "";
    public $_payload = "";

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

        // add payload
        if ($this->_payload != "") {
            $this->add_key('data', $this->_payload);
        }

        // bye bye
        echo json_encode($this->_output);
    }

    private function _setup() {
        if (isset($this->_incoming['auth'])) {

            // check for valid user/pass
            if (isset($this->_incoming['auth']['user']) && isset($this->_incoming['auth']['pass'])) {

                // check for user
                if ($this->stat_m->validate_api($this->_incoming['auth']['user'], $this->_incoming['auth']['pass'])) {

                    // update last hit
                    $this->stat_m->update_last_hit($this->_incoming['auth']['pass']);

                    // find action
                    if (isset($this->_incoming['action'])) {
                        $this->_switch($this->_incoming['action']);

                    } else {
                        $this->_error = "No action specified. (EO4)";
                        return;
                    }
                } else {
                    $this->_error = "Invalid user/pass. (EO3)";
                    return;
                }
            } else {
                $this->_error = "No user or pass block passed. (EO2)";
                return;
            }
        } else {
            $this->_error = "No authentication block passed. (EO1)";
            return;
        }
    }

    private function _switch($key) {
        switch($key) {

            case "playlist":
                $this->_payload = $this->_load_playlists();
                break;

            default:
                $this->_error = "No action found. (EO5)";
                return;
        }
    }

    private function _load_playlists() {
        $playlists = $this->stat_m->get_playlists();
        $ind = $this->config->item('h4_individual_csr');
        $team = $this->config->item('h4_team_csr');

        $_data = [];
        foreach($playlists as $playlist) {
            if (in_array($playlist['Id'], $ind)) {
                $csr = "Individual";

            } else if (in_array($playlist['Id'], $team)){
                $csr = "Team";

            } else {
                $csr = "Unknown";
            }

            $_data[] = array(
                'Id' => intval($playlist['Id']),
                'Name' => $playlist['Name'],
                'Description' => $playlist['Description'],
                'CSR'   => $csr
            );
        }
        return $_data;
    }

    public function add_key($key, $value) {
        $this->_output[$key] = $value;
    }

    public function index() {

    }

}
