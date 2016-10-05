<?php

class LibPost {
	function __construct() {
	}
	
	function updatePost($post, $formData, &$err=null) {
		// flags.
		$nsfw = $formData['is_nsfw'];
		$nsfw = !isset($nsfw) ? 0 : $nsfw;
		$original = $formData['is_original'];
		$original = !isset($original) ? 1 : $original;
		$status = isset($formData['status']) ? $formData['status'] : $post['status'];
		
		// title & description
		$title = $formData['title'];
		$description = $formData['description'];
		$bmore = 0b00; 
		
		$btitle = $title;
		if (mb_strlen($title) > 128) {
			$btitle = mb_substr($title, 0, 128, 'UTF-8');
			$bmore |= 0b01;
		} else $title = '';
		
		$bdescription = $description;
		if (mb_strlen($description) > 512) {
			$bdescription = mb_substr($description, 0, 512, 'UTF-8');
			$bmore |= 0b10;
		} else $description = '';
		
		// channel
		$channelId = $formData['channel'];
		$channelName = $post['fk_channel_name'];
		if ($post['fk_channel_id'] != $channelId) {
			$cond = array('where'=>array('pk_id'=>$channelId));
			$channel = end(m('LibChannel')->getChannels($cond));
			if (empty($channel)) {
				$err = 'Invalid channel selected.'; 
				return false;
			} else $channelName = $channel['name'];
		}
		
		// user id & user name
		$memberId = $formData['userid'];
		$memberName = $formData['username'];
		if (empty($memberId) && empty($memberName)) {
			$memberId = $post['fk_member_id']; $memberName = $post['fk_user_name'];
		} else {
			$where = array('pk_id'=>$memberId, 'username'=>$memberName);
			$condition = array('where'=>array_filter($where));
			$member = end(m('members')->find($condition));
			$memberId = $member['pk_id']; $memberName = $member['username'];
		}
		
		if (empty($memberId)) $memberId = $post['fk_member_id'];
		$memberName = $formData['username'];
		if (empty($memberName)) $memberName = $post['fk_user_name'];
		
		// update post
		$condition = array('where'=>array('pk_id'=>$post['pk_id']));
		$data = array(
			'is_nsfw'=>$nsfw, 'is_original'=>$original, 'status'=>$status,
			'btitle'=>$btitle, 'bdescription'=>$bdescription, 'bmore'=>$bmore,
			'fk_channel_id'=>$channelId, 'fk_channel_name'=>$channelName,
			'fk_member_id'=>$memberId, 'fk_user_name'=>$memberName, 
		);
		$mPostsModel = m('posts');
		if($mPostsModel->update($data, $condition) === false) {
			$err = $mPostsModel->getError(); 
			return false;
		}
		
		// update post detail
		$condition = array('where'=>array('fk_post_id'=>$post['pk_id']));
		//if ($bmore != $post['bmore']) {
		$postDetails = array('title'=>$title, 'description'=>$description);
		//}
		$mPostDetails = m('PostDetails');
		if($mPostDetails->update($postDetails, $condition) === false) {
			$err = $mPostDetails->getError();
			return false;
		}
		
		return true;
	}

