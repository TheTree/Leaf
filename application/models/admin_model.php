<?php

/**
 * Admin_model
 */
class Admin_model extends IBOT_Model {

    function __construct() {
        parent::__construct();
        log_message('debug', 'Admin_modal loaded');
    }

    /**
     * login
     *
     * @param $username
     * @param $pass
     * @return array|bool
     */
    public function login($username, $pass) {
        return $this->get_account_auth($username, $pass);
    }

    /**
     * get_account_auth
     *
     * @param $user
     * @param $pass
     * @return array|bool
     */
    public function get_account_auth($user, $pass) {
        $resp = $this->db
            ->select('id,username,password_hash,seo_username')
            ->get_where('ci_admins', array(
                'seo_username' => $user,
            ));

        $resp = $resp->row_array();

        if (is_array($resp) && isset($resp['username'])) {

            // check if
            if (password_verify($pass, $resp['password_hash'])) {
                unset($resp['password_hash']);
                return $resp;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * get_count
     *
     * Returns amount of `ci_gamertags`
     * @return mixed
     */
    public function get_count() {
        return $this->db->count_all('ci_gamertags');
    }

    /**
     * get_flagged_users()
     *
     * Grab any user who have flags, but not yet cheated
     * @return array|bool
     */
    public function get_flagged_users() {
        $resp = $this->db
            ->select('Count(`id`) as amt,`Gamertag`,`SeoGamertag`',FALSE)
            ->group_by("id")
            ->order_by("amt", "desc")
            ->get('ci_flagged');

        $resp = $resp->result_array();

        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }
}
