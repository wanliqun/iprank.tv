<?php
/**
 * 命令行入口文件
 * index.php
 *
 * @copyright Copyright (c) 2010 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage cmd
 * @version 2014-05-08
 */
include('../config.global.php');
if(!defined('BR'))  define('BR',"\n");
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('UTC');

helper( array( 'application','system','model','app' ) );

//加载app
$controller = 'cmd';	//默认控制器名
$action = 'index';		//默认操作名
$options = __argvs("act:action",$argv);
if($options) extract($options,EXTR_OVERWRITE);
$app = new CmdApp($config);
$app->argv = $argv;
$app->controllerName = $controller;
$app->actionName = $action;
echo $app->run();
return;
