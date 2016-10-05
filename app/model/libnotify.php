<?php

class LibNotify {
	function __construct() {
		$this->mailer = new Corex_Mailer();
	}
	
	function sendAccountActivationEmail($dstEmail, $dstName, $verifyCode, &$err=null)
	{
		$activationLink =  _act_acct_link($verifyCode);
		$title = "Please Activate Your iPrank Account";
		$txt = "To view this message please use an HTML compatible email viewer" 
				. " or visit {$activationLink} to activate your account.";
		
		$tplPath = APP_PATH . "/view/member/tpl_activation_email.php";
		$tplVariables = array(
			'top_banner_src' => media('account-act-mail-top-banner.png', 'images'),
			'member_name' => $dstName,
			'activate_link' => _act_acct_link($verifyCode),
			'botton_banner_src' => media('mail-bottom-banner.png', 'images'),
		);
		$html = get_include_contents($tplPath, $tplVariables);
		
		$notifyCfg = c('smtp');
		return $this->mailer->sendMail($title, $html, $txt, $dstEmail, $dstName, $notifyCfg, $err);
	}
	
	function sendPasswordResetEmail($dstEmail, $dstName, $secretToken, &$err=null) {
		$resetLink = _pwd_reset_link($secretToken);
		$title = "Account Password Reset";
		$txt = "To view this message please use an HTML compatible email viewer"
				. " or visit {$resetLink} to reset your account password.";
		
		$tplPath = APP_PATH . "/view/member/tpl_pwdreset_email.php";
		$tplVariables = array(
			'top_banner_src' => media('pwd-reset-mail-top-banner.png', 'images'),
			'member_name' => $dstName, 'reset_link' => $resetLink,
			'botton_banner_src' => media('mail-bottom-banner.png', 'images'),
		);
		$html = get_include_contents($tplPath, $tplVariables);
		$notifyCfg = c('smtp');
		return $this->mailer->sendMail($title, $html, $txt, $dstEmail, $dstName, $notifyCfg, $err);
	}
}