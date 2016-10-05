<?php
/**
 * 系统公共配置文件
 * config.global.php
 *
 * @copyright Copyright (c) 2010 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage config
 * @version 2014-05-08
 */
//定义网站根目录
define('DOCUROOT',dirname( __FILE__ ));

//定义系统路径
if(!defined('SYS_PATH')) define('SYS_PATH',dirname( __FILE__ ).'/coolphp' );

//定义项目路径
if(!defined('APP_PATH'))  define('APP_PATH',dirname( __FILE__ ).'/app');

//定义系统上传文件的路径
if(!defined('UPLOAD_PATH'))  define('UPLOAD_PATH',DOCUROOT.'/data');

//系统应用项目类库
if(!defined('SYS_APP_PATH')) define('SYS_APP_PATH',SYS_PATH.'/app' );

//加载公共文件
include(SYS_PATH.'/coolphp.php');
c('class_path',SYS_APP_PATH);

//加载测试环境设置
if(file_exists(DOCUROOT.'/config.test.php' )){
	include(DOCUROOT.'/config.test.php');
}

//加载发布环境设置
if(file_exists(DOCUROOT.'/config.release.php' )){
	include(DOCUROOT.'/config.release.php');
}