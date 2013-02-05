<?php

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
     */
    public function update_or_insert_gamertag($hash, $data) {

        // check for update
        if (($_tmp = $this->account_exists($hash)) != false) {
            
            // unset InactiveCounter
            unset($data['InactiveCounter']);
            $this->update_account($hash, $data);
        } else {
            $this->insert_account($data);
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
        
        return true;
    }
    
    /**
     * insert_account
     * @param type $hash
     * @param type $data
     */
    public function insert_account($data) {
        
        $this->db
                ->insert('ci_gamertags', $data);
        
    }

    /**
     * account_exists
     * 
     * @param type $hash
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
            return false;
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
            return false;
        }
    }
    
    /**
     * count_gamertags
     * 
     * @return type
     */
    public function count_gamertags($active = false) {
        
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
                    'Expiration <' => time() 
                ));
        
        $resp = $resp->result_array();
        
        if (is_array($resp)) {
            return $resp;
        } else {
            return false;
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
            return false;
        }
    }
    
    /**
     * get_top_5
     * @param type $field
     * @param type $asc
     * @return boolean
     */
    public function get_top_5($field, $asc) {
        
        $resp = $this->db
                ->select("Gamertag,ServiceTag," . $field)
                ->limit(5)
                ->order_by($field, ($asc == true ? "asc" : "desc"))
                ->get_where('ci_gamertags', array(
                    'TotalGamesStarted >' => intval(100)));
        
        $resp = $resp->result_array();
        
        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return false;
        }
                
    }

}