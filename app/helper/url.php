<?php

/**
 * 获取post view url地址
 * 
 * @param int $id 帖子的id
 * @param string $title 帖子的标题
 * @param string $type 帖子的类型
 * @return string 帖子的浏览地址
 */
function _pv($id, $title='', $type='') {
	$title = slugify($title);
	$url = base_url() . "/{$type}/{$id}-$title";
	return $url;
}

function _ppv($id, $title='', $type='') {
	if (!empty($type)) $type = "_{$type}";
	return _pv($id, $title, $type);
}

function _chs() {
	return base_url() . "/channels/";
}

function _pop() {
	return base_url() . "/popular/";
}

/**
 * 获取channel view url地址
 * @param int $id channel的id
 * @param string $name channel的名称
 */
function _chv($id, $name) {
	$chname = slugify($name);
	return base_url() . "/channel/{$id}-{$chname}";
}

/**
 * 获取用户主页url地址
 * 
 * @param int $mid 用户id
 * @param string $name 用户名
 * @return string 用户个人主页url地址
 */
function _mp($id, $name, $tab='') {
	$url = base_url() . "/profile/{$name}";
	if (!empty($tab)) $url .= "#{$tab}";
	return $url;
}

function _js($params) {
	$url = base_url() . "/?c=js&" . http_build_query($params);
	return $url;
}

function _ajax($params) {
	$url = base_url() . "/?c=ajax&" . http_build_query($params);
	return $url;
}

function _callback($params) {
	$url = base_url() . "/?c=callback&" . http_build_query($params);
	return $url;
}

function _dialog($params) {
	if (!empty($params['redirect'])) {
		$params['redirect'] = urlencode($params['redirect']);
	}
	$url = base_url() . "/?c=dialog&" . http_build_query($params);
	return $url;
}

function _this() {
	return url($_SERVER['REQUEST_URI']);
}

function _signup() {
	return base_url() . "/signup";
}

function _signin($redir='') {
	$url = base_url() . "/signin";
	if (!empty($redir)) {
		$redir = urlencode($redir);
		$url .= "?redirect={$redir}";
	}
	return $url;
}

function _signoff($redir='') {
	$url = base_url() . "/signoff";
	if (!empty($redir)) {
		$redir = urlencode($redir);
		$url .= "?redirect={$redir}";
	}
	return $url;
}

function _upload() {
	return base_url() . "/upload";
}

function _editp($id) {
	return base_url() . "/edit/{$id}";
}

function _search() {
	return base_url() . "/search/?_sfields=btitle,bdescription";
}

/**
 * fill upload detail link
 */
function _fud() {
	return base_url() . "/fill";
}

/**
 * Activate account link.
 */
function _act_acct_link($verifyCode) {
	return base_url() . "/activate/{$verifyCode}";
}

function _pwd_reset_link($secretToken) {
	return base_url() . "/reset/{$secretToken}";
}

function _404() {
	return base_url() . "/404";
}

function _about() {
	return base_url() . "/about";
}

function _tou() {
	return base_url() . "/tou";
}

function _privacy() {
	return base_url() . "/privacy";
}

function _faq() {
	return base_url() . "/faq";
}

function _admin($params = array()) {
	return base_url() . "admin/?" . http_build_query($params);
}

function _fb_picture($fbId, $type='large') {
	return "//graph.facebook.com/{$fbId}/picture?type={$type}";
}

function _fb_home() {
	return "https://www.facebook.com/www.iprank.tv";
}

function _tw_home() {
	return "https://twitter.com/iPrankTV";
}

function _minify($keys, $files=array(), $type='js') {
	$url = base_url() . "/min/"; 
	if (!empty($keys)) $url .= "?g={$keys}";

	if (!empty($files)) {
		if (empty($keys)) $url .= '?'; else $url .= '&';
		$url .= "f=" . implode(',', $files);
		
		if (strcasecmp($type, 'css') === 0) $url .= "&b=public/css";
		else if(strcasecmp($type, 'js') === 0) $url .= "&b=public/js";
	}
	
	return $url;
}
