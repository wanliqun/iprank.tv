<?php
/**
 * social配置文件
 * social.php
 */

return array (
	// facebook
	'facebook'=>array(
		'appid'=>'1513728292172568', 
		'appsecret'=>'7c5ba16b3b680c2e559df58dd583ae3d',
	),
	// google
	'google'=>array(
		'appname'=>'iPrank',
		'apikey'=>'AIzaSyBlyZv1QEOz2vs9s9pAGPq4FZATuE3GLXU',
	),
	// twitter
	'twitter'=>array(
		'consumerkey'=>'MIl1GLk1MS50RirEIEKQxSEAd',
		'consumersecret'=>'6MJ3rZvdbvFN20uhx5dnvXHekhkDzBw1XYWi0P6QIEYPxiCSPw',
		'callback'=> $_SERVER['HTTP_HOST'] . '/?c=callback&a=tw_authorize',
	),
	// disqus
	'disqus'=>array(
		'secretkey'=>'vMCcQa6cYZheDgq5cpED1LhqiIRvEMUq6NkINe6H6xqJ5w6ZqaPInzVEpbuMm8xk',
		'publickey'=>'rvVnHt8Bjn1rEKNmgqP4ItSTaGUkt6NrVtQNBL9fG6dEQvyBaA5wCTpCfZNFIEsf',
	),
);


