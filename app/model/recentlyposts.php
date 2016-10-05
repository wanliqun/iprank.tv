<?php

class RecentlyPosts {
	private $mPosts;

	function __construct(){
		$this->mPosts = m("Posts");
	}
	
	function getMostRecentlyPosts($lastTimestamp=-1, $num=25) {
		// posts.
		$condition = array(
			'limit'=>$num,
			'field'=>'pk_id, btitle, type, fk_channel_id, fk_channel_name,
				fk_member_id, fk_user_name, media_ids, created_at',
			'order'=>'created_at DESC',
			'where'=>array('status'=>1, ),
		);
		if ($lastTimestamp > 0) {
			$condition['where']['created_at <'] = mdate('%Y-%m-%d %H:%i:%s', $lastTimestamp);
		}
		$recentlyPosts = $this->mPosts->getPosts($condition);
		return $recentlyPosts;
	}
}