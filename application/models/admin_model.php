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
        $resp = $this->mongo_db
                ->aggregate('h4_gamertags', [
                    '$group'    => [
                        '_id'   => "$" . H4::STATUS . "",
                        'amt'   => [
                            '$sum'  => 1
                        ]
                    ]
            ]);

        if (is_array($resp)) {
            $rtr = array();
            $rtr[-1] = 0;

            foreach($resp as $item) {
                $rtr[$item['_id']] = number_format($item['amt']);
                $rtr[-1]              += $item['amt'];
            }
            return $rtr;
        } else {
            return FALSE;
        }
        return FALSE;
    }

    /**
     * get_api_count
     *
     * Returns count based on APIs.
     * @return array|bool
     */
    public function get_api_count() {
        $resp = $this->mongo_db
            ->aggregate('h4_gamertags', [
                '$group'    => [
                    '_id'   => "$" . H4::API_VERSION . "",
                    'amt'   => [
                        '$sum'  => 1
                    ]
                ]
            ]);
        return $resp;
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
            ->order_by("SeoGamertag", "asc")
            ->get('ci_flagged');

        $resp = $resp->result_array();

        if (is_array($resp) && count($resp) > 0) {
            return $resp;
        } else {
            return FALSE;
        }
    }
}
