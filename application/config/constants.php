<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/**
 * URLs
 */
define('LIVE_URL', 'leafapp.co'); # LIVE, ONLY PUSHED WHEN FUCKING TESTED
define('STAGE_URL', 'ibotpeaches.com'); # Stage Site. Testing site.
define('LOCAL_URL', 'localhost'); # When working locally.

/**
 * TIME
 */
define('HOUR_IN_SECONDS', 3600);
define("TWENTYFOUR_HOURS_IN_SECONDS", 86400);
define("FIVEMIN_IN_SECONDS", 300);

/**
 * MAX_HALO_RANK
 */
define('MAX_HALO_RANK', 130);

/**
 * CHEATER
 */
define("CHEATING_PLAYER", 1);
define("BOOSTING_PLAYER", 2);
define("MISSING_PLAYER", 3);

/**
 * API_VERSION
 *
 * Used in incrementing changes to the core API, which forces a recache of everyone not at that API version
 */
define("API_VERSION", 1);

/**
 * INACTIVE_COUNTER
 */
define("INACTIVE_COUNTER", 20);

/**
 * BRANCH
 *
 * Use a bool to enable/disable Branch integration. Just in case 3rd party stuff go down :/
 */
define("BRANCH", TRUE);

/* End of file constants.php */
/* Location: ./application/config/constants.php */