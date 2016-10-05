<?php
/**
 * 后台管理控制器
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @version 2014-05-08
 */
class AdminController extends AppController{
	protected $beforeFilter = array( 'checkAuth' );
	
	function __construct($app){
		parent::__construct($app);
		helper('admin');
	}
	
	function init(){
		$this->loadSysConfig();
		c('url_model',0);
	}
	
	function checkAuth(){
		if (!ME::isAdmin()) {
			redirect(_admin(array('c'=>'member', 'a'=>'signin')));
		}
	}
}