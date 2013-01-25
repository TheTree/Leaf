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
                
        $this->template->title("Leaf .:. Halo 4 Stats");
        $this->template->set('challenges', $this->library->get_challenges());
        $this->template->build('pages/home');
    }
    
    function home() {
        $this->template->build('pages/home');
    }
    
    function news() {
        $this->template->build("pages/comingsoon");
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */