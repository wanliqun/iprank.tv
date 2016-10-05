<?php

class Posts extends AppModel{
	public $tableName = 'posts';
	private $mMedias;
	private $mPostStatistics;
	
	function __construct($tableName = null){
		parent::__construct($tableName);
		
		$this->mMedias = m("Medias");
		$this->mPostStatistics  = m("PostStatistics");
	}
	
	function getPaginatedPosts($condition, $pageSize=15, &$pager=null, $getStatistics=true) {
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$posts = $modelPager->getRs();
		$pager = $modelPager->getPager();
		if ($getStatistics) {
			$this->mPostStatistics->initPostsStatistics($posts);
		}
		
		return $posts;
	}
	
	function getPosts($condition, $getDetails=false, $getMember=false, $getMedias=true, $getStatistics=true, $getTags=false) {
		$posts = $this->find($condition);
		if (empty($posts)) return;
		
		// details
		if($getDetails) {
			$condition = array('field'=>'fk_post_id, title, description, src_type, src_from' );
			$where = array();
			foreach ($posts as $post) {
				if ($post['bmore'] > 0) {
					$where['fk_post_id'][] = intval($post['pk_id']);
				}
			}
			$condition['where'] = $where;
			$details = m('PostDetails')->find($condition);
			foreach ($posts as &$post) {
				foreach($details as $detail) {
					if ($post['pk_id'] == $detail['fk_post_id']) {
						$post['detail'] = $detail;
						break;
					}
				}
			}
			unset($post);
		}
		
		// member info
		if ($getMember) {
			$condition = array('field'=>'pk_id, source, username, avatar_url, email, gender,
			 								fk_country_iso2, birthday, is_subscribed, member_since');
			$where = array();
			foreach($posts as $post) {
				$where['pk_id'][] = intval($post['fk_member_id']);
			}
			$condition['where'] = $where;
			$members = m('Members')->find($condition);
			foreach($posts as &$post) {
				foreach ($members as $member) {
					if ($post['fk_member_id'] == $member['pk_id']) {
						$post['member'] = $member;
						break;
					}
				}
			}
			unset($post);
		}
		
		// medias.
		if ($getMedias) {
			$condition = array('field'=>'pk_id, src_from',);
			$where = array();
			foreach ($posts as $post) {
				$where['pk_id'][] = intval($post['media_ids']);
			}
			
			$where['pk_id'] = array_unique($where['pk_id']);
			$condition['where'] = $where;
			$medias = $this->mMedias->find($condition);
			
			foreach ($posts as &$post) {
				foreach($medias as $media) {
					if ($media['pk_id'] == $post['media_ids']) {
						$post['media'] = $media;
						break;
					}
				}
			}
			unset($post);
		}
		
		// statistics.
		if ($getStatistics) {
			$this->mPostStatistics->initPostsStatistics($posts);
		}
		
		// tags
		if ($getTags) {
			$where = array('status'=>1);
			foreach ($posts as $post) {
				$where['fk_post_id'][] = intval($post['pk_id']);
			}
			$condition = array('where'=>$where);
			$postTags = m('PostTags')->find($condition);
			
			$tagIds = array();
			foreach ($postTags as $postTag) {
				$tagIds[] = intval($postTag['fk_tag_id']);
			}
			$tagIds = array_unique($tagIds);
			$condition = array('where'=>array('pk_id'=>$tagIds));
			$tags = m('Tags')->find($condition);
			$tags = reform_array_keyed_byfield($tags, 'pk_id');
			
			foreach ($posts as &$post) {
				foreach ($postTags as $key=>$postTag) {
					if ($post['pk_id'] == $postTag['fk_post_id']) {
						$tagid = $postTag['fk_tag_id']; 
						$tag = $tags[$tagid];
						if (!empty($tag)) $post['tags'][] = $tag;
						unset($postTags[$key]);
					}
				}
			}
			unset($post);
		}
		
		return $posts;
	}
}