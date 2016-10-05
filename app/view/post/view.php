<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<section class="ip-posts-wrapper">
		<article class="ip-posts-container">
			<header>
				<h1><?=_ptitle($post)?></h1>
				<div class="intro-brief-area">
					<div class="detail-info">
						<em>by</em>&nbsp;
						<a href="<?=_mp($post['fk_member_id'], $post['fk_user_name'])?>">
							<strong><?=$post['fk_user_name']?></strong>
						</a>
					</div>
					<div class="detail-info">
						<?php $chv=_chv($post['fk_channel_id'], $post['fk_channel_name']); ?>
						&nbsp;·&nbsp;<em>channel:</em>&nbsp;<a href="<?=$chv?>"><strong><?=$post['fk_channel_name']?></strong></a>
					</div>
					<div class="detail-info">
						<?php $createdAt=get_tzlocalized_readable_time($post['created_at']); ?>
						&nbsp;·&nbsp;<em><i class="fa fa-clock-o"></i></em>&nbsp;<strong><?=$createdAt?></strong>
					</div>
					<div class="detail-info">
						<?php $numViewed=number_format($post['statistics']['num_viewed']); ?>
						&nbsp;·&nbsp;<em><i class="fa fa-eye"></i></em>&nbsp;<strong><?=$numViewed?> views</strong>
					</div>
				</div>
			</header>
			<section class="ip-video-container">
				<?php 
					$YtVideoWatchUrl = $post['media']['src_from'].
						"?autoplay=1&rel=0&showinfo=0&wmode=transparent&origin=" . base_url();
				?>
				<div class="yt-video-player">
					<iframe src="<?=$YtVideoWatchUrl?>" frameborder="0" allowfullscreen></iframe>
				</div>
			</section>

			<section class="post-action-toolbar" role="toolbar">
				<div class="left-side">
					<div class="action-box" id="post-actionbox-<?=$post['pk_id']?>"> 
						<div class="like-action-box">
							<button onclick="likePost(<?=$post['pk_id']?>)" type="button" class="btn btn-default btn-like">
								<i class="fa fa-thumbs-up"></i>
							</button>
							<?php $numLiked = number_format($post['statistics']['num_liked']) ?>
							<div class="box-speech-bubble cnt-like"><?=$numLiked?></div>
						</div>
						<div class="dislike-action-box">
							<button onclick="dislikePost(<?=$post['pk_id']?>)" type="button" class="btn btn-default btn-dislike">
								<i class="fa fa-thumbs-down"></i>
							</button>
							<?php $numDisliked = number_format($post['statistics']['num_disliked']) ?>
							<div class="box-speech-bubble cnt-dislike"><?=$numDisliked?></div>
						</div>
						<div class="comment-action-box">
							<button onclick="location.hash='comments'" type="button" class="btn btn-default btn-comment">
								<i class="fa fa-comment"></i>
							</button>
							<?php $numCommented = number_format($post['statistics']['num_commented']) ?>
							<div class="box-speech-bubble cnt-comment"><?=$numCommented?></div>
						</div>
						<div class="bookmark-action-box">
							<button onclick="bookmarkPost(<?=$post['pk_id']?>)" type="button" class="btn btn-default btn-bookmark">
								<i class="fa fa-bookmark"></i>
							</button>
						</div>
					</div>
					<div class="addthis_toolbox addthis_default_style addthis_32x32_style"
						addthis:url="<?=$pageCanonical?>" addthis:title="<?=_ptitle($post)?>">
						<a class="addthis_button_facebook"></a>
						<a class="addthis_button_twitter"></a>
						<a class="addthis_button_email"></a>
						<a class="addthis_button_more"></a>
					</div>
				</div>
				<div class="spam-report">
					<div><span>Inappropriate?</span><button class="btn-reportspam" onclick="reportSpam(<?=$post['pk_id']?>)"><i class="fa fa-flag"></i></button></div>
					<div class="tip-spam-report"><span>Thanks for your report.</span></div>
				</div>
			</section>
			<hr class="seperator-line" />
			<section class="intro-detail-area">
				<div class="author-avatar-box">
					<a href="<?=_mp($post['fk_member_id'], $post['fk_user_name'])?>">
						<img class="avatar-img" alt="[<?=$post['fk_user_name']?>]'s avatar" src="<?=_mavatar($post['member'])?>" />
					</a>
				</div>
				<div class="description-box">
					<div class="description-content">
						<p class="text-content short-text">
							<?=_pdescription($post)?>
						</p>
						<div class="show-more-or-less">
							<button type="button" class="btn btn-xs btn-show-more">Show more</button>
						</div>
					</div>
				</div>
			</section>
			<section class="ads-container" style="margin-top:25px;">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Sidebar-Responsive-AdUnit-04 -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="ca-pub-1824985805781840"
				     data-ad-slot="9679057210"
				     data-ad-format="auto"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
			</section>
			<section class="comment-box" id="comments" >
				<div id="disqus_thread"></div>
				<script type="text/javascript">
					var disqus_config = function () {
						// The generated payload which authenticates users with Disqus
						this.page.remote_auth_s3 = '<?=$disqusSSO['payload']?>';
						this.page.api_key = '<?=$disqusSSO['apikey']?>';
						this.callbacks.onNewComment = [function(comment) {}];
					
						// This adds the custom login/logout functionality
						this.sso = {
							name:    "iPrank.TV",
							button:  "<?=media('logo.png', 'images')?>", 
							icon:    "<?=media('favicon.ico', 'images')?>",
							url:	 "<?=_dialog(array('a'=>'login_box', 'v'=>'full', 'redirect'=>_callback(array('a'=>'disqus_authorize'))))?>",
							logout:  "<?=_signoff(_this());?>",
							width:   "280",
							height:  "360",
						};
					};
					/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
					var disqus_shortname = "<?=disqus_shortname()?>"; 
					var disqus_identifier = "<?=disqus_identifier($post)?>";
					var disqus_title = "<?=disqus_title($post)?>";
					var disqus_url = "<?=disqus_url($post)?>";

					/* * * DON'T EDIT BELOW THIS LINE * * */
					(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					})();
				</script>
				<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
			</section>
		</article>
		<aside class="ip-aside-bar">
			<nav class="quick-nav-container">
				<?php print_r(LibPost::genQuickNavbox($prev)); ?>
				<?php print_r(LibPost::genQuickNavbox($next, 'Next')); ?>
			</nav>
			<section class="ads-container" style="margin-top:10px;">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Sidebar-Responsive-AdUnit-03 -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="ca-pub-1824985805781840"
				     data-ad-slot="8341924815"
				     data-ad-format="auto"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
			</section>
			<section class="recomended-posts-box">
				<h1>You might also like</h1>
				<div class="ip-article-recommended-stream">
				<?php if(empty($similarPosts)): ?>
					<span class="no-similar-tips">No similiar stuffs found at this moment!</span>
				<?php endif; ?>
				<?php foreach($similarPosts as $similar): ?>
					<?php $pv=_pv($similar['pk_id'], $similar['btitle'], $similar['type']); ?>
					<article class="ip-article-md">
						<div class="article-thumbnail">
							<a href="<?=$pv?>">
								<img class="similar-image" alt="[<?=$similar['btitle']?>]" src="<?=$similar['cover_url']?>" />
							</a>
						</div>
						<div class="article-description">
							<h1><a href="<?=$pv?>"><?=$similar['btitle']?></a></h1>
							<footer>
								<?php $numViewed = get_readable_number($similar['statistics']['num_viewed']); ?>
								<span><i class="fa fa-eye"></i>&nbsp;<?=$numViewed?></span>
								<?php $numLikes = number_format($similar['statistics']['num_liked']); ?>
								&nbsp;&nbsp;<span><i class="fa fa-thumbs-o-up"></i>&nbsp;<?=$numLikes?></span>
							</footer>
						</div>
					</article>
				<?php endforeach; ?>
				</div>
			</section>
		</aside>
	</section>
</div>

<?php 
	$params = array('a'=>'post_actionbox', 'postids'=>$post['pk_id'],); 
	$jsuri = _js($params); 

	$params = array('a'=>'markviewed', 'postid'=>$post['pk_id'], 'format'=>'json'); 
	$ajaxuri = _ajax($params);
?>
<?php $this->startBlock('__script');?>
<script type="text/javascript">$.getScript('<?=$jsuri?>', function(data, textStatus){});</script>
<script type="text/javascript">$.getScript('<?=$ajaxuri?>', function(data, textStatus){});</script>
<?php $this->endBlock('__script'); ?>