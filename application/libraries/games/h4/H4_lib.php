<?php

class H4_Lib {

    protected $_ci;
    public $lang;
    public $game;
    public $cli = FALSE;

    function __construct() {
        $this->_ci = & get_instance();
        $this->lang = "english";
        $this->game = "h4";

        $this->_ci->load->model('h4/stat_model', 'stat_m', TRUE);
        $this->_ci->load->helper("path");
        $this->_ci->load->config('h4_leaf');
    }

    // ---------------------------------------------------------------
    // Helper Calls
    // ---------------------------------------------------------------

    public function set_cli_mode($b) {
        $this->cli = (bool) $b;
    }

    /**
     * fix_date
     * Pass the full response from 343,
     *
     * @param type         $resp (raw array json)
     * @param string|\type $keys (array keys that need date fixed)
     * @param string|\type $part (Portion where loop starts ex "Challenges")
     * @return type
     */
    public function fix_date($resp, $keys = "", $part = "") {

        if ($resp == FALSE) {
            return FALSE;
        }

        // blows keys into array
        $keys = explode(",", $keys);

        // loop the entire structure of $part
        foreach ($resp[$part] as $key => $item) {

            // loop for every date to change
            foreach ($keys as $key_pair) {
                if (isset($item[$key_pair])) {
                    $resp[$part][$key][$key_pair] = strtotime($item[$key_pair], TRUE);
                }
            }
        }

        return $resp;
    }

    /**
     * get_trophy
     * returns image for place
     *
     * @param int|\type $x
     * @return string
     */
    public function get_trophy($x = 0) {

        switch ($x) {

            case 1:
                return '<img title="1st" src="' . base_url("assets/img/icons/medal_gold.png") . '" />';
                break;

            case 2:
                return '<img title="2nd" src="' . base_url("assets/img/icons/medal_silver.png") . '" />';
                break;

            case 3:
                return '<img title="3rd" src="' . base_url("assets/img/icons/medal_bronze.png") . '" />';
                break;

            default:
                return number_format($x) . "<sup>th</sup>";
                break;
        }
    }

    /**
     * check_status
     * Checks the API for the Status var to make sure its up and running
     *
     * @param type $resp
     * @param      $url
     * @return boolean
     */
    public function check_status(&$resp, $url) {
        if (isset($resp['StatusCode']) && ($resp['StatusCode'] == 0 || $resp['StatusCode'] == 1)) {
            return $resp;
        } else {
            $this->_ci->cache->delete('auth_spartan');
            log_message('error', 'URL Down: ' . $url);
            log_message('error', 'API Down: ' . $resp['StatusReason']);
            return FALSE;
        }
    }

    // ---------------------------------------------------------------
    // API Calls
    // ---------------------------------------------------------------


    /**
     * get_metadata()
     *
     * Goes through `/metadata` endpoint and grabs all data (purging all previous data) into the DB. Its ran every week
     * via CRON
     */
    public function get_metadata() {
        $_tmp = $this->get_url($this->lang . "/" . $this->game . "/" . "metadata",FALSE);

        // Achievements
        $ins_arr = array();
        foreach ($_tmp['AchievementsMetadata']['Achievements'] as $ach) {
            $ins_arr[$ach['Id']] = array(
                'Id'                        => $ach['Id'],
                'Name'                      => $ach['Name'],
                'LockedDescription'         => $ach['LockedDescription'],
                'UnlockedDescription'       => $ach['UnlockedDescription'],
                'GamerPoints'               => $ach['GamerPoints'],
                'LockedImageUrlAssetUrl'    => $ach['LockedImageUrl']['AssetUrl'],
                'UnlockedImageUrlAssetUrl'  => $ach['UnlockedImageUrl']['AssetUrl']
            );
        }
        $this->_ci->stat_m->insert_metadata("achievements", $ins_arr);

        // MedalsMetadata['Medals']
        $ins_arr = array();
        foreach($_tmp['MedalsMetadata']['Medals'] as $medal) {
            $ins_arr[$medal['Id']] = array(
                'Id'            => $medal['Id'],
                'Name'          => $medal['Name'],
                'TierId'        => $medal['TierId'],
                'ClassId'       => $medal['ClassId'],
                'Description'   => $medal['Description'],
                'ImageUrl'      => substr($medal['ImageUrl']['AssetUrl'],7)
            );
        }
        $this->_ci->stat_m->insert_metadata("medals", $ins_arr);

        // MedalsMetadata['MedalClasses']
        $ins_arr = array();
        foreach($_tmp['MedalsMetadata']['MedalClasses'] as $medal) {
            $ins_arr[$medal['Id']] = array(
                'Id'        => $medal['Id'],
                'Name'      => $medal['Name']
            );
        }
        $this->_ci->stat_m->insert_metadata("medalclasses", $ins_arr);


        // MedalsMetadata['MedalTiers']
        $ins_arr = array();
        foreach($_tmp['MedalsMetadata']['MedalTiers'] as $medal) {
            $ins_arr[$medal['Id']] = array(
                'Id'            => $medal['Id'],
                'Name'          => $medal['Name'],
                'Description'   => $medal['Description']
            );
        }
        $this->_ci->stat_m->insert_metadata("medaltiers", $ins_arr);

        // Specializations
        $ins_arr = array();
        foreach($_tmp['SpecializationsMetadata']['Specializations'] as $spec) {
            $ins_arr[$spec['Id']] = [
                'Id'            => intval($spec['Id']),
                'MaxXp'         => intval($spec['MaxSpecializationXP']),
                'Name'          => $spec['Name'],
                'Description'   => $spec['Description'],
                'AssetUrl'      => substr($spec['ImageUrl']['AssetUrl'],7)
            ];
        }
        $this->_ci->stat_m->insert_metadata('specializations', $ins_arr);

        // a little done msg
        echo "DONE";
    }

