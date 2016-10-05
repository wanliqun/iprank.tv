<?php
/**
 * 系统应用配置文件
 * application.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage config
 * @version 2014-05-08
 */
return array (
	'coolphp_version' => '1.0', //版本信息
	'class_path' => array(SYS_APP_PATH),	//系统加载路径
	'app_model'	=> 'product',				//开发模式，product,development只有product的时候才会开启缓存
	
	//WEB配置
	'base_url'	=>	"http://{$_SERVER['HTTP_HOST']}",					//末尾不用加/
	'media_url'	=>	"http://{$_SERVER['HTTP_HOST']}/public",			//网站多媒体网址
	'index_script' => 'index.php',										//网站入口脚本文件
	'data_url'	=> "http://{$_SERVER['HTTP_HOST']}/data",													//数据文件路径
	
	/* 区域设置 */
	'language_switch_on' => false,		//开启语言包
	'language'	=> 'en',				//系统语言包设置
	'time_zone'		=> 'UTC',		//默认时区  默认东八区
	
	/* 过滤器设置 */
	'pre_filter'	=> 'Corex_UrlFilter',	//前置过滤器
	'after_filter'	=> '',			//后置过滤器
	
	/* view试图渲染 */
	'view_adapter' => false,			//适配器
	
	// URL模式： 0 普通模式 1 PATHINFO
	'url_model' => 0,
	'url_model_style' => '/', 		//URL参数分隔符匹配分隔符
	'url_parameter_style' => '-', 	//URL参数匹配分隔符,将_换成-，避免与数据库字段的_分隔符冲突
	'url_suffix'		  => '',	//URL虚拟扩展名，比如html
	'url_patterns'	=> array('/(fortest\/index)/is','/(fortest\/view)/is'),
	'url_replace' => array('fortest','fortest_view'),
	'url_filter_patterns' => array("/(\/fortest)/is","/(\/fortest_view)/is"),
	'url_filter_replace' => array("/fortest/index",'/fortest/view'),
	
	//日志设置 置成false 关闭日志记录
	'loger_handler' => true,			//开启调试日志,设置日志记录处理器，false=关闭日志
	
	//session 设置
	'session_start' => true, 						//是否开启session
	'session_handler' => false,			//是否使用自定义session处理机制，系统支持Db和memcache储存，如采用默认处理机制可以将该项设置为空

	/* cookie设置 */
	//'cookie_expire' => 0, 			// cookie有效期
	'cookie_domain' => '', 				// cookie有效域名
	'cookie_path' => '/', 				// cookie路径
	'cookie_prefix' => false, 			// cookie前缀避免冲突
	
	/* database 设置*/
	'database_config' => APP_PATH.DS.'config'.DS.'database'.EXT,	//数据库配置文件路径
	'db_sql_type' => 'Mysql',//数据库类型
	
	/* cache setting*/
	'cache_adapter' => 'Corex_Cache_FileCache',
	
	/*controller&model 前后缀*/
	'controller_prefix' => '',
	'controller_suffix' => '',
	'model_prefix' => '',
	'model_suffix' => '',
	
	//URL 变量参数
	'var_controller' => 'c',
	'var_action'	=> 'a',
	'var_format'	=> 'format',
	'var_template'  => 'template',
	'default_action'=>'index',
	'default_controller' => 'home',
	'default_format'=>'html',
	
	//下面是系统应用相关约定，如非必要，请不要轻易改变
	'tmp_dir' => 'tmp', //临时文件路径
	'view_dir' => 'view', //视图显示路径
	'controller_dir' =>  'controller', //控制器路径
	'model_dir' =>  'model', //模型路径
	'config_dir' => 'config', //配置文件路径
	'lang_dir' =>   'lang', //语言包路径
	'helper_dir'	=> 'helper', //helper函数扩展目录
	'corex_dir' => 'corex',	//额外函数
);