<?php

class Library {

    public $ci;
    public $lang;
    public $game;
    
    // urls @todo Abstract to config/
    public $emblem_url = "https://emblems.svc.halowaypoint.com/h4/emblems/{EMBLEM}?size=120";
    public $spartan_url = "https://spartans.svc.halowaypoint.com/players/{GAMERTAG}/h4/spartans/fullbody?target=medium";

    function __construct() {
        $this->_ci = & get_instance();
        $this->lang = "english";
        $this->game = "h4";
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
     */
    function time_duration($seconds, $use = null, $zeros = false) {
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
            if ($use && strpos($use, $period[0]) === false) {
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
     * fix_date
     * 
     * Pass the full response from 343,
     * @param type $resp (raw array json)
     * @param type $keys (array keys that need date fixed)
     * @param type $part (Portion where loop starts ex "Challenges")
     * @return type
     */
    public function fix_date($resp, $keys = "", $part = "") {

        if ($resp == false) {
            return false;
        }

        // blows keys into array
        $keys = explode(",", $keys);

        // loop the entire structure of $part
        foreach ($resp[$part] as $key => $item) {

            // loop for every date to change
            foreach ($keys as $key_pair) {
                if (isset($item[$key_pair])) {
                    $resp[$part][$key][$key_pair] = strtotime($item[$key_pair], true);
                }
            }
        }

        return $resp;
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
            return false;
        }
    }

    // ---------------------------------------------------------------
    // API Calls
    // ---------------------------------------------------------------

    /**
     * get_url
     * 
     * Takes the url along w/ language / game, parameter is just paras of the URL
     * @param type $paras
     * @return type
     */
    private function get_url($paras) {

        // set accept header
        $this->_ci->curl->option('HTTPHEADER', array('Accept: application/json'));

        // make the url
        $url = "https://stats.svc.halowaypoint.com/" . $paras;

        // resp
        $resp = json_decode($this->_ci->curl->simple_get($url), true);

        // check it
        return $this->check_status($resp);
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
        if ($resp == false) {
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
     * 
     * 1) Checks for file-based cache.
     * 2) Returns if less than 1 hr old
     * 3) If not, pulls from API. Dumps into dB.
     * 4) Caches into file system for 1hr.
     * @param type $gt
     */
    public function get_profile($gt, $errors = true) {

        if (strlen(urldecode($gt)) > 15) {

            if ($errors) {
                show_error("wtf. This is more than 15 chars. This isn't a gamertag.");
            } else {
                return false;
            }
        } else {

            // get hashed name
            $hashed = "profile_" . md5(trim(urlencode($gt)));

            // check cache
            $resp = $this->_ci->cache->get($hashed);

            if ($resp == false || ENVIRONMENT == "development") {

                // grab new data
                $resp = $this->grab_profile_data($gt);

                if ($resp == false) {
                    if ($errors) {
                        show_error("stop. This isn't an Xbox Live (Halo 4) account.");
                    } else {
                        return false;
                    }
                }
                $this->_ci->cache->write($resp, $hashed);
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
     * grab_profile_data
     * 
     * Pulls directly from the API. Stores into dB
     * 
     * @param type $gt
     */
    public function grab_profile_data($gt) {

        // lets grab service record
        $service_record = $this->check_status($this->get_url($this->lang . "/players/" . trim(urlencode($gt)) . "/" . $this->game . "/servicerecord"));

        if ($service_record == false) {
            return false;
        }

        // make hashed name
        $hashed = md5(trim(urlencode($gt)));
        
        // lets do the URL work
        $this->build_spartan_with_emblem($hashed, substr_replace($service_record['EmblemImageUrl']['AssetUrl'], "", -12), $gt);

        // get ready for a dump of data
        return $this->_ci->stat_m->update_or_insert_gamertag($hashed, array(
                    'Gamertag' => urldecode($gt),
                    'HashedGamertag' => $hashed,
                    'Expiration' => intval(time()),
                    'KDRatio' => $service_record['GameModes'][2]['KDRatio'],
                    'Xp' => $service_record['XP'],
                    'SpartanPoints' => $service_record['SpartanPoints'],
                    'TotalChallengesCompleted' => $service_record['TotalChallengesCompleted'],
                    'TotalGameWins' => $service_record['GameModes'][2]['TotalGamesWon'],
                    'TotalGameQuits' => intval($service_record['GameModes'][2]['TotalGamesStarted'] - $service_record['GameModes'][2]['TotalGamesCompleted']),
                    'NextRankStartXP' => $service_record['NextRankStartXP'],
                    'TotalCommendationProgress' => floatval($service_record['TotalCommendationProgress']),
                    'TotalLoadoutItemsPurchased' => intval($service_record['TotalLoadoutItemsPurchased']),
                    'TotalMedalsEarned' => intval($service_record['GameModes'][2]['TotalMedals']),
                    'TotalGameplay' => $this->adjust_date($service_record['GameModes'][2]['TotalDuration']),
                    'TotalKills' => intval($service_record['GameModes'][2]['TotalKills']),
                    'TotalDeaths' => intval($service_record['GameModes'][2]['TotalDeaths']),
                    'TotalGamesStarted' => intval($service_record['GameModes'][2]['TotalGamesStarted']),
                    'ServiceTag' => $service_record['ServiceTag']
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
     * emblem
     * 
     * @param type $hashed
     * @param type $emblem
     * @param type $gamertag
     */
    public function build_spartan_with_emblem($hashed, $emblem, $gamertag) {
        
        // load path helper, setup vars
        $this->_ci->load->helper("path");
        $spartan_path = absolute_path('uploads/spartans/' . $hashed . "/tmp") . "/spartan.png";
        $emblem_path = absolute_path('uploads/spartans/' . $hashed . "/tmp/") . "emblem.png";
        
        // lets try and make a folder. check first :p
        if (!(is_dir(absolute_path('uploads/spartans/' . $hashed . "/tmp")))) {
            mkdir(absolute_path('uploads/spartans/' . $hashed . "/tmp"), 0777, true);
        }

        // download 2 images in there, (emblem and spartan). Ignore all errors. Check afterwards
        $emblem = @file_get_contents(str_replace("{EMBLEM}",$emblem,$this->emblem_url));
        @file_put_contents($emblem_path, $emblem);
        
        $spartan = @file_get_contents(str_replace("{GAMERTAG}", $gamertag, $this->spartan_url));
        @file_put_contents($spartan_path, $spartan);
        
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
            $config['wm_opacity'] = 0;
            $config['quality'] = "100%";
            $config['wm_x_transp'] = 1;
            $config['wm_y_transp'] = 1;
            $config['wm_hor_alignment'] = "right";
            $this->_ci->image_lib->initialize($config);
            $this->_ci->image_lib->watermark();
        }
    }

}