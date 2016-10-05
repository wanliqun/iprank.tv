<?php

/**
 * 获取post的标题
 * @param array post 帖子对象
 * @return string post 标题
 */
function _ptitle($post) {
	$title = $post['detail']['title'];
	if (empty($title)) {
		$title = $post['btitle'];
	} 
	
	return $title;
}

/**
 * 获取post的内容
 * @param array post 帖子对象
 * @return string post 内容
 */
function _pdescription($post) {
	$description = $post['detail']['description'];
	if (empty($description)) {
		$description = $post['bdescription'];
	}
	
	return nl2br(linkify($description));
}

/**
 * 获取用户的avatar地址.
 * @ param array member 用户对象
 * @ return string member avatar地址
 */ 
function _mavatar($member) {
	$avatarUrl = $member['avatar_url'];
	if (empty($avatarUrl)) {
		$avatarUrl = media('member-default-avatar.png', 'images');
	}
	return $avatarUrl;
}
