<?php

/**
 * Description of cron
 *
 * /usr/local/bin/php -f /home/ibotpeac/public_html/stats/cron para1 para2 para3
 * @author Connor Tumbleson <connor.tumbleson@gmail.com>
 */
class Cron extends IBOT_Controller {

    //put your code here

    function __construct() {
        parent::__construct();

        // this controller can only be called from the command line
        if (!$this->input->is_cli_request()) {
            show_error('Direct access is not allowed');
        }
    }

}
?>
