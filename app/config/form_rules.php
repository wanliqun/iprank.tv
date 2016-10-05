<?php
/**
 * form rules配置文件
 * form_rules.php
 */
return array(
	'member_rules' => array 
	(
		'username' => array (
			'label' => 'username',
			'rules' => 'trim|required|minlength[4]|maxlength[20]|callback_checkSignupForm[username]',
			'errors' => array(
				'required'	=> 'User name is blank.',
				'minlength' => 'User name is too short (length must be at least 4).',
				'maxlength'	=> 'User name is too long (length must be at most 20).',
			),
		),
		'email' => array (
			'label' => 'email',
			'rules' => 'trim|required|email|callback_checkSignupForm[email]',
			'errors' => array(
				'required'	=> 'Email is blank.',
				'email'	=> 'Email is not valid.',
			),
		),
		'password' => array (
			'label' => 'password',
			'rules' => 'required|minlength[5]|callback_checkSignupForm[password]',
			'errors' => array(
				'required'	=> 'Password is blank.',
				'minlength'	=> 'Password is too short (length must be at least 5).',
			),
		),
		'confirm_password' => array (
			'label' => 'confirm_password',
			'rules' => 'required|minlength[5]|callback_checkSignupForm[confirm_password]',
			'errors' => array(
				'required'	=> 'Confirm password is blank.',
				'minlength'	=> 'Confirm password is too short (length must be at least 5).',
			),
		),
		'adcopy_response' => array (
			'label' => 'adcopy_response',
			'rules' => 'required|callback_checkSignupForm[adcopy_response]',
			'errors' => array(
				'required'	=> 'Captcha is blank.',
			),
		),
		'accept_tou' => array (
			'label' => 'accept_tou',
			'rules' => 'required',
			'errors' => array(
				'required'	=> 'Terms of use is not accepted.',
			),
		),
	),
);