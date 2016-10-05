<?php
/**
 * 后台管理系统入口文件
 * index.php
 *
 * @copyright Copyright (c) 2010 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage admin
 * @version 2014-05-08
 */
include('../config.global.php');

//加载app
$config = array(
	'view_adapter' => 'AdminView',
	'controller_dir' => 'controller/admin',
	'index_script' => 'admin/index.php',
	'pagesize' => 20,
	'is_clone_appmodel' => false,
	'base_url' => "http://{$_SERVER['HTTP_HOST']}".str_replace('admin/index.php','',$_SERVER['SCRIPT_NAME']),
	'media_url' => "http://{$_SERVER['HTTP_HOST']}".str_replace('admin/index.php','',$_SERVER['SCRIPT_NAME']).'/public',
);

$controller = 'home';	//默认控制器名
$action = 'index';		//默认操作名

$app = new SysApp($config);
$app->controllerName = $controller;
$app->actionName = $action;
echo $app->run();
return;
