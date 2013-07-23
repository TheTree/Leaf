<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* -------------------------------------------------------------------
 * EXPLANATION OF VARIABLES
 * -------------------------------------------------------------------
 *
 * ['mongo_hostbase'] The hostname (and port number) of your mongod or mongos instances. Comma delimited list if connecting to a replica set.
 * ['mongo_database'] The name of the database you want to connect to
 * ['mongo_username'] The username used to connect to the database (if auth mode is enabled)
 * ['mongo_password'] The password used to connect to the database (if auth mode is enabled)
 * ['mongo_replica_set'] If connecting to a replica set, the name of the set. FALSE if not.
 * ['mongo_query_safety'] Safety level of write queries. "safe" = committed in memory, "fsync" = committed to harddisk
 * ['mongo_suppress_connect_error'] If the driver can't connect by default it will throw an error which dislays the username and password used to connect. Set to TRUE to hide these details.
 * ['mongo_host_db_flag']   If running in auth mode and the user does not have global read/write then set this to true
 */

$config['live']['mongo_hostbase']                = 'localhost:27017';
$config['live']['mongo_database']                = 'h4_gamertags';
$config['live']['mongo_username']                = 'leaf';
$config['live']['mongo_password']                = 'test';
$config['live']['mongo_replica_set']             = FALSE;
$config['live']['mongo_query_safety']            = 'safe';
$config['live']['mongo_suppress_connect_error']  = TRUE;
$config['live']['mongo_host_db_flag']            = FALSE;

$config['local']['mongo_hostbase']                = 'localhost:27017';
$config['local']['mongo_database']                = 'h4_gamertags';
$config['local']['mongo_username']                = 'leaf';
$config['local']['mongo_password']                = 'test';
$config['local']['mongo_replica_set']             = FALSE;
$config['local']['mongo_query_safety']            = 'safe';
$config['local']['mongo_suppress_connect_error']  = TRUE;
$config['local']['mongo_host_db_flag']            = FALSE;