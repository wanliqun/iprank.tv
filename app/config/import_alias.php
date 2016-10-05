<?php
/**
 * 类库映射表
 * import_alias.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage config
 * @version 2014-05-08
 */
return array(
	/*核心库映射*/
	'app' 				=> SYS_PATH.DS.'app.php',
	'bae' 				=> SYS_PATH.DS.'base.php',
	'cache' 			=> SYS_PATH.DS.'cache.php',
	'clexception' 		=> SYS_PATH.DS.'clexception.php',
	'controller' 		=> SYS_PATH.DS.'controller.php',
	'cookie'			=> SYS_PATH.DS.'cookie.php',
	'db'				=> SYS_PATH.DS.'db.php',
	'filter' 			=> SYS_PATH.DS.'filter.php',
	'itbeing' 			=> SYS_PATH.DS.'itbeing.php',
	'loger' 			=> SYS_PATH.DS.'loger.php',
	'model' 			=> SYS_PATH.DS.'model.php',
	'session' 			=> SYS_PATH.DS.'session.php',
	'sql'				=> SYS_PATH.DS.'sql.php',
	'view'				=> SYS_PATH.DS.'view.php',
	/*扩展库*/
	'corex/cldir'		=> SYS_PATH.DS.'corex/cldir.php',
	'corex/clencrypt'	=> SYS_PATH.DS.'corex/clencrypt.php',
	'corex/clfile'		=> SYS_PATH.DS.'corex/clfile.php',
	/*应用库映射*/
	'admincontroller'	=> APP_PATH.DS.'admincontroller.php',
	'adminview'			=> APP_PATH.DS.'adminview.php',
	'appcontroller'		=> APP_PATH.DS.'appcontroller.php',
	'appmodel'			=> APP_PATH.DS.'appmodel.php',
	'appview'			=> APP_PATH.DS.'appview.php',
	'sysapp' 			=> APP_PATH.DS.'sysapp.php',
);