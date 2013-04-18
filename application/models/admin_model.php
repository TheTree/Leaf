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

        // grab account
        $salt = $this->get_account_salt($username);

        // make find
        if ($salt != FALSE) {
            return $this->get_account_auth($username, $pass, $salt);
        } else {
            return FALSE;
        }
    }

    /**
     * get_account_auth
     *
     * @param $user
     * @param $pass
     * @param $salt
     * @return array|bool
     */
    public function get_account_auth($user, $pass, $salt) {
        $resp = $this->db
            ->get_where('ci_admins', array(
                'username' => $user,
                'password_hash' => md5($salt . md5($pass))
            ));

        $resp = $resp->row_array();

        if (is_array($resp) && isset($resp['username'])) {
            unset($resp['password_hash']);
            unset($resp['salt']);
            return $resp;
        } else {
            return FALSE;
        }
    }

    /**
     * get_account_salt
     *
     * @param $username
     * @return bool
     */
    public function get_account_salt($username) {
        $resp = $this->db
            ->select('salt')
            ->get_where('ci_admins', array(
                'username' => $username
            ));

        $resp = $resp->row_array();

        if (isset($resp['salt'])) {
            return $resp['salt'];
        } else {
            return FALSE;
        }
    }
}
