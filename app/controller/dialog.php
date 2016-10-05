<?php
class Dialog extends AppController {
	function __construct($app) {
		parent::__construct($app);
	}
	
	function signup_fb() {
		$email = $this->app->getRequest('email', '');
		$username = $this->app->getRequest('username', '');
		$userid = $this->app->getRequest('userid', 0);
		
		if(empty($email) || empty($username) || empty($userid)) {
			$this->app->showError("Invalid Access", "Please do not hack!");
		}
		
		return array(
			'email'=>$email, 'username'=>$username, 'userid'=>$userid,
			'lite'=>$this->_lite(), 'redirect'=>get_redirect_url('/'),
		);
	}
	
	function login_box() {
		return array('lite'=>$this->_lite(), 'redirect'=>get_redirect_url('/'),);
	}
	
	function resetpwd_box() {
		return array('lite'=>$this->_lite(),);
	}
	
	private function _lite() {
		$version = $this->app->getRequest('v', 'lite');
		if (!in_array($version, array('lite', 'full'))) $version = 'lite';
		return (strcasecmp($version, 'lite') == 0);
	}
}
