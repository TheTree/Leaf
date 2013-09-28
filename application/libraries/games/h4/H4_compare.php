<?php

class H4_Compare {

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
        $this->you = $this->_ci->h4_lib->grab_profile_data("", FALSE, $paras['you']);
        $this->them = $this->_ci->h4_lib->grab_profile_data("", FALSE, $paras['them']);

        // check for falses
        if ($this->you == FALSE || $this->them == FALSE) {
            // show error page
            $this->_ci->template->set("error_msg", "We could not find one of these accounts. Please try again.");
            $this->error_hit = TRUE;
        } else if ($this->you[H4::SEO_GAMERTAG] == $this->them[H4::SEO_GAMERTAG]) {
            // silly dog tricks r 4 kids
            $this->_ci->template->set("error_msg", "funny guy. can't search yo self");
            $this->error_hit = TRUE;
        } else if ($this->you[H4::STATUS] > 0) {
            $this->_ci->template->set("error_msg", $this->you[H4::GAMERTAG] . " is not allowed to be compared against due to " . $this->_ci->h4_lib->get_banned_name($this->you[H4::STATUS], TRUE));
            $this->error_hit = TRUE;
        } else if ($this->them[H4::STATUS] > 0) {
            $this->_ci->template->set("error_msg", $this->them[H4::GAMERTAG] . " is not allowed to be compared against due to " . $this->_ci->h4_lib->get_banned_name($this->them[H4::STATUS], TRUE));
            $this->error_hit = TRUE;
        } else {
            // unseralize
            $this->you[H4::SKILL_DATA]  = $this->_ci->h4_lib->return_highest_csr($this->_ci->h4_lib->return_csr($this->you[H4::SKILL_DATA]));
            $this->them[H4::SKILL_DATA] = $this->_ci->h4_lib->return_highest_csr($this->_ci->h4_lib->return_csr($this->them[H4::SKILL_DATA]));
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
            $this->you['TotalStyle'] = "label-danger";
        } else if ($this->you['TotalPoints'] == 0) {
            $this->you['TotalStyle'] = "label-default";
        } else {
            $this->you['TotalStyle'] = "label-success";
        }

        // check them style
        if ($this->them['TotalPoints'] < 0) {
            $this->them['TotalStyle'] = "label-danger";
        } else if ($this->them['TotalPoints'] == 0) {
            $this->them['TotalStyle'] = "label-default";
        } else {
            $this->them['TotalStyle'] = "label-success";
        }

        $final_arr = array();

        // determine the winner
        if ($this->them['TotalPoints'] == $this->you['TotalPoints']) {
            $final_arr['Status'] = 'T';
            $final_arr['Style'] = "";
            $final_arr['Winner'] = "";
            $final_arr['TweetWord'] = "tied";
            $final_arr['Winner'] = $this->you[H4::GAMERTAG];
            $final_arr['Looser'] = $this->them[H4::GAMERTAG];
        } else if ($this->you['TotalPoints'] > $this->them['TotalPoints']) {
            $final_arr['Style'] = "alert-success";
            $final_arr['Status'] = 'W';
            $final_arr['Winner'] = $this->you[H4::GAMERTAG];
            $final_arr['Looser'] = $this->them[H4::GAMERTAG];
            $final_arr['TweetWord'] = "won against";
        } else {
            $final_arr['Style'] = "alert-danger";
            $final_arr['Status'] = 'L';
            $final_arr['Winner'] = $this->them[H4::GAMERTAG];
            $final_arr['Looser'] = $this->you[H4::GAMERTAG];
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
        $this->them_style = "label-success";
    }

    function you_num($num = 1) {
        $this->you_pts = intval($num);
        $this->you_overall += intval($num);
        $this->you_style = "label-success";
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
        $this->you['SpartanSmallUrl']    = $this->_ci->h4_lib->return_image_url('Spartan', $this->you[H4::GAMERTAG],'small');
        $this->them['SpartanSmallUrl']   = $this->_ci->h4_lib->return_image_url('Spartan', $this->them[H4::GAMERTAG],'small');
    }

