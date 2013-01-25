<?php

class Library {

    public $ci;
    public $lang;
    public $game;
    
    function __construct() {
        $this->_ci = & get_instance();
        $this->lang = "english";
        $this->game = "h4";
    }
    
    
    
    // ---------------------------------------------------------------
    // API Calls
    // ---------------------------------------------------------------
    
    /**
     * get_url
     * 
     * Takes the url along w/ language / game, parameter is just paras of the URL
     * @param type $paras
     * @return type
     */
    private function get_url($paras) {
        return $this->_ci->curl->simple_get("https://stats.svc.halowaypoint.com/" . $this->lang . "/" . $this->game . $paras, array(CURLOPT_HTTPHEADER=> 'Accept: aaplication/json'));
    }
    
    public function get_challenges() {
        $resp = $this->get_url("/challenges");
        return $resp;
    }
}