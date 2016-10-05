<?php

class Post extends AdminController{
	function index() {
		$availableTabs = array('under review', 'featured', 'online', 'trash', 'all');
		$tab = $this->app->getRequest('tab', 'under review');
		if (!in_array($tab, $availableTabs)) { 
			redirect(_404()); 
		}
		
		$result = array(
			'tab'=>$tab, 'availableTabs'=>$availableTabs, 
			'pageJs'=>array('bootstrap-paginator.min.js'),
		);
		if (in_array($tab, array('under review', 'online', 'trash', 'all'))) {
			$condition = array('order'=>'created_at DESC');
			switch($tab) {
			case 'under review':
				$condition['where'] = array('status'=>0); break;
			case 'online':
				$condition['where'] = array('status'=>1); break;
			case 'trash':
				$condition['where'] = array('status'=>-1); break;
			}
			
			$pager = null;
			$result['posts'] = m('posts')->getPaginatedPosts($condition, 25, $pager, false);
			$result['pager'] = $pager;
		} 
		else {
			$condition = array(
				'order'=>'featured_at DESC', 'where'=>array('status'=>1),
			);
			
			$pager = null; $postIds = array();
			$featuredPosts = m('FeaturedPosts')->getPaginatedPosts($condition, 25, $pager);
			foreach($featuredPosts as $post) {
				$postIds[] = $post['fk_post_id'];
			}
			$condition = array('where'=>$where);
			$detailPosts = m('posts')->find($condition);
			
			$result['posts'] = sort_array_ref_field_values($detailPosts, 'pk_id', $postIds);
			$result['pager'] = $pager;
		}
		
		return $result;
	}
}