    function HighestRank() {

        if ($this->you[H4::RANK] == $this->them[H4::RANK]) {
            $this->tie(2);
        } else if ($this->you[H4::RANK] > $this->them[H4::RANK]) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // var prep
        $this->you[H4::RANK] = "SR-" . $this->you[H4::RANK];
        $this->them[H4::RANK] = "SR-" . $this->them[H4::RANK];

        // return
        return array(
            'Name' => "Highest Rank",
            'Max' => 1,
            'Field' => H4::RANK,
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
        if ($this->you[H4::MEDALS_PER_GAME_RATIO] == $this->them[H4::MEDALS_PER_GAME_RATIO]) {
            $this->tie(2);
        } else if ($this->you[H4::MEDALS_PER_GAME_RATIO] > $this->them[H4::MEDALS_PER_GAME_RATIO]) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Medals per game">MpG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::MEDALS_PER_GAME_RATIO,
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
        if ($this->you[H4::KILLS_PER_GAME_RATIO] == $this->them[H4::KILLS_PER_GAME_RATIO]) {
            $this->tie(2);
        } else if ($this->you[H4::KILLS_PER_GAME_RATIO] > $this->them[H4::KILLS_PER_GAME_RATIO]) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Kills per game">KpG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::KILLS_PER_GAME_RATIO,
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
        if ($this->you[H4::HEADSHOTS_PER_GAME_RATIO] == $this->them[H4::HEADSHOTS_PER_GAME_RATIO]) {
            $this->tie(2);
        } else if ($this->you[H4::HEADSHOTS_PER_GAME_RATIO] > $this->them[H4::HEADSHOTS_PER_GAME_RATIO]) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Headshots per game">HpG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::HEADSHOTS_PER_GAME_RATIO,
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
        if ($this->you[H4::ASSISTS_PER_GAME_RATIO] == $this->them[H4::ASSISTS_PER_GAME_RATIO]) {
            $this->tie(2);
        } else if ($this->you[H4::ASSISTS_PER_GAME_RATIO] > $this->them[H4::ASSISTS_PER_GAME_RATIO]) {
            $this->you_num(2);
        } else {
            $this->them_num(2);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Assists per game">ApG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::ASSISTS_PER_GAME_RATIO,
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

        if ($this->you[H4::KAD_RATIO] == $this->them[H4::KAD_RATIO]) {
            $this->tie(3);
        } else if ($this->you[H4::KAD_RATIO] > $this->them[H4::KAD_RATIO]) {
            $this->you_num(3);
        } else {
            $this->them_num(3);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Kills + Assists / Deaths">KA/D</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::KAD_RATIO,
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
        if ($this->you[H4::SUICIDES_PER_GAME_RATIO] == $this->them[H4::SUICIDES_PER_GAME_RATIO]) {
            $this->reset_function_vars();
        } else if ($this->you[H4::SUICIDES_PER_GAME_RATIO] > $this->them[H4::SUICIDES_PER_GAME_RATIO]) {
            $this->them_num(1);
        } else {
            $this->you_num(1);
        }

        // return
        return array(
            'Name' =>'Lowest <abbr title="Suicides per game">SpG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::SUICIDES_PER_GAME_RATIO,
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
        if ($this->you[H4::DEATHS_PER_GAME_RATIO] == $this->them[H4::DEATHS_PER_GAME_RATIO]) {
            $this->reset_function_vars();
        } else if ($this->you[H4::DEATHS_PER_GAME_RATIO] > $this->them[H4::DEATHS_PER_GAME_RATIO]) {
            $this->them_num(2);
        } else {
            $this->you_num(2);
        }

        // return
        return array(
            'Name' =>'Lowest <abbr title="Deaths per game">DpG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::DEATHS_PER_GAME_RATIO,
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
        if ($this->you[H4::BETRAYALS_PER_GAME_RATIO] == $this->them[H4::BETRAYALS_PER_GAME_RATIO]) {
            $this->reset_function_vars();
        } else if ($this->you[H4::BETRAYALS_PER_GAME_RATIO] > $this->them[H4::BETRAYALS_PER_GAME_RATIO]) {
            $this->them_num(1);
        } else {
            $this->you_num(1);
        }

        // return
        return array(
            'Name' =>'Lowest <abbr title="Betrayals per game">BpG</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::BETRAYALS_PER_GAME_RATIO,
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
        if ($this->you[H4::WIN_PERCENTAGE] == $this->them[H4::WIN_PERCENTAGE]) {
            $this->tie(3);
        } else if ($this->you[H4::WIN_PERCENTAGE] > $this->them[H4::WIN_PERCENTAGE]) {
            $this->you_num(3);
        } else {
            $this->them_num(3);
        }

        // prep vars
        $this->you[H4::WIN_PERCENTAGE] = $this->you[H4::WIN_PERCENTAGE] * 100 . "%";
        $this->them[H4::WIN_PERCENTAGE] = $this->them[H4::WIN_PERCENTAGE] * 100 . "%";

        // return
        return array(
            'Name' => "Win Percentage",
            'Max' => 1,
            'Field' => H4::WIN_PERCENTAGE,
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
        if ($this->you[H4::QUIT_PERCENTAGE] == $this->them[H4::QUIT_PERCENTAGE]) {
            $this->reset_function_vars();
        } else if ($this->you[H4::QUIT_PERCENTAGE] > $this->them[H4::QUIT_PERCENTAGE]) {
            $this->them_num(1);
        } else {
            $this->you_num(1);
        }

        // prep vars
        $this->you[H4::QUIT_PERCENTAGE] = $this->you[H4::QUIT_PERCENTAGE] * 100 . "%";
        $this->them[H4::QUIT_PERCENTAGE] = $this->them[H4::QUIT_PERCENTAGE] * 100 . "%";

        // return
        return array(
            'Name' => "Quit Percentage",
            'Max' => 1,
            'Field' => H4::QUIT_PERCENTAGE,
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
        if ($this->you[H4::TOTAL_COMMENDATION_PROGRESS] == $this->them[H4::TOTAL_COMMENDATION_PROGRESS]) {
            $this->tie();
        } else if ($this->you[H4::TOTAL_COMMENDATION_PROGRESS] > $this->them[H4::TOTAL_COMMENDATION_PROGRESS]) {
            $this->you_num(1);
        } else {
            $this->them_num(1);
        }

        // prep vars
        $this->you[H4::TOTAL_COMMENDATION_PROGRESS] = $this->you[H4::TOTAL_COMMENDATION_PROGRESS] * 100 . "%";
        $this->them[H4::TOTAL_COMMENDATION_PROGRESS] = $this->them[H4::TOTAL_COMMENDATION_PROGRESS] * 100 . "%";

        // return
        return array(
            'Name' => "Commendation Progress",
            'Max' => 1,
            'Field' => H4::TOTAL_COMMENDATION_PROGRESS,
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
        if ($this->you[H4::AVERAGE_PERSONAL_SCORE] == $this->them[H4::AVERAGE_PERSONAL_SCORE]) {
            $this->tie(3);
        } else if ($this->you[H4::AVERAGE_PERSONAL_SCORE] > $this->them[H4::AVERAGE_PERSONAL_SCORE]) {
            $this->you_num(3);
        } else {
            $this->them_num(3);
        }

        // return
        return array(
            'Name' => '<abbr title="Average">Avg</abbr> Personal Score',
            'Max' => 1,
            'Field' => H4::AVERAGE_PERSONAL_SCORE,
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
        if (count($this->you[H4::SKILL_DATA]) > 0) {
            $you_top = $this->you[H4::SKILL_DATA]['Team'];
        } else {
            $you_top = FALSE;
        }

        if (count($this->them[H4::SKILL_DATA]) > 0) {
            $them_top = $this->them[H4::SKILL_DATA]['Team'];
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
        if (count($this->you[H4::SKILL_DATA]) > 0) {
            $you_top = $this->you[H4::SKILL_DATA]['Ind'];
        } else {
            $you_top = FALSE;
        }

        if (count($this->them[H4::SKILL_DATA]) > 0) {
            $them_top = $this->them[H4::SKILL_DATA]['Ind'];
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
        if ($this->you[H4::KD_RATIO] == $this->them[H4::KD_RATIO]) {
            $this->tie(5);
        } else if ($this->you[H4::KD_RATIO] > $this->them[H4::KD_RATIO]) {
            $this->you_num(5);
        } else {
            $this->them_num(5);
        }

        // return
        return array(
            'Name' =>'Highest <abbr title="Kill / Death">KD</abbr> Ratio',
            'Max' => 1,
            'Field' => H4::KD_RATIO,
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
        if ($this->you[H4::TOTAL_CHALLENGES_COMPLETED] == $this->them[H4::TOTAL_CHALLENGES_COMPLETED]) {
            $this->tie();
        } else if ($this->you[H4::TOTAL_CHALLENGES_COMPLETED] > $this->them[H4::TOTAL_CHALLENGES_COMPLETED]) {
            $this->you_num(1);
        } else {
            $this->them_num(1);
        }

        // return
        return array(
            'Name' =>'Challenges Completed',
            'Max' => 1,
            'Field' => H4::TOTAL_CHALLENGES_COMPLETED,
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
