<?php

import('corex/Validation');
class FBSignupForm extends Validation{
	function __construct($callback = null){
		parent::__construct($callback);
		$memberRules = c('member_rules');
		
		$this->rules = array(
			'username' => $memberRules['username'],
			'password' => $memberRules['password'],
			'confirm_password' => $memberRules['confirm_password'],
			'accept_tou' => $memberRules['accept_tou'],
		);
	}
}
