<?php

class Library {

    protected $_ci;
    public $lang;
    public $game;
    public $cli = FALSE;

    function __construct() {
        $this->_ci = & get_instance();
        $this->lang = "english";
        $this->game = "h4";

        $this->_ci->load->model('stat_model', 'stat_m', TRUE);
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

    /**
     * get_badge
     * Gets little `badges` to put by usernames
     *
     * @param $resp
     * @internal param $gt
     * @return string
     */
    public function set_badge(&$resp) {

        if (isset($resp['title']) && strlen($resp['title']) > 2 && isset($resp['colour']) && strlen($resp['colour']) > 2) {
            $resp['badge'] =  '<span class="badge badge-' . $resp['colour'] . '">' . $resp['title'] . '</span>&nbsp;';
        } else {
            $resp['badge'] = '';
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

        // Step 1: Achievements
        $ins_arr = array();
        foreach ($_tmp['AchievementsMetadata']['Achievements'] as $ach) {
            $ins_arr[$ach['Id']] = array(
                'Id' => $ach['Id'],
                'Name' => $ach['Name'],
                'LockedDescription' => $ach['LockedDescription'],
                'UnlockedDescription' => $ach['UnlockedDescription'],
                'GamerPoints' => $ach['GamerPoints'],
                'LockedImageUrlAssetUrl' => $ach['LockedImageUrl']['AssetUrl'],
                'UnlockedImageUrlAssetUrl' => $ach['UnlockedImageUrl']['AssetUrl']
            );
        }
        $this->_ci->stat_m->insert_metadata("achievements", $ins_arr);

        // Step 2: ArmorGroupMetaData

        // Step 3: DamageMetadata['WeaponTypes']

        // Step 5: MedalsMetadata['Medals']
        $ins_arr = array();
        foreach($_tmp['MedalsMetadata']['Medals'] as $medal) {
            $ins_arr[$medal['Id']] = array(
                'Id' => $medal['Id'],
                'Name' => $medal['Name'],
                'TierId' => $medal['TierId'],
                'ClassId' => $medal['ClassId'],
                'Description' => $medal['Description'],
                'ImageUrl' => substr($medal['ImageUrl']['AssetUrl'],7)
            );
        }
        $this->_ci->stat_m->insert_metadata("medals", $ins_arr);

        // Step 5a: MedalsMetadata['MedalClasses']
        $ins_arr = array();
        foreach($_tmp['MedalsMetadata']['MedalClasses'] as $medal) {
            $ins_arr[$medal['Id']] = array(
                'Id' => $medal['Id'],
                'Name' => $medal['Name']
            );
        }
        $this->_ci->stat_m->insert_metadata("medalclasses", $ins_arr);


        // Step 5b: MedalsMetadata['MedalTiers']
        $ins_arr = array();
        foreach($_tmp['MedalsMetadata']['MedalTiers'] as $medal) {
            $ins_arr[$medal['Id']] = array(
                'Id' => $medal['Id'],
                'Name' => $medal['Name'],
                'Description' => $medal['Description']
            );
        }
        $this->_ci->stat_m->insert_metadata("medaltiers", $ins_arr);

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
                    if ($playlist['ModeId'] == 3) {
                        $ins_arr[$playlist['Id']] = array(
                            'Id' => $playlist['Id'],
                            'Name' => $playlist['Name'],
                            'Description' => $playlist['Description']
                        );
                    }
                }
            }

            // alpha order
            $this->set_sort_key("Name");
            uasort($ins_arr, array($this->ci->utils,"key_sort"));

            // store into db
            $this->_ci->stat_m->empty_playlists();
            $this->_ci->stat_m->insert_playlists($ins_arr);

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

            $get_url = "https://settings.svc.halowaypoint.com/RegisterClientService.svc/spartantoken/wlid";

            // get key via sekrit site
            $key = json_decode($this->_ci->curl->simple_get($url),TRUE);

            // check key
            if (!is_array($key)) {
                $count++;

                // if its looped more than 2 times, we didn't find a key :(
                if ($count < 2) {
                    $this->_ci->cache->delete('auth_spartan');
                    return $this->get_spartan_auth_key($count);
                }  else {
                    $this->_ci->utils->throw_error("API_AUTH_GONE");
                }
            }

            // check expiration key
            if (time() > intval($key['expiresIn'])) {
                return $this->get_spartan_auth_key($count);
            }

            // lets grab it
            $this->_ci->curl->option('HTTPHEADER', array(
                'Accept: application/json',
                'X-343-Authorization-WLID: ' ."v1=" . $key['accessToken']));

            $this->_ci->curl->option(CURLOPT_SSL_VERIFYPEER, '0');
            $this->_ci->curl->option(CURLOPT_SSL_VERIFYHOST, '0');
            $this->_ci->curl->option(CURLOPT_CAPATH,'cacert.pem');

            // lets make this URL
            $resp = $this->_ci->curl->simple_get($get_url);

            // count
            if (strlen($resp) > 150) {
                $this->_ci->cache->write($resp, 'auth_spartan', 3000);
                return json_decode($resp,TRUE)['SpartanToken'];
            } else {
                $count++;

                if ($count > 2) {
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
            if (intval($resp['Expiration']) < intval(time())) {
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

        // grab from db, if null continue
        $resp = $this->_ci->stat_m->get_gamertag_data($hashed);
        $this->set_badge($resp);

        if (isset($resp['Expiration']) && is_array($resp)) {
            if (intval($resp['ApiVersion']) != intval(API_VERSION) && $force == FALSE) {

            } else if (intval($resp['Expiration']) > intval(time()) && $force == FALSE) {
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
        $service_record = $this->check_status(json_decode($responses['service_record']['response'], TRUE),$parts['service_record']['url']);
        $wargames_record = $this->check_status(json_decode($responses['wargames_record']['response'],TRUE), $parts['wargames_record']['url']);

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

        // lets do the URL work, and medal
        $this->build_spartan_with_emblem($hashed, substr_replace($service_record['EmblemImageUrl']['AssetUrl'], "", -12), $gt);
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
            'Gamertag'                         => $service_record['Gamertag'],
            'HashedGamertag'                   => $hashed,
            'SeoGamertag'                      => $seo_gamertag,
            'Rank'                             => $service_record['RankName'],
            'RankImage'                        => substr($service_record['RankImageUrl']['AssetUrl'], 7),
            'Specialization'                   => $this->find_current_specialization($service_record['Specializations']),
            'SpecializationLevel'              => $this->find_current_specialization($service_record['Specializations'], "Level"),
            'Expiration'                       => intval(time() + SEVENDAYS_IN_SECONDS),
            'MedalData'                        => msgpack_pack($medal_data),
            'SkillData'                        => msgpack_pack($skill_data),
            'SpecData'                         => msgpack_pack($spec_data),
            'KDRatio'                          => $service_record['GameModes'][2]['KDRatio'],
            'Xp'                               => $service_record['XP'],
            'Emblem'                           => substr_replace($service_record['EmblemImageUrl']['AssetUrl'], "", -12),
            'SpartanPoints'                    => $service_record['SpartanPoints'],
            'FavoriteWeaponName'               => $service_record['FavoriteWeaponName'],
            'FavoriteWeaponDescription'        => $service_record['FavoriteWeaponDescription'],
            'FavoriteWeaponTotalKills'         => $service_record['FavoriteWeaponTotalKills'],
            'FavoriteWeaponUrl'                => $service_record['FavoriteWeaponImageUrl']['AssetUrl'],
            'AveragePersonalScore'             => intval($service_record['GameModes'][2]['AveragePersonalScore']),
            'MedalsPerGameRatio'               => round(intval($service_record['GameModes'][2]['TotalMedals']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            'DeathsPerGameRatio'               => round(intval($service_record['GameModes'][2]['TotalDeaths']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            'KillsPerGameRatio'                => round(intval($service_record['GameModes'][2]['TotalKills']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            'BetrayalsPerGameRatio'            => round(intval($wargames_record['TotalBetrayals']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            'SuicidesPerGameRatio'             => round(intval($wargames_record['TotalSuicides']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            'AssistsPerGameRatio'              => round(intval($wargames_record['TotalAssists']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            'HeadshotsPerGameRatio'            => round(intval($wargames_record['TotalHeadshots']) / intval($wargames_record['Summary']['TotalGamesStarted']), 2),
            'WinPercentage'                    => round(intval($service_record['GameModes'][2]['TotalGamesWon']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            'QuitPercentage'                   => round(intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']) /
                                                            intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            'TotalChallengesCompleted'         => $service_record['TotalChallengesCompleted'],
            'TotalGameWins'                    => $service_record['GameModes'][2]['TotalGamesWon'],
            'TotalGameQuits'                   => intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']),
            'NextRankStartXP'                  => $service_record['NextRankStartXP'],
            'RankStartXP'                      => $service_record['RankStartXP'],
            'TotalCommendationProgress'        => floatval($service_record['TotalCommendationProgress']),
            'TotalLoadoutItemsPurchased'       => intval($service_record['TotalLoadoutItemsPurchased']),
            'TotalMedalsEarned'                => intval($service_record['GameModes'][2]['TotalMedals']),
            'TotalGameplay'                    => $this->adjust_date($service_record['GameModes'][2]['TotalDuration']),
            'TotalKills'                       => intval($service_record['GameModes'][2]['TotalKills']),
            'TotalDeaths'                      => intval($service_record['GameModes'][2]['TotalDeaths']),
            'TotalGamesStarted'                => intval($service_record['GameModes'][2]['TotalGamesStarted']),
            'TotalHeadshots'                   => intval($wargames_record['TotalHeadshots']),
            'TotalAssists'                     => intval($wargames_record['TotalAssists']),
            'BestGameTotalKills'               => intval($wargames_record['BestGameTotalKills']),
            'BestGameTotalKillsGameId'         => $wargames_record['BestGameTotalKillsGameId'],
            'BestGameTotalMedals'              => intval($wargames_record['BestGameTotalMedals']),
            'BestGameTotalMedalsGameId'        => $wargames_record['BestGameTotalMedalsGameId'],
            'BestGameHeadshotTotal'            => intval($wargames_record['BestGameHeadshotTotal']),
            'BestGameHeadshotTotalGameId'      => $wargames_record['BestGameHeadshotTotalGameId'],
            'BestGameAssassinationTotal'       => intval($wargames_record['BestGameAssassinationTotal']),
            'BestGameAssassinationTotalGameId' => $wargames_record['BestGameAssassinationTotalGameId'],
            'BestGameKillDistance'             => intval($wargames_record['BestGameKillDistance']),
            'BestGameKillDistanceGameId'       => $wargames_record['BestGameKillDistanceGameId'],
            'ServiceTag'                       => $service_record['ServiceTag'],
            'TotalBetrayals'                   => intval($wargames_record['TotalBetrayals']),
            'TotalSuicides'                    => intval($wargames_record['TotalSuicides']),
            'LastUpdate'                       => intval(time()),
            'InactiveCounter'                  => intval(0),
            'Status'                           => intval(0),
            'ApiVersion'                       => intval(API_VERSION)
        );

        $this->_ci->mongo_db->add_index('leaf', array(
            'SeoGamertag'       => 'DESC',
            'HashedGamertag'    => 'DESC'), array(
            'unique'    => TRUE,
            'dropDups'  => TRUE
        ));

        utf8_encode_deep($dump);
        $id = $this->_ci->mongo_db->insert('leaf', $dump);
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
                $path = "uploads/emblems/" . $size;
                $image_path =  "/" . $image . ".png";
                $url = str_replace("{SIZE}", $size, str_replace("{EMBLEM}", $image, $urls['emblem_url']));
                break;

            case "Rank":
                $path = "uploads/ranks/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{RANK}", $image, $urls['rank_url']));
                break;

            case "Medal":
                $path = "uploads/medals/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{MEDAL}", $image, $urls['medal_url']));
                break;

            case "CSR":
                $path = "uploads/csr/" . $size;
                $image_path = "/" . $image . ".png";
                $url = str_replace("{SIZE}", $size, str_replace("{CSR}", $image, $urls['csr_url']));
                break;

            case "Weapon":
                $image = substr($image, 7); # remove `{SIZE}/` from url
                $path = "uploads/weapons/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{WEAPON}", $image, $urls['weapon_url']));
                break;

            case "Spartan":
                $path = "uploads/spartans/" . $this->get_hashed_seo_gamertag($this->get_seo_gamertag($image));
                $image_path = "/" . $size . "_spartan.png";
                $url = str_replace("{SIZE}", $size, str_replace("{GAMERTAG}", urlencode($image), $urls['spartan_url']));
                break;

            case "ProfileSpartan":
                $path = "uploads/spartans/" . $this->get_hashed_seo_gamertag($this->get_seo_gamertag($image));
                $image_path = "/spartan.png";
                $url = str_replace("{SIZE}", $size, str_replace("{GAMERTAG}", urlencode($image), $urls['spartan_url']));
                break;

            case "Spec":
                $image = substr($image, 7); # remove `{SIZE}/` from url
                $path = "uploads/specializations/" . $size;
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

            if ($_stream == "") {
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
                'Id' => intval($medal['Id']),
                'Count' => intval($medal['TotalMedals'])
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
        $x = 0;

        foreach($specs as $spec) {
            $rtr_arr[$x++] = array(
                'Name' => $spec['Name'],
                'Description' => $spec['Description'],
                'ImageUrl' => $spec['ImageUrl']['AssetUrl'],
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

        // start w/ 0 for our Team/Inv seperated CSR
        $rtr_arr['Team'] = array(
            'SkillRank' => 0,
            'PlaylistName' => ""
        );
        $rtr_arr['Ind'] = array(
            'SkillRank' => 0,
            'PlaylistName' => ""
        );

        // loop through each CSR adding them into final arr
        foreach ($all_csr as $csr) {
            $rtr_arr[$csr['PlaylistName']] = array(
                'Description' => $csr['PlaylistDescription'],
                'SkillRank' => intval($csr['CurrentSkillRank']),
                'Top' => ($csr['PlaylistName'] == $top_csr['PlaylistName']) ? TRUE : FALSE
            );

            // lets figure out team vs inv CSR
            if (in_array($csr['PlaylistId'],$this->_ci->config->item('h4_team_csr'))) {
                $csr_leader[$csr['PlaylistId'] . "_T"] = intval($csr['CurrentSkillRank']);
                $type = "Team";

                // check if largest - Team based CSR
                if (intval($csr['CurrentSkillRank']) >= $rtr_arr['Team']['SkillRank']) {
                    $rtr_arr['Team']['SkillRank'] = intval($csr['CurrentSkillRank']);
                    $rtr_arr['Team']['PlaylistName'] = $csr['PlaylistName'];
                }

            } else if (in_array($csr['PlaylistId'], $this->_ci->config->item('h4_individual_csr'))) {
                $csr_leader[$csr['PlaylistId'] . "_I"] = intval($csr['CurrentSkillRank']);
                $type = "Individual";

                // check if largest - Individual
                if (intval($csr['CurrentSkillRank']) >= $rtr_arr['Ind']['SkillRank']) {
                    $rtr_arr['Ind']['SkillRank'] = intval($csr['CurrentSkillRank']);
                    $rtr_arr['Ind']['PlaylistName'] = $csr['PlaylistName'];
                }
            } else {
                log_message('error', $csr['PlaylistName'] . " not found for CSR stats w/ id of " . $csr['PlaylistId']);
                $type = "Unknown";
            }
            $rtr_arr[$csr['PlaylistName']]['Type'] = $type;
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
            $data[$key]['Name'] = $this->get_metadata_name_via_id("medals", $item['Id'], "Name");
            $data[$key]['ImageUrl'] = $this->get_metadata_name_via_id("medals", $item['Id'], "ImageUrl");
            $data[$key]['TierId'] = $this->get_metadata_name_via_id("medals", $item['Id'], "TierId");
            $data[$key]['ImageUrl'] = $this->return_image_url("Medal", $data[$key]['ImageUrl'], "medium");
            $data[$key]['Description'] = $this->get_metadata_name_via_id("medals", $item['Id'], "Description");
        }

        // group according to TierId
        $new_arr = array();
        foreach ($data as $key => $item) {
            $new_arr[$item['TierId']][$item['Id']] = $item;
        }
        unset($data);

        // sort
        $this->_ci->utils->set_sort_key("Count");
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

        foreach ($data as $key => $value) {

            // Drop Team / Inv CSR
            if (count($value) == 4) {
                $data[$key]['Playlist'] = $key;
                $data[$key]['ImageUrl'] = $this->return_image_url("CSR", $data[$key]['SkillRank'], "medium");
            } else {
                unset($data[$key]);
            }
        }
        return $data;
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
        $csr = msgpack_unpack(utf8_decode($csr));
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
        $this->set_sort_key("SkillRank");
        uasort($rtr_arr, array($this, "key_sort"));
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
            if ($data[$key]['Completed']) {
                $data[$key]['ImageUrl'] = $this->return_image_url("Spec", $data[$key]['ImageUrl'], "small");
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
        // one by one lets grab em from db and cache em
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
                return '<span class="badge">Not banned</span>';

            case CHEATING_PLAYER:
                return '<span class="badge badge-important">Cheater</span>';

            case BOOSTING_PLAYER:
                return '<span class="badge badge-warning">Booster</span>';
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
     *
     * Used during recache/creation. Takes hashed $gt, and downloads / parses the emblem and Spartan Image.
     * They are then laid ontop of eachother (lol)
     *
     * @param type $hashed
     * @param type $emblem
     * @param type $gamertag
     */
    public function build_spartan_with_emblem($hashed, $emblem, $gamertag) {

        // load path helper, setup vars
        $this->_ci->load->helper("path");
        $spartan_path = absolute_path('uploads/spartans/' . $hashed) . "spartan.png";
        $emblem_path = absolute_path('uploads/spartans/' . $hashed . "/tmp/") . "emblem.png";

        // delete old spartans
        if (is_dir(absolute_path("uploads/spartans/" . $hashed))) {
            // only delete if $hashed is set
            if (strlen($hashed) > 10) {
                delete_files(absolute_path('uploads/spartans/' . $hashed), TRUE);
            }
        }

        // lets try and make a folder. check first :p
        if (!(is_dir(absolute_path('uploads/spartans/' . $hashed . "/tmp")))) {
            mkdir(absolute_path('uploads/spartans/' . $hashed . "/tmp"), 0777, TRUE);
            write_file(absolute_path('uploads/spartans/' . $hashed) . "index.html", $this->get_anti_dir_trav());
        } else {
            // only delete if $hashed is set
            if (strlen($hashed) > 10) {
                delete_files(absolute_path('uploads/spartans/' . $hashed), TRUE);
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
        delete_files(absolute_path('uploads/spartans/' . $hashed . "/tmp/"), FALSE);
        rmdir(absolute_path('uploads/spartans/' . $hashed . "/tmp"));
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

    /**
     * get anti_dir_traversal
     *
     * Returns file contents that can be injected into a `index.html`
     * to prevent directory traversal
     */
    public function get_anti_dir_trav() {

        // load config var
        $this->_ci->config->load('security');
        return $this->_ci->config->item('anti_tranversal_data');
    }

    public function backstage_gatekeeper($errors = TRUE) {
        // @todo create matching ci_backstage_table to verify against db along with session
        $session_data = $this->_ci->session->all_userdata();

        // make sure all fields are present
        foreach(["seo_username","username","id","expire","authenticated"] as $item) {
            if (!isset($session_data[$item])) {
                return $this->killoff_session($errors);
            }
        }

        // check for expiration
        if (intval($session_data['expire']) < time()) {
            return $this->killoff_session($errors);
        } else {
            // we are validated, update and move on
            $this->_ci->session->set_userdata('expire', intval(time() + HOUR_IN_SECONDS));
            return TRUE;
        }
        return $this->killoff_session($errors);
    }

    public function killoff_session($errors = TRUE) {
        if ($errors) {
            $this->_ci->session->sess_destroy();
            redirect(base_url('backstage'));
        } else {
            return FALSE;
        }
    }
}
