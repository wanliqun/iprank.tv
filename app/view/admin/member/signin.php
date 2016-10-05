<!DOCTYPE html>
<html lang="en">
<head>
<!-- title -->
<title>Sign in for dashboard</title>
<!-- meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- css -->
<link href="<?=_minify('css')?>" media="screen" rel="stylesheet" type="text/css">
<?php if (!empty($pageCss)): ?>
<link href="<?=_minify('', $pageCss, 'css')?>" media="screen" rel="stylesheet" type="text/css">
<?php endif ?>
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
<div class="admin-signin-wrapper">
	<h1>Sign in</h1>
	<form  id="signin-form" action="" method="post">
		<input type="hidden" name="signin_now" value="1" />
		<div class="form-group">
			<div class="input-group">
				<label for="email" class="input-group-addon required">Email Address</label>
				<input type="email" class="form-control" id="email" name="email" 
					placeholder="Please enter your email address" required="required" >
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<label for="password" class="input-group-addon required">Password</label>
				<input type="password" class="form-control" id="password" name="password" 
					placeholder="Please enter your password" required="required" >
			</div>
		</div>
		<div>
		<?php foreach($errors as $error): ?>
			<span class="error-tip"><?=$error?></span>
		<?php endforeach; ?>
		<div>
		<button type="submit" class="btn btn-default btn-signin">Sign in</button>
	</form>
</div>
</body>
<!-- js -->
<script src="<?=_minify('js')?>"></script>
<?php if(!empty($pageJs)): ?>
<script src="<?=_minify('', $pageJs, 'js')?>"></script>
<?php endif; ?>
<?php echo trim( $this->loadBlock('__script') );?>
</body>
</html>
