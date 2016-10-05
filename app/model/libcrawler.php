<?php

class LibCrawler {
	
	function crawlYtChannelVideos($filterCond, &$err=NULL) {
		$ytChannelIds = $filterCond['ytChannelIds'];
		if (!empty($filterCond['q'])) {
			return $this->crawlYtQuerySearchVideos($filterCond, $err);
		}
		
		$playlistCategory = $filterCond['category'];
		if(empty($playlistCategory)) $playlistCategory = 'uploads';
		$condition = array(
			'field'=>'playlist_uploads, playlist_likes, playlist_favorites', 
			'where'=>array('pk_channel_id'=>$ytChannelIds),
		);
		$ytPlaylists = m("CrawlerYtChannels")->find($condition);
		$ytPlaylistIds = array();
		foreach($ytPlaylists as $playlist) {
			$ytPlaylistIds[] = $playlist["playlist_{$playlistCategory}"];
		}
		
		$plFilterCond = array('ytPlaylistIds'=>array_unique($ytPlaylistIds),);
		$plFilterCond = array_merge($plFilterCond, $filterCond);
		return $this->crawlYtPlaylistVideos($plFilterCond, $err);
	}
	
	function crawlYtQuerySearchVideos($filterCond, &$err=NULL) {
		$ytChannelIds = $filterCond['ytChannelIds'];
		$queryTerms = $filterCond['q'];
		$maxResult = $filterCond['maxResult'];
		$maxResult = ($maxResult > 0) ? $maxResult : 50;
		$threshode = $filterCond['threshode'];
		$threshode = ($threshode > 0) ? $threshode : 10000;
		$iterate = $filterCond['iterate'];
		
		// Retrieve query-terms search video ids.
		$sdkYoutube = new Corex_YouTube(); $ytVideoIds = array();
		foreach($ytChannelIds as $ytChannelId) {
			if (count($ytVideoIds) >= $threshode) break;
			
			$parameters = array(
				'channelId'=>$ytChannelId, 'q'=>$queryTerms, 'maxResults'=>$maxResult, 
				'type'=>'video', 'videoEmbeddable'=>'true',
			);
			$searchListInfos = $sdkYoutube->getYtSearchListItemsInfo($parameters, 'id', $iterate);
			foreach($searchListInfos as $searchListInfo) {
				$searchListItems = $searchListInfo->getItems();
				foreach($searchListItems as $item) {
					$ytVideoIds[] = $item['id']['videoId'];
				}
			}
		}
		
		// Crawl YouTube videos
		$filterCond['ytVideoIds'] = $ytVideoIds;
		return $this->crawYtVideos($filterCond, $err);
	}
	
	function crawlYtPlaylistVideos($filterCond, &$err=NULL) {
		$maxResult = $filterCond['maxResult'];
		$maxResult = ($maxResult > 0) ? $maxResult : 50;
		$threshode = $filterCond['threshode']; 
		$threshode = ($threshode > 0) ? $threshode : 10000;
		$ytPlaylistIds = $filterCond['ytPlaylistIds'];
		$start = $filterCond['start']; $end = $filterCond['end'];
		$iterate = $filterCond['iterate'];
		
		// Retrieve playlist video ids.
		$sdkYoutube = new Corex_YouTube(); $ytVideoIds = array();
		foreach($ytPlaylistIds  as $playlistId) {
			if (count($ytVideoIds) >= $threshode) break;
			
			$parameters = array('playlistId'=>$playlistId, 'maxResults'=>$maxResult);
			$playlistInfos = $sdkYoutube->getYtChannelListItemsInfos($parameters, 'contentDetails', $iterate);
			foreach($playlistInfos as $playlistInfo) {
				$playlistItems = $playlistInfo->getItems();
				foreach($playlistItems as $item) {
					$ytVideoIds[] = $item['contentDetails']['videoId'];
				}
			}
		}
		
		// Crawl YouTube videos
		$filterCond['ytVideoIds'] = $ytVideoIds;
		return $this->crawYtVideos($filterCond, $err);
	}
	
