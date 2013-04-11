<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Core extends IBOT_Controller {

    function __construct() {
        parent::__construct();

        // load model
        $this->load->model('stat_model', 'stat_m', true);
    }

    public function compare_api($letter = "") {
        $gts = $this->stat_m->load_gamertags($letter);
        $gts_json = array();

        if ($gts != false) {
            foreach ($gts as $key => $value) {
                $gts_json[] = $value['Gamertag'];
            }
        }

        // cleanup
        unset($gts);

        // output
        $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($gts_json));
    }
}
