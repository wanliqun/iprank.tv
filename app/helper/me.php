<?php

class ME {
	function __construct() {}

	static function isLogined() {
		return intval(self::id()) > 0;
	}

	static function isAdmin() {
		$adminUsers = c('admin_users'); $email = ME::email();
		$adminEmails = array_column($adminUsers, 'email');
		
		return !empty($email) && in_array($email, $adminEmails);
	}
	
	static function id() {
		return Session::getInstance()->get('userid', 0);
	}

	static function name() {
		return Session::getInstance()->get('username', '');
	}
	
	static function password() {
		return Session::getInstance()->get('password', '');
	}

	static function email() {
		return Session::getInstance()->get('email', '');
	}

	static function avatar() {
		return Session::getInstance()->get('avatar', '');
	}
	
	static function cover() {
		return Session::getInstance()->get('cover', '');
	}
}

?>