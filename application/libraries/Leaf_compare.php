<?php

class Leaf_Compare {

    public $you;
    public $them;

    // function vars
    public $you_pts;
    public $you_style;
    public $them_pts;
    public $them_style;
    public $you_overall     = 0;
    public $them_overall    = 0;

    // error var
    public $error_hit = FALSE;

    function __construct($paras) {
        $this->_ci = & get_instance();

        // set globals
        $this->you = $this->_ci->library->grab_profile_data($paras['you'], FALSE, $paras['you']);
        $this->them = $this->_ci->library->grab_profile_data($paras['them'], FALSE, $paras['them']);

        // check for falses
        if ($this->you == FALSE || $this->them == FALSE) {
            // show error page
            $this->_ci->template->set("error_msg", "We could not find one of these accounts. Please try again.");
            $this->error_hit = TRUE;
        } else if ($this->you['SeoGamertag'] == $this->them['SeoGamertag']) {
            // silly dog tricks r 4 kids
            $this->_ci->template->set("error_msg", "funny guy. can't search yo self");
            $this->error_hit = TRUE;
        } else {
            // unseralize
            $this->you['SkillData'] = @unserialize($this->you['SkillData']);
            $this->them['SkillData'] = @unserialize($this->them['SkillData']);

            // badges
            $this->you['badge'] = $this->_ci->library->get_badge($this->you['Gamertag']);
            $this->them['badge'] = $this->_ci->library->get_badge($this->them['Gamertag']);
        }
    }

    public function get_error() {
        return $this->error_hit;
    }

    function compare() {

        // return array;
        $rtr_arr = array();

        // list of functions to run
        $comparisons = ["MediumSpartan", "HighestRank", "MedalsPerGame", "KillsPerGame", "DeathsPerGame",
            "WinPercentage","QuitPercentage", "CommendationProgress", "ChallengesCompleted",
            "AveragePersonalScore", "KDRatio", "HighestCSR"];

        // run em
        foreach ($comparisons as $task) {

            // reset previous iteration vars
            $this->reset_function_vars();

            // run this task
            $rtr_arr[] = $this->$task();
        }

        // overall
        $this->you['TotalPoints'] = $this->you_overall;
        $this->them['TotalPoints'] = $this->them_overall;

        // check style
        if ($this->you['TotalPoints'] < 0) {
            $this->you['TotalStyle'] = "badge-important";
        } else if ($this->you['TotalPoints'] == 0) {
            $this->you['TotalStyle'] = "";
        } else {
            $this->you['TotalStyle'] = "badge-success";
        }

        // check them style
        if ($this->them['TotalPoints'] < 0) {
            $this->them['TotalStyle'] = "badge-important";
        } else if ($this->them['TotalPoints'] == 0) {
            $this->them['TotalStyle'] = "";
        } else {
            $this->them['TotalStyle'] = "badge-success";
        }

        $final_arr = array();

        // determine the winner
        if ($this->them['TotalPoints'] == $this->you['TotalPoints']) {
            $final_arr['Status'] = 'T';
            $final_arr['Style'] = "";
            $final_arr['Winner'] = "";
            $final_arr['TweetWord'] = "tied";
            $final_arr['Winner'] = $this->you['Gamertag'];
            $final_arr['Looser'] = $this->them['Gamertag'];
        } else if ($this->you['TotalPoints'] > $this->them['TotalPoints']) {
            $final_arr['Style'] = "alert-success";
            $final_arr['Status'] = 'W';
            $final_arr['Winner'] = $this->you['Gamertag'];
            $final_arr['Looser'] = $this->them['Gamertag'];
            $final_arr['TweetWord'] = "won against";
        } else {
            $final_arr['Style'] = "alert-error";
            $final_arr['Status'] = 'L';
            $final_arr['Winner'] = $this->them['Gamertag'];
            $final_arr['Looser'] = $this->you['Gamertag'];
            $final_arr['TweetWord'] = "lost to";
        }

        return array(
            'you' => $this->you,
            'them' => $this->them,
            'stats' => $rtr_arr,
            'final' => $final_arr
        );
    }

