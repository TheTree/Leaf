<?php

class Leaf_Compare {

    public $you;
    public $them;

    function __construct($paras) {
        $this->_ci = & get_instance();

        // set globals
        $this->you = $this->_ci->library->grab_profile_data($paras['you'], false, $paras['you']);
        $this->them = $this->_ci->library->grab_profile_data($paras['them'], false, $paras['them']);

    }

    function compare() {

        // return array;
        $rtr_arr = array();

        // list of functions to run
        $comparisons = ["MediumSpartan", "HighestRank"];

        // run em
        foreach ($comparisons as $task) {
            $rtr_arr[] = $this->$task();
        }
        return array(
            'you' => $this->you,
            'them' => $this->them,
            'stats' => $rtr_arr
        );
    }

    //----------------------------------------
    // Comparison Functions
    //----------------------------------------
    function MediumSpartan() {
        // lets get the small spartan image
        $this->you['SpartanSmallUrl']    = $this->_ci->library->return_image_url('Spartan', $this->you['Gamertag'],'small');
        $this->them['SpartanSmallUrl']   = $this->_ci->library->return_image_url('Spartan', $this->them['Gamertag'],'small');
    }

    function HighestRank() {
        $you_pts = 0;
        $you_style = "";
        $them_pts = 0;
        $them_style = "";

        if ($this->you['Rank'] == $this->them['Rank']) {
            $you_pts = $them_pts = 0;
        } else if ($this->you['Rank'] > $this->them['Rank']) {
            $you_pts = 1;
            $you_style = "badge-success";
        } else {
            $them_pts = 1;
            $them_style = "badge-success";
        }

        // return
        return array(
            'Name' => "Highest Rank",
            'Max' => 1,
            'YouField' => "Rank",
            'ThemField' => "Rank",
            'you' => array(
                'pts' => intval($you_pts),
                'style' => $you_style),
            'them' => array(
                'pts' => intval($them_pts),
                'style' => $them_style),
            'higher' => TRUE
        );
    }


}
