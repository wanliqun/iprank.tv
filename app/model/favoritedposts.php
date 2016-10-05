<?php

class FavoritedPosts extends AppModel{
	public $tableName = 'favorited_posts';
	var $mPosts;

	function __construct($tableName = null){
		parent::__construct($tableName);
		$this->mPosts = m('Posts');
	}
	
	function getPaginatedPosts($condition, $pageSize=15, &$pager=null) {
		// Get favourited posts index.
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$favouritedPosts = $modelPager->getRs();
		$pager = $modelPager->getPager();
		if(empty($favouritedPosts)) return;
	
		// Get $favourited referenced posts.
		$where = array('status'=>1);
		foreach($favouritedPosts as $favourite) {
			$where['pk_id'][] = $favourite['fk_post_id'];
		}
		$condition = array('where'=>$where);
		$refPosts = $this->mPosts->getPosts($condition, false, false, false);
		$refPosts = reform_array_keyed_byfield($refPosts, 'pk_id');
	
		// Resort the posts.
		$posts = array();
		foreach($favouritedPosts as $favourite) {
			if (!empty($refPosts[$favourite['fk_post_id']])) {
				$posts[] = $refPosts[$favourite['fk_post_id']];
			}
		}
		return $posts;
	}
}