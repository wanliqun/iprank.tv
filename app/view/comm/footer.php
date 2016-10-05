<?php
/**
 * 尾部文件
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage view
 * @version 2014-05-08
 */
?>
<footer class="ip-footer">
	<nav class="intro-nav">
		<ul class="list-inline">
			<li><a href="<?=_about();?>">About</a></li><li><i class="link-seperator">|</i></li>
			<li><a href="<?=_tou();?>">Terms Of Use</a></li><li><i class="link-seperator">|</i></li>
			<li><a href="<?=_privacy();?>">Privacy Policy</a></li><li><i class="link-seperator">|</i></li>
			<li><a href="<?=_faq();?>">Help</a></li>
		</ul>
	</nav>
	<section class="social-banner">
		<div class="fb-like-wrapper">
			<div class="fb-like" data-href="<?=_fb_home();?>" data-layout="button_count" 
				data-action="like" data-show-faces="false" data-share="true"></div>
		</div>
		<div class="tw-follow-wrapper">
			<a href="<?=_tw_home();?>" class="twitter-follow-button" data-show-count="true">Follow @iPrank.tv</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
	</section>
	<hr class="seperator-line" />
	<div class="copyright">
		<div class="cr-reserved" style="display:inline-block;">
			<span>&copy; <?=date("Y")?> iPrank.TV&nbsp;All rights reserved.&nbsp;</span>
		</div>
		<div class="author" style="display:inline-block;float:right;">
			<span style="background:#333;color:#666;font-size:10px;">Proudly powered by 
				<a class="author-link" href="http://www.itbeing.com" target="_blank">CoolPHP Framework</a>
			</span>
		</div> 
	</div>
	<div><a href="#"><span class="sr-only">Scroll To Top</span></a></div>

</footer>