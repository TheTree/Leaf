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
    // Helper Calls
    // ---------------------------------------------------------------
    
    /**
     * fix_date
     * 
     * Pass the full response from 343,
     * @param type $resp (raw array json)
     * @param type $keys (array keys that need date fixed)
     * @param type $part (Portion where loop starts ex "Challenges")
     * @return type
     */
    public function fix_date($resp, $keys = "", $part = "") {
        
        if ($resp == false) {
            return false;
        }
        
        // blows keys into array
        $keys = explode(",", $keys);

        // loop the entire structure of $part
        foreach ($resp[$part] as $key => $item) {

            // loop for every date to change
            foreach ($keys as $key_pair) {
                if (isset($item[$key_pair])) {
                    $resp[$part][$key][$key_pair] = strtotime($item[$key_pair], true);
                }
            }
        }
                    
            return $resp;
    }
    
    /**
     * check_status
     * 
     * Checks the API for the Status var to make sure its up and running
     * @param type $resp
     * @return boolean
     */
    public function check_status($resp) {
        if (isset($resp['StatusCode']) && $resp['StatusCode'] == 0) {
            return $resp;
        } else {
            log_message('error', 'API Down: ' . $resp['StatusReason']);
            return false;
        }
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

        // set accept header
        $this->_ci->curl->option('HTTPHEADER', array('Accept: application/json'));

        // make the url
        $url = "https://stats.svc.halowaypoint.com/" . $this->lang . "/" . $this->game . $paras;

        // resp
        $resp = json_decode($this->_ci->curl->simple_get($url), true);

        // check it
        return $this->check_status($resp);
    }
    
    public function get_challenges() {
        $resp = $this->fix_date($this->get_url("/challenges"), "BeginDate,EndDate", "Challenges");
        return $resp;
    }
}