<?php
$title = isset($title) ? $title : '系统提示信息';
?>
<div style="width:300px; height:100px;">
  <div style="padding-top:10px; color:#F00; font-weight:800"><?php echo $title?>：</div>
  <div><?php echo $msg?></div>
</div>
