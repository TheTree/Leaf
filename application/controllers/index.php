<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends IBOT_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model', "news_m", true);
    }

    function about() {

        $this->utils->description = "LeafApp .:. About Us";

        $this->template
            ->set("meta", $this->utils->return_meta())
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

    function h4_profile_seo($gt) {
        redirect('h4/record/' . $gt, 'location', 301);
    }

    function h4_leaderboard_seo($type) {
        redirect('h4/csr_leaderboards/' . $type, 'location', 301);
    }

    function h4_compare($you, $them) {
        redirect('h4/compare/' . $you . "/" . $them, 'location', 301);
    }

    function h4_compare_one_seo($you) {
        redirect('h4/compare/' . $you, 'location', 301);
    }

    function h4_leaderboard_static_seo() {
        redirect('h4/csr_leaderboards', 'location', 301);
    }

    function h4_top_ten_static_seo() {
        redirect('h4/top_ten', 'location', 301);
    }

    function h4_compare_static_seo() {
        redirect('h4/compare', 'location', 301);
    }
}