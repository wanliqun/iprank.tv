<?php
/**
 * 系统入口文件
 * index.php
 *
 * @copyright Copyright (c) 2010 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage config
 * @version 2014-05-08
 */
include('./config.global.php');

//加载app
$config = array(
	'view_adapter' => 'AppView',
	'index_script' => '',
	'pagesize' => 20,
	'url_model' => 1,
	'is_clone_appmodel' => false,
	'base_url' => "http://{$_SERVER['HTTP_HOST']}".str_replace('/index.php','',$_SERVER['SCRIPT_NAME']),
	'media_url' => "http://{$_SERVER['HTTP_HOST']}".str_replace('/index.php','',$_SERVER['SCRIPT_NAME']).'/public',
);
$app = new SysApp($config);
echo $app->run();
return;
