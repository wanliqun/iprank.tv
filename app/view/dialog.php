<?php if($lite): ?>
<?php echo $this->loadBlock('__style');?>
<div class="ip-dialog-wrapper">
<?php echo $this->content; ?> 
</div>
<?php echo trim( $this->loadBlock('__script') );?>
<? else: ?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- title -->
<?php $pageTitle = isset($pageTitle) ? $pageTitle : 'iPrank dialog'; ?>
<title><?= $pageTitle;?></title>
<!-- meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- css -->
<link href="<?=_minify('css')?>" media="screen" rel="stylesheet" type="text/css">
<?php foreach( $pageCss  as $file):?>
<link href="<?=media("{$file}",'css')?>" media="screen" rel="stylesheet" type="text/css" />
<?php endforeach;?>
<?php echo $this->loadBlock('__style');?>
<!-- js -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- body content -->
<div class="ip-dialog-wrapper">
<?php echo $this->content; ?> 
</div>
<!-- js -->
<script src="<?=_minify('js')?>"></script>
<?php foreach( $pageJs  as $file):?>
<script src="<?=media("{$file}","js")?>"></script>
<?php endforeach;?>
<?php echo trim( $this->loadBlock('__script') );?>
<script src="<?=media("facebook","js")?>"></script>
</body>
</html>
<? endif; ?>