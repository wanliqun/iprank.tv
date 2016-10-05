<?php

class FeaturedPosts extends AppModel{
	public $tableName = 'featured_posts';
	private $mPosts;

	function __construct($tableName = null){
		parent::__construct($tableName);
		$this->mPosts = m("Posts");
	}
	
	function getPaginatedPosts($condition, $pageSize=15, &$pager=null) {
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$featuredPosts = $modelPager->getRs();
		$pager = $modelPager->getPager();
		
		return $featuredPosts;
	}
	
	function getFeaturedPosts($num=4) {
		$condition = array(
			'limit'=>$num,
			'field'=>'fk_post_id, cover_url',
			'order'=>'position DESC, featured_at DESC',
			'where'=>array('status'=>1),
		);
		$featuredPosts = $this->find($condition);
		
		$condition = array('limit'=>$num, 'field'=>'pk_id, btitle, type, cover_url',);
		$postIds = array();
		foreach($featuredPosts as $post) {
			$postIds[] = $post['fk_post_id'];
		}
		$condition['where'] = array('pk_id'=>$postIds);
		$detailPosts = $this->mPosts->find($condition);
		return sort_array_ref_field_values($detailPosts, 'pk_id', $postIds);
	}
}