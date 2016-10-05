<?php
/**
 * 首页模板
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author kokko<kokkowon@itbeing.com>
 * @package iprank.co
 * @subpackage view
 * @version 2014-05-08
 */
$this->layout('default');
?>
<div class="ip-wrapper">
	<section class="ip-featured-container">
		<h1>Featured</h1>
		<div class="well">
			<div class="carousel slide">
				<div class="carousel-inner">
					<div class="item active">
						<div class="featured-row">
						<?php foreach($featuredPosts as $post): ?>
							<figure class="featured-card">
								<figcaption class="overlay-caption"><?=$post['btitle']?></figcaption>
								<a target="_blank" href="<?=_pv($post['pk_id'], $post['btitle'], $post['type'])?>" title="<?=$post['btitle']?>"> 
									<img src="<?=$post['cover_url']?>" alt="[<?=$post['btitle']?>]" class="featured-img">
								</a>
							</figure>
						<?php endforeach ?>
                        </div>
					</div>
				</div>
			</div>
		</div> <!--/well-->
	</section> <!--/#featured-container -->
	<div class="ip-recent-container">
		<section class="ip-left-pannel">
			<header><h1>Most Recently</h1></header>
			<div class="ip-article-timeline-stream">
			<?php foreach($recentlyPosts as $post): ?>
				<?php print_r(LibAjax::genAjaxPostHtml($post)); ?>
			<?php endforeach; ?>
			</div>  <!--/.ip-article-timeline-stream -->
			<?php if($hasMore): ?>
			<div class="ip-load-more">
				<?php 
					$loadmoreUrl = ($lastTimestamp > 0) ? base_url() . "?last_timestamp={$lastTimestamp}" : "#";
				?>
				<a href="<?=$loadmoreUrl?>"  class="btn btn-default btn-load-more" 
					onclick="ajaxLoadMore(<?=$lastTimestamp?>); return false;" >Load more</a>
			</div>
			<?php endif; ?>
		</section>
		<aside class="ip-aside-bar">
			<section class="most-popular-posts">
				<header>
					<h1>Most Popular</h1>
					<div class="ip_type_filter">
						<span class="filter-flag"><i class="fa fa-filter"></i></span>
						<select class="filter-select" id="popular-filter-select" onchange="ajaxLoadMostPopular()">
							<option value="always">Always</option>
							<option value="today">Today</option>
							<option value="this-week">This week</option>
							<option value="this-month">This Month</option>
						</select>
						 <noscript><style>#popular-filter-select{ display:none; }</style></noscript>
					</div>
				</header>
				<section class="ads-container" style="margin-bottom:10px;">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Sidebar-Responsive-AdUnit-01 -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-1824985805781840"
					     data-ad-slot="3368498417"
					     data-ad-format="auto"></ins>
					<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
				</section>
				<div class="article-popular-stream">
				<?php foreach($popularPosts as $post): ?>
					<?php print_r(LibAjax::genAjaxMostPopularHtml($post)); ?>
				<?php endforeach; ?>
				</div>
			</section>
	
			<div class="sidebar-floatdiv" id="sidebar-sticker">
				<section class="ads-container" style="margin-top:10px;">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Sidebar-Responsive-AdUnit-02 -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-1824985805781840"
					     data-ad-slot="6042763214"
					     data-ad-format="auto"></ins>
					<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
				</section>
				<section class="subscribe-block">
					<h1>Subscribe to iPrank</h1>
					<?php $emailSubscribeUrl = _ajax(array('a'=>'subscribe_email')); ?>
					<form id="form-subscribe" method="post" action="<?=$emailSubscribeUrl?>">
						<div class="input-group">
							<input type="email" name="email" class="form-control" placeholder="your email address...">
							<span class="input-group-btn">
								<button class="btn btn-default btn-subscribe" type="submit">Subscribe</button>
							</span>
						</div>
						<div class="subscription-tip"></div>
					</form>
				</section>
				<section class="sidebar-social-box">
					<h1>We are social</h1><br/>
					<div class="fb-like-box" data-href="<?=_fb_home()?>" data-colorscheme="light" 
						data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>
					<noscript><style>.fb-like-box{ display:none; }</style></noscript>
					<div class="tw-follow-box">
						<a href="https://twitter.com/iPrankTV" class="twitter-follow-button" data-show-count="false" data-size="large">
							Follow @iPrankTV</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					</div>
				</section>
			</div>
		</aside>
	</div> <!--/#main-container-->
</div>
<?php if(!empty($recentlyPosts)): ?>
<?php
	$postids = array();
	foreach($recentlyPosts as $post) {
		$postids[] = $post['pk_id']; 
	}
	$postids = implode(',', $postids);
	$params = array('a'=>'post_actionbox', 'postids'=>$postids);
	$jsuri = _js($params);
	$mediaMathJsUri = media('media.match.min', 'js');
?>
<?php $this->startBlock('__script');?>
<!--[if lte IE 9]>
<script>window.matchMedia=null;</script><script src="<?=$mediaMathJsUri?>"></script>
<![endif]-->
<script type="text/javascript">
	$.getScript('<?=$jsuri?>', function(data, textStatus){}); 
	optimizeHDThumbnails();
</script>
<?php $this->endBlock('__script'); ?>
<?php endif; ?>