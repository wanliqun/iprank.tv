<?php
$this->layout('default_xml');
$title = isset($title) ? $title : '系统提示信息';
?>
<title><?php echo $title?></title>
<?php foreach($this->vars as $key=>$var):
	if(is_array($var)) continue;
?>
<<?=$key?>><?php echo $var?></<?=$key?>>
<?php endforeach;?>