    /**
     * get_playlist_csr
     *
     * Gets playlist via CSR style
     * @return array
     */
    public function get_playlist_csr() {
        // get arrays of playlists
        $ind = $this->_ci->config->item('h4_individual_csr');
        foreach ($ind as $item) {
            $csr[] = $item . "_I";
        }

        $team = $this->_ci->config->item('h4_team_csr');
        foreach ($team as $item) {
            $csr[] = $item . "_T";
        }
        unset($ind);
        unset($team);

        return $csr;
    }

    /**
     * get_playlists
     *
     * Pulls from API if more than 5 days have passed, otherwise pulls from cache.
     * @return array|mixed
     */
    public function get_playlists() {

        // check for 5 day old cache
        if (($_tmp = @json_decode($this->_ci->cache->get('playlists'), TRUE)) == FALSE)  {
            $resp = $this->get_url("https://presence.svc.halowaypoint.com/" . $this->lang . "/" . $this->game . "/playlists", TRUE);

            $ins_arr = array();
            if (is_array($resp)) {
                foreach($resp['Playlists'] as $playlist) {
                    if ($playlist['ModeId'] == 3 && $playlist['IsCurrent'] === TRUE) {
                        $ins_arr[$playlist['Id']] = array(
                            'Id' => $playlist['Id'],
                            'Name' => $playlist['Name'],
                            'Description' => $playlist['Description']
                        );
                    }
                }
            }

            // alpha order
            $this->_ci->utils->set_sort_key("Name");
            uasort($ins_arr, array($this->_ci->utils,"key_sort"));

            // store into db
            $this->_ci->stat_m->empty_playlists();
            $this->_ci->stat_m->insert_playlists($ins_arr);

            // lets update `ci_csr`
            //foreach($ins_arr as $key => $value) {
            //
            //    // lets check if we exist
            //    if ($this->_ci->stat_m->check_csr_column($key) !== TRUE) {
            //
            //    }
            //}

            // dump into cache
            $this->_ci->cache->write(json_encode($ins_arr), 'playlists');
            return $ins_arr;
        } else {
            return $_tmp;
        }
    }

    /**
     * get_url
     * Takes the url along w/ language / game, parameter is just paras of the URL
     *
     * @param type $paras
     * @param bool $auth
     * @param bool $return
     * @return string
     */
    private function get_url($paras, $auth = FALSE, $return = FALSE) {

        // set AUTH
        if ($auth) {
            $key = $this->get_spartan_auth_key();
            $header_paras = array('Accept: application/json',
                'X-343-Authorization-Spartan: ' . $key);
        } else {
            $header_paras = array('Accept: application/json');
        }

        // set accept header, and SpartanToken if needed
        $this->_ci->curl->option('HTTPHEADER', $header_paras);
        $this->_ci->curl->option(CURLOPT_SSL_VERIFYPEER, '0');
        $this->_ci->curl->option(CURLOPT_SSL_VERIFYHOST, '0');
        $this->_ci->curl->option(CURLOPT_CAPATH,'cacert.pem');

        if (substr($paras, 0, 5) === "https") {
            $url = $paras;
        } else {
            // make the url
            $url = "https://stats.svc.halowaypoint.com/" . $paras;
        }

        if ($return) {
            return array(
                'headers' => array(
                    CURLOPT_HTTPHEADER => $header_paras),
                'url' => $url
            );
        }

        // resp
        $resp = json_decode($this->_ci->curl->simple_get($url), TRUE);

        // check it
        return $this->check_status($resp, $url);
    }

    /**
     * get_spartan_auth_key
     * Uses separate non-public method to generate AUTH code for authenticated API endpoints
     *
     * @param int $count
     * @return null $key|null
     */
    private function get_spartan_auth_key($count = 0) {

        // grab from cache
        if (($_tmp = @json_decode($this->_ci->cache->get('auth_spartan'), TRUE)) == FALSE)  {

            // get the key, via config file
            $this->_ci->config->load('sekrit');

            // we got the keys, lets make the url
            $url =  $this->_ci->config->item('sekrit_url') . "/" . $this->_ci->config->item('serkit_key_1') . "/" .
                $this->_ci->config->item('serkit_key_2') . "/" . $this->_ci->config->item('serkit_key_3');

            // get key via sekrit site
            $key = json_decode($this->_ci->curl->simple_get($url),TRUE);


            // check expiration key
            if (time() > intval($key['expiresIn'])) {
                return $this->get_spartan_auth_key($count);
            }

            // count
            if (strlen($key) > 150) {
                $this->_ci->cache->write($key, 'auth_spartan', 3000);
                return json_decode($key,TRUE)['SpartanToken'];
            } else {
                $count++;

                if ($count > 2) {
                    log_message('error', 'API Down ' . $key);
                    $this->_ci->utils->throw_error("API_AUTH_GONE");
                } else {
                    $this->_ci->curl->simple_get($url . "/kill");
                    return $this->get_spartan_auth_key($count);
                }
            }
        } else {
            return $_tmp['SpartanToken'];
        }
    }

