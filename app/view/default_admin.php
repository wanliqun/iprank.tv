<?php
/**
 * 布局模版
 * default_admin.php
 */
$pageCss = isset($pageCss) ? $pageCss : array();
$pageJs = isset($pageJs) ? $pageJs : array();
$controllerName = $this->app->controllerName;
$actionName = $this->app->actionName;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- title -->
<title>iPrank Admin Panel</title>
<!-- meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="generator" content="http://www.coolphp.org" />
<meta name="author" content="http://www.itbeing.com">
<!-- icon -->
<link href="<?=media('favicon.ico','images')?>" rel="shortcut icon" type="image/x-icon">
<link href="<?=media('favicon.ico','images')?>" rel="bookmark" type="image/x-icon">
<!-- css -->
<?php if(true): ?>
<link href="<?=_minify('css')?>" media="screen" rel="stylesheet" type="text/css">
<?php else: ?>
<!-- for development use only -->
<link rel="stylesheet/less" type="text/css" href="/less/style.less" />
<!-- set options before less.js script -->
<script type="application/javascript">
  var less = {
      env: "development",
      async: false,
      fileAsync: false,
      poll: 1000,
      functions: {},
      dumpLineNumbers: "mediaquery",
      relativeUrls: false,
      rootpath: "",
    };
</script>
<script src="/less/less.js/dist/less-1.7.0.js" type="text/javascript"></script>
<?php endif; ?>
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
<div class="ip-admin-wrapper">
	<header role="banner" class="ip-header" >
		<nav role="navigation" class="ip-navbar" >
			<div class="navbar-header">
				<h1 class="navbar-brand">
					<a title="iPrank Home" href="/">
						<span class="sr-only">iPrank</span><img alt="iPrank Logo" src="/public/images/logo.png" />
					</a>
				</h1>
			</div>
			<div class="nav navbar-nav navbar-right">
				<span>Welcome on board, <?=ME::name()?>.&nbsp;&nbsp;<a href="<?=_signoff()?>">[sign out]</a></span>
			</div>
		</nav>
	</header>
	<div class="ip-container">
		<div class="ip-master">
			<h1>Main Menu</h1>
			<?php $tabActiveClsMap = array($controllerName=>array($actionName=>'active')) ?>
			<section class="ip-menulist">
				<div class="menu-header">
					<?php $arrowSrcPath=media('admin-menu-arrow.png', 'images'); ?>
					<span>Posts</span><img class="img-menu-arrow" alt="arrow" src="<?=$arrowSrcPath?>" />
				</div>
				<ul class="ip-menuitem">
					<li class="<?=$tabActiveClsMap['post']['index']?>">
						<a href="<?=_admin(array('c'=>'post'))?>"><i class="fa fa-angle-double-right"></i> manage posts</a>
					</li>
					<li><a target="_blank" href="<?=_upload()?>"><i class="fa fa-angle-double-right"></i> new post</a></li>
				</ul>
			</section>
			<section class="ip-menulist">
				<div class="menu-header">
					<span>Members</span><img class="img-menu-arrow" alt="arrow" src="<?=$arrowSrcPath?>" />
				</div>
				<ul class="ip-menuitem">
					<li class="<?=$tabActiveClsMap['member']['index']?>">
						<a href="<?=_admin(array('c'=>'member'))?>"><i class="fa fa-angle-double-right"></i> manage members</a>
					</li>
				</ul>
			</section>
			<!--
			<section class="ip-menulist">
				<div class="menu-header">
					<span>Tags</span><img class="img-menu-arrow" alt="arrow" src="<?=$arrowSrcPath?>" />
				</div>
				<ul class="ip-menuitem">
					<li class="<?=$tabActiveClsMap['tag']['index']?>">
						<a href="<?=_admin(array('c'=>'tag'))?>"><i class="fa fa-angle-double-right"></i> manage tags</a>
					</li>
				</ul>
			</section>
			-->
			<section class="ip-menulist">
				<div class="menu-header">
					<span>Crawler</span><img class="img-menu-arrow" alt="arrow" src="<?=$arrowSrcPath?>" />
				</div>
				<ul class="ip-menuitem">
					<li class="<?=$tabActiveClsMap['crawler']['youtube']?>">
						<a href="<?=_admin(array('c'=>'crawler', 'a'=>'youtube'))?>"><i class="fa fa-angle-double-right"></i> YouTube</a>
					</li>
				</ul>
			</section>
		</div>
		<div class="ip-detail"><?php echo $this->content; ?></div>
	</div>
</div>
<!-- js -->
<script src="<?=_minify('js')?>"></script>
<?php if(!empty($pageJs)): ?>
<script src="<?=_minify('', $pageJs, 'js')?>"></script>
<?php endif; ?>
<?php echo trim( $this->loadBlock('__script') );?>
</body>
</html>
<?php if(c('after_view_html')) echo c('after_view_html'); ?>