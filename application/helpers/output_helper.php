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

function search_big($leaders, $needle) {
    foreach($leaders as $key => $leader) {
        if ($leader['Gamertag'] === $needle) {
            return TRUE;
        }
    }
    return FALSE;
}