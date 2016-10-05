<?php
class Home extends AppController {
	private $libHome;
	
	function __construct($app) {
		parent::__construct($app);
		m('LibAjax');
	}
	
	function index(){
		$lastTimestamp = $this->app->getRequest('last_timestamp', -1);
		$result = array(
			'pageCanonical'=>base_url(), 'pageJs' => array('youtube.js', 'home.js',)
		);
		
		$featuredPosts = m("FeaturedPosts")->getFeaturedPosts(4);
		$result['featuredPosts'] = $featuredPosts;
		
		$pageSize = c('ajax_num_loadmore');
		$recentlyPosts = m('RecentlyPosts')->getMostRecentlyPosts($lastTimestamp, $pageSize);
		$result['recentlyPosts'] = $recentlyPosts;
		
		$popularPosts = m('PopularPosts')->getMostPopularPosts();
		$result['popularPosts'] = $popularPosts;
		
		if (count($recentlyPosts) == $pageSize) {
			$lastPost = end($recentlyPosts);
			$result['lastTimestamp'] = strtotime($lastPost['created_at']);
			$result['hasMore'] = true;
		}
		
		$result['twCard']  = array(
			'card'=>'summary', 'site'=>'@iPrankTV', 'url'=>base_url(),
			'title'=>c('meta_title'), 'description'=>get_page_meta('description'),
			'image'=>media('maxreslogo.png', 'images'),
		);
		
		$result['ogTags'] = array(
			'site_name'=>c('sitename'), 'url'=>base_url(), 
			'type'=>'website', 'title'=>c('meta_title'),
			'image'=>media('maxreslogo.png', 'images'),
			'description'=>get_page_meta('description'),
		);
		return $result;
	}
}