	function crawYtVideos($filterCond, &$err=NULL) {
		$ytVideoIds = $filterCond['ytVideoIds'];
		$maxResult = $filterCond['maxResult'];
		$maxResult = ($maxResult > 0) ? $maxResult : 50;
		
		// Fetch already saved video ids.
		$mCrawlerYtVideosModel = m('CrawlerYtVideos');
		$condition = array('field'=>'pk_ytvid', 'where'=>array('pk_ytvid'=>$ytVideoIds), );
		$savedYtVideos = $mCrawlerYtVideosModel->find($condition);
		$savedYtVideoIds = array_column($savedYtVideos, 'pk_ytvid');
		$toCrawlYtVideoIds = array_diff($ytVideoIds, $savedYtVideoIds);
	
		// Crawl YouTube videos.
		$sdkYoutube = new Corex_YouTube(); $crawledYtVideos = array();
		$toCrawlYtVideoIdChunks = array_chunk($toCrawlYtVideoIds, $maxResult);
		foreach($toCrawlYtVideoIdChunks as $videoIdChunk) {
			$videoInfo = $sdkYoutube->getYtVideoInfo(
				array('id'=>implode(',', $videoIdChunk)), 'snippet,statistics'
			);
			$videoItems = $videoInfo->getItems();
			foreach($videoItems as $item) $crawledYtVideos[] = $item;
		}
		
		// Save crawled YouTube videos to db.
		foreach ($crawledYtVideos as $video) {
			$dummyUsers = c('dummy_users'); $user = $dummyUsers[array_rand($dummyUsers)];
			$snippet = $video['snippet']; $statistics = $video['statistics']; $videoId = $video['id'];
			$channelId = $snippet['channelId']; $channelTitle = $snippet['channelTitle'];
			$createDate = mdate('%Y-%m-%d %H:%i:%s', strtotime($snippet['publishedAt']));
			$now = mdate('%Y-%m-%d %H:%i:%s');
			$guessChannel = m('LibChannel')->guessChannel(array('title'=>$snippet['title']));
			$guessChid = $guessChannel['chid'];
			$post = array(
				'title'=>$snippet['title'], 'description'=>$snippet['description'],
				'channel'=>$guessChid, 'is_nsfw'=>0, 'is_original'=>0, 'created_at'=>$createDate,
				'ytv_id'=>$videoId, 'src_type'=>'YouTube', 'status'=>0,
				'userid'=>$user['userid'], 'username'=>$user['username'],
				'statistics'=>$statistics,
			);
			$dbPost = m('LibPost')->newPost($post, $err);
			if (empty($dbPost)) return false;
	
			$data = array(
				'pk_ytvid'=>$videoId, 'fk_post_id'=>$dbPost['pk_id'],
				'yt_chid'=>$channelId, 'yt_chtitle'=>$channelTitle,
				'published_at'=>$createDate, 'crawled_at'=>$now,
			);
			if(!$mCrawlerYtVideosModel->add($data)) {
				$err = $mCrawlerYtVideosModel->getError(); return false;
			}
		}
		
		return true;
	}
	
	function refreshYtVideos($filterCond, &$err=NULL) {
		$sqlCond = $filterCond['sqlCond']; $pager = null; 
		$mPostStatistics = m('PostStatistics'); 
		$mCrawlerYtVideosModel = m('CrawlerYtVideos');
		$sdkYoutube = new Corex_YouTube();
		
		$oldPage = $_REQUEST['page'];
		do {
			$toCrawlYtVideos = array();
			$_REQUEST['page'] = max(array(1, $pager['nextPage'])); 
			$ytVideos = $mCrawlerYtVideosModel->getPaginatedVideos($sqlCond, 50, $pager, false);
			foreach($ytVideos as $ytVideo) {
				$toCrawlYtVideos[$ytVideo['pk_ytvid']] = $ytVideo;
			}
			$ytVids = array_unique(array_keys($toCrawlYtVideos)); 
			$ytVids = implode(',', $ytVids);
			
			$videoInfo = $sdkYoutube->getYtVideoInfo(array('id'=>$ytVids), 'statistics');
			$videoInfoItems = $videoInfo->getItems();
			foreach($videoInfoItems as $item) {
				$ytVideo = $toCrawlYtVideos[$item->getId()];
				$statistics = $item['statistics']; 
				$postId = $ytVideo['fk_post_id'];
				
				$updateData = array(
					'default_viewed'=>intval($statistics['viewCount']),
					'default_liked'=>intval($statistics['likeCount']),
					'default_disliked'=>intval($statistics['dislikeCount']),
					'default_favorited'=>intval($statistics['favoriteCount']),
					'default_commented'=>intval($statistics['commentCount']),
				);
				$where = array('fk_post_id'=>intval($postId),);
				if($mPostStatistics->update($updateData, array('where'=>$where)) === false) {
					$err = $mPostStatistics->getError();
					$_REQUEST['page'] = $oldPage;
					return false;
				}
			}
		} while($pager['nextPage'] > 0);
		$_REQUEST['page'] = $oldPage;
		return true;
	}
	
