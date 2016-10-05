<?php

/**
 * Google SDK封装
 * google.php
 */
class Google {
	var $client;
	
	function __construct() {
		$this->_registerAutoloader();
		
		$glConf = c('google');
		$glAppName = $glConf['appname'];
		$glApiKey = $glConf['apikey'];
		
		$this->client = new Google_Client();
		$this->client->setApplicationName($glAppName);
		$this->client->setDeveloperKey($glApiKey);
	}
	
	private function _registerAutoloader() {
		set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/google/src' );
		
		// The Google SDK requires PHP version 5.2.1 or higher.
		if (version_compare(PHP_VERSION, '5.2.1', '<')) {
			throw new Exception('The Google SDK requires PHP version 5.2.1 or higher.');
		}
		
		// If your code has an existing __autoload function then this function must be explicitly 
		// registered on the __autoload stack.
		// (PHP Documentation for spl_autoload_register [@see http://php.net/spl_autoload_register])
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		
		/**
		 * Register the autoloader for the Google SDK classes.
		 * Based off the official PSR-4 autoloader example found here:
		 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
		 *
		 * @param string $class The fully-qualified class name.
		 * @return void
		 */
		spl_autoload_register(function ($class) {
			// class name prefix
			$prefix = 'Google_';
			// base directory for the namespace prefix
			$base_dir = defined('GOOGLE_SDK_SRC_DIR') ? GOOGLE_SDK_SRC_DIR : __DIR__ . '/google/src/Google/';
		
			// does the class use the namespace prefix?
			$len = strlen($prefix);
			if (strncmp($prefix, $class, $len) !== 0) {
				// no, move to the next registered autoloader
				return;
			}
		
			// get the relative class name
			$relative_class = substr($class, $len);
			// split the relative class name by '_' to assemble the class file path.
			$pathComps = explode('_', $relative_class);
			$file = $base_dir . '/' . implode('/', $pathComps) . '.php';
			// if the file exists, require it
			if (file_exists($file)) {
				require $file;
			}
		});
	}
}