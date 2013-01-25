<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function output($my_array, $my_die = 0) {
    echo '<pre>';
    print_r($my_array);
    echo '</pre>';

    if ($my_die == 1) {
        die();
    }
}