<?php
class Popular extends AppController{
	var $defaultJs = array (
		'jquery.dotdotdot.min.js', 'bootstrap-paginator.min.js', 'bootstrap-select.min.js',
		'popular.js',
	);
	
	function __construct($app){
		parent::__construct($app);
		m("LibAjax");
	}
	
	function index(){
		$populars = m('PopularPosts')->getPopularPosts('most-viewed');
		$mostPopulars = m('PopularPosts')->getMostPopularPosts();
		
    	return array(
    		'pageTitle'=>"iPrank.TV - Popular videos", 'pageCanonical'=>_pop(),
    		'pageDescription'=>"Check out the popular videos from iPrank.TV",
    		'pageJs' => $this->defaultJs,
    		'populars' => $populars,
    		'mostPopulars' => $mostPopulars,
    	);
	}
}
