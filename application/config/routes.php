<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// basic pages
$route['about']             = "index/about";
$route['error']             = "index/error";
$route['cron']              = "cron_task/update_gamertags";
$route['cron2']             = "h4/cron_task/mysql_to_mongo";
$route['news']              = "news/index";

// ajax calls
$route['ajax/gt/(:any)']    = "ajax/core/compare_api/$1";

// games
$route['h4']                = "h4/home/index";

//-------------------------------------------------------
// START: Halo 4
//-------------------------------------------------------
$route['h4/stats']                  = "h4/home/index";
$route['h4/compare']                = "h4/compare/index";
$route['h4/top_ten']                = "h4/leaderboards/top_10";
$route['h4/csr_leaderboards']       = "h4/leaderboards/panel/100_I/0";

// variable pages
$route['h4/compare/(:any)/(:any)']                  = "h4/compare/comparison/$1/$2";
$route['h4/compare/(:any)']                         = "h4/compare/comparison_prefill/$1";
$route['h4/csr_leaderboards/(:any)']                = "h4/leaderboards/panel/$1/0";
$route['h4/csr_leaderboards/(:any)/(:num)']         = "h4/leaderboards/panel/$1/$2";
$route['h4/record/(:any)/recache']                  = "h4/profile/recache_gt/$1";
$route['h4/record/(:any)']                          = "h4/profile/gt/$1";

// recaching stuff
$route['h4/metadata/recache']       = "h4/profile/metadata";
$route['h4/playlists/recache']      = "h4/profile/redo_playlists";

// star, unfreeze, mod
$route['h4/star/(:any)']            = "h4/profile/star/$1";
$route['h4/flag/(:any)']            = "h4/moderate/flagged/$1";



//-------------------------------------------------------
// END: Halo 4
//-------------------------------------------------------


//-------------------------------------------------------
// START: Backstage
//-------------------------------------------------------

// static login page
$route['backstage']                 = "admin/gate";
$route['backstage/index']           = "admin/index";
$route['backstage/flagged']         = "admin/index/flagged";
$route['backstage/find']            = "admin/index/find";
$route['backstage/news/create']     = "admin/index/news_create";
$route['backstage/news/list']       = "admin/index/news_list";
$route['backstage/badges/list']     = "admin/index/badges_list";
$route['backstage/badges/create']   = "admin/index/badges_create";
$route['backstage/logout']          = "admin/index/logout";
$route['backstage/keys']            = "admin/index/keys";

// api
$route['api']                       = "api/index/index";

// variable backstage
$route['backstage/news/list/(:any)']                = "admin/index/news_list/$1"; #pagination
$route['backstage/badges/list/(:any)']              = "admin/index/badges_list/$1"; #pagination
$route['backstage/flagged/mod/(:any)/(:num)']       = "admin/index/flagged_mod/$1/$2"; #seo_username/status
$route['backstage/key_delete/(:num)']               = "admin/index/key_delete/$1"; #key_id

//-------------------------------------------------------
// End: Backstage
//-------------------------------------------------------


// variable pages
$route['removefreeze/(:any)']            = "stats/home/removefreeze/$1";
$route['news/(:num)']                    = "news/index/index/$1";
$route['news/view/(:any)']               = "news/index/view/$1";

// default home
$route['default_controller']    = "h4/home";
$route['404_override']          = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */