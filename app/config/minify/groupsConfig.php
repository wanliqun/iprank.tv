<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
	'css' => array( 
		'//public/css/style.min.css', 
		'//public/css/bootstrap-select.min.css',
	),
	'js' => array(
		'//public/js/jquery.min.js', '//public/js/jquery.rule.js',
		'//public/js/jquery.cookie.js', '//public/js/bootstrap.min.js',
		'//public/js/bootstrap-dialog.min.js', '//public/js/jstz.min.js', 
		'//public/js/app.js', '//public/js/post_action.js', 
		'//public/js/facebook.js', 
	),
);