	function newPost($formData, &$err=null) {
		$title = $formData['title'];
		$description = $formData['description'];
		$channelId = $formData['channel'];
		
		$nsfw = $formData['is_nsfw'];
		$nsfw = !isset($nsfw) ? 0 : $nsfw;
		$original = $formData['is_original'];
		$original = !isset($original) ? 1 : $original;
		
		$memberId = $formData['userid'];
		$memberName = $formData['username'];
		if (empty($memberId) && empty($memberName)) {
			$memberId = ME::id(); $memberName = ME::name();
		} else {
			$where = array('pk_id'=>$memberId, 'username'=>$memberName);
			$condition = array('where'=>array_filter($where));
			$member = end(m('members')->find($condition));
			$memberId = $member['pk_id']; $memberName = $member['username'];
		}
		
		$now = mdate('%Y-%m-%d %H:%i:%s');
		$createDate = !empty($formData['created_at']) ? $formData['created_at'] : $now;
		$status = isset($formData['status']) ? $formData['status'] : 0;
		
		$srcType = $formData['src_type'];
		$ytVid = $formData['ytv_id'];
		$ytWatchUrl = m('LibYoutube')->assembleYtWatchUri($ytVid);
		$ytEmbedUrl = m('LibYoutube')->assembleYtEmbededUri($ytVid);
		$ytThumbUrl = m('LibYoutube')->assembleYtThumbnailUri($ytVid);
		
		// statistics
		$statistics = $formData['statistics'];
		if (empty($statistics)) {
			$sdkYouTube = new Corex_YouTube();
			$videoInfo = $sdkYouTube->getYtVideoInfo(array('id'=>$ytVid), 'statistics');
			if (empty($videoInfo)) {
				$err = "Invalid YouTube video id.";
				return false;
			}
			$statistics = end($videoInfo->getItems())['statistics'];
		}
		
		// media
		$media = array(
			'fk_member_id'=>$memberId, 'created_at'=>$now,
			'src_type'=>$srcType, 'src_from'=>$ytEmbedUrl,
		);
		$mMedias = m('medias');
		$mediaId = $mMedias->add($media);
		if (empty($mediaId)) {
			$err = $mMedias->getError();
			return;
		}
		
		// post
		$bmore = 0b00;
		$btitle = $title;
		if (mb_strlen($title) > 128) {
			$btitle = mb_substr($title, 0, 128, 'UTF-8');
			$bmore |= 0b01;
		}
		$bdescription = $description;
		if (mb_strlen($description) > 512) {
			$bdescription = mb_substr($description, 0, 512, 'UTF-8');
			$bmore |= 0b10;
		}
		
		$cond = array('where'=>array('pk_id'=>$channelId));
		$channel = end(m('LibChannel')->getChannels($cond));
		if (empty($channel)) {
			$err = 'Invalid channel selected.';
			return;
		}
		
		$post = array(
			'btitle'=>$btitle, 'bdescription'=>$bdescription, 'bmore'=>$bmore, 
			'fk_channel_id'=>$channel['pk_id'], 'fk_channel_name'=>$channel['name'],
			'fk_member_id'=>$memberId, 'fk_user_name'=>$memberName, 'status'=>$status,
			'media_ids'=>$mediaId, 'cover_url'=>$ytThumbUrl, 'created_at'=>$createDate,
			'is_nsfw'=>$nsfw, 'is_original'=>$original, 'type'=>'Video',
		);
		
		$mPosts = m('Posts');
		$postId = $mPosts->add($post);
		if (empty($postId)) {
			$err = $mPosts->getError();
			return;
		}
		
		// Post detail
		$postDetails = array(
			'fk_post_id'=>$postId, 'src_type'=>$srcType, 'src_from'=>$ytWatchUrl,
		);
		if (($bmore & 0b01) > 0) { $postDetails['title'] = $title; }
		if (($bmore & 0b10) > 0) { $postDetails['description'] = $description; }
		$mPostDetails = m('PostDetails');
		if(!$mPostDetails->add($postDetails)) {
			$err = $mPostDetails->getError();
			return;
		}
		
		// Post statistics.
		$postStatistics = array(
			'fk_post_id'=>intval($postId), 'created_at'=>$createDate,
			'default_viewed'=>intval($statistics['viewCount']),
			'default_liked'=>intval($statistics['likeCount']),
			'default_disliked'=>intval($statistics['dislikeCount']),
			'default_favorited'=>intval($statistics['favoriteCount']),
			'default_commented'=>intval($statistics['commentCount']),
		);
		$mPostStatistics = m('PostStatistics');
		if(!$mPostStatistics->add($postStatistics)) {
			$err = $mPostStatistics->getError();
			return;
		}
		
		$post['pk_id'] = $postId;
		$post['medias'][] = $media;
		$post['details'] = $postDetails;
		$post['statistics'] = $postStatistics;
		
		return $post;
	}
	
	/**
	 * 获取按时间排序的三个相邻帖子id列表
	 * @param int $mid_postid 中间帖子的id
	 * @return array 按照时间排序的三个相邻的帖子id.
	 */
	function getChronologyAdjPosts($mid_postid, $cfg=array()) {
		$config = array();
		if (!empty($cfg)) {
			foreach($cfg as $cfgKey=>$cfgVal) {
				if (strpos($cfgKey, '!=') === false) {
					$config[] = "`{$cfgKey}` = {$cfgVal}";
				} else {
					$cfgKey = trim(str_replace('!=', '', $cfgKey));
					$config[] = "`{$cfgKey}` != {$cfgVal}";
				}
			}
			$config = implode(' AND ', $config);
		} else $config = 1;
		
		$sql1 = <<<EOD
SELECT `pk_id` FROM posts WHERE {$config} AND `created_at` >=
	(SELECT MAX(`created_at`) FROM posts WHERE {$config} AND `created_at` <
		(SELECT `created_at` FROM posts WHERE `pk_id` = {$mid_postid} AND {$config}))
	ORDER BY `created_at` ASC
	LIMIT 3
EOD;
		
		$sql2 = <<<EOD
SELECT `pk_id` FROM posts WHERE {$config} AND `created_at` >=
	(SELECT `created_at` FROM posts WHERE `pk_id` = {$mid_postid} AND {$config})
	ORDER BY `created_at` ASC
	LIMIT 2
EOD;
		
		$adjPosts = array(); $isMPostOldest = false;
		if(Db::getInstance()->query($sql1)) {
			$adjPosts = Db::getInstance()->getAll();
		}
		if (count($adjPosts) == 0 && Db::getInstance()->query($sql2)) {
			$adjPosts = Db::getInstance()->getAll();
			if (!empty($adjPosts)) $isMPostOldest = true;
		}
		
		switch(count($adjPosts))
		{
		case 0: //the mid postid doesn't exist.
			break;
		case 1: //the mid postid is the first post.
			array_unshift($adjPosts, array('pk_id'=>0));
			array_push($adjPosts, array('pk_id'=>0));
			break;
		case 2: //the mid postid is the last post or not.
			if (!$isMPostOldest) array_push($adjPosts, array('pk_id'=>0));
			else array_unshift($adjPosts, array('pk_id'=>0));
			break;	
		case 3: // the mid postid is in the middle.
			break;
		}
		$result = array();
		if (!empty($adjPosts)) {
			$where = array();
			foreach($adjPosts as $post) {
				if ($post['pk_id'] > 0) {
					$where['pk_id'][] = $post['pk_id'];
				}
			}
			$posts = m('Posts')->getPosts(array('where'=>$where), true, true);
			foreach($adjPosts as $adjPost) {
				$bingo = false;
				foreach($posts as $post) {
					if ($adjPost['pk_id'] == $post['pk_id']) {
						$result[] = $post;
						$bingo = true;
						break;
					}
				}
				if (!$bingo) $result[] = null;
			}
		}
		return $result;
	}
	
