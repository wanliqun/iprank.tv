<?php
/**
 * URL前置过滤器
 * urlfilter.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author kokko<kokkowon@itbeing.com>
 * @package iprank.co
 * @subpackage corex
 * @version 2014-05-08
 */
class Urlfilter extends Filter{
	/**
	 * 项目应用
	 * 
	 * @access public
	 * @var object
	 */
	public $app;

	/**
	 * 执行过滤器
	 */
	public function doFilter($app) {
		$this->app = $app;
		//url为PATH_INFO的时候需要解析参数
		if (c('url_model')) {
			$this->uriFilter();
		}
		helper('app');
		$sysVars = array('c','a','template','format');
		foreach($_GET as $key=>$val){
			if(is_array($val)){
				foreach($val as $k=>$v){
					if(!is_array($v))$_GET[$key][$k] = $this->_filterParam($v);
				}
			}else{
				$_GET[$key] = $this->_filterParam($val);
			}
		}
		$_REQUEST = array_merge($_REQUEST, $_GET);
	}
	
	/**
	 * 根据应用程序设置 'urlMode' 分析 $_GET 参数
	 *
	 * @access private
	 */
	private function uriFilter() {
		$pathinfo = null;
		if(isset($_REQUEST['__path_info'])){
			$pathinfo = trim($_REQUEST['__path_info']);
		}else{
			$pathinfo = !empty ($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (!empty ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '');
		}
		if(!$pathinfo) return;
		$patterns = c('url_filter_patterns');
		$replace = c('url_filter_replace');
		if($patterns && $replace) $pathinfo = preg_replace($patterns, $replace, $pathinfo);
		$urlSuffix = c('url_suffix');
		$explode = c('url_model_style');
		if( preg_match("/\.{$urlSuffix}$/i", $pathinfo) ){	//判断重写后缀
			$pathinfo = str_replace(".{$urlSuffix}",'',$pathinfo);
		}
		$parts = explode($explode, substr($pathinfo, 1));
		
		//解析第一个Path参数为controller
		if (isset ($parts[0]) && strlen($parts[0])) {
			$_GET[c('var_controller')] = $parts[0];
		}
		//解析第二个Path参数为action
		if (isset ($parts[1]) && strlen($parts[1])) {
			$_GET[c('var_action')] = $parts[1];
		}

		$style = c('url_parameter_style');
		if ($style == $explode) {
			for ($i = 2; $i < count($parts); $i += 2) {
				if (isset ($parts[$i +1])) {
					$_GET[$parts[$i]] = $parts[$i +1];
				}
			}
		} else {
			for ($i = 2; $i < count($parts); $i++) {
				$p = $parts[$i];
				$arr = explode($style, $p,2);
				if (isset ($arr[1])) {
					$_GET[$arr[0]] = $arr[1];
				}
			}
		}
		
		// 将 $_GET 合并到 $_REQUEST，
		// 有时需要使用 $_REQUEST 统一处理 url 中的 id=? 这样的参数
		$_REQUEST = array_merge($_REQUEST, $_GET);
	}
	
	/**
	 * 过滤参数
	 * 
	 * @param $val
	 * @return string
	 */
	private function _filterParam($val){
		$search = '`"\'';
		if(is_array($val)){	//如果是数组
			foreach($val as $k=>$v){
				$val[$k] = $this->_filterParam($v);
			}
			return $val;
		}else{
			for ($i = 0; $i < strlen($search); $i++) {
				$val = str_replace($search[$i],'',$val);
			}
		}
		return strip_tags($val);
	}
}