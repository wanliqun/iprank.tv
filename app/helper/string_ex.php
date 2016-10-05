<?php

/**
 * 将数字转化为可读的字符串
 *
 * @param int number
 * @return string 可读字符串
 */
if (!function_exists('get_readable_number')) {
	function get_readable_number($n) {
		// first strip any formatting;
		$n = (0 + str_replace(",", "", $n));
		// is this a number?
		if (!is_numeric($n)) return false;
		// now filter it;
		if ($n>1000000000000) return round(($n / 1000000000000), 1) . ' T'; // trillion
		else if ($n>1000000000) return round(($n / 1000000000), 1) . ' B'; // billion
		else if ($n>1000000) return round(($n / 1000000), 1) . ' M'; // million
		else if ($n>1000) return round(($n / 1000), 1) . ' K'; // thounsands
		
		return number_format($n);
	}
}

/**
 * 将字符串中的url地址自动转换为anchor链接
 *
 * @param string 字符串
 * @return string 包含anchor链接的字符串
 */
if (!function_exists('linkify')) {
	function linkify($text) {
		$reg_exUrl = "/((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/";
		if(preg_match($reg_exUrl, $text)) {
			return preg_replace($reg_exUrl, '<a rel="nofollow" href="${1}" target="_blank">${1}</a> ', $text);
		} else {
			return $text;
		}
	}
}

if (!function_exists('slugify')) {
	function slugify($string) {
		$accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
	    $special_cases = array( '&' => 'and');
	    $separator = '-';
	    $string = mb_strtolower( trim( $string ), 'UTF-8' );
	    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
	    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
	    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
	    $string = preg_replace("/[$separator]+/u", "$separator", $string);
	    return trim($string, "{$separator}");
	}
}

/**
 * 将文件中模板变量替换为传递的设置数组元素.
 *
 * @param $filename 模板文件的路径
 * @return $variablesToMakeLocal 要设置为本地变量的设置数组
 */
if (!function_exists('get_include_contents')) {
	function get_include_contents($filename, $variablesToMakeLocal) {
		extract($variablesToMakeLocal);
		if (is_file($filename)) {
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return false;
	}
}

?>