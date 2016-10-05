<?php

class LibAjax {
	
	static function genAjaxMostPopularHtml($post) {
		$pv = _pv($post['pk_id'], $post['btitle'], $post['type']);
		$numViewed = get_readable_number($post['statistics']['num_viewed']);
		$numLiked = number_format($post['statistics']['num_liked']);
		$numCommented = number_format($post['statistics']['num_commented']);
		$html =
<<<EOD
<article class="ip-article-md">
	<div class="article-thumbnail">
		<a href="{$pv}" title="{$post['btitle']}" target="_blank"> 
			<img src="{$post['cover_url']}" alt="[{$post['btitle']}]" class="popular-img">
		</a>
	</div>
	<div class="article-description">
		<h1>
			<a href="{$pv}" title="{$post['btitle']}" target="_blank">{$post['btitle']}</a>
		</h1>
		<footer>
			<span>
				<i class="fa fa-eye"></i>
				<span class="sr-only">Views</span>&nbsp;{$numViewed}
			</span>&nbsp;&nbsp;
			<span>
				<i class="fa fa-thumbs-o-up"></i>
				<span class="sr-only">Likes</span>&nbsp;{$numLiked}
			</span>&nbsp;&nbsp;
			<span>
				<i class="fa fa-comments-o"></i>
				<span class="sr-only">Comments</span>&nbsp;{$numCommented}
			</span>
		</footer>
	</div>
</article>
EOD;
		return $html;
	}
	
	static function genAjaxPopularHtml($post, $config=array('showview'=>true)) {
		$pv = _pv($post['pk_id'], $post['btitle'], $post['type']);
		$createdAt=get_tzlocalized_readable_time($post['created_at']);
		$numLiked = number_format($post['statistics']['num_liked']);
		$numViewed = get_readable_number($post['statistics']['num_viewed']);
		$numCommented = number_format($post['statistics']['num_commented']);
	
		$footerHtml = "<span><i class='fa fa-clock-o'></i>&nbsp;{$createdAt}</span>&nbsp;&nbsp;";
		if ($config['showview']) {
			$footerHtml .= "<span><i class='fa fa-eye'></i>&nbsp;{$numViewed}</span>&nbsp;&nbsp;";
		}
		if ($config['showlike']) {
			$footerHtml .= "<span><i class='fa fa-thumbs-o-up'></i>&nbsp;{$numLiked}</span>&nbsp;&nbsp;";
		}
		if ($config['showcomment']) {
			$footerHtml .= "<span><i class='fa fa-comments-o'></i>&nbsp;{$numCommented}</span>&nbsp;&nbsp;";
		}
	
		$html = <<<EOD
<article class="ip-article-md">
	<div class="article-thumbnail">
		<a href="{$pv}" title="{$post['btitle']}" target="_blank">
			<img class="popular-cover-img" alt="[{$post['btitle']}]" src="{$post['cover_url']}" />
		</a>
	</div>
	<div class="article-description">
		<h1 class="article-header-h1"><a href="{$pv}" title="{$post['btitle']}">{$post['btitle']}</a></h1>
		<footer>{$footerHtml}</footer>
	</div>
</article>
EOD;
		return $html;
	}
	
	static function genAjaxProfileHtml($post, $myProfile=false) {
		if ($myProfile) {
			$pv = _ppv($post['pk_id'], $post['btitle'], $post['type']);
		} else {
			$pv = _pv($post['pk_id'], $post['btitle'], $post['type']);
		}
		$createdAt=get_tzlocalized_readable_time($post['created_at']);
		$numLiked = number_format($post['statistics']['num_liked']);
		$numViewed = get_readable_number($post['statistics']['num_viewed']);
		$numCommented = number_format($post['statistics']['num_commented']);
		$closeIcon = media('close-icon.png', 'images');
		
		$html =
<<<EOD
<article class="ip-article-md">
	<div class="article-thumbnail">
		<figcaption><span class="sr-only">[{$post['btitle']}] Video</span></figcaption>
		<a href="{$pv}" target='_blank' title="{$post['btitle']}">
			<img class='profile-post-thumbnail'  alt="[{$post['btitle']}]" src="{$post['cover_url']}">
		</a>
	</div>
	<div class="article-description">
		<h1 class="article-header-h1">
			<a href="{$pv}" target='_blank' title="{$post['btitle']}">{$post['btitle']}</a>
		</h1>
		<footer>
			<span><i class="fa fa-clock-o"></i>&nbsp;{$createdAt}</span>
			<span><i class="fa fa-eye"></i>&nbsp;{$numViewed}</span>
		</footer>
	</div>
</article>
EOD;
		return $html;
	}
	