	function adminYtChannels($ytChannelIds, $status, &$err=NULL) {
		$ytChannelsModel = m("CrawlerYtChannels");
		$data = array('status'=>$status);
		$condition = array('where'=>array('pk_channel_id'=>$ytChannelIds));
		
		if ($ytChannelsModel->update($data, $condition) === false) {
			$err = $ytChannelsModel->getError();
			return false;
		}
		
		return true;
	}
	
	function refreshYtChannels($ytChannelIds, &$err=NULL) {
		$ytChannelsModel = m("CrawlerYtChannels");
		$sdkYoutube = new Corex_YouTube();
		
		$ytChannelIds = array_unique($ytChannelIds);
		$ytChannelIds = implode(',', $ytChannelIds);
		
		$channelInfo = $sdkYoutube->getYtChannelInfo(array('id'=>$ytChannelIds), 'statistics');
		if (!empty($channelInfo)) {
			$channelItems = $channelInfo->getItems();
			foreach($channelItems as $channelItem) {
				$id = $channelItem->id; $statistics = $channelItem['statistics'];
				$updateDate = mdate('%Y-%m-%d %H:%i:%s');
				
				$condition = array('where'=>array('pk_channel_id'=>$id));
				$channel = array( 
					'updated_at'=>$updateDate,
					'views_count'=>$statistics['viewCount'],
					'videos_count'=>$statistics['videoCount'],
					'comments_count'=>$statistics['commentCount'],
					'subscribers_count'=>$statistics['subscriberCount'],
				);
				
				if($ytChannelsModel->update($channel, $condition) === false) {
					$err = $ytChannelsModel->getError();
					return false;
				}
			}
		}
		
		return true;
	}
	
	function crawlYtChannels($filterCond, &$err=NULL) {
		$ytChannelsModel = m("CrawlerYtChannels"); 
		$sdkYoutube = new Corex_YouTube();
		
		$ytUsernames = $filterCond['ytUsernames'];
		$ytChannelIds = $filterCond['ytChannelIds'];
		$ytChannels = array();
		if (!empty($ytUsernames)) {
			// Fetch youtube channels by username(s)
			foreach($ytUsernames as $forUsername) {
				$channelInfo = $sdkYoutube->getYtChannelInfo(
					array('forUsername'=>$forUsername), 'snippet,statistics,contentDetails'
				);
				if (!empty($channelInfo)) {
					$channelItem = end($channelInfo->getItems());
					$channelItem['snippet']['forUsername'] = $forUsername;
					$ytChannels[] = $channelItem;
				}
			}
		} else if (!empty($ytChannelIds)) {
			// Fetch YouTube channels by channel id(s)
			$ytChannelIds = implode(',', $ytChannelIds);
			$channelInfo = $sdkYoutube->getYtChannelInfo(
				array('id'=>$ytChannelIds), 'snippet,statistics,contentDetails'
			);
			if (!empty($channelInfo)) {
				$ytChannels = $channelInfo->getItems();
			}
		}
		
		foreach($ytChannels as $item) {
			$snippet = $item['snippet']; $statistics = $item['statistics']; 
			$contentDetails = $item['contentDetails'];
			
			$id = $item->id; $username = $snippet['forUsername'];
			$relatedPlaylists = $item['contentDetails']['relatedPlaylists'];
			
			helper('datetime');
			$pubDate = mdate('%Y-%m-%d %H:%i:%s', strtotime($snippet['publishedAt']));
			$updateDate = mdate('%Y-%m-%d %H:%i:%s');
			
			$channel = array(
				'pk_channel_id'=>$id, 'for_username'=>$username, 'status'=>1,
		
				'title'=>$snippet['title'], 'description'=>$snippet['description'],
				'thumbnail'=>$snippet['thumbnails']['medium']['url'],
				'published_at'=>$pubDate, 'updated_at'=>$updateDate,
		
				'views_count'=>$statistics['viewCount'],
				'videos_count'=>$statistics['videoCount'],
				'comments_count'=>$statistics['commentCount'],
				'subscribers_count'=>$statistics['subscriberCount'],
		
				'playlist_likes'=>$relatedPlaylists['likes'],
				'playlist_favorites'=>$relatedPlaylists['favorites'],
				'playlist_uploads'=>$relatedPlaylists['uploads'],
			);
				
			$condition = array('where'=>array('pk_channel_id'=>$id));
			$records = $ytChannelsModel->find($condition);
			if (!empty($records)) {
				if($ytChannelsModel->update($channel, $condition) === false) {
					$err = $ytChannelsModel->getError();
					return false;
				}
			} else if(!$ytChannelsModel->add($channel)) {
				$err = $ytChannelsModel->getError();
				return false;
			}
		}
		
		return true;
	}
}