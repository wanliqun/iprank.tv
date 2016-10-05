<?php
/**
 * Mailer邮件发送
 * mailer.php
 */
class Mailer {
	
	function __construct() {
		$this->_registerAutoloader();
	}
	
	/**
	 * 通用邮件发送函数
	 * i.g.
	 $config=array(
	 	'host'=>'mail.domain.com',
	 	'user'=>'abc@domain.com',
	 	'pass'=>'123456',
	 	'name'=>'abc',
	 );
	 $status = sendMail('title111','<b>content</b>','nothing','abc@domain.com','abc',$config);
	 var_dump($status);
	 *
	 * @param string $title    标题，默认为utf-8编码
	 * @param string $html     Html信体,默认为utf-8编码
	 * @param string $text     纯文本信体,默认为utf-8编码
	 * @param string $email    收件人地址
	 * @param string $user     收件人称谓
	 * @param array  $config   邮件发送参数
	 * @return boolean         返回邮件发送情况
	 */
	function sendMail($title, $html, $text, $email, $user, $config=array(), &$err=null){
		//下面是几个不常用到的变量
		$charset = empty($config["charset"]) ? 'UTF-8' : $config["charset"];
		$encode = empty($config["encoding"]) ? 'base64' : $config["encoding"];
		$debug = empty($config["debug"]) ? false : $config["debug"];
	
		//初始化邮件类
		$mail = new PHPMailer();
		$mail->IsSMTP();                           // send via SMTP
		$mail->Host        = $config["host"];      // SMTP servers
		$mail->Port        = empty($config["port"]) ? 25 : $config["port"];
		$mail->SMTPSecure  = empty($config["Secure"]) ? 'ssl' : $config["Secure"];
		$mail->SMTPAuth    = true;                 // turn on SMTP authentication 开启验证
		$mail->Username    = $config["user"];      // SMTP username  注意：普通邮件认证不需要加 @域名
		$mail->Password    = $config["pass"];      // SMTP password
		$mail->From        = empty($config["fromuser"])?$config["user"]:$config["fromuser"];      // 发件人邮箱
		$mail->FromName    = empty($config["fromname"])?$config["name"]:$config["fromname"];      // 发件人
		$mail->CharSet     = $charset;             // 这里指定字符集！
		$mail->Encoding    = $encode;              // 编码方式
		$mail->SMTPDebug   = $debug;               // 调试用的开关
		$mail->AddReplyTo($config["user"], $config["name"]);
	
		if (!empty($config["gmail"])){
			$mail->SMTPSecure = empty($config["Secure"])?'ssl':$config["Secure"];
			$mail->Port       = empty($config["port"])?465:$config["port"];
		}
	
		$mail->Subject = $title;
		if(!empty($config["istext"])){
			$mail->IsHTML(false);
			$mail->Body = $text;
		}else{
			$mail->IsHTML(true);
			$mail->AltBody = $text;  //不支持html格式时，显示的文本
			$mail->Body = $html;//html信体
		}
		
		$mail->AddAddress($email,$user);
		if(!$mail->Send()) {
			$err = $mail->ErrorInfo;
			return false;
		}
		
		return true;
	}
	
	private function _registerAutoloader() {
		// If your code has an existing __autoload function then this function must be explicitly
		// registered on the __autoload stack.
		// (PHP Documentation for spl_autoload_register [@see http://php.net/spl_autoload_register])
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		
		/**
		 * Register the autoloader for the PHPMailer SDK classes.
		 * Based off the official PSR-4 autoloader example found here:
		 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
		 *
		 * @param string $class The fully-qualified class name.
		 * @return void
		 */
		spl_autoload_register(function ($class) {
			if (in_array($class, array('PHPMailer', 'POP3', 'SMTP'))) {
				$filename = __DIR__.'/phpmailer/class.'.strtolower($class).'.php';
				if (is_readable($filename)) {
					require $filename;
				}
			}
		});
	}
}