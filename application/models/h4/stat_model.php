<?php

/**
 * Class Stat_model
 */
class Stat_model extends IBOT_Model {

    function __construct() {
        parent::__construct();
        log_message('debug', 'Stat_modal loaded');
    }

    /**
     * update_or_insert_gamertag
     * 
     * Checking
     * @param type $hash
     * @param type $data
     * @return \type
     */
    public function update_or_insert_gamertag($hash, $data) {

        // check for update
        if (($_tmp = $this->account_exists($hash)) != FALSE) {

            if (isset($_tmp[H4::TOTAL_GAMEPLAY]) && isset($data[H4::TOTAL_GAMEPLAY])) {
                if (floatval($_tmp[H4::TOTAL_GAMEPLAY]) != floatval($data[H4::TOTAL_GAMEPLAY])) {
                    $data[H4::INACTIVE_COUNTER] = 0;

                    if (isset($data[H4::STATUS]) && $data[H4::STATUS] == MISSING_PLAYER) {

                        // update status to 0
                        $this->change_status($data[H4::STATUS], intval(0));
                    }
                } else {
                    // if InactiveCounter is above INACTIVE_COUNTER
                    // set InactiveCounter to MAX, otherwise
                    // previous InactiveCounter + 1;
                    $data[H4::INACTIVE_COUNTER] = ($_tmp[H4::INACTIVE_COUNTER] >= INACTIVE_COUNTER) ? INACTIVE_COUNTER : $_tmp[H4::INACTIVE_COUNTER] + 1;
                }
            }

            // unset some temporarily, so we can add back on postback
            $unset_arra = array(
                'Gamertag'          => $_tmp[H4::GAMERTAG],
                'SeoGamertag'       => $_tmp[H4::SEO_GAMERTAG],
                'HashedGamertag'    => $_tmp[H4::HASHED_GAMERTAG],
                'Status'            => $_tmp[H4::STATUS]
            );

            // remove these vars to prevent changing them in db
            // ex accidentally changing gt from TEST to teST
            unset($data[H4::GAMERTAG]);
            unset($data[H4::SEO_GAMERTAG]);
            unset($data[H4::HASHED_GAMERTAG]);
            unset($data[H4::STATUS]);
            $this->update_account($hash, $data);
        } else {
            $this->insert_account($data);
        }

        // if we unset some elements
        // lets add them back
        if (isset($unset_arra)) {
            $data = array_merge($data, $unset_arra);
        }
        return $data;
    }

    /**
     * update_or_mark_as_missing
     * Tries to see if the gamertag passed is MISSING or not, inserts if so
     *
     * @param $gt
     * @param $seo_gt
     * @internal param $status
     */
    public function update_or_mark_as_missing($gt, $seo_gt) {

        // get status
        $status = $this->get_status($seo_gt);

        if (($_tmp = $this->get_missing_record($seo_gt)) != FALSE) {
            $this->update_missing_record($seo_gt, $status, $_tmp['Count']);
        }  else {
            $this->insert_missing_record($gt, $seo_gt, $status);
        }
    }

    /**
     * get_missing_record
     *
     * Pulls record via `ci_missing` via `SeoGamertag`
     * @param $seo_gt
     * @return bool
     */
    public function get_missing_record($seo_gt) {

       $resp =  $this->db
                    ->select('SeoGamertag,Status,Count')
                    ->get_where('ci_missing', array(
                        'SeoGamertag' => $seo_gt
                    ));

        $resp = $resp->row_array();

        if (isset($resp['Count'])) {
            return $resp;
        } else {
            return FALSE;
        }

    }

    /**
     * update_missing_record
     *
     * Updates the `ci_missing` record w/ `Status,Count` via `SeoGamertag`
     * @param $seo_gt
     * @param $status
     * @param $count
     */
    public function update_missing_record($seo_gt, $status, $count) {
        $this->db
            ->where('SeoGamertag', $seo_gt)
            ->update('ci_missing', array(
                'Status'    => intval($status),
                'Count'     => intval($count + 1)
            ));

    }