	static function genAjaxPostHtml($post) {
		$pv = _pv($post['pk_id'], $post['btitle'], $post['type']);
		$mp = _mp($post['fk_member_id'], $post['fk_user_name']);
		$chv = _chv($post['fk_channel_id'], $post['fk_channel_name']);
		$btitle = htmlentities($post['btitle']);
		$createdAt = get_tzlocalized_readable_time($post['created_at']);
		$numViewed = get_readable_number($post['statistics']['num_viewed']);
		$numLiked = number_format($post['statistics']['num_liked']);
		$numDisliked = number_format($post['statistics']['num_disliked']);
		$numCommented = number_format($post['statistics']['num_commented']);
		$libYt = m("LibYoutube");
		$ytVideoId = $libYt->parseYtVid($post['media']['src_from']);
		$ytMdThumbnail = $libYt->assembleYtThumbnailUri($ytVideoId, 'mq');
		$ytStdThumbnail = $libYt->assembleYtThumbnailUri($ytVideoId, 'sd');
		$ytEmbededUrl = $post['media']['src_from'] . 
			"?wmode=transparent&rel=0&controls=2&showinfo=0&modestbranding=1" .
			"&enablejsapi=1&origin=" . base_url();
		$likeActUrl = _ajax(array('a'=>'likepost', 'postid'=>$post['pk_id'],)); 
		$dislikeActUrl = _ajax(array('a'=>'dislikepost', 'postid'=>$post['pk_id'],));
		$bookmarkActUrl = _ajax(array('a'=>'bookmarkPost', 'postid'=>$post['pk_id'],));
		
		$html = <<<EOD
<article class="ip-article-big">
	<section class="ip-video-container">
		<div class="yt-video-player" data-pid="{$post['pk_id']}" onclick="playYtVideo(this, '{$ytEmbededUrl}')">
			<img class="most-recent-img" alt="[{$btitle}]" src="{$ytMdThumbnail}" />
			<div class="hd-thumbnail-holder" data-thumbnail-src="{$ytStdThumbnail}">
				<img class="dummy-stub" style="display:none;" />
			</div>
			<div class="btn-play"><i class="fa fa-play-circle-o"></i></div>
		</div>
	</section>
	<section class="ip-article-detail">
		<h1><a href="{$pv}" title="{$btitle}" target="_blank">{$btitle}</a></h1>
		<div class="detail-content-area">
			<div class="detail-info">
				<em>by</em>&nbsp;
				<a href="{$mp}" target="_blank"><strong>{$post['fk_user_name']}</strong></a>
			</div>
			<div class="detail-info">
				&nbsp;·&nbsp;<em>channel:</em>&nbsp;
				<a href="{$chv}" target="_blank"><strong>{$post['fk_channel_name']}</strong></a>
			</div>
			<div class="detail-info">
				&nbsp;·&nbsp;<em><i class="fa fa-clock-o"></i></em>&nbsp;
				<strong>{$createdAt}</strong>
			</div>
			<div class="detail-info">
				&nbsp;·&nbsp;<em><i class="fa fa-eye"></i></em>&nbsp;
				<strong>{$numViewed} views</strong>
			</div>
		</div>
	</section>
	<section class="ip-article-toolbar" role="toolbar">
		<div class="left-action-bar" id="post-actionbox-{$post['pk_id']}">
			<div class="like-action-box">
				<button class="btn btn-default btn-like" onclick="likePost({$post['pk_id']});">
					<i class="fa fa-thumbs-up"></i><span class="sr-only">Like</span>
				</button>
				<div class="box-speech-bubble cnt-like">{$numLiked}</div>
			</div>
			<div class="dislike-action-box">
				<button class="btn btn-default btn-dislike" onclick="dislikePost({$post['pk_id']});">
					<i class="fa fa-thumbs-down"></i><span class="sr-only">Dislike</span>
				</button>
				<div class="box-speech-bubble cnt-dislike">{$numDisliked}</div>
			</div>
			<div class="comment-action-box"> 
				<button class="btn btn-default btn-comment" onclick="window.open('{$pv}#comments')">
					<i class="fa fa-comment"></i><span class="sr-only">Comments</span>
				</button>
				<div class="box-speech-bubble cnt-comment">{$numCommented}</div>
			</div>
			<div class="bookmark-action-box">
				<button class="btn btn-default btn-bookmark" onclick="bookmarkPost({$post['pk_id']});">
					<i class="fa fa-bookmark"></i><span class="sr-only">Bookmark</span>
				</button>
			</div>
		</div>
		<div class="addthis_toolbox addthis_default_style addthis_32x32_style" 
			addthis:url="{$pv}" addthis:title="{$btitle}">
			<a class="addthis_button_facebook"></a>
			<a class="addthis_button_twitter"></a>
			<a class="addthis_button_email"></a>
			<a class="addthis_button_more"></a>
		</div>
	</section>
</article>
EOD;
		return $html;
	}
	
	static function genAjaxChannelPreviewPostHtml($post) {
		$pv = _pv($post['pk_id'], $post['btitle'], $post['type']);
		$createdAt = get_tzlocalized_readable_time($post['created_at']);
		$btitle = htmlentities($post['btitle']);
		
		$html = <<<EOD
<article class="ip-article-md">
	<div class="article-thumbnail">
		<a href="{$pv}" title="{$btitle}" target="_blank">
			<img class="channel-post-cover-img" alt="[{$btitle}]" src="{$post['cover_url']}"  />
		</a>
	</div>
	<div class="article-description">
		<h1 class="article-header-h1">
			<a href="{$pv}"  title="{$btitle}" target="_blank">{$btitle}</a>
		</h1>
		<footer>
			<span><i class="fa fa-clock-o"></i>&nbsp;{$createdAt}</span>
		</footer>
	</div>
</article>
EOD;
		return $html;
	}
}