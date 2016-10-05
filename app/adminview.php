<?php
/**
 * 后台管理视图控制器
 * adminview.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @version 2014-05-08
 */
class AdminView extends AppView{
	public $adminPath = 'admin';
	
	/**
	 * 获取模板文件绝对途径
	 * 
	 * @param string $name
	 * @return string
	 * @exception FileNotFound
	 */
	protected function _getViewFile($name){
		if(file_exists($name)){	//绝对路径
			return $name;
		}
		$dir = c('view_dir');
		$classPath = c('class_path');
		$classPath = is_string( $classPath ) ? explode(',',$classPath) : $classPath;
		$roots = array_unique( array_merge(array(SYS_PATH,APP_PATH),$classPath ) );
		foreach( $roots as $root ){			
			if(file_exists($root.DS.$dir.DS.$this->adminPath.DS.$name.$this->ext)){
				return $root.DS.$dir.DS.$this->adminPath.DS.$name.$this->ext; 
			}elseif( file_exists( $root.DS.$dir.DS.$this->adminPath.DS.$this->app->controllerName.DS.$name.$this->ext )){ //在控制器所在的视图文件夹下
				return $root.DS.$dir.DS.$this->adminPath.DS.$this->app->controllerName.DS.$name.$this->ext;
			}
		}
		return parent::_getViewFile($name);
	}
}
?>
