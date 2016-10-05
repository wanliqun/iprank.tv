<?php
$this->layout('default');
$pageCss = array('log');
$pageTitle = isset($pageTitle) ? $pageTitle : '系统提示';
$delay = 2;
$redirect = isset($redirect) ? $redirect : '';
?>

<div class="wrapBox clearfix">
	<div class="regSuccess">
		<div class="boxLft">
			<h5><?php echo $pageTitle; ?></h5>
			<div>
				<?php echo nl2br($msg); ?>
			</div>
           <div> <?php
		if($redirect):
		?>
        <script type="text/javascript">
		setTimeout("window.location.href ='<?php echo $redirect; ?>';", <?php echo $delay * 1000; ?>);
		</script> 
        系统将在 <span style="color:blue;font-weight:bold"> <?=$delay?></span> 秒后自动跳转,如果不想等待或者您的浏览器没有自动跳转,<a href="<?php echo $redirect; ?>" >请点击这里</a> <br/>
        <?php endif;?>
			</div>
            <div>
            <a href="javascript:history.back();" class="button button_l btn_blue">返回上一页</a>
            </div>
		</div>
		<div class="boxRgt">
			<dl>
				<dt>您还可以进行如下操作：</dt>
				<dd>· 可以点击这里<a href="<?=url()?>">返回首页</a></dd>
				<dd>· 如果您还没有拥有我们的账户可以<a href="<?=url('c=users&a=register')?>">点击这里去注册</a></dd>
			</dl>
		</div>
	</div>
</div>
