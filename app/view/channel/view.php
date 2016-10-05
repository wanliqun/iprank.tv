<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-channel-wrapper">
		<section class="ip-channel-container">
			<header>
				<div class="left-panel">
					<div class="channel-icon">
						<img class="channel-cover-img" alt="[<?=$channel['name']?>]" src="<?=$channel['icon_path']?>" />
					</div>
					<h1>
						<dl>
							<dt><?=$channel['name']?></dt>
							<dd><blockquote><?=$channel['description']?></blockquote></dd>
						</dl>
					</h1>
				</div>
				<div class="right-panel">
					<span class="channel-total-cnt">Totally <?=number_format($pager['totalRows']);?> results</span>
				</div>
			</header>
			<div class="info-filter">
				<span class="sortby-tip" style="display:none"><em>Sort by:</em></span>
				<div class="ip_type_filter" style="display:none">
					<span class="filter-flag"><i class="fa fa-sort"></i></span>
					<select class="filter-select" id="popular-filter-select" onchange="ajaxLoadPopular()">
						<option>Recent</option>
						<option>Most Viewed</option>
						<option>Most Liked</option>
						<option>Most Commented</option>
					</select>
				</div>
			</div>
			<div class="channel-post-cards">
			<?php $groups = array_chunk($posts, 8); ?>
			<?php foreach($groups as $group): ?>
				<?php foreach($group as $post): ?>
				<article class="ip-article-md">
					<div class="article-thumbnail">
						<?php $pv = _pv($post['pk_id'], $post['btitle'], $post['type']); ?>
						<a href="<?=$pv?>" target="_blank" title="<?=$post['btitle']?>">
							<img class="channel-post-cover" alt="[<?=$post['btitle']?>]" src="<?=$post['cover_url']?>">
						</a>
					</div>
					<div class="article-description">
						<h1 class="article-header-h1">
							<a href="<?=$pv?>" target="_blank" title="<?=$post['btitle']?>"><?=$post['btitle']?></a>
						</h1>
						<footer>
							<?php $createdAt = get_tzlocalized_readable_time($post['created_at']); ?>
							<span><i class="fa fa-clock-o"></i>&nbsp;<?=$createdAt;?></span>
							<?php $numViewed = number_format($post['statistics']['num_liked']); ?>
							<span><i class="fa fa-eye"></i>&nbsp;<?=$numViewed?></span>
						</footer>
					</div>
				</article>
				<?php endforeach; ?>		
			<?php endforeach; ?>			
			</div>
			<?php if($pager['totalPages'] > 1): ?>
			<div class="ip-pagination-box" style="display:inline-block;"><ul class="bootstrap-paginator"></ul></div>
			<?php endif; ?>
		</section>
		<aside class="ip-channel-aside-bar">
			<section class="ads-container" style="">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Sidebar-Responsive-AdUnit-06 -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="ca-pub-1824985805781840"
				     data-ad-slot="1318185615"
				     data-ad-format="auto"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
			</section>
		</aside>
	</div>
</div>
<?php if($pager['totalPages'] > 1): ?>
	<?php $this->startBlock('__script');?>
<script type="text/javascript">
var options = { 
	currentPage: <?=$pager['page']?>, 
	totalPages: <?=$pager['totalPages']?>, 
	bootstrapMajorVersion: 3, 
	size: 'small', 
	numberOfPages: 10, 
	useBootstrapTooltip: false, 
	itemContainerClass: function (type, page, current) {
		return (page===current) ? "active" : "pointer-cursor";
	},
	pageUrl: function(type, page, current){
		return "<?=_chv($channel['pk_id'], $channel['name'])?>?page=" + page;
	}
}
$('.bootstrap-paginator').bootstrapPaginator(options);
</script>
	<?php $this->endBlock('__script'); ?>
<?php endif; ?>