    /**
     * insert_missing_record
     *
     * Inserts record into `ci_missing`
     * @param $gt
     * @param $seo_gt
     * @param $status
     */
    public function insert_missing_record($gt, $seo_gt, $status) {
        $this->db
            ->insert('ci_missing', array(
                'Gamertag'      => $gt,
                'SeoGamertag'   => $seo_gt,
                'Status'        => intval($status),
                'Count'         => intval(0)
            ));
    }

    /**
     * delete_missing_record
     *
     * Deletes missing record from `ci_missing` where `SeoGamertag`
     * @param $seo_gt
     */
    public function delete_missing_record($seo_gt) {
        $this->db
            ->delete('ci_missing', array(
                'SeoGamertag' => $seo_gt
            ));
    }
    
    /**
     * update_account
     * @param type $hash
     * @param type $data
     * @return boolean
     */
    public function update_account($hash, $data) {
        
        $this->mongo_db
                ->set($data)
                ->where(H4::HASHED_GAMERTAG, $hash)
                ->update('h4_gamertags');

    }

    /**
     * insert_account
     *
     * @param type $data
     * @return void
     * @internal param \type $hash
     */
    public function insert_account($data) {
        $this->mongo_db
            ->insert('h4_gamertags', $data);
    }

    /**
     * account_exists
     * 
     * @param type $hash
     * @return array|bool
     */
    public function account_exists($hash) {

        // generate resp
        $resp = $this->mongo_db
                ->select([H4::HASHED_GAMERTAG, H4::INACTIVE_COUNTER, H4::GAMERTAG, H4::SEO_GAMERTAG, H4::STATUS, H4::TOTAL_GAMEPLAY])
                ->get_where('h4_gamertags', array(
                    H4::HASHED_GAMERTAG => $hash
        ));

        return $this->_get_one($resp, H4::HASHED_GAMERTAG);
    }

