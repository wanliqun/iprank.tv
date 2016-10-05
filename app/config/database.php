<?php
/**
 * 数据库配置文件
 * database.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage config
 * @version 2014-05-08
 */
if(file_exists(APP_PATH.DS.'tmp/~database.php')){
	return include(APP_PATH.DS.'tmp/~database.php');
}

$__release = c('__release');
if (isset($__release) && !empty($__release['db'])) return $__release['db'];

$db = array();
/*
//应用的数据库适配器
$db['adapter']	= c('db_adapter');			
$db['charset']  = c('db_charset');
$db['hostname'] = c('db_hostname');
$db['username'] = c('db_username');
$db['password'] = c('db_password');
$db['database'] = c('db_database');
$db['pconnect'] = TRUE;
$db['hostport'] = c('db_hostport');
$db['is_master']= true;
$db['debug'] = true;
*/

//应用的数据库适配器
$db['adapter']	= 'Mysql';
$db['charset']  = "utf8";
$db['hostname'] = "localhost";
$db['username'] = "root";
$db['password'] = "1314521";
$db['database'] = "iprank";
$db['pconnect'] = TRUE;
$db['hostport'] = '3306';
$db['is_master']= true;
$db['debug'] = true;

/**
 * 分布式主从数据库支持
 */
//$db['db_type'] = 'Mysql';
//$db['debug'] = true;
//$db['charset'] = 'utf8';
//$db['max_client'] = false;
$db['server']['server1']['hostname'] = 'localhost';
$db['server']['server1']['username'] = "username";
$db['server']['server1']['password'] = "password";
$db['server']['server1']['database'] = "database";
$db['server']['server1']['pconnect'] = TRUE;
$db['server']['server1']['hostport'] = '3306';
$db['server']['server1']['is_master']= true;

$db['server']['server2']['hostname'] = 'localhost';
$db['server']['server2']['username'] = "username";
$db['server']['server2']['password'] = "password";
$db['server']['server2']['database'] = "database";
$db['server']['server2']['pconnect'] = TRUE;
$db['server']['server2']['hostport'] = '3306';
$db['server']['server2']['is_master']= false;

return $db;