<?php

/**
 * Youtube SDK封装
 * youtube.php
 */
class YouTube {
	var $sdkGoogle;
	var $serviceYt;
	
	function __construct() {
		$this->sdkGoogle = new Corex_Google();
		$this->serviceYt = new Google_Service_YouTube($this->sdkGoogle->client);
	}
	
	function getYtVideoInfo($parameters, $part='snippet') {
		return $this->serviceYt->videos->listVideos($part, $parameters);
	}
	
	function getYtChannelInfo($parameters, $part='snippet') {
		return $this->serviceYt->channels->listChannels($part, $parameters);
	}
	
	function getYtChannelListItemsInfos($parameters, $part='snippet', $iterate=false) {
		if (intval($parameters['maxResults']) <= 0) $parameters['maxResults'] = 50;
		
		$rs = $this->serviceYt->playlistItems->listPlaylistItems($part, $parameters); 
		$result[] = $rs;
		if ($iterate && !empty($rs['nextPageToken'])) {
			$pageToken = $rs['nextPageToken'];
			while(!empty($pageToken)) {
				$parameters['pageToken'] = $pageToken;
				$rs = $this->serviceYt->playlistItems->listPlaylistItems($part, $parameters);
				$pageToken = $rs['nextPageToken']; $result[] = $rs;
			}
		}
		
		return empty($result) ? array() : $result;
	}
	
	function getYtSearchListItemsInfo($parameters, $part='snippet', $iterate=false) {
		if (intval($parameters['maxResults']) <= 0) $parameters['maxResults'] = 50;
		
		$rs = $this->serviceYt->search->listSearch($part, $parameters);
		$result[] = $rs;
		if ($iterate && !empty($rs['nextPageToken'])) {
			$pageToken = $rs['nextPageToken'];
			while(!empty($pageToken)) {
				$parameters['pageToken'] = $pageToken;
				$rs = $this->serviceYt->search->listSearch($part, $parameters);
				$pageToken = $rs['nextPageToken']; $result[] = $rs;
			}
		}
		return empty($result) ? array() : $result;
	}
}