<?php
class Post extends AppController{
	private $mLibPost;
	
	function __construct($app) {
		parent::__construct($app);
		$this->mLibPost = m('LibPost');
	}
	
	function index(){
    	return array(
    		//'pageCss' => array('home-index', ),
    		//'pageJs' => array('home'),
    	);
	}
	
	function _view() {
		$this->view = 'post/view';
		$cfg = array('status !='=>-1);
		
		return $this->view($cfg);
	}
	
	function view($cfg=array('status'=>1)) {
		$postId = $this->app->getRequest('id', 0);
		if (intval($postId) <= 0) redirect(_404());
		
		// Adjacent posts.
		$threePosts = $this->mLibPost->getChronologyAdjPosts($postId, $cfg);
		if (empty($threePosts)) redirect(_404());
		$prev = $threePosts[0];
		$post = $threePosts[1];
		$next = $threePosts[2];
		
		// Similiar posts.
		$similarPosts = $this->mLibPost->getSimilarPosts($post);
		
		// Disqus sso.
		$sdkDisqus = new Corex_Disqus();
		$dsqSSOData = array('apikey'=>$sdkDisqus->getPublicKey());
		if (ME::isLogined()) {
			$data = array(
				"id"=>ME::id(), "username"=>ME::name(), "email"=>ME::email(), 
				'avatar'=>ME::avatar(), 'url'=>_mp(ME::id(), ME::name()),
			);
			$payload = $sdkDisqus->encodeSSOPayload($data);
			$dsqSSOData['payload'] = $payload;
		}
		
		helper(array('models', 'social'));
		$pageTitle = "iPrank.TV - {$post['btitle']}";
		$pageDescription = htmlentities(str_replace(array("\n", "\r"), ' ', $post['bdescription']));
		$canonicalUri = _pv($post['pk_id'], $post['btitle'], $post['type']);
		$ogTags = array(
			'site_name'=>c('sitename'), 'url'=>$canonicalUri,
			'type'=>'article', 'title'=>$pageTitle, 
			'description'=>$pageDescription,
			'image'=>media($post['cover_url'], 'images'),
		);
		$twCard = array(
			'card'=>'summary', 'site'=>'@iPrankTV', 'url'=>$canonicalUri,
			'title'=>$pageTitle, 'description'=>$pageDescription,
			'image'=>media($post['cover_url'], 'images'),
		);
		
		return array(
			'pageTitle'=>$pageTitle, 'pageJs' => array('posts.js'),
			'pageCanonical'=>$canonicalUri, 
			'pageDescription'=>$pageDescription,
			'prev'=>$prev, 'post'=>$post, 'next'=>$next,
			'similarPosts'=>$similarPosts, 'disqusSSO'=>$dsqSSOData,
			'ogTags'=>$ogTags, 'twCard'=>$twCard,
		);
	}
	
	function search() {
		$keyword = $this->app->getRequest('_keyword', '');
		$result = array(
			'pageTitle'=>"iPrank.TV - Search",
			'pageDescription'=>"Find some cool stuff on iPrank.TV",
		);
		
		if (!empty($keyword)) {
			$pager = null;
			$condition = array('where'=>array('status'=>1), 'order'=>'created_at DESC');
			$posts = m('Posts')->getPaginatedPosts($condition, 24, $pager);
			
			$result = array_merge($result, array(
				'pageTitle'=>"iPrank.TV - Search result for '{$keyword}'",
				'pageJs'=>array("bootstrap-paginator.min.js"),
				'pageDescription'=>"Search '{$keyword}' on iPrank.TV",
				'pager'=>$pager, 'posts'=>$posts, 'keyword'=>$keyword
			));
		}
		return $result;
	}
}
