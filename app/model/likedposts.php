<?php

class LikedPosts extends AppModel{
	public $tableName = 'liked_posts';
	var $mPosts;

	function __construct($tableName = null){
		parent::__construct($tableName);
		$this->mPosts = m('Posts');
	}
	
	function getPaginatedPosts($condition, $pageSize=15, &$pager=null) {
		// Get liked posts index.
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$likedPosts = $modelPager->getRs();
		$pager = $modelPager->getPager();
		if(empty($likedPosts)) return;
		
		// Get liked referenced posts.
		$where = array('status'=>1);
		foreach($likedPosts as $like) {
			$where['pk_id'][] = $like['fk_post_id'];
		}
		$condition = array('where'=>$where);
		$refPosts = $this->mPosts->getPosts($condition, false, false, false);
		$refPosts = reform_array_keyed_byfield($refPosts, 'pk_id');

		// Resort the posts.
		$posts = array();
		foreach($likedPosts as $like) {
			if (!empty($refPosts[$like['fk_post_id']])) {
				$posts[] = $refPosts[$like['fk_post_id']];
			}
		}
		return $posts;
	}
}