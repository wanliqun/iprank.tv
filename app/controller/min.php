<?php
/**
 * minify
 * min.php
 *
 */
class Min extends AppController{
	
	function index(){
		$minifyLib = new Corex_MinifyLib();
		$minifyLib->exec(); 
		exit();
	}
}
