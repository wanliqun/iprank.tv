<?php
/**
 * 系统帮助函数
 * app.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage helper
 * @version 2014-05-08
 */
function __app(){
	
}

/**
 * 获取模型实例化对象
 * 
 * @param string $tableName 获取模型实例化对象
 * @return AppModel
 */
function _m($tableName) {
	return Itbeing :: loadModel(_t($tableName));
}

/**
 * 获取真实表名称
 * 
 * @param string $tableName
 * @return string
 *
 */

function _t($tableName) {
	return $tableName;
}

/*****************************
 * 需要数据库模型支持的函数列表
 */
function __app_m(){
	
}
