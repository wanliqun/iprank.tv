<?php
class Channel extends AppController{
	var $libChannel;
	
	function __construct($app) {
		parent::__construct($app);
		$this->libChannel = m('LibChannel');
	}
	
	function index(){
		$allChannels = $this->libChannel->getChannels(array('order'=>'position DESC'));
		$channelPosts = $this->libChannel->getChannelRecentPosts($allChannels);
		$chnames = array_column($allChannels, 'name');
		$chnames = implode(',', $chnames);
		
    	return array(
    		'pageTitle'=>'iPrank.TV - Channels', 'pageCanonical'=>_chs(), 
    		'pageDescription'=>"Check out iPrank.TV's curated channels: {$chnames}",
    		'channels'=>$allChannels, 'channelPosts'=>$channelPosts,
    		'pageJs'=>array('bootstrap-paginator.min.js', 'slick.min.js'),
    	);
	}
	
	function view() {
		$chId = $this->app->getRequest('id', 0);
		if (intval($chId) <= 0) {
			redirect(_404());
		}
		
		$condition = array('where'=>array('pk_id'=>$chId));
		$channels = $this->libChannel->getChannels($condition);
		if (empty($channels)) redirect(_404());
		$channel = end($channels);	
		
		$condition = array(
			'where'=>array('fk_channel_id'=>$chId, 'status'=>1),
			'order'=>'created_at DESC',
		);
		c('pagesize', 24);
		$modelPager = new Corex_ModelPager(m('Posts'), $condition);
		$modelPager->exec();
		$posts = $modelPager->getRs();
		m('PostStatistics')->initPostsStatistics($posts);
		$pager = $modelPager->getPager();
		
		return array(
			'pageTitle'=>"iPrank.TV - {$channel['name']} channel",
			'pageCanonical'=>_chv($channel['pk_id'], $channel['name']),
			'pageDescription'=>"Check out {$channel['name']} videos on iPrank.TV",
			'channel'=>$channel, 'posts'=>$posts, 'pager'=>$pager, 
			'pageJs' =>array('bootstrap-paginator.min.js'), 
    	);
	}
}
