<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (PHP_SAPI != 'cli') { exit('You cannot access this directly in the browser CLI only!'); }

/**
 * Description of cron
 *
 * /usr/local/bin/php -f /home/ibotpeac/public_html/stats/cron para1 para2 para3
 * @author Connor Tumbleson <connor.tumbleson@gmail.com>
 */
class Cron_task extends IBOT_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function update_gamertags() {
        
        // load db
        $this->load->model('stat_model', 'stat_m', true);
        
        // check our previous and max
        $_previous = $this->cache->get('cron_lastid');
        $_max = $this->cache->get('cron_maxid');
        
        if (intval($_previous) >= intval($_max)) {
            $_max = 0;
        }
        
        // check if its null
        if (intval($_max) == 0 || intval($_previous) == 0) {
            $_max = $this->stat_m->count_gamertags(true);
            $this->cache->write($_max, 'cron_maxid');
            
            $_previous = 0;
        }
        
         // Lets load eh 10 people who are expired
        $results = $this->stat_m->cron_gamertag($_previous, intval(5));
        
        if (is_array($results) && count($results) > 0) {
            foreach ($results as $result) {
                
                // pull new data update their record
                $new_record = $this->library->get_profile(str_replace(" ", "%20",$result['Gamertag']), FALSE, TRUE);
                
                // check comparison, if so increment there `InactiveCounter` by 1
                if ($new_record['Xp'] == $result['Xp']) {
                    $this->stat_m->update_account($result['HashedGamertag'], array(
                        'InactiveCounter' => intval($result['InactiveCounter'] + 1)
                    ));

                    print $result['Gamertag'] . " has had 0 Xp change. +1 to `InactiveCounter` \n";
                    unset($new_record);
                } else {

                    // reset `InactiveCounter` to 0
                    $this->stat_m->update_account($result['HashedGamertag'], array(
                        'InactiveCounter' => intval(0)
                    ));

                    print $result['Gamertag'] . " has had " . ($new_record['Xp'] - $result['Xp']) . " Xp change. Reset `InactiveCounter` \n";
                }
            }
            
            // store new data
            $cache_write = $this->cache->write($result['id'], 'cron_lastid');
            echo $cache_write;
        }  
    }
}
