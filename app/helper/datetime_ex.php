<?php

/**
 * 获取指定时区和GMT之间的时间戳差
 * @param string $timeZone 指定时区
 * @return 指定时区和GMT时区之间的时间戳差
 */
if (!function_exists('get_tztimestamp_offset'))
{
	function get_tztimestamp_offset($timeZone) {
		static $localTimeOffset;
		
		$timezoneDefine = $timeZone;
		if ($timeZone == 'local') {
			if (empty($localTimeOffset)) {
				$timezoneDefine = empty($_COOKIE['tz']) ? c('time_zone') : $_COOKIE['tz'];
			} else return $localTimeOffset;
		}
		
		try {
			$specTimeZone = new DateTimeZone($timezoneDefine);
		} catch(Exception $e) {
			$specTimeZone = new DateTimeZone("America/Los_Angeles");
		}
		$specDateTimeObj = new DateTime("now", $specTimeZone);
		$specTZOffset = $specDateTimeObj->getOffset();
		
		if ($timeZone == 'local') $localTimeOffset = $specTZOffset;
		return $specTZOffset;
	}
}

/**
 * 根据时区设置返回本地化的时间
 * 系统默认时区，由系统配置变量'time_zone'定义
 * 用户时区由 $_COOKIE['tz'] 定义
 * @param int $timestamp utc时间戳
 * @return int 用户本地时区时间戳
 */
if (!function_exists('get_tzlocalized_timestamp'))
{
	function get_tzlocalized_timestamp($timestamp=null) {
		if (empty($timestamp)) { 
			$timestamp = time();
		}
		$localTimeOffset = get_tztimestamp_offset('local');
		$timestamp = $timestamp + $localTimeOffset;
		return $timestamp;
	}
}

if (!function_exists('get_tzlocalized_time')) 
{
	function get_tzlocalized_time($datetime=null) {
		$timestamp = time();
		if (!empty($datetime)) {
			$timestamp = get_timestamp($datetime);
		}
		$timestamp = get_tzlocalized_timestamp($timestamp);
		return date("Y-m-d H:i", $timestamp); 
	}
}

/**
 * 获取本地时区易读时间
 * @param string $datetime 时间字符串
 * @return string 易读时间字符串
 */
if (!function_exists('get_tzlocalized_readable_time'))
{
	function get_tzlocalized_readable_time($datetime=null) {
		$timestamp = time();
		if (!empty($datetime)) {
			$timestamp = get_timestamp($datetime);
		}
		
		$timestamp = get_tzlocalized_timestamp($timestamp);
		$etimestamp = get_tzlocalized_timestamp(time());
		return cltime($timestamp, $etimestamp);
	}
}

if (!function_exists('get_utctime_from_localtz'))
{
	function get_utctime_from_localtz($localDatetime, $format="Y-m-d H:i") {
		$localTZOffset = get_tztimestamp_offset('local');
		$localDate =  new DateTime($localDatetime);
		$utcTime = $localDate->getTimestamp() - $localTZOffset;
		$utcDate = mdate($format, $utcTime);
		
		return $utcDate;
	}
}

if (!function_exists('get_age_from_birthday')) 
{
	function get_age_from_birthday($birthday) {
		$age = date_create($birthday)->diff(date_create('today'))->y;
		return $age;
	}
}

?>