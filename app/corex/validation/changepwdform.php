<?php

import('corex/Validation');
class ChangePwdForm extends Validation{
	function __construct($callback = null, $oldPwdRequired=true){
		parent::__construct($callback);
		
		$this->rules = array(
			'old_password' => array (
				'label' => 'old_password',
				'rules' => 'required|callback_checkChangePwdForm[old_password]',
				'errors' => array(
					'required'	=> 'Old password is blank.',
				),
			),
			'new_password' => array (
				'label' => 'new_password',
				'rules' => 'required|minlength[5]|callback_checkChangePwdForm[new_password]',
				'errors' => array(
					'required'	=> 'New password is blank.',
					'minlength'	=> 'New password is too short (length must be at least 5).',
				),
			),
			'confirm_password' => array (
				'label' => 'confirm_password',
				'rules' => 'required|minlength[5]|callback_checkChangePwdForm[confirm_password]',
				'errors' => array(
					'required'	=> 'Confirm password is blank.',
					'minlength'	=> 'Confirm password is too short (length must be at least 5).',
				),
			),
				
		);
		
		if (!$oldPwdRequired) unset($this->rules['old_password']);
	}
}
