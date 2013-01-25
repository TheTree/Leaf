<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends H4_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    function about() {
        $this->template
                ->build("pages/about");
    }
    function index() {
                
        // load challenges
        $tmp = $this->library->get_challenges();
        
        $this->template->set('tmp', $tmp);
        $this->template->build('pages/home');
    }
    
    function home() {
        $this->template->build('pages/home');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */