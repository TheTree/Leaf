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
    // 3rd Party
    // ---------------------------------------------------------------
    
    /**
     * A function for making time periods readable
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     2.0.1
     * @link        http://aidanlister.com/2004/04/making-time-periods-readable/
     * @param       int     number of seconds elapsed
     * @param       string  which time periods to display
     * @param       bool    whether to show zero time periods
     */
    function time_duration($seconds, $use = null, $zeros = false) {
        // Define time periods
        $periods = array(
            'years' => 31556926,
            'Months' => 2629743,
            'weeks' => 604800,
            'days' => 86400,
            'hours' => 3600,
            'minutes' => 60,
            'seconds' => 1
        );

        // Break into periods
        $seconds = (float) $seconds;
        $segments = array();
        foreach ($periods as $period => $value) {
            if ($use && strpos($use, $period[0]) === false) {
                continue;
            }
            $count = floor($seconds / $value);
            if ($count == 0 && !$zeros) {
                continue;
            }
            $segments[strtolower($period)] = $count;
            $seconds = $seconds % $value;
        }

        // Build the string
        $string = array();
        foreach ($segments as $key => $value) {
            $segment_name = substr($key, 0, -1);
            $segment = $value . ' ' . $segment_name;
            if ($value != 1) {
                $segment .= 's';
            }
            $string[] = $segment;
        }

        return implode(', ', $string);
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

        // check Cache
        $resp = $this->_ci->cache->get('current_challenges');

        // check if cache exists.
        if ($resp == false) {
            $resp = $this->fix_date($this->get_url("/challenges"), "BeginDate,EndDate", "Challenges");
            $this->_ci->cache->write($resp, 'current_challenges');
        } else {

            // Check EndDate
            if (time() > $resp['Challenges'][0]['EndDate']) {
                $this->_ci->cache->delete('current_challenges');
                return $this->get_challenges();
            }
        }

        return $resp;
    }

}