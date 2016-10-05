<?php

class PostStatistics extends AppModel{
	public $tableName = 'post_statistics';
	
	public function getPostStatistics($postid) {
		$posts = array(array('pk_id'=>$postid));
		$this->initPostsStatistics($posts);
		$statistics = end($posts)['statistics'];
		return $statistics;
	}
	
	public function initPostsStatistics(&$posts) {
		$condition = array(
			'field'=>'fk_post_id, (default_liked + newly_liked) as num_liked,
			(default_disliked + newly_disliked) as num_disliked,
			(default_favorited + newly_favorited) as num_favorited,
			newly_commented as num_commented,
			(default_viewed + newly_viewed) as num_viewed',
		);
		$where = array();
		foreach($posts as $post) {
			$where['fk_post_id'][] = intval($post['pk_id']);
		}
		$condition['where'] = $where;
		$statistics = $this->find($condition);
		foreach($posts as &$post) {
			foreach ($statistics as $statistic) {
				if ($statistic['fk_post_id'] == $post['pk_id']) {
					$post['statistics'] = $statistic;
					break;
				}
			}
		}
		unset($post);
	}
}