    /**
     * badge_exists
     *
     * Checks if a badge exists for this gamertag.
     * @param $seo_gt
     * @return bool
     */
    public function badge_exists($seo_gt) {
        $resp = $this->db
                ->select('id')
                ->get_where('ci_badges', array(
                'SeoGamertag'   => $seo_gt
            ));

        $resp = $resp->row_array();

        if (isset($resp['id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * get_status
     *
     * Returns `Status` via `ci_gamertags` from `SeoGamertag`
     * @param $seo_gamertag
     * @return bool|int
     */
    public function get_status($seo_gamertag) {
        $resp = $this->mongo_db
                ->select([H4::STATUS])
                ->get_where('h4_gamertags', array(
                    H4::SEO_GAMERTAG => $seo_gamertag
        ));

        return $this->_get_one($resp, H4::STATUS);
    }
    
    /**
     * get_gamertag
     * 
     * Returns every field from `ci_gamertags`
     * @param type $hashed
     * @return boolean
     */
    public function get_gamertag_data($hashed) {

        $resp = $this->mongo_db
                    ->where(H4::HASHED_GAMERTAG, $hashed)
                    ->limit(1)
                    ->get('h4_gamertags');

        return $this->_get_one($resp, H4::HASHED_GAMERTAG);
    }

    /**
     * count_gamertags
     *
     * @param bool $active
     * @return type
     */
    public function count_gamertags($active = FALSE) {
        
        if ($active) {
            $this->db->where("Expiration <", time());
        }
        
        return $this->db->count_all_results('ci_gamertags');
    }

    /**
     * get_max_id
     *
     * Find the largest `id` in `ci_gamertags` to be used in cron searches
     *
     * @return bool
     */
    public function get_max_id() {
        $this->db->select_max('id','max');
        $resp = $this->db->get('ci_gamertags');

        $resp = $resp->row_array();

        if (isset($resp['max'])) {
            return $resp['max'];
        } else {
            return FALSE;
        }
    }
    
    /**
     * cron_gamertags
     * 
     * Grabs data based on previous run.
     * @param type $start
     * @param type $max
     * @return boolean
     */
    public function cron_gamertag($start, $max) {
        $resp = $this->db
                ->select('HashedGamertag,TotalGameplay,id,InactiveCounter,Gamertag,SeoGamertag')
                ->limit(intval($max),intval($start))
                ->order_by("id", "asc")
                ->get_where('ci_gamertags', array(
                    'Status'    => intval(0),
                    'Expiration <' => time(),
                    'InactiveCounter <' => INACTIVE_COUNTER
                ));
        
        $resp = $resp->result_array();
        
        if (is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
                
    }
    
    /**
     * get_expiration_date
     * 
     * @param type $hashed
     * @return boolean
     */
    public function get_expiration_date($hashed) {
        $resp = $this->mongo_db
                ->select(H4::EXPIRATION)
                ->limit(1)
                ->where(H4::HASHED_GAMERTAG, $hashed)
                ->where_lt(H4::INACTIVE_COUNTER, intval(INACTIVE_COUNTER))
                ->get('h4_gamertags');

        return $this->_get_one($resp, H4::EXPIRATION);
    }
    
    /**
     * get_top_10
     *
     * @param type $field
     * @param type $asc
     * @return boolean
     */
    public function get_top_10($field, $asc) {
        
        $resp = $this->mongo_db
                ->select([H4::GAMERTAG, H4::SERVICE_TAG, H4::SEO_GAMERTAG, $field])
                ->limit(10)
                ->order_by([$field => $asc])
                ->where_gt(H4::TOTAL_GAMES_STARTED, intval(100))
                ->where(H4::STATUS, intval(0))
                ->get("h4_gamertags");
        
        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }
    
    /**
     * get_last_5
     * 
     * Returns last 5 made accounts.
     * @return boolean
     */
    public function get_last_5() {
        $resp = $this->mongo_db
                ->select([H4::GAMERTAG, H4::SEO_GAMERTAG, H4::RANK, H4::SERVICE_TAG])
                ->order_by([ "_id" => -1])
                ->limit(5)
                ->get('h4_gamertags');

        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * load_gamertags
     *
     * Loads gamertags from `ci_gamertags` that being with `$letter`
     * @param string $letter
     * @return array|bool
     */
    public function load_gamertags($letter = "") {

        // only search if letters passed
        if ($letter != "") {
            $this->mongo_db->like(H4::GAMERTAG, $letter);
        }

        // execute
        $resp = $this->mongo_db
            ->select([H4::GAMERTAG])
            ->limit(25)
            ->get('h4_gamertags');

        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * insert_metadata
     *
     * Inserts passed `$table` data into dB associated table. Since metadata is volatile,
     * it is TRUNCATED on every insert to start fresh.
     *
     * @param $table
     * @param $data
     */
    public function insert_metadata($table, $data) {
        // delete old data
        $this->db
            ->from('ci_m_' . $table)
            ->truncate();

        // insert new data
        $this->db
            ->insert_batch('ci_m_' . $table, $data);
    }

    /**
     * get_metdata
     *
     * Give it the type of metadata which corresponds to the table
     * `ci_m_$type` where $type is the lowercase metadata.
     *
     * Results are automatically re-aligned so the $key value is that ID
     * of that element, instead of being sequential
     *
     * @param $type
     * @return array|bool
     */
    public function get_metadata($type) {
       $resp = $this->db
           ->get('ci_m_' . $type);

       $resp = $resp->result_array();

        if (is_array($resp) && count($resp) > 0) {
            // re-align IDs
            $rtr_array = array();
            foreach ($resp as $key => $item) {
                $rtr_array[$item['Id']] = $item;
            }
            unset($resp);
            return $rtr_array;
        } else {
            return FALSE;
        }
    }

    /**
     * get_cheating_users()
     *
     * Grab any users who cheated.
     * @return array|bool
     */
    public function get_cheating_users() {
        $resp = $this->db
            ->select('Gamertag,SeoGamertag,HashedGamertag,Status')
            ->limit(8)
            ->get_where('ci_gamertags', array(
                'Status >' => intval(0)
            ));
        $resp = $resp->result_array();

        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * check_for_flag
     *
     * Takes `ip` and `$seo` name to see if this IP has already flagged this gamertag
     * @param $seo
     * @param $ip
     * @return bool
     */
    public function check_for_flag($seo, $ip) {
        $resp = $this->db
            ->get_where('ci_flagged', array(
                'SeoGamertag' => $seo,
                'ip_address' => $ip
            ));

        $resp = $resp->row_array();

        if (isset($resp['SeoGamertag'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * insert_flag
     *
     * Inserts the current id `flag` into the $gt array
     * @param $gt
     * @param $ip
     */
    public function insert_flag($gt, $ip) {
        $this->db
            ->insert('ci_flagged', array(
                'SeoGamertag' => $gt['SeoGamertag'],
                'Gamertag' => $gt['Gamertag'],
                'Id'    => $gt['id'],
                'ip_address' => $ip
            ));
    }

    /**
     * insert_badge
     *
     * Inserts badge into `ci_badges`
     * @param $data
     */
    public function insert_badge($data) {
        $this->db
            ->insert('ci_badges', $data);
    }

    /**
     * delete_pending_flagged_users
     *
     * @param $seo
     */
    public function delete_pending_flagged_users($seo) {
        $resp = $this->db
            ->delete('ci_flagged', array(
                'SeoGamertag' => $seo
            ));
    }

    /**
     * change_status
     *
     * @param $seo
     * @param $status
     */
    public function change_status($seo, $status) {
        $this->db
            ->where('SeoGamertag', $seo)
            ->update('ci_gamertags', array(
                'Status' => intval($status)
            ));

        $this->db
            ->where('SeoGamertag', $seo)
            ->update('ci_csr', array(
                'Status' => intval($status)
            ));
    }

    /**
     * change_csr_status
     *
     * Flags user in `ci_csr` as booster/cheater
     * @param $seo_gt
     * @param $status
     */
    public function change_csr_status($seo_gt, $status) {
        $this->db
            ->where('SeoGamertag', $seo_gt)
            ->update('ci_csr', array(
                'Status' => intval($status)
            ));
    }

    /**
     * get_gamertag_name
     *
     * Returns `Gamertag` when passed `SeoGamertag`
     * @param $seo_gt
     * @return bool
     */
    public function get_gamertag_name($seo_gt) {
        $resp = $this->mongo_db
                ->select([H4::GAMERTAG])
                ->get_where('h4_gamertags', array(
                H4::SEO_GAMERTAG => $seo_gt
            ));

        return $this->_get_one($resp, H4::GAMERTAG);
    }

    /**
     * get_name_and_emblem
     *
     * Grabs `Gamertag` and `Emblem` via `SeoGamertag`
     *
     * @param $seo_gt
     * @return bool
     */
    public function get_name_and_emblem($seo_gt) {
        $resp = $this->mongo_db
            ->select([H4::GAMERTAG,H4::SEO_GAMERTAG, H4::EMBLEM])
            ->get_where('h4_gamertags', array(
                H4::SEO_GAMERTAG => $seo_gt
            ));

        return $this->_get_one($resp, H4::GAMERTAG);
    }

    /**
     * get_name_and_kd
     *
     * Gets `Gamertag` and `KDRatio` via `SeoGamertag`
     *
     * @param $seo_gt
     * @return bool
     */
    public function get_name_and_kd($seo_gt) {
        $resp = $this->mongo_db
                ->select([H4::GAMERTAG, H4::SEO_GAMERTAG, H4::KD_RATIO])
                ->get_where('h4_gamertags', array(
                H4::SEO_GAMERTAG => $seo_gt
            ));

        return $this->_get_one($resp, H4::GAMERTAG);
    }

    /**
     * get_unfreeze_data
     *
     * Grabs `InactiveCounter,Gamertag,SeoGamertag` via `SeoGamertag`
     * @param $seo_gt
     * @return bool
     */
    public function get_unfreeze_data($seo_gt) {
        $resp = $this->db
            ->select("InactiveCounter,Gamertag,SeoGamertag,TotalGamesStarted")
            ->get_where('ci_gamertags', array(
                'SeoGamertag' => $seo_gt
            ));

        $resp = $resp->row_array();

        if (isset($resp['Gamertag'])) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * get_id_via_seo_gt
     *
     * Pass `SeoGamertag` and get `id` back via dB
     *
     * @param $seo_gt
     * @return bool
     */
    public function get_id_via_seo_gt($seo_gt) {
        $resp = $this->db
            ->select("id")
            ->get_where('ci_gamertags', array(
                'SeoGamertag' => $seo_gt
            ));

        $resp = $resp->row_array();

        if (isset($resp['id'])) {
            return $resp['id'];
        } else {
            return FALSE;
        }
    }

    /**
     * get_csr_data
     *
     * Grabs `Gamertag`,`SeoGamertag`,`Status` from `ci_csr` via `SeoGamertag`
     * @param $seo_gt
     * @return bool
     */
    public function get_csr_data($seo_gt) {
        $resp = $this->db
                ->select('Gamertag,SeoGamertag,Status')
                ->get_where('ci_csr', array(
                'SeoGamertag' => $seo_gt
            ));

        $resp = $resp->row_array();

        if (isset($resp['SeoGamertag'])) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * update_csr_leaderboards
     *
     * Adds abstracted CSR data into trackable leaderboard
     * @param $data
     * @return bool
     */
    public function update_csr_leaderboards($data) {

        if (isset($data['SeoGamertag'])) {

            // check if record exists
            if (($_tmp = $this->get_csr_data($data['SeoGamertag'])) !== FALSE) {
                $status = $_tmp['Status'];
            }   else {
                // new record
                $status = 0;
            }

            // delete old record
            $this->db
                ->delete('ci_csr', array(
                    'SeoGamertag' => $data['SeoGamertag']
                ));

            // add `Status` and `LastUpdated` vars
            $data['Status'] = intval($status);
            $data['LastUpdated'] = time();

            // insert
            $this->db
                ->insert('ci_csr', $data);
        } else {
            return FALSE;
        }
    }

    /**
     * get_unique_csr_position
     *
     * This complicated bugger sends a query first that creates a local mySQL variable (row_number)
     * then uses that to count to figure out their position on the sorted query. Thus getting their
     * rank in that playlist.
     *
     * @param $seo_gt
     * @return array
     */
    public function get_unique_csr_position($seo_gt) {
        $rtr_arr = array();

        // get arrays of playlists
        $ind = $this->config->item('h4_individual_csr');
        foreach ($ind as $item) {
            $csr[] = $item . "_I";
        }

        $team = $this->config->item('h4_team_csr');
        foreach ($team as $item) {
            $csr[] = $item . "_T";
        }
        unset($ind);
        unset($team);

        // lets get Gamertag, SeoGamertag, KDRatio
        $rtr_arr = $this->get_name_and_kd($seo_gt);

        foreach ($csr as $id) {

            // set our tmp var
            $this->db->simple_query('SET @row_number := 0');

            // lets grab this query rank id
            $resp = $this->db->query('SELECT * FROM (SELECT `' . $id . '`,`SeoGamertag`,`Status`, @row_number := @row_number + 1 as `Rank`
                                FROM `ci_csr` WHERE `' . $id . '` > 0 AND `Status` = 0 ORDER BY `' . $id . '` DESC, `KDRatio` DESC) as row_to_return WHERE `' .
                                         $id . '` > 0 AND `Status` = 0 AND `SeoGamertag` =' . '"' . $this->db->escape_str($seo_gt) . '"');

            $rtr_arr[$id] = $resp->row_array();
            unset($rtr_arr[$id]['SeoGamertag']);
        }
        return $rtr_arr;
    }

    /**
     * get_playlist
     *
     * Takes an incoming playlist and grabs the most recent.
     * @param $playlist
     * @param $limit
     * @param $start
     * @return mixed
     */
    public function get_playlist($playlist, $limit, $start) {
        if ($start != 1 && $start != 0) {
            $start--;
        }
        $max = ($start * $limit);

        $query = $this->db
            ->select("C." . $playlist . ",C.SeoGamertag,C.KDRatio,C.Gamertag")
            ->from('ci_csr C')
            ->where("C." . $playlist . ' > ', 0)
            ->where('C.Status', 0)
            ->order_by("C." . $playlist, "desc")
            ->order_by("C.KDRatio", "desc")
            ->limit(intval($limit), intval($max))
            ->get();

        return $query->result_array();
    }

    /**
     * count_csr
     *
     * Counts records in `ci_csr` that have more than 0 and ready to leaderboard.
     * @param $playlist
     * @return mixed
     */
    public function count_csr($playlist) {
        $this->db
            ->where('Status', 0)
            ->where($playlist . ' > ', 0);
        return $this->db->count_all_results('ci_csr');
    }

    /**
     * count_badges
     *
     * Returns amount of users who have badges
     * @return mixed
     */
    public function count_badges() {
        return $this->db->count_all('ci_badges');
    }

    /**
     * insert_playlists
     *
     * Inserts playlist into dB
     * @param $data
     */
    public function insert_playlists($data) {
        $this->db
            ->insert_batch('ci_m_playlists', $data);
    }

    /**
     * empty_playlists
     *
     * Cleans out `ci_playlist` table, so new data can come in.
     */
    public function empty_playlists() {
        $this->db
            ->truncate('ci_m_playlists');
    }

    /**
     * remove_old_gamertags
     *
     * Finds all gamertags where `Count` is greater than MISSING_COUNTER,
     * and `Status` is equal to 0.
     */
    public function remove_old_gamertags() {
        $resp = $this->db
            ->where('Count >=', MISSING_COUNTER)
            ->where('Status', intval(0))
            ->select("Gamertag,Status,SeoGamertag,Count")
            ->limit(10)
            ->get('ci_missing');

        $resp = $resp->result_array();

        if (is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * validate_api
     *
     * Checks for valid API
     * @param $user
     * @param $pass
     * @return bool
     */
    public function validate_api($user, $pass) {
        $resp = $this->db
                ->select('id')
                ->get_where('ci_api', array(
                'user'  => $user,
                'pass'  => $pass
            ));

        $resp = $resp->row_array();

        if (isset($resp['id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * get_playlists
     *
     * Returns all results from `ci_playlists`
     * @return mixed
     */
    public function get_playlists() {
        $resp = $this->db
                    ->from('ci_playlists')
                    ->get();

        return $resp->result_array();
    }

    /**
     * get_badges
     *
     * Returns amount of badges, w/ gts.
     * @param $limit
     * @param $start
     * @return array|bool
     */
    public function get_badges($limit, $start) {
        $resp = $this->db
                    ->select('B.title,B.colour,G.SeoGamertag,G.Gamertag')
                    ->from("ci_badges B")
                    ->limit(intval($limit), intval($start))
                    ->join("ci_gamertags G", "B.SeoGamertag = G.SeoGamertag", "left")
                    ->get();

        $resp = $resp = $resp->result_array();

        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * update_last_hit
     *
     * Update last_hit to `$key`
     * @param $key
     */
    public function update_last_hit($key) {
        $this->db
            ->where('pass', $key)
            ->update('ci_api', array(
                'last_hit' => intval(time())
            ));
    }

    /**
     * insert_api
     *
     * Inserts api key into db
     * @param $user
     */
    public function insert_api($user) {
        $this->db
            ->insert('ci_api', array(
                'user'  => $user,
                'pass' => random_string('alnum', 32),
                'last_hit' => intval(0)
            ));
    }

    /**
     * get_keys()
     *
     * Returns keys from `ci_api`
     * @return array|bool
     */
    public function get_keys() {
        $resp = $this->db
                ->select('user,pass,last_hit,id')
                ->from('ci_api')
                ->limit(15)
                ->order_by('last_hit', "DESC")
                ->get();

        $resp = $resp->result_array();

        if (is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * delete_key
     *
     * Deletes `key` of $key
     * @param $key
     */
    public function delete_key($key) {
        $this->db
            ->delete('ci_api', array(
                'id' => intval($key)
            ));
    }

    /**
     * _get_one
     *
     * If you are only grabbing one record Mongo will assign it $array[0], but we just want $array
     * so this removes the [0] and returns it without the id.
     *
     * @param        $resp
     * @param string $field
     * @return array|bool
     */
    private function _get_one(&$resp, $field = '') {
        if (is_array($resp) && count($resp) == 1) {
            $resp = $resp[0];
        }

        if (isset($resp[$field])) {
            unset($resp['_id']);
            return $resp;
        } else {
            return FALSE;
        }
    }
}