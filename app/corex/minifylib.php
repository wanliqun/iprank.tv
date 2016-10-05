<?php

/**
 * Minify SDK封装
 * minifylib.php
 */
class MinifyLib {
	var $customConfDir;
	var $minCustomConfs;
	
	function __construct() {
		$this->customConfDir = APP_PATH.DS.c('config_dir').DS.'minify';
		$this->minCustomConfs = array(
			'base' 	 => $this->customConfDir . '/config.php',
			'test'   => $this->customConfDir . '/config-test.php',
			'groups' => $this->customConfDir . '/groupsConfig.php'
		);
	}
	
	function exec() {
		$min_customConfigPaths = $this->minCustomConfs;
		include_once 'minify/min/index.php';
	}
}