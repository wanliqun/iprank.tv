<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<section class="ip-channels-menu">
		<h1>All Channels</h1>
		<ul class="channels-menu-list">
			<?php foreach($channels as $channel): ?>
				<?php $chv=_chv($channel['pk_id'], $channel['name']); ?>
			<li style="min-height:55px;">
				<figure class="channel-icon">
					<a href="<?=$chv?>">
						<img src="<?=$channel['icon_path']?>" alt="[<?=$channel['name']?>]">				
					</a>
				</figure>
				<div class="channel-detail">
					<h2><a href="<?=$chv?>"><?=$channel['name']?></a></h2>
					<div class="channel-description"><span><?=$channel['description']?></span></div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<section class="ip-channel-preview-wrapper">
		<h1>Preview The Channels</h1>
		<?php foreach($channelPosts as $channelId => $channelPost): ?>
		<section class="ip-preview-container">
			<header>
				<h1><?=$channelPost['channel_name']?></h1>
				<?php $chv=_chv($channelId, $channelPost['channel_name'])?>
				<a href="<?=$chv?>"><span>See More&nbsp;<i class="fa fa-angle-double-right"></i></span></a>
			</header>
			<div id="preview-carousel-<?=$channelId?>" class="ip-preview-carousel">
			<?php $posts = $channelPost['posts']; m('LibAjax'); ?>
			<?php foreach($posts as $post): ?>
				<?=LibAjax::genAjaxChannelPreviewPostHtml($post);?>
			<?php endforeach; ?> 
				<div class="carousel-control-pannel hidden">
					<a id="carousel-control-<?=$channelId?>" class="left carousel-control" role="button">
						<span class="fa fa-chevron-left carousel-arrow-left"></span>
					</a>
					<a id="carousel-control-<?=$channelId?>" class="right carousel-control" role="button">
						<span class="fa fa-chevron-right carousel-arrow-right"></span>
					</a>
				</div>
			</div>
		</section>
		<?php endforeach; ?>
	</section>
</div>

<?php $this->startBlock('__script');?>
<script type="text/javascript">
$(document).ready(function() {
	var g_responsiveOptions = [
		{ breakpoint: 992, settings: { slidesToShow: 4, slidesToScroll: 4, } },
		{ breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2, } },
		{ breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, } }
	];
	var g_options = { 
		dots: false, arrows: true, slide: 'article', slidesToShow: 6, slidesToScroll: 6,
		responsive : g_responsiveOptions
	};

	jQuery.fn.toHtmlString = function () {
		return $('<div></div>').html($(this).clone()).html();
	};
	function initPreviewCarousel(channelId) {
		var carouselOption = {
			prevArrow: $(".left#carousel-control-"+channelId).toHtmlString(),
			nextArrow: $(".right#carousel-control-"+channelId).toHtmlString(),
			onInit: function(self) { },
			onReInit: function(self) { }
		};
		$.extend(carouselOption, g_options);
		$('#preview-carousel-'+channelId).slick(carouselOption);
	}
<?php foreach($channelPosts as $channelId =>$channelPost): ?>
	initPreviewCarousel(<?=$channelId?>);
<?php endforeach; ?>
});
</script>
<?php $this->endBlock('__script'); ?>