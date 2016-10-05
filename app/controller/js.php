<?php
class Js extends AppController {
	function __construct($app) {
		parent::__construct($app);
	}
	
	function post_actionbox() {
		$postids = $this->app->getRequest('postids', '');
		$boxprefix = $this->app->getRequest('prefix', 'post-actionbox');
		
		$postids = explode(',', $postids);
		$postids = array_map('trim', $postids);
		if (!ME::isLogined() || empty($postids)) exit();
		
		$condition = array('where'=>array(
			'fk_user_name'=>ME::name(), 'fk_post_id'=>$postids,
		));
		// Liked posts.
		$mLikedPosts = m('LikedPosts');
		$likedPosts = $mLikedPosts->find($condition);
		$likedPosts = reform_array_keyed_byfield($likedPosts, 'fk_post_id');
		// Favourited post.
		$mFavedPosts = m('FavoritedPosts');
		$favedPosts = $mFavedPosts->find($condition);
		$favedPosts = reform_array_keyed_byfield($favedPosts, 'fk_post_id');
		
		$result = array('prefix'=>$boxprefix, 'posts'=>array());
		foreach($postids as $postid) {
			$likeVal = intval($likedPosts[$postid]['liked_value']);
			$faved = !empty($favedPosts[$postid]);
			$result['posts'][$postid] = array('like'=>$likeVal, 'favoured'=>$faved);
		}
		return $result;
	}
}
