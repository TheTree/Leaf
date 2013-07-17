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
$route['about']             = "home/about";
$route['error']             = "home/error";
$route['cron']              = "cron_task/update_gamertags";
$route['news']              = "news/index";
$route['metadata/recache']  = "stats/home/metadata";
$route['playlists/recache'] = "stats/home/redo_playlists";
$route['stats']             = "stats/home/index";
$route['compare']           = "stats/compare/index";
$route['leaderboards']      = "stats/home/index";
$route['csr_leaderboards']  = "csr/leaderboards/index";

// variable mod pages
$route['guilty_spark/flag/(:any)']          = "moderate/home/flagged/$1";

// moderate
$route['guilty_spark']          = "moderate/home";

// ajax calls
$route['ajax/gt/(:any)'] = "ajax/core/compare_api/$1";

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

// variable pages
$route['csr_leaderboards/(:any)']        = "csr/leaderboards/leaderboard/$1/0";
$route['csr_leaderboards/(:any)/(:num)'] = "csr/leaderboards/leaderboard/$1/$2";
$route['removefreeze/(:any)']            = "stats/home/removefreeze/$1";
$route['star/(:any)']                    = "stats/home/star/$1";
$route['gt/(:any)/recache']              = "stats/home/recache_gt/$1";
$route['gt/(:any)']                      = "stats/home/gt/$1";
$route['news/(:num)']                    = "news/index/index/$1";
$route['news/view/(:any)']               = "news/index/view/$1";
$route['compare/(:any)/(:any)']          = "stats/compare/comparison/$1/$2";
$route['compare/(:any)']                 = "stats/compare/comparison_prefill/$1";

// other
$route['default_controller']    = "home";
$route['404_override']          = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */