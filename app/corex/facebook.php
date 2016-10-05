<?php

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

/**
 * Facebook SDK封装
 * facebook.php
 */
class Facebook {
	function __construct() {
		$this->_registerAutoloader();
		
		$fbConf = c('facebook');
		$this->appId = $fbConf['appid'];
		$this->appSecret = $fbConf['appsecret'];
		FacebookSession::setDefaultApplication($this->appId, $this->appSecret);
	}
	
	function getFbProfile(&$sess=null, &$err=null) {
		$sess = $this->getFbSession($err);
		if (!empty($sess)) {
			return $this->makeFbApiCall($sess, 'GET', '/me', null, $err);
		}
	}
	
	function postFbLink(&$sess=null, $msg='', $link='', &$err=null) {
		$sess = $this->getFbSession($err);
		if (!empty($sess)) {
			$params = array('link'=>$link, 'message'=>$msg);
			return $this->makeFbApiCall($sess, 'POST', '/me/feed', $params, $err);
		}
	}
	
	function makeFbApiCall($session, $method='GET', $path, $parameters=null, &$err=null) {
		try {
			$request = new FacebookRequest($session, $method, $path, $parameters);
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
			
			return $graphObject;
		} catch (FacebookRequestException $ex) {
			// When Facebook returns an error
			$err = $ex->getMessage();
		} catch (\Exception $ex) {
			// When validation fails or other local issues
			$err = $ex->getMessage();
		}
	}
	
	function getFbJsSession(&$err=null) {
		$helper = new FacebookJavaScriptLoginHelper();
		try {
			return $helper->getSession();
		} catch(FacebookRequestException $ex) {
			// When Facebook returns an error
			$err = $ex->getMessage();
		} catch(\Exception $ex) {
			// When validation fails or other local issues
			$err = $ex->getMessage();
		} 
	}
	
	function getFbLoginUrl($redirect) {
		$helper = new FacebookRedirectLoginHelper($redirect);
		$loginUrl = $helper->getLoginUrl();
		return $loginUrl;
	}
	
	function getFbSession(&$err=null) {
		$sess = null; $oauthTokenSecret = $_SESSION['fb_oauth_token_secret'];
		if (!empty($oauthTokenSecret)) {
			$isTokenSecretValid = false;
			try {
				$sess = new FacebookSession($oauthTokenSecret);
				$isTokenSecretValid = $sess->getAccessToken()->isValid();
			} catch(\Exception $ex) {
				// When validation fails or other local issues
				$err = $ex->getMessage();
			}
			if (!$isTokenSecretValid) {
				$sess = null; unset($_SESSION['fb_oauth_token_secret']);
			}
		}
	
		if (empty($sess)) {
			$sess = $this->getFbJsSession($err);
			$_SESSION['fb_oauth_token_secret'] = $sess ? $sess->getToken() : '';
		}
		
		return $sess;
	}
	
	private function _registerAutoloader() {
		// If your code has an existing __autoload function then this function must be explicitly
		// registered on the __autoload stack.
		// (PHP Documentation for spl_autoload_register [@see http://php.net/spl_autoload_register])
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		
		require_once( __DIR__ . '/facebook/autoload.php' );
	}
}