<?php

class Stat_model extends IBOT_Model {

    function __construct() {
        parent::__construct();
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

}