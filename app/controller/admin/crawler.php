<?php

class Crawler extends AdminController{
	
	function youtube() {
		$availableTabs = array('channels', 'videos');
		$tab = $this->app->getRequest('tab', 'channels');
		if (!in_array($tab, $availableTabs)) {
			redirect(_404());
		}
		
		$result = array( 
			'tab'=>$tab, 'availableTabs'=>$availableTabs, 
			'pageJs'=>array('bootstrap-paginator.min.js'),
		);
		if ($tab == 'videos') {
			$data = $this->_yt_tab_videos();
			return array_merge($result, $data);
		} else {
			$data = $this->_yt_tab_channels();
			return array_merge($result, $data);
		}
	}
	
	function delete_ytchannel() {
		$ytChannelId = $this->app->getRequest('chid', '');
		if (!empty($ytChannelId)) {
			m('LibCrawler')->adminYtChannels(array($ytChannelId), -1);
		}
		redirect(get_redirect_url());
	}
	
	function refresh_ytchannel() {
		$ytChannelId = $this->app->getRequest('chid', '');
		if (!empty($ytChannelId)) {
			m('LibCrawler')->refreshYtChannels(array($ytChannelId));
		}
		redirect(get_redirect_url());
	}
	
	private function _yt_tab_channels() {
		if (!empty($_POST['add_ytchannels'])) {
			$forYtUsernames = $_POST['for_ytusers'];
			$forYtUsernames = array_filter(explode(',', $forYtUsernames));
			$channelIds = $_POST['chids'];
			$channelIds = array_filter(explode(',', $channelIds));
			
			if (!empty($channelIds) || !empty($forYtUsernames)) {
				$filterCond = array('ytUsernames'=>array_unique($forYtUsernames));
				if (!empty($channelIds)) $filterCond = array('ytChannelIds'=>array_unique($channelIds));
				
				set_time_limit(0);
				m('LibCrawler')->crawlYtChannels($filterCond);
			}
		}
		
		$pager = null; 
		$condition = array('order'=>'updated_on DESC', 'where'=>array('status'=>1));
		$ytChannels = m('CrawlerYtChannels')->getPaginatedChannels($condition, 25, $pager);
		return array('ytChannels'=>$ytChannels, 'pager'=>$pager);
	}
	
	private function _yt_tab_videos() {
		$this->view = 'admin/crawler/crawling';
		
		$trigger = $this->app->getRequest('trigger', 'filter');
		$ytvids = $this->app->getRequest('ytvids', '');
		$ytChannels = m('CrawlerYtChannels')->find(array('where'=>array('status'=>1)));
		$where = array('status >='=>0); $pager = null; 
		$result = array('ytChannels'=>$ytChannels);
		
		if (!empty($ytvids)) { 
			$ytvids = array_unique(array_filter(explode(',', $ytvids)));
			$result['ytvids'] = $where['pk_ytvid'] = $ytvids;
			
			if ($trigger == 'crawl') { 
				set_time_limit(0); 
				m('LibCrawler')->crawYtVideos(array('ytVideoIds'=>$ytvids));
			} else if ($trigger == 'refresh') {
				set_time_limit(0); 
				m('LibCrawler')->refreshYtVideos(array('sqlCond'=>array('where'=>$where)));
			}
		} else {
			$channel = $this->app->getRequest('channel', '');
			$start = $startDate = urldecode($this->app->getRequest('start', ''));
			$end = $endDate = urldecode($this->app->getRequest('end', ''));
			if (!empty($startDate)) {
				$startDate = get_utctime_from_localtz($startDate, '%Y-%m-%d %H:%i:%s');
				$where['published_at >'] = $startDate;
			}
			if (!empty($endDate)) {
				$endDate = get_utctime_from_localtz($endDate, '%Y-%m-%d %H:%i:%s');
				$where['published_at <='] = $endDate;
			}
			if (!empty($channel)) $where['yt_chid'] = $channel;
			$result['ytchannel'] = $channel;
			
			if ($trigger == 'crawl') {
				$result['threshode'] = $threshode = intval($this->app->getRequest('threshode', 0));
				$result['maxresult'] = $maxResult = intval($this->app->getRequest('maxResult', 0));
				$result['iterate'] = $iterate = intval($this->app->getRequest('iterate', 0));
				$result['queryparam'] = $queryParam = $this->app->getRequest('q', '');
				$crawlChannelIds = array();
				if (!empty($channel)) {
					$crawlChannelIds[] = $channel;
				} else {
					foreach($ytChannels as $ytChannel) {
						$crawlChannelIds[] = $ytChannel['pk_channel_id'];
					}
				}
				
				$filterCond = array(
					'ytChannelIds'=>array_unique($crawlChannelIds), 'threshode'=>$threshode, 'maxResult'=>$maxResult,
					'category'=>'uploads', 'iterate'=>$iterate, 'q'=>$queryParam,
				);
				set_time_limit(0); m('LibCrawler')->crawlYtChannelVideos($filterCond);
			} else {
				$result['start'] = $start; $result['end'] = $end;
				if ($trigger == 'refresh') {
					set_time_limit(0); m('LibCrawler')->refreshYtVideos(array('sqlCond'=>array('where'=>$where)));
				}
			}
		}
		
		$condition = array('order'=>'published_at DESC', 'where'=>$where);
		$ytVideos = m('CrawlerYtVideos')->getPaginatedVideos($condition, 25, $pager, true);
		$result['baseUrl'] = _admin(array('c'=>'crawler', 'a'=>'youtube', 'tab'=>'videos'));
		$result['ytVideos'] = $ytVideos; $result['pager'] = $pager;
		return $result;
	}
}
