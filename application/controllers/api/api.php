<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends H4_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    function about() {
        $this->template
                ->build("pages/about");
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/api/api.php */