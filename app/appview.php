<?php
/**
 * 应用显示视图
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @version 2014-05-08
 */
class AppView extends View{
	/**
	* 模板文件所在路径
	*
	* @var string
	*/
	public $path = '';
	
	public function __construct($app){
		parent::__construct($app);
		
		if(c('view_path')){
			$this->path = c('view_path');
		}
	}
	
	/**
	 * 获取模板文件绝对途径
	 * 
	 * @param string $name
	 * @return string
	 * @exception FileNotFound
	 */
	protected function _getViewFile($name){
		$dir = c('view_dir');
		if( $this->path && file_exists( APP_PATH.DS.$dir.DS.$this->path.DS.$name.$this->ext ) ){ //当前目录下？
			return APP_PATH.DS.$dir.DS.$this->path.DS.$name.$this->ext;
		}elseif( file_exists( APP_PATH.DS.$dir.DS.$this->path.DS.$this->app->controllerName.DS.$name.$this->ext )){ //在控制器所在的视图文件夹下
			return APP_PATH.DS.$dir.DS.$this->path.DS.$this->app->controllerName.DS.$name.$this->ext;
		}
		
		try{
			return parent::_getViewFile($name);
		}catch(Exception $ex){
			//如果布局文件存在，直接返回布局文件
			$format = $this->app->format;
			if($format=='json'||$format=='xml'){
				if(file_exists(APP_PATH.DS.$dir.DS."default_{$format}.php")){
					return APP_PATH.DS.$dir.DS."default_{$format}.php";
				}
			}
			
			if(c('app_model')=='product')$file = __('system._view_').$name;
			throw new CLException('ViewNotFound',$name);
		}
	}

	public function display( $name=null,$vars=array() ) {
		$content = parent::display($name,$vars);
		return $content;
	}
}