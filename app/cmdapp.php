<?php
/**
 * 命令行应用
 * cmdapp.php
 *
 * @copyright Copyright (c) 2010 Itbeing (Beijing) Tech co.,Ltd
 * @author kokko<kokkowon@itbeing.com>
 * @package	iprank.co
 * @version 2014-05-08
 */
class CmdApp extends App{
	public $argv = array();
	
	public function init(){
		$this->__loadConfig('application');
		$this->__loadConfig('seo');
		$this->__loadConfig('popularity_weight');
		$this->__loadConfig('ajax');
		$this->__loadConfig('ads');
		$this->__loadConfig('system');
		$this->__loadConfig('social');
		$this->__loadConfig('form_rules');
		$this->__loadConfig('mime_extensions');
	}
	
	public function run(){
		$this->controller = Controller::loadController( $this->controllerName );	//获取控制器
		$this->controller->exec( $this->actionName );
		exit();
	}
	
	public function getRequest($parameter = null, $default = null) {
		$var = $default;
		$options = __argvs($parameter.":var",$this->argv);
		if($options) extract($options,EXTR_OVERWRITE);
		return $var ? $var : $default;
	}
	
	private function __loadConfig($cfgName) {
		if(file_exists( APP_PATH.DS.c('config_dir').DS."$cfgName.php" )){
			$setting = include( APP_PATH.DS.c('config_dir').DS."$cfgName.php" );
			c($setting);
		}
	}
}
?>
