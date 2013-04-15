<?php

class Library {

    protected $_ci;
    public $lang;
    public $game;
    
    // urls @todo Abstract to config/
    public $emblem_url = "https://emblems.svc.halowaypoint.com/h4/emblems/{EMBLEM}?size={SIZE}";
    public $spartan_url = "https://spartans.svc.halowaypoint.com/players/{GAMERTAG}/h4/spartans/fullbody?target={SIZE}";
    public $rank_url = "https://assets.halowaypoint.com/games/h4/ranks/v1/{SIZE}/{RANK}";
    public $medal_url = "https://assets.halowaypoint.com/games/h4/medals/v1/{SIZE}/{MEDAL}";
    public $csr_url = "https://assets.halowaypoint.com/games/h4/csr/v1/{SIZE}/{CSR}.png";
    public $weapon_url = "https://assets.halowaypoint.com/games/h4/damage-types/v1/{SIZE}/{WEAPON}";

    function __construct() {
        $this->_ci = & get_instance();
        $this->lang = "english";
        $this->game = "h4";
        $this->_ci->load->model('stat_model', 'stat_m', TRUE);

        // load path helper, setup vars
        $this->_ci->load->helper("path");
    }

    // ---------------------------------------------------------------
    // 3rd Party
    // ---------------------------------------------------------------

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

    // ---------------------------------------------------------------
    // Helper Calls
    // ---------------------------------------------------------------