	/**
	 * 获取和指定帖子相关的类似帖子
	 * @param array $post
	 * @return array 和指定帖子相关的类似帖子
	 */
	function getSimilarPosts($post, $num=15) {
		$result = array();
		$sql = <<<EOD
SELECT `pk_id` FROM `posts` WHERE `status` = 1 AND `pk_id` IN 
	(SELECT DISTINCT `fk_post_id` FROM `post_tags`
		WHERE `fk_post_id` !={$post['pk_id']} AND `fk_tag_id` IN 
			(SELECT `fk_tag_id` FROM `post_tags` WHERE `fk_post_id`={$post['pk_id']}))
	ORDER BY RAND()
	LIMIT {$num}; 
EOD;
		$taggedPosts = array();
		if(Db::getInstance()->query($sql)) {
			$taggedPosts = Db::getInstance()->getAll();
		}
		
		$channelPosts = array();
		$remain = $num - count($taggedPosts);
		if ($remain > 0) { // find the same channel posts.
			$excludePids = array_column($taggedPosts, 'pk_id');
			$excludePids = array_merge($excludePids, array($post['pk_id']));
			$where = array(
				'pk_id !='=>$excludePids, 'status'=>1,
				'fk_channel_id'=>$post['fk_channel_id'],
			);
			$condition = array(
				'field'=>'pk_id', 'where'=>$where, 
				'limit'=>$remain, 'order'=>'RAND()',
			);
			$channelPosts = m('Posts')->find($condition);
		}
		
		$postIds = array_merge($taggedPosts, $channelPosts);
		$postIds = array_column($postIds, 'pk_id');
		if (!empty($postIds)) {
			$condition = array('where'=>array('pk_id'=>$postIds));
			$result = m('Posts')->getPosts($condition);
		}
		
		return $result;
	}
	
	static function genQuickNavbox($post, $type="Prev") {
		$btnClass = 'fa-chevron-left';
		if (strcasecmp($type, 'prev') != 0) {
			$btnClass = 'fa-chevron-right';
		}
		
		$postUrl = '';
		$anchorClickAct = $btnClickAct = 'return false';
		if (!empty($post)) {
			$postUrl = _pv($post['pk_id'], $post['btitle'], $post['type']);
			$btnClickAct = "window.location='{$postUrl}'";
			$anchorClickAct = 'return true';
		}
		
		$title = "This is the end!";
		$titleCls = "tip-no-more";
		if (!empty($post)) {
			$title = $post['btitle'];
			$titleCls = '';
		}
		
		$cover = $post['cover_url'];
		if (empty($cover)) {
			$cover = media('navbox-theend.png', 'images');
		}
		
		$html = <<<EOD
<div class="nav-box">
	<button class="button btn-sm btn-quick-nav" onclick="{$btnClickAct}">
		<span><i class="fa {$btnClass} fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;{$type}</span>
	</button>
	<section class="nav-thumbnail">
		<a href="{$postUrl}" onclick="{$anchorClickAct}" title="{$title}">
			<img class="navbox-image" alt="[$title]" src="{$cover}" />
		</a>
		<h1 class="nav-thumbnail-title {$titleCls}">
			<a href="{$postUrl}" onclick="{$anchorClickAct}" title="{$title}">{$title}</a>
		</h1>
	</section>
</div>
EOD;
		return $html;
	}
	
}