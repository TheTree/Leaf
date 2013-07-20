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
        // we pass NULL to the $gamertag parameter, because we only have SeoGamertag
        // at this point. We can get their Gamertag via SeoGamerta
        $this->you = $this->_ci->library->grab_profile_data("", FALSE, $paras['you']);
        $this->them = $this->_ci->library->grab_profile_data("", FALSE, $paras['them']);

        // check for falses
        if ($this->you == FALSE || $this->them == FALSE) {
            // show error page
            $this->_ci->template->set("error_msg", "We could not find one of these accounts. Please try again.");
            $this->error_hit = TRUE;
        } else if ($this->you['SeoGamertag'] == $this->them['SeoGamertag']) {
            // silly dog tricks r 4 kids
            $this->_ci->template->set("error_msg", "funny guy. can't search yo self");
            $this->error_hit = TRUE;
        } else if ($this->you['Status'] > 0) {
            $this->_ci->template->set("error_msg", $this->you['Gamertag'] . " is not allowed to be compared against due to " . $this->_ci->library->get_banned_name($this->you['Status'], TRUE));
            $this->error_hit = TRUE;
        } else if ($this->them['Status'] > 0) {
            $this->_ci->template->set("error_msg", $this->them['Gamertag'] . " is not allowed to be compared against due to " . $this->_ci->library->get_banned_name($this->them['Status'], TRUE));
            $this->error_hit = TRUE;
        } else {
            // unseralize
            $this->you['SkillData'] = msgpack_unpack(utf8_decode($this->you['SkillData']));
            $this->them['SkillData'] = msgpack_unpack(utf8_decode($this->them['SkillData']));
        }
    }

    public function get_error() {
        return $this->error_hit;
    }

    function compare() {

        // return array;
        $rtr_arr = array();

        // list of functions to run
        $comparisons = ["MediumSpartan", "HighestRank", "MedalsPerGame", "KillsPerGame", "AssistsPerGame","HeadshotsPerGame",
            "DeathsPerGame", "BetraysPerGame", "SuicidesPerGame", "WinPercentage","QuitPercentage", "CommendationProgress",
            "ChallengesCompleted","AveragePersonalScore", "KDRatio","KillsPlusAssistsPerGame", "HighestTeamCSR",
            "HighestIndividualCSR"];

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

    function them_num($num = 1) {
        $this->them_pts = intval($num);
        $this->them_overall += intval($num);
        $this->them_style = "badge-success";
    }

    function you_num($num = 1) {
        $this->you_pts = intval($num);
        $this->you_overall += intval($num);
        $this->you_style = "badge-success";
    }

    function tie($num = 1) {
        $this->them_num($num);
        $this->you_num($num);
    }

    public function wrap_image($rank_num) {
        return '<span class="flair flair-CSR-' . $rank_num . '"></span>';
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
            $this->tie(2);
        } else if ($this->you['Rank'] > $this->them['Rank']) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
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
            $this->tie(2);
        } else if ($this->you['MedalsPerGameRatio'] > $this->them['MedalsPerGameRatio']) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
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
            $this->tie(2);
        } else if ($this->you['KillsPerGameRatio'] > $this->them['KillsPerGameRatio']) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
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

    function HeadshotsPerGame() {
        if ($this->you['HeadshotsPerGameRatio'] == $this->them['HeadshotsPerGameRatio']) {
            $this->tie(2);
        } else if ($this->you['HeadshotsPerGameRatio'] > $this->them['HeadshotsPerGameRatio']) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Headshots per game">HpG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'HeadshotsPerGameRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function AssistsPerGame() {
        if ($this->you['AssistsPerGameRatio'] == $this->them['AssistsPerGameRatio']) {
            $this->tie(2);
        } else if ($this->you['AssistsPerGameRatio'] > $this->them['AssistsPerGameRatio']) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Assists per game">ApG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'AssistsPerGameRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function KillsPlusAssistsPerGame() {
        $this->you['KillsPlusAssistsPerGame']   = round(floatval(($this->you['TotalKills'] + $this->you['TotalAssists']) / $this->you['TotalDeaths']), 2);
        $this->them['KillsPlusAssistsPerGame']  = round(floatval(( $this->them['TotalKills'] +  $this->them['TotalAssists']) /  $this->them['TotalDeaths']), 2);

        if ($this->you['KillsPlusAssistsPerGame'] == $this->them['KillsPlusAssistsPerGame']) {
            $this->tie(3);
        } else if ($this->you['KillsPlusAssistsPerGame'] > $this->them['KillsPlusAssistsPerGame']) {
            $this->you_num(3);
        } else {
            $this->them_num(3);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Kills + Assists / Deaths">KA/D</abbr> Ratio',
            'Max' => 1,
            'Field' => 'KillsPlusAssistsPerGame',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function SuicidesPerGame() {
        if ($this->you['SuicidesPerGameRatio'] == $this->them['SuicidesPerGameRatio']) {
            $this->reset_function_vars();
        } else if ($this->you['SuicidesPerGameRatio'] > $this->them['SuicidesPerGameRatio']) {
            $this->them_num(1);
        } else {
            $this->you_num(1);
        }

        // return
        return array(
            'Name' =>'Lowest <abbr title="Suicides per game">SpG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'SuicidesPerGameRatio',
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => FALSE
        );
    }

    function DeathsPerGame() {
        if ($this->you['DeathsPerGameRatio'] == $this->them['DeathsPerGameRatio']) {
            $this->reset_function_vars();
        } else if ($this->you['DeathsPerGameRatio'] > $this->them['DeathsPerGameRatio']) {
            $this->them_num(2);
        } else {
            $this->you_num(2);
        }

        // return
        return array(
            'Name' =>'Lowest <abbr title="Deaths per game">DpG</abbr> Ratio',
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

    function BetraysPerGame() {
        if ($this->you['BetrayalsPerGameRatio'] == $this->them['BetrayalsPerGameRatio']) {
            $this->reset_function_vars();
        } else if ($this->you['BetrayalsPerGameRatio'] > $this->them['BetrayalsPerGameRatio']) {
            $this->them_num(1);
        } else {
            $this->you_num(1);
        }

        // return
        return array(
            'Name' =>'Lowest <abbr title="Betrayals per game">BpG</abbr> Ratio',
            'Max' => 1,
            'Field' => 'BetrayalsPerGameRatio',
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
            $this->tie(3);
        } else if ($this->you['WinPercentage'] > $this->them['WinPercentage']) {
            $this->you_num(3);
        } else {
            $this->them_num(3);
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
            $this->them_num(1);
        } else {
            $this->you_num(1);
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
            $this->you_num(1);
        } else {
            $this->them_num(1);
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
            $this->tie(3);
        } else if ($this->you['AveragePersonalScore'] > $this->them['AveragePersonalScore']) {
            $this->you_num(3);
        } else {
            $this->them_num(3);
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

    function HighestTeamCSR() {
        $you_top = FALSE;
        $them_top = FALSE;

        // get top CSR
        if (count($this->you['SkillData']) > 0) {
            $you_top = $this->you['SkillData']['Team'];
        } else {
            $you_top = FALSE;
        }

        if (count($this->them['SkillData']) > 0) {
            $them_top = $this->them['SkillData']['Team'];
        } else {
            $them_top = FALSE;
        }

        // compare them
        if ($you_top == FALSE && $them_top == FALSE) {
            $this->reset_function_vars();
            $this->you['TopTeamCSR']  = $this->wrap_image(intval(0));
            $this->them['TopTeamCSR'] = $this->wrap_image(intval(0));
        } else if ($you_top == FALSE && $them_top != FALSE) {
            $this->you['TopTeamCSR']  = $this->wrap_image(intval(0));
            $this->them['TopTeamCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->them_num(3);
        } else if ($you_top != FALSE && $them_top == FALSE) {
            $this->you['TopTeamCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopTeamCSR'] = $this->wrap_image(intval(0));
            $this->you_num(3);
        } else if ($you_top['SkillRank'] == $them_top['SkillRank']) {
            $this->you['TopTeamCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopTeamCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->tie(3);
        } else if ($you_top['SkillRank'] > $them_top['SkillRank']) {
            $this->you['TopTeamCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopTeamCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->you_num(3);
        } else if ($you_top['SkillRank'] < $them_top['SkillRank']) {
            $this->you['TopTeamCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopTeamCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->them_num(3);
        }

        // return
        return array(
            'Name' => 'Highest Team <abbr title="Competitive Skill Rank">CSR</abbr>',
            'Max' => 1,
            'Field' => "TopTeamCSR",
            'you' => array(
                'pts' => intval($this->you_pts),
                'style' => $this->you_style),
            'them' => array(
                'pts' => intval($this->them_pts),
                'style' => $this->them_style),
            'higher' => TRUE
        );
    }

    function HighestIndividualCSR() {
        $you_top = FALSE;
        $them_top = FALSE;

        // get top CSR
        if (count($this->you['SkillData']) > 0) {
            $you_top = $this->you['SkillData']['Ind'];
        } else {
            $you_top = FALSE;
        }

        if (count($this->them['SkillData']) > 0) {
            $them_top = $this->them['SkillData']['Ind'];
        } else {
            $them_top = FALSE;
        }

        // compare them
        if ($you_top == FALSE && $them_top == FALSE) {
            $this->reset_function_vars();
            $this->you['TopIndCSR']  = $this->wrap_image(intval(0));
            $this->them['TopIndCSR'] = $this->wrap_image(intval(0));
        } else if ($you_top == FALSE && $them_top != FALSE) {
            $this->you['TopIndCSR']  = $this->wrap_image(intval(0));
            $this->them['TopIndCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->them_num(1);
        } else if ($you_top != FALSE && $them_top == FALSE) {
            $this->you['TopIndCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopIndCSR'] = $this->wrap_image(intval(0));
            $this->you_num(1);
        } else if ($you_top['SkillRank'] == $them_top['SkillRank']) {
            $this->you['TopIndCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopIndCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->tie(1);
        } else if ($you_top['SkillRank'] > $them_top['SkillRank']) {
            $this->you['TopIndCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopIndCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->you_num(1);
        } else if ($you_top['SkillRank'] < $them_top['SkillRank']) {
            $this->you['TopIndCSR']  = $this->wrap_image(intval($you_top['SkillRank']));
            $this->them['TopIndCSR'] = $this->wrap_image(intval($them_top['SkillRank']));
            $this->them_num(1);
        }

        // return
        return array(
            'Name' => 'Highest Individual <abbr title="Competitive Skill Rank">CSR</abbr>',
            'Max' => 1,
            'Field' => "TopIndCSR",
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
            $this->tie(5);
        } else if ($this->you['KDRatio'] > $this->them['KDRatio']) {
            $this->you_num(5);
        } else {
            $this->them_num(5);
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
            $this->you_num(1);
        } else {
            $this->them_num(1);
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
