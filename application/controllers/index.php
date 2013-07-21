<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
    }

    function about() {

        $this->library->description = "LeafApp .:. About Us";
        $this->template->set("meta", $this->utils->return_meta());

        $this->template
            ->title("About")
            ->build("pages/about");
    }

    function error() {
        // get the flash data error
        if (($_tmp = $this->session->flashdata('error_msg')) == FALSE) {
            $_tmp = "hi. this is the error page, but no error was thrown. happy?";
        }

        // set the template and page
        $this->template
            ->title("Leaf .:. Error Occurred")
            ->set("error_msg", $_tmp)
            ->build("pages/error");
    }
}