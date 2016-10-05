<?php

class CrawlerYtVideos extends AppModel{
	public $tableName = 'crawler_ytvideos';
	
	function getPaginatedVideos($condition, $pageSize=15, &$pager=null, $getPost=true) {
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$videos = $modelPager->getRs();
		$pager = $modelPager->getPager();
		
		if ($getPost && !empty($videos)) {
			$postids = array();
			foreach($videos as $video) $postids[] = $video['fk_post_id'];
			
			$posts = m('Posts')->find(array('where'=>array('pk_id'=>$postids)));
			foreach($videos as &$video) {
				foreach ($posts as $key=>$post) {
					if ($video['fk_post_id'] == $post['pk_id']) {
						$video['post'] = $post; 
						unset($posts[$key]);  break;
					}
				}
			}
			unset($video);
		}
		
		return $videos;
	}
	
}