    /**
     * is_active
     * 
     * Determines if passed $item is equal to navigation
     * 
     * @param type $item
     * @return type
     */
    public function is_active($item) {
        if (uri_string() == "") {
            $uri_string = "home";
        } else {
            $uri_string = uri_string();
        }

        return strpos($uri_string, $item) !== FALSE ? 'active' : '';
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
     * get_trophy
     * returns image for place
     *
     * @param int|\type $x
     * @return string
     */
    public function get_trophy($x = 0) {

        switch ($x) {

            case 1:
                return '<img src="' . base_url("assets/img/icons/medal_gold.png") . '" />';
                break;

            case 2:
                return '<img src="' . base_url("assets/img/icons/medal_silver.png") . '" />';
                break;

            case 3:
                return '<img src="' . base_url("assets/img/icons/medal_bronze.png") . '" />';
                break;

            case 4:
                return "4<sup>th</sup>";
                break;

            case 5:
                return "5<sup>th</sup>";
                break;

            default:
                return $x;
                break;
        }
    }

    /**
     * check_status
     * 
     * Checks the API for the Status var to make sure its up and running
     * @param type $resp
     * @return boolean
     */
    public function check_status($resp) {
        if (isset($resp['StatusCode']) && ($resp['StatusCode'] == 0 || $resp['StatusCode'] == 1)) {
            return $resp;
        } else {
            log_message('error', 'API Down: ' . $resp['StatusReason']);
            return FALSE;
        }
    }

    /**
     * get_badge
     *
     * Gets little `badges` to put by usernames
     * @param $gt
     * @return string
     */
    public function get_badge($gt) {
        // check for badges
        if (in_array($gt, $this->_ci->config->item('employees_343'))) {
            return '<span class="badge badge-info">343 Employee</span>&nbsp;';
        } else if ($gt == "iBotPeaches v5") {
            return '<span class="badge badge-info">Owner</span>&nbsp;';
        } else {
            return "";
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



    }

    /**
     * get_url
     * Takes the url along w/ language / game, parameter is just paras of the URL
     *
     * @param type $paras
     * @param bool $auth
     * @return string
     */
    private function get_url($paras, $auth = FALSE) {

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


        // make the url
        $url = "https://stats.svc.halowaypoint.com/" . $paras;

        // resp
        $resp = json_decode($this->_ci->curl->simple_get($url), TRUE);

        // check it
        return $this->check_status($resp);
    }

    /**
     * get_spartan_auth_key
     * Uses separate non-public method to generate AUTH code for authenticated API endpoints
     *
     * @param int $count
     * @return null $key|null
     */
    private function get_spartan_auth_key($count = 0) {
        #$this->get_metadata();

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

                // if its looped more than 5 times, we didn't find a key :(
                if ($count < 5) {
                    $this->_ci->cache->delete('auth_spartan');
                    return $this->get_spartan_auth_key($count);
                }  else {
                    $this->throw_error("API_AUTH_GONE");
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

            // lets make this URL
            $resp = $this->_ci->curl->simple_get($get_url);

            // count
            if (strlen($resp) > 150) {
                $this->_ci->cache->write($resp, 'auth_spartan', 3000);
                return json_decode($resp,TRUE)['SpartanToken'];
            } else {
                $count++;
                return $this->get_spartan_auth_key($count);
            }
        } else {
            return $_tmp['SpartanToken'];
        }

    }

    /**
     * get_challenges
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
                $this->throw_error("LONGER_THAN_15_CHARS_GT");
            } else {
                return FALSE;
            }
        } else {

            // get hashed name
            $hashed = "profile_" . $this->get_hashed_seo_gamertag($seo_gamertag);

            // check cache
            $resp = $this->_ci->cache->get($hashed);

            if ($resp == FALSE || ENVIRONMENT == "development" || $force == TRUE) {

                // grab new data
                $resp = $this->grab_profile_data($gt,$force, $seo_gamertag);

                if ($resp == FALSE) {
                    if ($errors) {
                        $this->throw_error("NOT_XBL_ACCOUNT");
                    } else {
                        return FALSE;
                    }
                }
                $this->_ci->cache->write($resp, $hashed, 3600);
                return $resp;
            } else {

                // check for expiration
                if ($resp['Expiration'] > time()) {
                    $this->_ci->cache->delete($hashed);
                    return $this->get_profile($gt);
                } else {
                    return $resp;
                }
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
        return preg_replace('/\s+/', '_', strtolower(urldecode($gt)));
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

        // make hashed name
        $hashed = $this->get_hashed_seo_gamertag($seo_gamertag);

        // grab from db, if null continue
        $resp = $this->_ci->stat_m->get_gamertag_data($hashed);
        
        if (isset($resp['Expiration']) && is_array($resp)) {
            if (intval($resp['Expiration']) > intval(time()) && $force == FALSE) {
                return $resp;
            }
        }

        // lets grab service record
        $service_record = $this->check_status($this->get_url($this->lang . "/players/" . trim(urlencode(strtolower($gt))) . "/" . $this->game . "/servicerecord", FALSE));

        // lets grab service record /wargames
        $wargames_record = $this->check_status($this->get_url($this->lang . "/players/" . trim(urlencode(strtolower($gt))) . "/" . $this->game . "/servicerecord/wargames", TRUE));

        if ($service_record == FALSE || $wargames_record == FALSE) {
            $this->throw_error("ENDPOINTS_DOWN");
        }
        
        // lets do the URL work, and medal
        $this->build_spartan_with_emblem($hashed, substr_replace($service_record['EmblemImageUrl']['AssetUrl'], "", -12), $gt);
        $medal_data = $this->get_medal_data($service_record['TopMedals']);

        // get skill stuff
        $skill_data = $this->get_skill_data($service_record['SkillRanks'], $service_record['TopSkillRank']);
        
        // check for lvl 130
        if ($service_record['NextRankId'] == 0) {
            $service_record['NextRankStartXP'] = 0;
        }

        // nasty little hack. If they have 0 games played in matchmaking, reject this bitch.
        if (isset($service_record['GameModes'][2]['TotalGamesStarted']) && intval($service_record['GameModes'][2]['TotalGamesStarted']) == 0) {
            $this->throw_error("NO_GAMES_PLAYED");
        }

        // get ready for a dump of data
        return $this->_ci->stat_m->update_or_insert_gamertag($hashed, array(
            'Gamertag'                   => urldecode($gt),
            'HashedGamertag'             => $hashed,
            'SeoGamertag'                => $seo_gamertag,
            'Rank'                       => $service_record['RankName'],
            'RankImage'                  => substr($service_record['RankImageUrl']['AssetUrl'], 7),
            'Specialization'             => $this->find_current_specialization($service_record['Specializations']),
            'SpecializationLevel'        => $this->find_current_specialization($service_record['Specializations'], "Level"),
            'Expiration'                 => intval(time() + THREE_HOURS_IN_SECONDS),
            'MedalData'                  => @serialize($medal_data),
            'SkillData'                  => @serialize($skill_data),
            'KDRatio'                    => $service_record['GameModes'][2]['KDRatio'],
            'Xp'                         => $service_record['XP'],
            'SpartanPoints'              => $service_record['SpartanPoints'],
            'FavoriteWeaponName'         => $service_record['FavoriteWeaponName'],
            'FavoriteWeaponDescription'  => $service_record['FavoriteWeaponDescription'],
            'FavoriteWeaponTotalKills'   => $service_record['FavoriteWeaponTotalKills'],
            'FavoriteWeaponUrl'          => $service_record['FavoriteWeaponImageUrl']['AssetUrl'],
            'AveragePersonalScore'       => intval($service_record['GameModes'][2]['AveragePersonalScore']),
            'MedalsPerGameRatio'         => round(intval($service_record['GameModes'][2]['TotalMedals']) / intval($service_record['GameModes'][2]['TotalGamesStarted']),2),
            'DeathsPerGameRatio'         => round(intval($service_record['GameModes'][2]['TotalDeaths']) / intval($service_record['GameModes'][2]['TotalGamesStarted']),2),
            'KillsPerGameRatio'          => round(intval($service_record['GameModes'][2]['TotalKills']) / intval($service_record['GameModes'][2]['TotalGamesStarted']),2),
            'WinPercentage'              => round(intval($service_record['GameModes'][2]['TotalGamesWon']) / intval($service_record['GameModes'][2]['TotalGamesStarted']), 2),
            'QuitPercentage'             => round(intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']) /
                                                    intval($service_record['GameModes'][2]['TotalGamesStarted']),2),
            'TotalChallengesCompleted'   => $service_record['TotalChallengesCompleted'],
            'TotalGameWins'              => $service_record['GameModes'][2]['TotalGamesWon'],
            'TotalGameQuits'             => intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']),
            'NextRankStartXP'            => $service_record['NextRankStartXP'],
            'RankStartXP'                => $service_record['RankStartXP'],
            'TotalCommendationProgress'  => floatval($service_record['TotalCommendationProgress']),
            'TotalLoadoutItemsPurchased' => intval($service_record['TotalLoadoutItemsPurchased']),
            'TotalMedalsEarned'          => intval($service_record['GameModes'][2]['TotalMedals']),
            'TotalGameplay'              => $this->adjust_date($service_record['GameModes'][2]['TotalDuration']),
            'TotalKills'                 => intval($service_record['GameModes'][2]['TotalKills']),
            'TotalDeaths'                => intval($service_record['GameModes'][2]['TotalDeaths']),
            'TotalGamesStarted'          => intval($service_record['GameModes'][2]['TotalGamesStarted']),
            'ServiceTag'                 => $service_record['ServiceTag'],
            'LastUpdate'                 => intval(time()),
            'InactiveCounter'            => intval(0)
        ));
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
        
        // switch for Type
        switch ($type) {
            
            case "Emblem":
                $path = "uploads/emblems/" . $size;
                $image_path =  "/" . $image . ".png";
                $url = str_replace("{SIZE}", $size, str_replace("{EMBLEM}", $image, $this->emblem_url));
                break;
            
            case "Rank":
                $path = "uploads/ranks/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{RANK}", $image, $this->rank_url));
                break;
            
            case "Medal":
                $path = "uploads/medals/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{MEDAL}", $image, $this->medal_url));
                break;

            case "CSR":
                $path = "uploads/csr/" . $size;
                $image_path = "/" . $image . ".png";
                $url = str_replace("{SIZE}", $size, str_replace("{CSR}", $image, $this->csr_url));
                break;

            case "Weapon":
                $image = substr($image, 7); # remove `{SIZE}/` from url
                $path = "uploads/weapons/" . $size;
                $image_path = "/" . $image;
                $url = str_replace("{SIZE}", $size, str_replace("{WEAPON}", $image, $this->weapon_url));
                break;

            case "Spartan":
                $path = "uploads/spartans/" . $this->get_hashed_seo_gamertag($this->get_seo_gamertag($image));
                $image_path = "/" . $size . "_spartan.png";
                $url = str_replace("{SIZE}", $size, str_replace("{GAMERTAG}", urlencode($image), $this->spartan_url));
                break;

            case "ProfileSpartan":
                $path = "uploads/spartans/" . $this->get_hashed_seo_gamertag($this->get_seo_gamertag($image));
                $image_path = "/spartan.png";
                $url = str_replace("{SIZE}", $size, str_replace("{GAMERTAG}", urlencode($image), $this->spartan_url));
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
                'Name' => $medal['Name'],
                'Count' => intval($medal['TotalMedals']),
                'Description' => $medal['Description'],
                'ImageUrl' => str_replace("{size}/", NULL, $medal['ImageUrl']['AssetUrl'])
            );
        }
        
        return $rtr_arr;
    }

    /**
     * get_skill_data
     *
     * Goes through skill data finding CSR
     *
     * @param $all_csr
     * @param $top_csr
     * @return array
     */
    public function get_skill_data($all_csr, $top_csr) {
        $rtr_arr = array();

        foreach ($all_csr as $csr) {

            // only add if CSR is more than 0
            //if ($csr['CurrentSkillRank'] > 0) {
                $rtr_arr[$csr['PlaylistName']] = array(
                    'Description' => $csr['PlaylistDescription'],
                    'SkillRank' => intval($csr['CurrentSkillRank']),
                    'Top' => ($csr['PlaylistName'] == $top_csr['PlaylistName']) ? TRUE : FALSE
                );
            //}
        }

        if (count($rtr_arr) > 0) {
            uasort($rtr_arr, "key_sort");
            return $rtr_arr;
        } else {
            return FALSE;
        }
    }
    
    /**
     * return_medals
     * 
     * Parses url into real url of image.
     * @param type $data
     * @return mixed
     */
    public function return_medals($data) {
        $data = @unserialize($data);
        
        foreach ($data as $key => $item) {
            $data[$key]['ImageUrl'] = $this->return_image_url("Medal", $data[$key]['ImageUrl'], "large");
        }
        return $data;
    }

    /**
     * return_csr
     *
     * Parses serialized array `$data` for display on profile
     * @param $data
     * @return mixed
     */
    public function return_csr($data) {
        if ($data == FALSE || $data == "" | $data == "b:0;") {
            return FALSE;
        }
        $data = @unserialize($data);

        foreach ($data as $key => $value) {
            $data[$key]['Playlist'] = $key;
            $data[$key]['ImageUrl'] = $this->return_image_url("CSR", $data[$key]['SkillRank'], "large");
        }
        return $data;
    }

    /**
     * return favorite
     *
     * Preps data for display on Profile
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
     * emblem
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

        // download 2 images in there, (emblem and spartan). Ignore all errors. Check afterwards
        $emblem = file_get_contents($this->return_image_url("Emblem", $emblem, "80"));
        file_put_contents($emblem_path, $emblem);
        
        $spartan = file_get_contents($this->return_image_url("ProfileSpartan",$gamertag, "medium"));
        file_put_contents($spartan_path, $spartan);
        
        // cleanup
        unset($emblem);
        unset($spartan);
        
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
     * @param type       $field
     * @param bool|\type $asc
     * @return
     */
    public function get_top_5_leaderboard($field, $asc = FALSE) {
        return $this->_ci->stat_m->get_top_5($field, $asc);
    }
    
    /**
     * get anti_dir_traversal
     */
    public function get_anti_dir_trav() {
        
        // load config var
        $this->_ci->config->load('security');
        return $this->_ci->config->item('anti_tranversal_data');
    }
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
    if ($a['SkillRank'] == $b['SkillRank']) {
        return 0;
    }
    return ($a['SkillRank'] < $b['SkillRank'] ? 1 : -1);
}