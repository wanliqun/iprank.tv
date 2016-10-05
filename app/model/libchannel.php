<?php

class LibChannel {
	/**
	 * 获取channels
	 * @param string $condition
	 * @return array 按照指定条件返回channels
	 */
	function getChannels($condition=null) {
		return m('Channels')->find($condition);
	}
	
	/**
	 * 获取channel下面最新的帖子
	 * @param array $channels channel对象列表
	 * @param number $num 每个channel下面最新帖子的数目
	 * @return array channel下面最新帖子列表
	 */
	function getChannelRecentPosts($channels, $num=12) {
		// How to select the first max row per group in sql?
		// http://www.xaprb.com/blog/2006/12/07/how-to-select-the-firstleastmax-row-per-group-in-sql/
		$sqls = array(); 
		$fields = "`pk_id`, `btitle`, `type`, `fk_channel_id`, `fk_channel_name`, `cover_url`, `created_at`";
		foreach($channels as $channel) {
			$sqls[] = "(SELECT {$fields} FROM `posts` "
				. " WHERE `fk_channel_id`={$channel['pk_id']} AND `status`=1"
				. " ORDER BY `created_at` DESC LIMIT {$num})";
		}
		$sql = implode(' UNION ALL ', $sqls);
		$channelPosts = array();
		if(Db::getInstance()->query($sql)) {
			$channelPosts = Db::getInstance()->getAll();
		}
		
		$result = array();
		foreach($channelPosts as $channelPost) {
			$channelId = $channelPost['fk_channel_id'];
			$channelName = $channelPost['fk_channel_name'];
			$postId = $channelPost['pk_id'];
				
			$result[$channelId]['posts'][] = $channelPost;
			$result[$channelId]['channel_name'] = $channelName;
		}
		return $result;
	}
	
	/**
	 * Guess which channel by provided information like title, description...
	 * @param info provided detail to be guessed.
	 * @param default the default channel which will return if not pattern matched.
	 * @return the channel id and channel name.
	 */
	function guessChannel($info, $default=3) {
		static $guessTable = array(
			'channels'=>array(
				1=>'Pickup Line Pranks',
				2=>'Sexy Pranks',
				3=>'Funny Pranks',
				4=>'Scary Pranks',
				5=>'Office/School Pranks',
				6=>'Police/Criminal Pranks',
				7=>'Animal Pranks',
				8=>'Prank Call',
				9=>'Social Experiment',
				10=>'How to Prank',
				11=>'Awkward Pranks',
			),
			'matchPatterns'=>array(
				'/(pickup|pick up|picking up|get girl|gets girl )/i'=>1,
				'/(sex|dick|boob|nude|yoga pant|pussy)/i'=>2,
				'/(scare|scary|zombie|devil|evil)/i'=>4,
				'/(office|school|colleague|classmate)/i'=>5,
				'/(police|cop|gang|gangster|mafia|murder|prisoner|criminal)/i'=>6,
				'/(animal|cat|dog)/i'=>7,
				'/(prank call|call prank)/i'=>8,
				'/(social experiment|experiment)/i'=>9,
				'/(how to prank|pranks to play|prank to play)/i'=>10,
				'/(awkward)/i'=>11,
			)
		);
		
		$channelTable = $guessTable['channels'];
		$matchPtnTable = $guessTable['matchPatterns'];
		$chid = $default;
		foreach($matchPtnTable as $pattern=>$channelId) {
			if(preg_match($pattern, $info['title'])) {
				$chid = $channelId; 
				break;
			}
		}
		$chname = $channelTable[$chid];
		return array('chid'=>$chid, 'chname'=>$chname);
	}
}