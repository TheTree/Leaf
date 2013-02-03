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
    
    function update_gamertags() {
        
        // load db
        $this->load->model('stat_model', 'stat_m', true);
        
        // check our previous and max
        $_previous = $this->cache->get('cron_lastid');
        $_max = $this->cache->get('cron_maxid');
        
        log_message('debug', 'Previous: ' . intval($_previous));
        log_message('debug', 'Max: ' . intval($_max));
        
        // check if its null
        if (intval($_max) == 0 || intval($_previous) == 0) {
            $_max = $this->stat_m->count_gamertags();
            $this->cache->write($_max, 'cron_maxid');
            
            $_previous = 0;
        }
         // Lets load eh 10 people who are expired
        $results = $this->stat_m->cron_gamertag($_previous, $_max);
        
        if (is_array($results) && count($results) > 0) {
            foreach ($results as $result) {
                
                // pull new data update their record
                $new_record = $this->library->get_profile(str_replace(" ", "%20",$result['Gamertag']));
                
                // check comparison, if so increment there InactiveCounter by 1
                if ($new_record['Xp'] == $result['Xp']) {
                    $this->stat_m->update_account($result['HashedGamertag'], array(
                        'InactiveCounter' => intval($result['InactiveCounter'] + 1)
                    ));
                    
                    unset($new_record);
                }
            }
            
            // store new data
            $this->cache->write($result['id'], 'cron_lastid');
        }
        
    }

}
?>
