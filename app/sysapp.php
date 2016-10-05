<?php
/**
 * 系统应用类
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @version 2014-05-08
 */
class SysApp extends App{
	function __construct($config=null){
		$this->__loadConfig('application');
		$this->__loadConfig('seo');
		$this->__loadConfig('popularity_weight');
		$this->__loadConfig('ajax');
		$this->__loadConfig('ads');
		$this->__loadConfig('system');
		$this->__loadConfig('social');
		$this->__loadConfig('form_rules');
		$this->__loadConfig('mime_extensions');
		$this->__loadConfig('admin_users');
		$this->__loadConfig('dummy_users');
				
		//设置session ID，可能会存在一些安全漏洞风险...
		if(isset($_REQUEST['__sid'])){	//设置自定义的session id，
			helper('decode');
			$sid = $_REQUEST['__sid'];
			$sessionId = intval(sp_decrypt_str($sid,c('secret_key')));
			Session::setSessionId($sessionId);
		}
		parent::__construct($config);
	}
	
	/**
	 * 初始化项目
	 */
	function init(){
		// 禁止 magic quotes
		set_magic_quotes_runtime(0);
        // 处理被 magic quotes 自动转义过的数据
        if (get_magic_quotes_gpc())
        {
            $in = array(& $_GET, & $_POST, & $_COOKIE, & $_REQUEST);
            while (list ($k, $v) = each($in))
            {
                foreach ($v as $key => $val)
                {
                    if (! is_array($val))
                    {
                        $in[$k][$key] = stripslashes($val);
                        continue;
                    }
                    $in[] = & $in[$k][$key];
                }
            }
            unset($in);
        }
        parent::init();
        
        //生成临时目录
        $tmpDir = APP_PATH . DS . c('tmp_dir');
        if(!file_exists($tmpDir)){
        	mkdir($tmpDir);
        }
	}
	
	public function getController(){
		$controller =  $this->getRequest(c('var_controller'),c('default_controller') );
		$alias = $this->__loadControllerAlias();
		if($alias && isset($alias[$controller])){
			$controller = $alias[$controller];
		}
		return $controller;
	}
	
	public function getAction(){
		$controller =  $this->getRequest(c('var_controller'),c('default_controller') );
		$action = $this->getRequest(c('var_action'), c('default_action'));
		$controller =  $this->getRequest(c('var_controller'),c('default_controller') );
		$alias = $this->__loadControllerAlias();
		if($alias && isset($alias[$controller.'/'.$action])){
			$action = $alias[$controller.'/'.$action];
			$arr = explode('/',$action);
			if(count($arr)==2){
				$this->controllerName = $arr['0'];
				$action = $arr[1];
			}else{
				$action = $arr[0];
			}
		}
		return $action;
	}
	
	private function __loadControllerAlias(){
		static $alias;
		if(!isset($alias)){
			if(file_exists(APP_PATH.DS.'config'.DS.'controller_alias.php')){
				$alias  = include(APP_PATH.DS.'config'.DS.'controller_alias.php');
			}else{
				$alias = array();
			}
		}
		return $alias;
	}
	
	private function __loadConfig($cfgName) {
		if(file_exists( APP_PATH.DS.c('config_dir').DS."$cfgName.php" )){
			$setting = include( APP_PATH.DS.c('config_dir').DS."$cfgName.php" );
			c($setting);
		}
	}
}