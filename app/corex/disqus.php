<?php

/**
 * Disqus SDK封装
 * disqus.php
 */
class Disqus {
	var $conf;
	
	function __construct() {
		$this->_registerAutoloader();
		$this->conf = c('disqus');
	}
	
	function listPosts($params) {
		$default = array('forum'=>'prank', 'order'=>'asc', 'since'=>0, 'limit'=>100, );
		$params = array_merge($default, $params);
		
		$sdkDisqus = new DisqusAPI($this->conf['secretkey']);
		return $sdkDisqus->forums->listPosts($params);
	}
	
	function encodeSSOPayload($data) {
		$msg = base64_encode(json_encode($data));
		$timestamp = time();
		$hmac = $this->_hmacsha1($msg . ' ' . $timestamp);
		return "$msg $hmac $timestamp";
	}
	
	function getPublicKey() {
		return $this->conf['publickey'];
	}
	
	private function _hmacsha1($data) {
		$blocksize = 64;
		$hashfunc = 'sha1';
		$key = $this->conf['secretkey'];
		if (strlen($key) > $blocksize) $key=pack('H*', $hashfunc($key));
		$key = str_pad($key, $blocksize, chr(0x00));
		$ipad = str_repeat(chr(0x36), $blocksize);
		$opad = str_repeat(chr(0x5c), $blocksize);
		$hmac = pack('H*', $hashfunc
					(($key ^ $opad).pack
						('H*', $hashfunc(($key^$ipad).$data))
					));
		return bin2hex($hmac);
	}
	
	private function _registerAutoloader() {
		// If your code has an existing __autoload function then this function must be explicitly 
		// registered on the __autoload stack.
		// (PHP Documentation for spl_autoload_register [@see http://php.net/spl_autoload_register])
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		
		/**
		 * Register the autoloader for the Disqus SDK classes.
		 * Based off the official PSR-4 autoloader example found here:
		 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
		 *
		 * @param string $class The fully-qualified class name.
		 * @return void
		 */
		spl_autoload_register(function ($class) {
			// class name prefix
			$prefix = 'Disqus';
			// base directory for the namespace prefix
			$base_dir = __DIR__ . '/disqus/disqusapi/';
			// does the class use the namespace prefix?
			$len = strlen($prefix);
			if (strncmp($prefix, $class, $len) !== 0) {
				// no, move to the next registered autoloader
				return;
			}
			
			$file = $base_dir . '/disqusapi.php';
			// if the file exists, require it
			if (file_exists($file)) {
				require $file;
			}
		});
	}
}