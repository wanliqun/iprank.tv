<?php
/**
 * 命令行控制器
 * cmdcontroller.php
 *
 * @copyright Copyright (c) 2010 Itbeing (Beijing) Tech co.,Ltd
 * @author kokko<kokkowon@itbeing.com>
 * @package	iprank.co
 * @version 2014-05-08
 */
class CmdController extends Controller{	
	/**
	 * 提示消息
	 * 
	 * @param string $msg
	 * @param boolean $isBreak
	 * @param array	$extra 		额外信息
	 */
	public function msg($msg,$isBreak=true,$extra=array()){
		$msg = $isBreak ? $msg.BR : $msg;
		echo $msg;
	}
	
	/**
	 * 初始化
	 */
	public function init(){
		helper(array( 
			'app','application','system','model','array','url','datetime',
			'datetime_ex','string_ex', 'array_ex', 'network','me', 'resources',) 
		);
	}
}
?>