    function reset_function_vars() {
        $this->you_pts = 0;
        $this->them_pts = 0;
        $this->you_style = "";
        $this->them_style = "";
    }

    function you_plus_one_good() {
        $this->you_pts = 1;
        $this->you_overall += 1;
        $this->you_style = "badge-success";
    }

    function you_plus_two_good() {
        $this->you_pts = 2;
        $this->you_overall += 2;
        $this->you_style = "badge-success";
    }

    function them_plus_one_good() {
        $this->them_pts = 1;
        $this->them_overall += 1;
        $this->them_style = "badge-success";
    }

    function them_plus_two_good() {
        $this->them_pts = 2;
        $this->them_overall += 2;
        $this->them_style = "badge-success";
    }

    function two_tie() {
        $this->you_plus_two_good();
        $this->them_plus_two_good();
    }

    function tie() {
        $this->you_plus_one_good();
        $this->them_plus_one_good();
    }

    public function wrap_image($img_src) {
        return '<img src="' . $img_src . '" />';
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

        if ($this->you['Rank'] == $this->them['Rank']) {
            $this->tie();
        } else if ($this->you['Rank'] > $this->them['Rank']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // var prep
        $this->you['Rank'] = "SR-" . $this->you['Rank'];
        $this->them['Rank'] = "SR-" . $this->them['Rank'];

        // return
        return array(
            'Name' => "Highest Rank",
            'Max' => 1,
            'Field' => "Rank",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function MedalsPerGame() {
        if ($this->you['MedalsPerGameRatio'] == $this->them['MedalsPerGameRatio']) {
            $this->tie();
        } else if ($this->you['MedalsPerGameRatio'] > $this->them['MedalsPerGameRatio']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Medals per game">MpG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'MedalsPerGameRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function KillsPerGame() {
        if ($this->you['KillsPerGameRatio'] == $this->them['KillsPerGameRatio']) {
            $this->tie();
        } else if ($this->you['KillsPerGameRatio'] > $this->them['KillsPerGameRatio']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Kills per game">KpG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'KillsPerGameRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function DeathsPerGame() {
        if ($this->you['DeathsPerGameRatio'] == $this->them['DeathsPerGameRatio']) {
            $this->reset_function_vars();
        } else if ($this->you['DeathsPerGameRatio'] > $this->them['DeathsPerGameRatio']) {
            $this->them_plus_one_good();
        } else {
            $this->you_plus_one_good();
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Deaths per game">DpG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'DeathsPerGameRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => FALSE
        );
    }

    function WinPercentage() {
        if ($this->you['WinPercentage'] == $this->them['WinPercentage']) {
            $this->tie();
        } else if ($this->you['WinPercentage'] > $this->them['WinPercentage']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // prep vars
        $this->you['WinPercentage'] = $this->you['WinPercentage'] * 100 . "%";
        $this->them['WinPercentage'] = $this->them['WinPercentage'] * 100 . "%";

        // return
        return array(
            'Name' => "Win Percentage",
            'Max' => 1,
            'Field' => "WinPercentage",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function QuitPercentage() {
        if ($this->you['QuitPercentage'] == $this->them['QuitPercentage']) {
            $this->reset_function_vars();
        } else if ($this->you['QuitPercentage'] > $this->them['QuitPercentage']) {
            $this->them_plus_one_good();
        } else {
            $this->you_plus_one_good();
        }

        // prep vars
        $this->you['QuitPercentage'] = $this->you['QuitPercentage'] * 100 . "%";
        $this->them['QuitPercentage'] = $this->them['QuitPercentage'] * 100 . "%";

        // return
        return array(
            'Name' => "Quit Percentage",
            'Max' => 1,
            'Field' => "QuitPercentage",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => FALSE
        );
    }

    function CommendationProgress() {
        if ($this->you['TotalCommendationProgress'] == $this->them['TotalCommendationProgress']) {
            $this->tie();
        } else if ($this->you['TotalCommendationProgress'] > $this->them['TotalCommendationProgress']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // prep vars
        $this->you['TotalCommendationProgress'] = $this->you['TotalCommendationProgress'] * 100 . "%";
        $this->them['TotalCommendationProgress'] = $this->them['TotalCommendationProgress'] * 100 . "%";

        // return
        return array(
            'Name' => "Commendation Progress",
            'Max' => 1,
            'Field' => "TotalCommendationProgress",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function AveragePersonalScore() {
        if ($this->you['AveragePersonalScore'] == $this->them['AveragePersonalScore']) {
            $this->tie();
        } else if ($this->you['AveragePersonalScore'] > $this->them['AveragePersonalScore']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // return
        return array(
            'Name' => '<abbr title="Average">Avg</abbr> Personal Score',
            'Max' => 1,
            'Field' => "AveragePersonalScore",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function HighestCSR() {
        $you_top = FALSE;
        $them_top = FALSE;

        // get top CSR
        if (count($this->you['SkillData']) > 0) {
            foreach($this->you['SkillData'] as $item) {
                if ($item['Top']) {
                    $you_top = $item;
                    break;
                }
            }
        } else {
            $you_top = FALSE;
        }

        if (count($this->them['SkillData']) > 0) {
            foreach($this->them['SkillData'] as $item) {
                if ($item['Top']) {
                    $them_top = $item;
                    break;
                }
            }
        } else {
            $them_top = FALSE;
        }

        // compare them
        if ($you_top == FALSE && $them_top == FALSE) {
            $this->reset_function_vars();
            $this->you['TopCSR']  = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval(0), "small"));
            $this->them['TopCSR'] = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval(0), "small"));
        } else if ($you_top == FALSE && $them_top != FALSE) {
            $this->you['TopCSR']  = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval(0), "small"));
            $this->them['TopCSR'] = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($them_top['SkillRank']), "small"));
            $this->them_plus_one_good();
        } else if ($you_top != FALSE && $them_top == FALSE) {
            $this->you['TopCSR']  = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($you_top['SkillRank']), "small"));
            $this->them['TopCSR'] = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval(0), "small"));
            $this->you_plus_one_good();
        } else if ($you_top['SkillRank'] == $them_top['SkillRank']) {
            $this->you['TopCSR']  = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($you_top['SkillRank']), "small"));
            $this->them['TopCSR'] = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($them_top['SkillRank']), "small"));
            $this->tie();
        } else if ($you_top['SkillRank'] > $them_top['SkillRank']) {
            $this->you['TopCSR']  = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($you_top['SkillRank']), "small"));
            $this->them['TopCSR'] = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($them_top['SkillRank']), "small"));
            $this->you_plus_one_good();
        } else if ($you_top['SkillRank'] < $them_top['SkillRank']) {
            $this->you['TopCSR']  = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($you_top['SkillRank']), "small"));
            $this->them['TopCSR'] = $this->wrap_image($this->_ci->library->return_image_url('CSR', intval($them_top['SkillRank']), "small"));
            $this->them_plus_one_good();
        }

        // return
        return array(
            'Name' => 'Highest <abbr title="Competitive Skill Rank">CSR</abbr>',
            'Max' => 1,
            'Field' => "TopCSR",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function KDRatio() {
        if ($this->you['KDRatio'] == $this->them['KDRatio']) {
            $this->two_tie();
        } else if ($this->you['KDRatio'] > $this->them['KDRatio']) {
            $this->you_plus_two_good();
        } else {
            $this->them_plus_two_good();
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Kill / Death">KD</abbr> Ratio',
            'Max' => 1,
            'Field' => 'KDRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function ChallengesCompleted() {
        if ($this->you['TotalChallengesCompleted'] == $this->them['TotalChallengesCompleted']) {
            $this->tie();
        } else if ($this->you['TotalChallengesCompleted'] > $this->them['TotalChallengesCompleted']) {
            $this->you_plus_one_good();
        } else {
            $this->them_plus_one_good();
        }

        // return
        return array(
            'Name' =>'Challenges Completed',
            'Max' => 1,
            'Field' => 'TotalChallengesCompleted',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }
}
