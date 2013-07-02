<?php

class Utils {

    protected $_ci;

    function __construct() {
        $this->_ci = & get_instance();

        // load path helper, setup vars
        $this->_ci->load->helper("path");
    }


}