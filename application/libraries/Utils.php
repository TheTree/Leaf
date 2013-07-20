<?php

class Utils {

    protected $_ci;

    // key for sort functions
    public $sort_key = "";

    // meta stuff
    public $keywords;
    public $description;

    function __construct() {
        $this->_ci = & get_instance();

        // helper functions
        $this->_ci->load->helper("path");
    }

    //----------------------------------------------------------------
    // START: Third Party
    //----------------------------------------------------------------

    /**
     * A function for making time periods readable
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     2.0.1
     * @link        http://aidanlister.com/2004/04/making-time-periods-readable/
     * @param       int     number of seconds elapsed
     * @param       string  which time periods to display
     * @param       bool    whether to show zero time periods
     * @return      string
     */
    function time_duration($seconds, $use = NULL, $zeros = FALSE) {
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
            if ($use && strpos($use, $period[0]) === FALSE) {
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

    //----------------------------------------------------------------
    // END: Third Party
    //----------------------------------------------------------------

    //----------------------------------------------------------------
    // START: Helpers
    //----------------------------------------------------------------

    public function set_sort_key($sort_key) {
        $this->sort_key = $sort_key;
    }

    public function get_sort_key() {
        return $this->sort_key;
    }

    public function return_meta() {
        return array(
            'description' => $this->description,
            'keywords' => $this->keywords
        );
    }

    /**
     * is_active
     * Determines if passed $item is equal to navigation
     *
     * @param type   $item
     * @param string $default
     * @param int    $url_num
     * @internal param int $id
     * @return type
     */
    public function is_active($item, $default = "home", $url_num = 1) {
        $url_num = intval($url_num);

        // get segment array
        $segs = $this->_ci->uri->segment_array();
        if(!isset($segs[$url_num]) || $this->_ci->uri->segment($url_num) == ""){
            $uri_string = $default;
        } else {
            $uri_string = $this->_ci->uri->segment($url_num);
        }

        return strpos($uri_string, $item) !== FALSE ? 'active' : '';
    }

    //----------------------------------------------------------------
    // END: Helpers
    //----------------------------------------------------------------

    /**
     * throw_error
     *
     * Takes error_id from language file and uses that on error page.
     * @param $error_id
     */
    public function throw_error($error_id = "GENERAL_ERROR") {

        // get lang file
        $this->_ci->lang->load('errors', 'english');

        // try and load file
        if (($_tmp = $this->_ci->lang->line($error_id)) == FALSE) {
            $_tmp = "I'm sorry, an unknown error has occurred.";
        }

        // set the session & pass it on
        $this->_ci->session->set_flashdata('error_msg', $_tmp);

        // redirect to error page
        redirect('/error/', 'refresh');
    }

    /**
     * key_sort
     *
     * Sorts associative array based on `SkillRank` to sort CSR's in decreasing #
     * @param $a
     * @param $b
     * @return int
     */
    function key_sort($a, $b) {
        if ($a[$this->get_sort_key()] == $b[$this->get_sort_key()]) {
            return 0;
        }
        return ($a[$this->get_sort_key()] < $b[$this->get_sort_key()] ? 1 : -1);
    }
}