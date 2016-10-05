<?php

include_once(APP_PATH.DS.c('corex_dir').DS.'minify'.DS.'min'.DS.'config.php');

/**
 * For best performance, specify your temp directory here. Otherwise Minify
 * will have to load extra code to guess. Some examples below:
 */
//$min_cachePath = 'c:\\WINDOWS\\Temp';
//$min_cachePath = '/tmp';
//$min_cachePath = preg_replace('/^\\d+;/', '', session_save_path());
$min_cachePath = APP_PATH.DS.c('tmp_dir');
