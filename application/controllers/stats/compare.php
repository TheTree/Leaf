<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare extends H4_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->template->build("pages/comingsoon");
    }
}
?>