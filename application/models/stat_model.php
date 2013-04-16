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
            
            // unset some temporarily, so we can add back on postback
            $unset_arra = array(
                'InactiveCounter' => $data['InactiveCounter'],
                'Gamertag' => $data['Gamertag'],
                'SeoGamertag' => $data['SeoGamertag'],
                'HashedGamertag' => $data['HashedGamertag']
            );

            // remove these vars to prevent changing them in db
            // ex accidentally changing gt from TEST to teST
            unset($data['InactiveCounter']);
            unset($data['Gamertag']);
            unset($data['SeoGamertag']);
            unset($data['HashedGamertag']);
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
     * update_account
     * @param type $hash
     * @param type $data
     * @return boolean
     */
    public function update_account($hash, $data) {
        
        $this->db
                ->where('HashedGamertag', $hash)
                ->update('ci_gamertags', $data);
        
        return TRUE;
    }

    /**
     * insert_account
     *
     * @param type $data
     * @return void
     * @internal param \type $hash
     */
    public function insert_account($data) {
        
        $this->db
                ->insert('ci_gamertags', $data);
        
    }

    /**
     * account_exists
     * 
     * @param type $hash
     * @return array|bool
     */
    public function account_exists($hash) {

        // generate resp
        $resp = $this->db
                ->select('HashedGamertag')
                ->get_where('ci_gamertags', array(
            'HashedGamertag' => $hash
                ));

        $resp = $resp->row_array();

        if (isset($resp['HashedGamertag']) && is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
    }
    
    /**
     * get_gamertag
     * 
     * Returns every field from `ci_gamertags`
     * @param type $hashed
     * @return boolean
     */
    public function get_gamertag_data($hashed) {
        $resp = $this->db
                ->get_where('ci_gamertags', array(
                    'HashedGamertag' => $hashed
                ));
        
        $resp = $resp->row_array();
        
        if (isset($resp['HashedGamertag']) && is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
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
     * cron_gamertags
     * 
     * Grabs data based on previous run.
     * @param type $start
     * @param type $max
     * @return boolean
     */
    public function cron_gamertag($start, $max) {
        $resp = $this->db
                ->select('HashedGamertag,Xp,id,InactiveCounter,Gamertag')
                ->limit(intval($max),intval($start))
                ->get_where('ci_gamertags', array(
                    'Expiration <' => time(),
                    'InactiveCounter <' => 40
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
        $resp = $this->db
                ->select("Expiration")
                ->limit(1)
                ->get_where("ci_gamertags", array(
                    'HashedGamertag' => $hashed,
                    'InactiveCounter <' => intval(40)
                ));
        
        $resp = $resp->row_array();
        
        if (isset($resp['Expiration']) && is_array($resp)) {
            return $resp;
        } else {
            return FALSE;
        }
    }
    
    /**
     * get_top_5
     * @param type $field
     * @param type $asc
     * @return boolean
     */
    public function get_top_8($field, $asc) {
        
        $resp = $this->db
                ->select("Gamertag,ServiceTag," . $field)
                ->limit(8)
                ->order_by($field, ($asc == TRUE ? "asc" : "desc"))
                ->get_where('ci_gamertags', array(
                    'TotalGamesStarted >' => intval(100)));
        
        $resp = $resp->result_array();
        
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
        $resp = $this->db
                ->select("Gamertag,ServiceTag,Rank")
                ->order_by("id", "desc")
                ->limit(5)
                ->get('ci_gamertags');
        
        $resp = $resp->result_array();
        
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
            $this->db->like('Gamertag', $letter);
        }

        // execute
        $resp = $this->db
            ->select('Gamertag')
            ->limit(25)
            ->get('ci_gamertags');

        $resp = $resp->result_array();

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

}