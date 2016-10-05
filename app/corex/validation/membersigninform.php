<?php

import('corex/Validation');
class MemberSigninForm extends Validation{
	function __construct($callback = null){
		parent::__construct($callback);
		
		$memberRules = c('member_rules');
		$this->rules = array(
			'email' => array (
				'label' => 'email',
				'rules' => 'trim|required|email',
				'errors' => array(
					'required'	=> 'Email is blank.',
					'email'	=> 'Email is not valid.',
				),
			),
				
			'password' => array (
				'label' => 'password',
				'rules' => 'required',
				'errors' => array(
					'required'	=> 'Password is blank.',
				),
			),
		);
	}
}
