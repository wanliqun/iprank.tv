<?php

class LibYoutube {
	/**
	 * Get the youtube video id by the watch video url.
	 * @param string $ytvUri
	 * @return string youtube video id
	 */
	function parseYtVid($ytvUri) {
		$matches = array();
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.|m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
			$ytvUri, $matches);
		
		return $matches[1];
	}
	
	/**
	 * Validate if the youtube video id is ok.
	 * @param string $ytVid youtube video id.
	 * @return boolean true if valid or false.
	 */
	function validateYtVid($ytVid) {
		$ytvUri = "http://www.youtube.com/watch?v={$ytVid}";
		$testUri = "http://www.youtube.com/oembed?url={$ytvUri}&format=json";
		$json = file_get_contents($testUri);
		if(json_decode($json)) {
			return true;
		}
	}	
	
	function assembleYtEmbededUri($ytVid, $protocolPrefix='http') {
		if (!empty($protocolPrefix)) $protocolPrefix .= ':';
		return "{$protocolPrefix}//www.youtube.com/embed/{$ytVid}";
	}
	
	function assembleYtWatchUri($ytVid, $protocolPrefix='http') {
		if (!empty($protocolPrefix)) $protocolPrefix .= ':';
		return "{$protocolPrefix}//www.youtube.com/watch?v={$ytVid}";
	}
	
	function assembleYtThumbnailUri($ytVid, $quality='mq', $protocolPrefix='http') {
		$thumbImgName = 'default.jpg';
		switch($quality) {
		case 'hq':
		case 'mq':
		case 'sd':
		case 'maxres':
			$thumbImgName = $quality . $thumbImgName;
		}
		if (!empty($protocolPrefix)) $protocolPrefix .= ':';
		return "{$protocolPrefix}//i1.ytimg.com/vi/{$ytVid}/{$thumbImgName}";
	}
	
	function assembleYtChannelUrl($ytChannelId, $protocolPrefix='http') {
		if (!empty($protocolPrefix)) $protocolPrefix .= ':';
		return "{$protocolPrefix}//www.youtube.com/channel/{$ytChannelId}";
	}
	
	function assembleYtUserHomepageUrl($ytUserName, $protocolPrefix='http') {
		if (!empty($protocolPrefix)) $protocolPrefix .= ':';
		return "{$protocolPrefix}//www.youtube.com/user/{$ytUserName}";
	}
	
}