    /**
     * get_challenges
     *
     * Pulls from API `/challenges`, to pull current daily/weekly/monthly challenges.
     *
     * @return type
     */
    public function get_challenges() {

        // check Cache
        $resp = $this->_ci->cache->get('current_challenges');

        // check if cache exists.
        if ($resp == FALSE) {

            // fixes date, gets url via api
            $resp = $this->fix_date($this->get_url($this->lang . "/" . $this->game . "/challenges"), "BeginDate,EndDate", "Challenges");
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

    /**
     * get_badge_colour
     *
     * @param $id
     * @return string
     */
    public function get_badge_colour($id) {
        $this->_ci->load->library('structs/h4/Category');

        switch (intval($id)) {
            case Category::CAMPAIGN:
                return 'warning';
            case Category::SPARTAN_OPS:
                return 'info';
            case Category::WAR_GAMES:
                return 'primary';
            default:
                return '';
        }
    }

    /**
     * set_badge
     *
     * Returns formatted badge
     * @param $resp
     * @return string
     */
    public function set_badge(&$resp) {
        if (isset($resp[H4::BADGE])) {
            $resp[H4::BADGE] = '<span class="label label-static-size label-' . (isset($resp[H4::BADGE_COLOR]) ? $resp[H4::BADGE_COLOR] : "info") . '">' . $resp[H4::BADGE] . '</span>&nbsp;';
            return;
        }
        $resp[H4::BADGE] = '';
    }

    /**
     * get_profile
     * 1) Checks for file-based cache.
     * 2) Returns if less than 1 hr old
     * 3) If not, pulls from API. Dumps into dB.
     * 4) Caches into file system for 1hr.
     *
     * @param type   $gt
     * @param bool   $errors
     * @param bool   $force
     * @param string $seo_gamertag
     * @return type data
     */
    public function get_profile($gt, $errors = TRUE, $force = FALSE, $seo_gamertag = "") {

        if ($seo_gamertag == "") {
            $seo_gamertag = $this->get_seo_gamertag($gt);
        }

        if (strlen(urldecode($gt)) > 15) {

            if ($errors) {
                $this->_ci->utils->throw_error("LONGER_THAN_15_CHARS_GT");
            } else {
                return FALSE;
            }
        } else {

            // grab new data
            $resp = $this->grab_profile_data($gt,$force, $seo_gamertag);

            if ($resp == FALSE) {
                if ($errors) {
                    $this->_ci->utils->throw_error("NOT_XBL_ACCOUNT");
                } else {
                    return FALSE;
                }
            }
            // check for expiration
            if (intval($resp[H4::EXPIRATION]) < intval(time())) {
                return $this->get_profile($gt, $errors, TRUE, $seo_gamertag);
            } else {
                return $resp;
            }
        }
    }

    /**
     * get_seo_gamertag
     *
     * Takes `gamertag`, urldecodes, makes it lowercase, and moves spaces to _
     * @param $gt
     * @return mixed
     */
    public function get_seo_gamertag($gt) {
        return preg_replace('/\s+/', '_', strtolower(urldecode(trim($gt))));
    }

    /**
     * geo_hashed_seo_gamertag
     *
     * Takes `seo_gamertag` and makes hash based on it.
     * @param $seo_gt
     * @return string
     */
    public function get_hashed_seo_gamertag($seo_gt) {
        return md5(trim($seo_gt));
    }

    /**
     * reverse_seo_gamertag
     *
     * Takes `seo_gamertag` to database to return `Gamertag`
     * @param $seo_gt
     */
    public function reverse_seo_gamertag($seo_gt) {
        return $this->_ci->stat_m->get_gamertag_name($seo_gt);
    }

    /**
     * make_branch_gt
     *
     * Handles prepping a gt for Branch's URL system.
     *
     * @param $gt
     * @return mixed
     */
    public function make_branch_gt($gt) {
        return str_ireplace(" ", "%20", $gt);
    }

    /**
     * grab_profile_data
     * Pulls directly from the API. Stores into dB
     *
     * @param type   $gt
     * @param bool   $force
     * @param string $seo_gamertag
     * @return array|bool
     */
    public function grab_profile_data($gt, $force = FALSE, $seo_gamertag = "") {

        if ($seo_gamertag == "") {
            $seo_gamertag = $this->get_seo_gamertag($gt);
        }

        if ($gt == "") {
            $gt = $this->reverse_seo_gamertag($seo_gamertag);
        }

        // make hashed name
        $hashed = $this->get_hashed_seo_gamertag($seo_gamertag);

        // profile
        //if (ENVIRONMENT == "development") {
        //  $this->_ci->stat_m->set_profile_level(2, 50);
        //}

        // grab from db, if null continue
        $resp = $this->_ci->stat_m->get_gamertag_data($seo_gamertag);
        $this->_ci->h4_lib->set_badge($resp);

        if (isset($resp[H4::EXPIRATION]) && is_array($resp)) {
            if (intval($resp[H4::API_VERSION]) != intval(API_VERSION) && $force == FALSE) {

            } else if (intval($resp[H4::EXPIRATION]) > intval(time()) && $force == FALSE) {
                return $resp;
            }
        }

        // we gonna multithread this bitch. Get our header ready
        $this->_ci->load->library("chad/mcurl");
        $parts['service_record'] = $this->get_url($this->lang . "/players/" . trim(rawurlencode(strtolower($gt))) . "/" . $this->game . "/servicerecord", FALSE, TRUE); #unvalidated
        $parts['wargames_record'] = $this->get_url($this->lang . "/players/" . trim(rawurlencode(strtolower($gt))) . "/" . $this->game . "/servicerecord/wargames", TRUE, TRUE); #validated

        // add the calls
        $this->_ci->mcurl->add_call('service_record', "get", $parts['service_record']['url'], array(), $parts['service_record']['headers']);
        $this->_ci->mcurl->add_call('wargames_record', "get", $parts['wargames_record']['url'], array(), $parts['wargames_record']['headers']);

        // execute it
        $responses = $this->_ci->mcurl->execute();

        // hacky assignment to get around references
        $decoded_service = json_decode($responses['service_record']['response'], TRUE);
        $decoded_wargames = json_decode($responses['wargames_record']['response'],TRUE);

        $service_record = $this->check_status($decoded_service,$parts['service_record']['url']);
        $wargames_record = $this->check_status($decoded_wargames, $parts['wargames_record']['url']);

        // cleanup multipart
        unset($responses);

        if ($service_record == FALSE || $wargames_record == FALSE) {

            // insert in ci_missing
            $this->_ci->stat_m->update_or_mark_as_missing($gt, $seo_gamertag);

           if ($this->cli) {
               return FALSE;
           } else {
               $this->_ci->utils->throw_error("NOT_XBL_ACCOUNT");
           }
        }

        $medal_data = $this->get_medal_data($wargames_record['TotalMedalsStats']);

        // get skill stuff
        $skill_data = $this->get_skill_data($service_record['SkillRanks'], $service_record['TopSkillRank'],
                                            $service_record['Gamertag'], $seo_gamertag, $service_record['GameModes'][2]['KDRatio']);

        // get spec data
        $spec_data = $this->get_spec_data($service_record['Specializations']);

        // check for lvl 130
        if ($service_record['NextRankId'] == 0) {
            $service_record['NextRankStartXP'] = 0;
        }

        // nasty little hack. If they have 0 games played in matchmaking, reject this bitch.
        if (isset($service_record['GameModes'][2]['TotalGamesStarted']) && intval($service_record['GameModes'][2]['TotalGamesStarted']) == 0) {
            if ($this->cli) {
                return FALSE;
            } else {
                $this->_ci->utils->throw_error("NO_GAMES_PLAYED");
            }
        }

        // update possibly cached CSR data
        $this->_ci->cache->model('stat_m','get_unique_csr_position', array($seo_gamertag), -1);

        // get ready for a dump of data
        $dump = array(
            H4::GAMERTAG                       => $service_record['Gamertag'],
            H4::HASHED_GAMERTAG                => $hashed,
            H4::SEO_GAMERTAG                   => $seo_gamertag,
            H4::RANK                           => $service_record['RankName'],
            H4::SPECIALIZATION                 => $this->find_current_specialization($service_record['Specializations']),
            H4::SPECIALIZATION_LEVEL           => $this->find_current_specialization($service_record['Specializations'], "Level"),
            H4::EXPIRATION                     => intval(time() + SEVENDAYS_IN_SECONDS),
            H4::MEDAL_DATA                     => msgpack_pack($medal_data),
            H4::SKILL_DATA                     => msgpack_pack($skill_data),
            H4::SPEC_DATA                      => msgpack_pack($spec_data),
            H4::KD_RATIO                       => $service_record['GameModes'][2]['KDRatio'],
            H4::KAD_RATIO                      => round(($service_record['GameModes'][2]['TotalKills'] + intval($wargames_record['TotalAssists'])) / $service_record['GameModes'][2]['TotalDeaths'], 2),
            H4::XP                             => $service_record['XP'],
            H4::EMBLEM                         => substr_replace($service_record['EmblemImageUrl']['AssetUrl'], "", -12),
            H4::SPARTAN_POINTS                 => $service_record['SpartanPoints'],
            H4::FAVORITE_WEAPON_NAME           => $service_record['FavoriteWeaponName'],
            H4::FAVORITE_WEAPON_DESCRIPTION    => $service_record['FavoriteWeaponDescription'],
            H4::FAVORITE_WEAPON_TOTAL_KILLS    => $service_record['FavoriteWeaponTotalKills'],
            H4::FAVORITE_WEAPON_URL            => $service_record['FavoriteWeaponImageUrl']['AssetUrl'],
            H4::AVERAGE_PERSONAL_SCORE         => intval($service_record['GameModes'][2]['AveragePersonalScore']),
            H4::MEDALS_PER_GAME_RATIO          => round(intval($service_record['GameModes'][2]['TotalMedals']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            H4::DEATHS_PER_GAME_RATIO          => round(intval($service_record['GameModes'][2]['TotalDeaths']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            H4::KILLS_PER_GAME_RATIO           => round(intval($service_record['GameModes'][2]['TotalKills']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            H4::BETRAYALS_PER_GAME_RATIO       => round(intval($wargames_record['TotalBetrayals']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            H4::SUICIDES_PER_GAME_RATIO        => round(intval($wargames_record['TotalSuicides']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            H4::ASSISTS_PER_GAME_RATIO         => round(intval($wargames_record['TotalAssists']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            H4::HEADSHOTS_PER_GAME_RATIO       => round(intval($wargames_record['TotalHeadshots']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            H4::WIN_PERCENTAGE                 => round(intval($service_record['GameModes'][2]['TotalGamesWon']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            H4::QUIT_PERCENTAGE                => round(intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']) /
                                                            intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            H4::TOTAL_CHALLENGES_COMPLETED     => $service_record['TotalChallengesCompleted'],
            H4::TOTAL_GAME_WINS                => $service_record['GameModes'][2]['TotalGamesWon'],
            H4::TOTAL_GAME_QUITS               => intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']),
            H4::NEXT_RANK_START_XP             => $service_record['NextRankStartXP'],
            H4::RANK_START_XP                  => $service_record['RankStartXP'],
            H4::TOTAL_COMMENDATION_PROGRESS    => floatval($service_record['TotalCommendationProgress']),
            H4::TOTAL_LOADOUT_ITEMS_PURCHASED  => intval($service_record['TotalLoadoutItemsPurchased']),
            H4::TOTAL_MEDALS                   => intval($service_record['GameModes'][2]['TotalMedals']),
            H4::TOTAL_GAMEPLAY                 => $this->adjust_date($service_record['GameModes'][2]['TotalDuration']),
            H4::TOTAL_KILLS                    => intval($service_record['GameModes'][2]['TotalKills']),
            H4::TOTAL_DEATHS                   => intval($service_record['GameModes'][2]['TotalDeaths']),
            H4::TOTAL_GAMES_STARTED            => intval($service_record['GameModes'][2]['TotalGamesStarted']),
            H4::TOTAL_HEADSHOTS                => intval($wargames_record['TotalHeadshots']),
            H4::TOTAL_ASSISTS                  => intval($wargames_record['TotalAssists']),
            H4::BEST_GAME_TOTAL_KILLS          => intval($wargames_record['BestGameTotalKills']),
            H4::BEST_GAME_TOTAL_KILLS_GAMEID   => $wargames_record['BestGameTotalKillsGameId'],
            H4::BEST_GAME_TOTAL_MEDALS         => intval($wargames_record['BestGameTotalMedals']),
            H4::BEST_GAME_TOTAL_MEDALS_GAMEID  => $wargames_record['BestGameTotalMedalsGameId'],
            H4::BEST_GAME_HEADSHOT_TOTAL       => intval($wargames_record['BestGameHeadshotTotal']),
            H4::BEST_GAME_HEADSHOT_GAMEID      => $wargames_record['BestGameHeadshotTotalGameId'],
            H4::BEST_GAME_ASSASSINATION_TOTAL  => intval($wargames_record['BestGameAssassinationTotal']),
            H4::BEST_GAME_ASSASSINATION_GAMEID => $wargames_record['BestGameAssassinationTotalGameId'],
            H4::BEST_GAME_KILL_DISTANCE        => intval($wargames_record['BestGameKillDistance']),
            H4::BEST_GAME_KILL_DISTANCE_GAMEID => $wargames_record['BestGameKillDistanceGameId'],
            H4::SERVICE_TAG                    => $service_record['ServiceTag'],
            H4::TOTAL_BETRAYALS                => intval($wargames_record['TotalBetrayals']),
            H4::TOTAL_SUICIDES                 => intval($wargames_record['TotalSuicides']),
            H4::LAST_UPDATE                    => intval(time()),
            H4::INACTIVE_COUNTER               => intval(0),
            H4::STATUS                         => intval(0),
            H4::API_VERSION                    => intval(API_VERSION),
            H4::DAY                            => date('d'),
            H4::MONTH                          => date('m'),
            H4::YEAR                           => date('Y')
        );

        // Check if we have a previously set date and month
        if (($time_check = $this->_ci->stat_m->get_install_time($hashed)) == FALSE) {
            $time_check[H4::YEAR]       = $dump[H4::YEAR];
            $time_check[H4::MONTH]      = $dump[H4::MONTH];
            $time_check[H4::DAY]        = $dump[H4::DAY];
        }

        // lets do the URL work, and medal
        $this->build_spartan_with_emblem($hashed, substr_replace($service_record['EmblemImageUrl']['AssetUrl'], "", -12), $gt, $time_check[H4::YEAR], $time_check[H4::MONTH], $time_check[H4::DAY]);

        //$this->_ci->mongo_db->add_index('h4_gamertags', array(
        //    H4::SEO_GAMERTAG       => 1,
        //    H4::HASHED_GAMERTAG    => 1,
        //    H4::GAMERTAG           => 1,
        //    H4::BADGE              => 1,
        //    H4::STATUS             => 1), array(
        //   'unique'        => TRUE,
        //    'dropDups'      => TRUE,
        //    'background'    => TRUE,
        //    'name'          => 'h4_index'
        //));

        utf8_encode_deep($dump);
        return $this->_ci->stat_m->update_or_insert_gamertag($hashed, $dump);
    }

    /**
     * adjust_date
     *
     * Takes form P3DT4H18M45S into seconds.
     * @param type $str
     * @return int
     */
    public function adjust_date($str) {
        if (preg_match('/(?P<days>[0-9]*).(?P<hours>[0-9]*):(?P<minutes>[0-9]*):(?P<seconds>[0-9]*)/', $str, $regs)) {
            return (($regs['days'] * 86400) + ($regs['hours'] * 3600) + ($regs['minutes'] * 60) + $regs['seconds']);
        } else {
            log_message('error', 'We could not find this tag: ' . $str);
            return 0;
        }
    }

    /**
     * find_current_specializations
     * Loops through `specializations` looking for IsCurrent. Returns its name.
     *
     * @param type   $data
     * @param string $type
     * @return string
     */
    public function find_current_specialization($data, $type = "Name") {
        foreach ($data as $spec) {
            if ($spec['IsCurrent'] == TRUE) {
                return $spec[$type];
            }
        }
        return "No Spec";
    }

    /**
     * return_image_url
     * Checks if this image exists locally. If so, uses it. Otherwise pulls from API
     *
     * @param string $type
     * @param type $image
     * @param      $size
     * @return boolean
     */
    public function return_image_url($type, $image, $size) {

        $urls = $this->_ci->config->item('h4_urls');
        // switch for Type
        switch ($type) {

            case "Emblem":
                $path = "uploads/h4/emblems/" . $size;
                $image_path =  "/" . $image . ".png";
                $url = str_replace("{SIZE}", $size, str_replace("{EMBLEM}", $image, $urls['emblem_url']));
                break;

            case "Rank":
                $image = sprintf('%03d', $image);
                $path = "uploads/h4/ranks/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{RANK}", $image, $urls['rank_url']));
                break;

            case "Medal":
                $path = "uploads/h4/medals/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{MEDAL}", $image, $urls['medal_url']));
                break;

            case "CSR":
                $path = "uploads/h4/csr/" . $size;
                $image_path = "/" . $image . ".png";
                $url = str_replace("{SIZE}", $size, str_replace("{CSR}", $image, $urls['csr_url']));
                break;

            case "Weapon":
                $image = substr($image, 7); # remove `{SIZE}/` from url
                $path = "uploads/h4/weapons/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{WEAPON}", $image, $urls['weapon_url']));
                break;

            case "Spartan":
                $hashed_gt  = $this->get_hashed_seo_gamertag($this->get_seo_gamertag($image));
                $time_check = $this->fix_times($this->_ci->stat_m->get_install_time($hashed_gt));
                $path = "uploads/h4/spartans/" . $time_check[H4::YEAR] . "/" . $time_check[H4::MONTH] . "/" . $time_check[H4::DAY] . "/" . $hashed_gt;
                $image_path = "/" . $size . "_spartan.png";
                $url = str_replace("{SIZE}", $size, str_replace("{GAMERTAG}", urlencode($image), $urls['spartan_url']));
                break;

            case "ProfileSpartan":
                $hashed_gt  = $this->get_hashed_seo_gamertag($this->get_seo_gamertag($image));
                $time_check = $this->fix_times($this->_ci->stat_m->get_install_time($hashed_gt));
                $path = "uploads/h4/spartans/" . $time_check[H4::YEAR] . "/" . $time_check[H4::MONTH] . "/" . $time_check[H4::DAY] . "/" . $hashed_gt;
                $image_path = "/spartan.png";
                $url = str_replace("{SIZE}", $size, str_replace("{GAMERTAG}", urlencode($image), $urls['spartan_url']));
                break;

            case "Spec":
                $path = "uploads/h4/specializations/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{SPEC}", urlencode($image), $urls['spec_url']));
                break;

            default:
                log_message('error', 'Type: ' . $type . " not found in our `return_image_url` Library");
                return FALSE;
        }

        // check for the file locally
        if (file_exists(absolute_path($path) . $image_path)) {
            return base_url($path . $image_path);
        } else {
            $_stream = file_get_contents($url);

            if ($_stream === FALSE) {
                return $url;
            } else {
                file_put_contents(absolute_path($path) . $image_path, $_stream);
                chmod(absolute_path($path) . $image_path, 0777);
                unset($_stream);
                return base_url($path . $image_path);
            }
        }
    }

    /**
     * fix_times
     *
     * Sets default values due to poorly code script
     * @todo remove
     * @param $times
     * @return mixed
     */
    private function fix_times($times) {
        if (!isset($times[H4::MONTH])) {
            $times[H4::DAY]     = date('d');
            $times[H4::MONTH]   = date('m');
            $times[H4::YEAR]    = date('Y');
        }
        return $times;
    }

    /**
     * return_spartan_url
     * Tries and find's local Spartan. Otherwise returns url to it.
     *
     * @param type   $gt
     * @param string $type
     * @internal param \type $hashed
     * @return mixed|string
     */
    public function return_spartan_url($gt, $type = "Profile") {
        if ($type == "Profile") {
            return $this->return_image_url("ProfileSpartan", $gt, "medium");
        } else {
            return $this->return_image_url("Spartan", $gt, "medium");
        }
    }

    /**
     * get_medal_data
     *
     * Extracts from api `TopMedals` into serialized form
     * @param type $medals
     * @return type
     */
    public function get_medal_data($medals) {
        $rtr_arr = array();
        $x = 0;

        // loop top medals, extract data.
        foreach($medals as $medal) {
            $rtr_arr[$x++] = array(
                'i' => intval($medal['Id']),
                'c' => intval($medal['TotalMedals'])
            );
        }
        return $rtr_arr;
    }

    /**
     * get_spec_data
     *
     * Extracts from api `SpecilizationData` into serialized form
     * @param $specs
     * @return array
     */
    public function get_spec_data($specs) {
        $rtr_arr = array();

        foreach($specs as $spec) {
            $rtr_arr[intval($spec['Id'])] = array(
                'Level' => $spec['Level'],
                'IsCurrent' => $spec['IsCurrent'],
                'Completed' => $spec['Completed']
            );
        }
        return $rtr_arr;
    }

    /**
     * get_skill_data
     * Goes through skill data finding CSR
     *
     * @param $all_csr
     * @param $top_csr
     * @param $gt
     * @param $seo_gt
     * @param $kd_ratio
     * @return array
     */
    public function get_skill_data($all_csr, $top_csr, $gt, $seo_gt, $kd_ratio) {
        $rtr_arr = array();

        // add basic data for our CSR leaderboards
        $csr_leader = array();
        $csr_leader['SeoGamertag'] = $seo_gt;
        $csr_leader['Gamertag'] = $gt;
        $csr_leader['KDratio'] = floatval($kd_ratio);

        // loop through each CSR adding them into final arr
        foreach ($all_csr as $csr) {
            $rtr_arr[$csr['PlaylistId']] = intval($csr['CurrentSkillRank']);

            // lets figure out team vs inv CSR
            if (in_array($csr['PlaylistId'],$this->_ci->config->item('h4_team_csr'))) {
                $csr_leader[$csr['PlaylistId'] . "_T"] = intval($csr['CurrentSkillRank']);
            } else if (in_array($csr['PlaylistId'], $this->_ci->config->item('h4_individual_csr'))) {
                $csr_leader[$csr['PlaylistId'] . "_I"] = intval($csr['CurrentSkillRank']);
            } else {
                log_message('error', $csr['PlaylistName'] . " not found for CSR stats w/ id of " . $csr['PlaylistId']);
            }
        }

        // fire off to csr leaderboards
        $this->_ci->stat_m->update_csr_leaderboards($csr_leader);

        // return CSR stuff
        if (count($rtr_arr) > 0) {
            $this->_ci->utils->set_sort_key("SkillRank");
            uasort($rtr_arr, array($this->_ci->utils,"key_sort"));
            return $rtr_arr;
        } else {
            return FALSE;
        }
    }

    /**
     * return_medals
     *
     * Takes Id -> Count representation of Medals. Collects metadata and re-makes array.
     *
     * Sorts via TierId, and then sorted by Highest `Count` field.
     * @param type $data
     * @return mixed
     */
    public function return_medals($data) {
        $data = msgpack_unpack(utf8_decode($data));
        foreach ($data as $key => $item) {
            $data[$key]['Name'] = $this->get_metadata_name_via_id("medals", $item['i'], "Name");
            $data[$key]['ImageUrl'] = $this->get_metadata_name_via_id("medals", $item['i'], "ImageUrl");
            $data[$key]['TierId'] = $this->get_metadata_name_via_id("medals", $item['i'], "TierId");
            $data[$key]['ImageUrl'] = $this->return_image_url("Medal", $data[$key]['ImageUrl'], "medium");
            $data[$key]['Description'] = $this->get_metadata_name_via_id("medals", $item['i'], "Description");
        }

        // group according to TierId
        $new_arr = array();
        foreach ($data as $key => $item) {
            $new_arr[$item['TierId']][$item['i']] = $item;
        }
        unset($data);

        // sort
        $this->_ci->utils->set_sort_key("c");
        ksort($new_arr);
        foreach ($new_arr as $key => $value) {
            uasort($new_arr[$key], array($this->_ci->utils,"key_sort"));
            $new_arr[$key]['Name'] = $this->get_metadata_name_via_id("medaltiers",$key, "Name");
            $new_arr[$key]['Description'] = $this->get_metadata_name_via_id("medaltiers",$key, "Description");
        }
        return $new_arr;
    }

    /**
     * return_csr
     *
     * Parses serialized array `$data` for display on profile
     *
     * @param $data
     * @return mixed
     */
    public function return_csr($data) {
        if ($data == FALSE || $data == "" | $data == "b:0;") {
            return FALSE;
        }
        $data = msgpack_unpack(utf8_decode($data));
        $playlists = $this->get_playlists();
        $team_csr = $this->_ci->config->item('h4_team_csr');

        $rtr_arr = array();
        foreach ($data as $key => $value) {

            $csr = (in_array($key, $team_csr)) ? "Team" : "Individual";
            if (isset($playlists[$key])) {
                $rtr_arr[] = array(
                    'Playlist'  => $playlists[$key]['Name'],
                    'SkillRank' => intval($value),
                    'Type'      => $csr,
                    'ImageUrl'  => $this->return_image_url("CSR", $value, "medium")
                );
            }
        }
        $this->_ci->utils->set_sort_key("SkillRank");
        uasort($rtr_arr, array($this->_ci->utils, "key_sort"));
        return $rtr_arr;
    }

    /**
     * return_highest_csr
     *
     * Returns the highest Team/Ind for CSR data
     * @param $data
     * @return array|bool
     */
    public function return_highest_csr($data) {
        $top_team   = [
            'SkillRank' => 0
        ];

        $top_ind    = [
            'SkillRank' => 0
        ];

        if (is_array($data)) {
            foreach($data as $item) {
                if ($item['Type'] == "Individual") {
                    if ($item['SkillRank'] >= $top_ind['SkillRank']) {
                        $top_ind = $item;
                    }
                } else if ($item['Type'] == "Team") {
                    if ($item['SkillRank'] >= $top_team['SkillRank']) {
                        $top_team = $item;
                    }
                } else {
                    log_mesasage('error', $item['Type'] . " is unknown.");
                }
            }
            return [
                'Team'  => $top_team,
                'Ind'   => $top_ind
            ];
        }
        return FALSE;
    }

    /**
     * return_csr_v2
     * Parses data to make presentable on profile
     *
     * @param $leaderboard
     * @param $csr
     * @return array
     * @internal param $data
     */
    public function return_csr_v2($leaderboard, $csr) {
        if (!is_array($csr)) {
            $csr = msgpack_unpack(utf8_decode($csr));
        }

        $rtr_arr = array();
        $playlists = $this->get_playlists();

        // we are going to loop the `leaderboard` variable, because that is what we have / is current
        // using that, we will pull their SkillRank in that playlist
        foreach($leaderboard as $key => $item) {
            if (is_array($item)) {
                $rtr_arr[$key] = array(
                    'Name'      => isset($playlists[substr($key,0,-2)]['Name']) ? $playlists[substr($key,0,-2)]['Name'] : NULL,
                    'Id'        => $key,
                    'SkillRank' => isset($item[$key]) ? intval($item[$key]) : 0,
                    'Rank'      => isset($item['Rank']) ? intval($item['Rank']) : 0
                );
            }
        }
        // sort
        $this->_ci->utils->set_sort_key("SkillRank");
        uasort($rtr_arr, array($this->_ci->utils, "key_sort"));
        return $rtr_arr;
    }

    /**
     * return spec
     *
     * Parses serialized array `$data` for display on profile.
     *
     * @param $data
     * @return bool|mixed
     */
    public function return_spec($data) {
        if ($data == FALSE || $data == "" || $data == "b:0;") {
            return FALSE;
        }
        $data = msgpack_unpack(utf8_decode($data));

        foreach($data as $key => $value) {
            $spec = $this->get_metadata_name_via_id('specializations', $key);
            if ($data[$key]['Completed']) {
                $data[$key]['ImageUrl']         = $this->return_image_url("Spec", $spec['AssetUrl'], "small");
                $data[$key]['Name']             = $spec['Name'];
                $data[$key]['Description']      = $spec['Description'];
            } else {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * return favorite
     *
     * Preps data for display on Profile, aligning the FavoriteWeapon into an array
     * @param $name
     * @param $desc
     * @param $total
     * @param $url
     * @return array
     */
    public function return_favorite($name, $desc, $total, $url) {

        // check for null
        if ($name == "") {
            return FALSE;
        }

        $rtr_array = array();
        $rtr_array['WeaponName'] = $name;
        $rtr_array['WeaponDesc'] = $desc;
        $rtr_array['WeaponTotalKills'] = $total;
        $rtr_array['WeaponUrl'] = $this->return_image_url("Weapon", $url, "large");
        return $rtr_array;
    }

    /**
     * get_metadata_via_db
     *
     * Goes to database in order to find table `ci_m_$type` and returns all of its data. Its cached
     * forever, so the amount of queries to the database are very little.
     *
     * @param $type
     * @return mixed
     */
    public function get_metadata_via_db($type) {
        return $this->_ci->cache->model('stat_m', 'get_metadata', array($type));
    }

    /**
     * get_metadata_name_via_id
     *
     * Pass $type of metadata, and the id of it along w/ what your want. It uses the above function
     * `get_metadata_via_db` to get the db information, then this function takes the passed $id
     * and uses it against the returned $metadata before returning it.
     * @param        $type
     * @param        $id
     * @param string $name
     * @return bool
     */
    public function get_metadata_name_via_id($type, $id, $name = "ALL") {

        // get our damn metadata
        $metadata = $this->get_metadata_via_db($type);

        // do our switch
        switch($type) {
            case "medaltiers":
            case "medals":
                if (isset($metadata[$id])) {
                    if ($name != "ALL") {
                        return $metadata[$id][$name];
                    } else {
                        return $metadata[$id];
                    }
                } else {
                    return FALSE;
                }
                break;

            case "specializations":
                if (isset($metadata[$id])) {
                    return $metadata[$id];
                } else {
                    return FALSE;
                }
                break;

            default:
                die("You idiot! Add" . $type . " into the Library::get_metadata_name_via_id function");
                break;
        }
    }

    /**
     * get_banned_type
     *
     * Take the incoming ID and prep for display
     * @param $status
     * @return string
     */
    public function get_banned_type($status) {
        switch ($status) {
            case 0:
                return '<span class="label">Not banned</span>';

            case CHEATING_PLAYER:
                return '<span class="label label-important">Cheater</span>';

            case BOOSTING_PLAYER:
                return '<span class="label label-warning">Booster</span>';
        }
    }

    /**
     * get_banned_name
     * Gets banned name via
     *
     * @param      $status
     * @param bool $type
     * @return string
     */
    public function get_banned_name($status, $type = FALSE) {
        switch($status) {
            case 0:
                return "nothing.";
            case CHEATING_PLAYER:
                return ($type) ? "Cheating" : "Cheater";
            case BOOSTING_PLAYER:
                return ($type) ? "Boosting" : "Booster";
            case MISSING_PLAYER:
                return ($type) ? "Missing" : "Inactive";
        }
    }

    /**
     * get_flagged
     *
     * Removes under 5 flagged accounts.
     * @param array $flagged_users
     * @return array
     */
    public function get_flagged($flagged_users = array()) {
        if (is_array($flagged_users)) {
            foreach ($flagged_users as $key => $user) {
                if ($user['amt'] < 5) {
                    unset($flagged_users[$key]);
                }
            }
        }
        return $flagged_users;
    }

    /**
     * build_spartan_with_emblem
     * Used during recache/creation. Takes hashed $gt, and downloads / parses the emblem and Spartan Image.
     * They are then laid ontop of eachother (lol)
     *
     * @param string $hashed
     * @param string $emblem
     * @param string $gamertag
     * @param int  $year
     * @param int  $month
     * @param int  $day
     */
    public function build_spartan_with_emblem($hashed, $emblem, $gamertag, $year = 2012, $month = 01, $day = 01) {

        // load path helper, setup vars
        $this->_ci->load->helper("path");
        $spartan_dir    = absolute_path('uploads/h4/spartans/' . $year . "/" . $month . "/" . $day . "/" . $hashed);
        $spartan_path   = $spartan_dir . "spartan.png";
        $emblem_dir     = absolute_path('uploads/h4/spartans/' . $year . "/" . $month . "/" . $day . "/" . $hashed . "/tmp/");
        $emblem_path    = $emblem_dir . "emblem.png";

        // delete old spartans
        if (is_dir($spartan_dir)) {
            // only delete if $hashed is set
            if (strlen($hashed) > 10) {
                delete_files($spartan_dir, TRUE);
            }
        }

        // lets try and make a folder. check first :p
        if (!(is_dir($emblem_dir))) {
            mkdir($emblem_dir, 0777, TRUE);
            write_file($spartan_dir . "index.html", $this->_ci->utils->get_anti_dir_trav());
        } else {
            // only delete if $hashed is set
            if (strlen($hashed) > 10) {
                delete_files($spartan_dir, TRUE);
            }
        }

        // Backward Code: Delete old images in h4/spartans/ once moved
        if (is_dir(absolute_path('uploads/h4/spartans/' . $hashed))) {
            if (strlen($hashed) > 10) {
                delete_files(absolute_path('uploads/h4/spartans/' . $hashed));
                rmdir(absolute_path('uploads/h4/spartans/' . $hashed));
            }
        }

        // multi thread this bitch
        $this->_ci->load->library("chad/mcurl");
        $this->_ci->mcurl->add_call("emblem", "get",$this->return_image_url("Emblem", $emblem, "80"));
        $this->_ci->mcurl->add_call("spartan", "get",$this->return_image_url("ProfileSpartan",$gamertag, "medium"));

        // get them
        $responses = $this->_ci->mcurl->execute();

        // download 2 images in there, (emblem and spartan). Ignore all errors. Check afterwards
        $emblem = $responses['emblem']['response'];
        if ($emblem != FALSE) {
            file_put_contents($emblem_path, $emblem);
        } else {
            // default emblem for fucking hackers
            file_put_contents($emblem_path, 'https://emblems.svc.halowaypoint.com/h4/emblems/steel_brick_recruit-on-silver_plus?size=80');
        }

        $spartan = $responses['spartan']['response'];
        if ($spartan != FALSE) {
            file_put_contents($spartan_path, $spartan);
        }

        // cleanup
        unset($emblem);
        unset($spartan);
        unset($responses);

        // check if both files are there.
        if (file_exists($spartan_path) &&  file_exists($emblem_path)) {

            // we got em both. Lets merge them.
            $config = array();
            $config['source_image'] = $spartan_path;
            $config['wm_type'] = "overlay";
            $config['wm_overlay_path'] = $emblem_path;
            $config['quality'] = "100%";
            $config['wm_hor_alignment'] = "right";
            $config['wm_vrt_alignment'] = 'top';
            $this->_ci->image_lib->initialize($config);
            $this->_ci->image_lib->watermark();

        } else {
            log_message('debug', 'spartan: ' . $spartan_path);
            log_message('debug', 'emblem: ' . $emblem_path);
        }

        // delete tmp dir
        delete_files($emblem_dir, FALSE);
        rmdir($emblem_dir);
    }

    /**
     * get_top_5_leaderboard
     *
     * Helper method for Stat_m::get_top_5
     * @param type       $field
     * @param bool|\type $asc
     * @return
     */
    public function get_top_5_leaderboard($field, $asc = FALSE) {
        return $this->_ci->stat_m->get_top_5($field, $asc);
    }
}
