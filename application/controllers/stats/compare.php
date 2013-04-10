<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->template->build("pages/comingsoon");
    }
}