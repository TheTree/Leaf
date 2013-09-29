<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {


    public function __construct() {
        parent::__construct();

        $this->template->set_layout('default');
        $this->template->title('Unit Test Results');
        $this->load->library('unit_test');
    }

    public function tests() {

        // start (DEBUG)
        $this->unit->run(1+1,2,"1 + 1 = 2", "Used for testing whether unit testing actually works");

        // run the unit tests
        $this->check_for_msgpack();
        $this->check_for_mongodb();
        $this->check_for_curl();
        $this->check_for_redis();

        // end generate report
        $this->_build();
    }

    private function check_for_msgpack() {
        $exists = (function_exists("msgpack_pack") ? TRUE : FALSE);
        $this->unit->run($exists, TRUE, "Msgpack Exists?", "Tests if msgpack extension is installed and running.");
    }

    private function check_for_mongodb() {
        $exists = (class_exists("MongoClient") ? TRUE : FALSE);
        $this->unit->run($exists, TRUE, "MongoDB Exists?", "Tests if MongoDB PHP extension is installed and running.");
    }

    private function check_for_curl() {
        $exists = (function_exists("curl_version") ? TRUE : FALSE);
        $this->unit->run($exists, TRUE, "Curl Exists?", "Tests if Curl is running and installed.");
    }

    private function check_for_redis() {
        $exists = (class_exists("Redis") ? TRUE : FALSE);
        $this->unit->run($exists, TRUE, "Redis Exists?" , "Tests if Redis PHP Driver is installed and running.");
    }

    private function _build(){
        $this->template
            ->set('test_results', $this->unit->result())
            ->build('pages/tests/results');
    }
}