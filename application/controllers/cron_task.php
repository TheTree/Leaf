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
        print "Running CRON\n";
        
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

        print "Previous: " . $_previous . "\n";
        print "Max: " . $_max . "\n";
        
         // Lets load eh 10 people who are expired
        $results = $this->stat_m->cron_gamertag($_previous, intval(5));

        print "Count of `Results`: " . count($results) . "\n";
        
        if (is_array($results) && count($results) > 0) {
            print "results is an array. Going on... \n";
            foreach ($results as $result) {
                
                // pull new data update their
                print "Running : " . $result['Gamertag'] . "\n";
                $new_record = $this->library->get_profile($result['Gamertag'], FALSE, TRUE, $result['SeoGamertag']);

                if ($new_record == FALSE) {
                    print "Couldn't pull: " . $result['Gamertag'] . "\n";
                }
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
            print "Wrote: " . $result['id'] . " to cache. \n";
        }  
    }
}
