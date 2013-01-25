<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends H4_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->template->build('pages/home');
    }
    
    function home() {
        $this->template->build('pages/home');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */