<?php
class Callback extends AppController {
	
	function __construct($app) {
		parent::__construct($app);
	}
	
	function disqus_authorize() {
		
	}
	
	function tw_authorize() {
		$denied = $this->app->getRequest('denied', '');
		if (!empty($denied)) {
			return array (
				'result'=>array('success'=>false, 'errtip'=>'You must authorize us to tweet for you.'),
			);
		}
		
		$oauthToken = $_SESSION['tw_oauth_token'];
		$oauthTokenSecret = $_SESSION['tw_oauth_token_secret'];
		$oauthVerifier = $this->app->getRequest('oauth_verifier', '');
		$oauthToken2 = $this->app->getRequest('oauth_token', '');
		if (empty($oauthVerifier)) {
			return array (
				'result'=>array('success'=>false, 'errtip'=>'Invalid callback request from Twitter.'),
			);
		}
		if (!empty($oauthToken2) && $oauthToken != $oauthToken2) {
			$oauthToken = $_SESSION['oauth_token'] = $oauthToken2;
		}
		
		$mTwitter = new Corex_Twitter($oauthToken, $oauthTokenSecret);
		$tokenCredentials = $mTwitter->getAccessToken($oauthVerifier);
		if(!empty($tokenCredentials['oauth_token'])) {
			$_SESSION['tw_token_credentials'] = $tokenCredentials;
			return array('result'=>array('success'=>true));
		} else {
			return array('result'=>array('success'=>false, 'errtip'=>'Invalid token credentials.'));
		}
	}
}
