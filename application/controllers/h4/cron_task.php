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
        $this->load->model('h4/stat_model', 'stat_m', true);
        $this->h4_lib->set_cli_mode(TRUE);

        print "Running CRON\n";

        // check our previous and max
        $_previous = $this->cache->get('cron_lastid');
        $_max      = $this->cache->get('cron_maxid');

        print "Raw Max: " . $_max . "\n";
        print "Raw Previous: " . $_previous . "\n";

        // check maxes
        if (intval($_max) <= intval($_previous)) {
            $_max = $this->stat_m->count_gamertags();
            $this->cache->write(intval($_max), 'cron_maxid');
            $_previous = intval(0);
        }

        print "Max: " . $_max . "\n";
        print "Previous: " . $_previous . "\n";
        
         // Lets load eh 10 people who are expired
        $results = $this->stat_m->cron_gamertag($_previous, intval(5));

        print "Count of `Results`: " . count($results) . "\n";
        
        if (is_array($results) && count($results) > 0) {
            print "results is an array. Going on... \n";
            foreach ($results as $result) {
                
                // pull new data update their
                print "Running : " . $result[H4::GAMERTAG] . "\n";
                $flag = FALSE;
                $new_record = $this->h4_lib->get_profile($result[H4::GAMERTAG], FALSE, TRUE, $result[H4::SEO_GAMERTAG]);

                // check if they can be loaded
                if ($new_record == FALSE) {
                    $this->stat_m->update_account($result[H4::HASHED_GAMERTAG], array(
                        H4::INACTIVE_COUNTER => intval($result[H4::INACTIVE_COUNTER] + 1)
                    ));

                    $flag = TRUE;
                    print $result[H4::GAMERTAG] . " could not be loaded. +1 to `InactiveCounter` , which is now at: " . intval($result[H4::INACTIVE_COUNTER] + 1) . "\n";
                }

                // only run if they loaded data.
                if ($flag == FALSE) {
                    // check comparison, if so increment there `InactiveCounter` by 1
                    if ($new_record[H4::TOTAL_GAMEPLAY] == $result[H4::TOTAL_GAMEPLAY]) {
                        $this->stat_m->update_account($result[H4::HASHED_GAMERTAG], array(
                            H4::INACTIVE_COUNTER => intval($result[H4::INACTIVE_COUNTER] + 1)
                        ));

                        print $result[H4::GAMERTAG] . " has had 0 TotalGameplay change. +1 to `InactiveCounter`, which is now at: " . intval($result[H4::INACTIVE_COUNTER] + 1) . "\n";
                        unset($new_record);
                    } else {

                        // reset `InactiveCounter` to 0
                        $this->stat_m->update_account($result[H4::HASHED_GAMERTAG], array(
                            H4::INACTIVE_COUNTER => intval(0)
                        ));

                        print $result[H4::GAMERTAG] . " has had " . ($this->utils->time_duration($new_record[H4::TOTAL_GAMEPLAY] - $result[H4::TOTAL_GAMEPLAY])) . " TotalGameplay change. Reset `InactiveCounter` \n";
                    }
                }
            }
            
            // store new data
            $this->cache->write(($_previous + 5), 'cron_lastid');
            print "Wrote: " . ($_previous + 5) . " to cache. \n";
        } else {
            $this->cache->write(($_previous + 5), 'cron_lastid');
            print "Skipping this iteration.";
        }
    }

    function cleanup_gamertags() {
        $this->load->model('h4/stat_model', 'stat_m', true);
        $this->h4_lib->set_cli_mode(TRUE);
        print "Looking for deleted gamertags...\n";

        $resp = $this->stat_m->remove_old_gamertags();


        // find all GTs with `InactiveCounter` equal or above 40, and cannot be loaded.
        // Mark them as "MISSING_PLAYER"
        if ($resp != FALSE) {
            print "Count: " . count($resp) . "\n";

            foreach ($resp as $item) {
                print "Running: " . $item['Gamertag'] . "\n";
                $item['TotalGameplay'] = (isset($item['TotalGameplay']) ? intval($item['TotalGameplay']) : intval(1));

                // check for FAILED msg
                $new_record = $this->h4_lib->get_profile($item['Gamertag'], FALSE, TRUE, $item['SeoGamertag']);

                if ($new_record == FALSE) {
                    $this->stat_m->change_status($item['SeoGamertag'], MISSING_PLAYER);
                    $this->stat_m->delete_missing_record($item['SeoGamertag']);
                    print $item['SeoGamertag'] . " is marked at MISSING. \n";
                } else {
                    print $item['Gamertag'] . " loaded fine :( \n";
                    $this->stat_m->delete_missing_record($item['SeoGamertag']);

                    if ($item['TotalGameplay'] != $new_record['TotalGameplay']) {
                        print $item['Gamertag'] . " has had an TotalGameplay Change. Reset InactiveCounter \n";
                        $this->stat_m->update_account($item['HashedGamertag'], array(
                            H4::GAMERTAG => intval(0)
                        ));
                        print $item['Gamertag'] . " now is at " . intval(0) . "\n";
                    } else {
                        print $item['Gamertag'] . " has had no TotalGameplay Change.+ 1 InactiveCounter \n";
                        $this->stat_m->update_account($item['HashedGamertag'], array(
                            H4::GAMERTAG => intval(INACTIVE_COUNTER + 1)
                        ));
                        print $item['Gamertag'] . " now is at " . intval(INACTIVE_COUNTER + 1) . "\n";
                    }
                }

            }
        } else {
            print "None found... \n";
        }
    }
}
