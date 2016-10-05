<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-popular-wrapper">
		<section class="ip-popular-container">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-most-viewed" data-toggle="tab">Most Viewed</a></li>
				<li><a href="#tab-most-liked" data-toggle="tab">Most Liked</a></li>
				<li><a href="#tab-most-commented" data-toggle="tab">Most Commented</a></li>
				<li class="filter">
					<div class="ip_type_filter">
						<span class="filter-flag"><i class="fa fa-filter"></i></span>
						<select class="filter-select" id="tab-filter-select" onchange="ajaxLoadPopular()">
							<option value="always">Always</option>
							<option value="today">Today</option>
							<option value="this-week">This week</option>
							<option value="this-month">This Month</option>
						</select>
					</div>
				</li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab-most-viewed">
					<div class="popular-post-cards">
						<?php if(empty($populars)): ?>
						<span class="ip-post-tips">No related stuffs found at this moment!</span>
						<?php else: ?>
							<?php foreach($populars as $popular): ?>
								<? print_r(LibAjax::genAjaxPopularHtml($popular));?>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			  	<div class="tab-pane fade" id="tab-most-liked"></div>
			  	<div class="tab-pane fade" id="tab-most-commented"></div> 
			</div>
		</section>
		<aside class="ip-popular-aside-bar">
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
					</div>
				</header>
				<section class="ads-container" style="margin-bottom:10px;">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Sidebar-Responsive-AdUnit-05 -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-1824985805781840"
					     data-ad-slot="2016189616"
					     data-ad-format="auto"></ins>
					<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
				</section>
				<div class="article-popular-stream">
				<?php foreach($mostPopulars as $popular): ?> 
					<?php print_r(LibAjax::genAjaxMostPopularHtml($popular)); ?>
				<?php endforeach; ?>
				</div>
			</section>
		</aside>
	</div>
</div>