<?php

/**
 * Twitter SDK封装
 * twitter.php
 */
class Twitter {
	var $conf;
	var $connection;
	
	function __construct($oauthToken=NULL, $oauthTokenSecret=NULL) {
		$this->_registerAutoloader();
		
		$this->conf = c('twitter');
		$consumerKey = $this->conf['consumerkey'];
		$consumerSecret = $this->conf['consumersecret'];
		$this->_constructConnection($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
	}
	
	function verifyTwCredentials($tokenCredentials, &$err=null) {
		$result = $this->makeTwApiCall($tokenCredentials, 'GET', 'account/verify_credentials', $err);
		return $result;
	}
	
	function tweetTwStatus($tokenCredentials, $statusText, &$err=null) {
		$param = array('status'=>$statusText);
		return $this->makeTwApiCall($tokenCredentials, 'POST', 'statuses/update', $param, $err);
	}
	
	function makeTwApiCall($tokenCredentials, $method='GET', $path, $parameters=null, &$err=null) {
		$consumerKey = $this->conf['consumerkey'];
		$consumerSecret = $this->conf['consumersecret'];
		$oauthToken = $tokenCredentials['oauth_token'];
		$oauthTokenSecret = $tokenCredentials['oauth_token_secret'];
		$this->_constructConnection($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
		
		$result = NULL;
		if (strcasecmp($method, 'POST')===0) {
			$result = $this->connection->post($path, $parameters);
		} else if (strcasecmp($method, 'DELETE')===0) {
			$result = $this->connection->delete($path, $parameters);
		} else {
			$result = $this->connection->get($path, $parameters);
		}
		
		if ($this->connection->http_code === 200) return $result;
		else {
			$error = end($result->errors);
			$err = $error->message;
			return FALSE;
		}
	}
	
	function getAccessToken($oauthVerifier) {
		return $this->connection->getAccessToken($oauthVerifier);
	}
	
	function getAuthorizeURL() {
		$requestToken = $this->getRequestToken();
		$redirectUrl = $this->connection->getAuthorizeURL($requestToken, FALSE);
		
		return $redirectUrl;
	}
	
	function getRequestToken() {
		$callback = $this->conf['callback'];
		$temporaryCredentials = $this->connection->getRequestToken($callback);
		/* Save temporary credentials to session. */
		$_SESSION['tw_oauth_token'] = $temporaryCredentials['oauth_token'];
		$_SESSION['tw_oauth_token_secret'] = $temporaryCredentials['oauth_token_secret'];
		
		return $temporaryCredentials;
	}
	
	function _constructConnection($consumerKey, $consumerSecret, $oauthToken=NULL, $oauthTokenSecret=NULL) {
		$this->connection = new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
		return $this->connection;
	}
	
	private function _registerAutoloader() { 
		// If your code has an existing __autoload function then this function must be explicitly 
		// registered on the __autoload stack.
		// (PHP Documentation for spl_autoload_register [@see http://php.net/spl_autoload_register])
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		
		/**
		 * Register the autoloader for the Twitter SDK classes.
		 * Based off the official PSR-4 autoloader example found here:
		 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
		 *
		 * @param string $class The fully-qualified class name.
		 * @return void
		 */
		spl_autoload_register(function ($class) {
			if ($class === 'TwitterOAuth') {
				$file = __DIR__ . '/twitter/twitteroauth/twitteroauth.php';
				// if the file exists, require it
				if (file_exists($file)) {
					require $file;
				}
			}
		});
	}
}