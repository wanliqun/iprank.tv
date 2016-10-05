<?php
/**
 * 头文件
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage view
 * @version 2014-05-08
 */
?>
<header role="banner">
	<nav class="ip-navbar" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> 
					<span class="sr-only">Toggle navigation</span> 
					<span class="icon-bar"></span> 
					<span class="icon-bar"></span> 
					<span class="icon-bar"></span> 
				</button>
				<h1 class="navbar-brand">
					<a title="iPrank Home" href="/">
						<span class="sr-only">iPrank</span>
						<img alt="iPrank Logo" src="/public/images/logo.png" />
					</a>
				</h1>
			</div><!-- /.ip-navbar-header -->
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav main-group">
					<li><a href="<?=_pop()?>">Popular</a></li>
					<li><a href="<?=_chs()?>">Channels</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<form class="navbar-form" role="search" method="post" action="<?=_search();?>">
							<div class="input-group input-group-sm">
								<input type="search" name="_keyword" class="form-control" placeholder="Search Awesome Stuff...">
								<span class="input-group-btn">
        							<button class="btn btn-default btn-nav-search" type="submit">
										<i class="fa fa-search fa-lg"></i><span class="sr-only">Search</span>
									</button>
      							</span>
    						</div>
						</form>
					</li>
					<li>
						<a class="btn btn-default btn-sm" id="upload-btn" href="<?=_upload();?>">
							<i class="fa fa-cloud-upload fa-3x"></i><span class="sr-only">Upload</span>
						</a>
					</li>
					<?php $liCls='unlogined';?>
					<?php if(Me::isLogined()):?><?php $liCls='logined';?><?php endif;?>
					<li class="<?=$liCls?>">
						<?php if(!Me::isLogined()): ?>
						<div class="nav-unlogined-block" >
							<a class="btn btn-default" id="login-signup-btn" href="<?=_signin();?>">Login/Signup</a>
						</div>
						<?php else: ?>
						<div class="nav-logined-block" >
							<a class="nav-userinfo-box" href="<?=_mp(ME::id(), ME::name())?>">
								<strong class="nav-username"><?=Me::name();?></strong>&nbsp;
								<img width="32" height="32" class="nav-useravatar" src="<?=Me::avatar();?>" />
							</a>
							<div class="nav-user-dropmenu">
								<ul>
									<?php $myProfileUri=_mp(ME::id(), ME::name(), 'settings'); ?>
									<li><a href="<?=$myProfileUri?>"><span><i class="fa fa-user"></i>&nbsp;&nbsp;My Profile</span></a></li>
									<?php $myPostsUri=_mp(ME::id(), ME::name(), 'posts');?>
									<li><a href="<?=$myPostsUri?>"><span><i class="fa fa-cloud-upload"></i>&nbsp;My Uploads</span></a></li>
									<li class="sperator-line"><span class="sr-only">&nbsp;Seperator line</span></li>
									<li><a href="<?=_signoff()?>"><span><i class="fa fa-power-off"></i>&nbsp;&nbsp;Sign Out</span></a></li>
								</ul>
							</div>
						</div>
						<?php endif; ?>
                    </li>
				</ul>
			</div> <!-- /.ip-navbar-collapse -->
		</div>
	</nav>
</header>
