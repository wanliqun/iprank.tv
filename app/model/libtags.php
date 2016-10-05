<?php

function _format_post_tag($tag) {
	$tag = strtolower($tag);
	$tag = trim($tag);
	return $tag;
}

class LibTags {
	function __construct() {
	}
	
	function updateTagsForPost($tags, $post, &$err=null) {
		$postId = $post['pk_id'];
		$memberId = $post['fk_member_id'];
		$username = $post['fk_user_name'];
		$tags = $this->_formatTags($tags);
		
		$addRs = $existRs = m('Tags')->find(array('where'=>array('name'=>$tags)));
		$diffTags = array();
		foreach($existRs as $rs) {
			$diffTags[] = $rs['name'];
		}
		
		// Create tags if necessary.
		$newTags = array_diff($tags, $diffTags);
		if (!empty($newTags)) {
			if (!$this->_createNewTags($newTags, $memberId, $username, $err)) return false;
			
			$newRs = m('Tags')->find(array('where'=>array('name'=>$newTags)));
			$addRs = array_merge($addRs, $newRs);
		}
		
		// Post tags.
		$allPostTagIds = array(); 
		foreach($addRs as $rs) { $allPostTagIds[] = $rs['pk_id']; }
		$allPostTagIds = array_unique($allPostTagIds);
		
		$newPostTagIds = array(); $removePostTagsIds = array();
		$updatePostTagIds = array(); $duplicatedPostTagIds = array();
		
		$condition = array('where'=>array('fk_post_id'=>$postId));
		$postTags = m('PostTags')->find($condition);
		foreach($postTags as $postTag) {
			$postTagId = $postTag['fk_tag_id'];
			
			if (in_array($postTagId, $allPostTagIds)) {
				if ($postTag['status'] != 1) $updatePostTagIds[] = $postTagId;
				else $duplicatedPostTagIds[] = $postTagId;
			} else if($postTag['status'] == 1) {
				$removePostTagsIds[] = $postTagId;
			}
		}
		$newPostTagIds = array_diff($allPostTagIds, $duplicatedPostTagIds);
		$newPostTagIds = array_diff($newPostTagIds, $updatePostTagIds);
		
		// new post tags records.
		if (!empty($newPostTagIds) && !$this->_newPostTagRecords($post, $newPostTagIds, $err)) {
			return false;
		}
		
		// update/remove post tags
		if (!empty($updatePostTagIds) || !empty($removePostTagsIds)) {
			$postTagStatusIds = array(
				array('status'=>1, 'tagids'=>$updatePostTagIds),
				array('status'=>-1, 'tagids'=>$removePostTagsIds),
			);
			
			return $this->_updatePostTagStatus($post, $postTagStatusIds, $err);
		}
		
		return true;
	}
	
	function addTagsForPost($tags, $post, &$err=null) {
		$postId = $post['pk_id'];
		$memberId = $post['fk_member_id'];
		$username = $post['fk_user_name'];
		$tags = $this->_formatTags($tags);
		
		$addRs = $existRs = m('Tags')->find(array('where'=>array('name'=>$tags)));
		$diffTags = array();
		foreach($existRs as $rs) { 
			$diffTags[] = $rs['name'];
		}
		
		// Create tags if necessary.
		$newTags = array_diff($tags, $diffTags);
		if (!empty($newTags)) {
			if (!$this->_createNewTags($newTags, $memberId, $username, $err)) return false;
			
			$newRs = m('Tags')->find(array('where'=>array('name'=>$newTags)));
			$addRs = array_merge($addRs, $newRs);
		}
		
		// new post tags records.
		$tagIds = array();
		foreach($addRs as $rs) { $tagIds[] = $rs['pk_id']; }
		if (!empty($tagIds)) {
			return $this->_newPostTagRecords($post, $tagIds, $err);
		}
		
		return true;
	}
	
	function _formatTags($tags) {
		$rtags = explode(',', $tags);
		$rtags = array_map('_format_post_tag', $rtags);
		return $rtags;
	}
	
	function _createNewTags($newTags, $memberId, $username, &$err=null) {
		$now = mdate('%Y-%m-%d %H:%i:%s');
		$insVals = array();
		foreach($newTags as $tag) {
			$insVals[] = "('{$tag}', {$memberId}, '{$username}', '{$now}')";
		}
		$valStm = implode(',', $insVals);
		$sql = "INSERT INTO `tags`(`name`, `fk_member_id`, `fk_user_name`, `created_at`) VALUES {$valStm};";
		if (Db::getInstance()->query($sql) === false) {
			$err = Db::getInstance()->error(); 
			return false;
		}
		
		return true;
	}
	
	function _newPostTagRecords($post, $tagIds, &$err=null) {
		$postId = $post['pk_id'];
		$insVals = array();
		foreach($tagIds as $tagid) {
			$insVals[] = "({$postId}, $tagid)";
		}
		$valStm = implode(',', $insVals);
		$sql = "INSERT INTO `post_tags`(`fk_post_id`, `fk_tag_id`) VALUES {$valStm};";
		if(Db::getInstance()->query($sql) === false) {
			$err = Db::getInstance()->error();
			return false;
		}
		
		return true;
	}
	
	function _updatePostTagStatus($post, $statusTagIds, &$err=null) {
		$postId = $post['pk_id']; 
		$insVals = array();
		foreach($statusTagIds as $statusTagId) {
			$status = $statusTagId['status'];
			$tagIds = $statusTagId['tagids'];
			foreach($tagIds as $tagid) {
				$insVals[] = "({$postId}, $tagid, $status)";
			}
		}
		
		$valStm = implode(',', $insVals);
		$sql = "INSERT INTO `post_tags`(`fk_post_id`, `fk_tag_id`, `status`) VALUES {$valStm}" 
					. " ON DUPLICATE KEY UPDATE `status` = VALUES(`status`); ";
		if(Db::getInstance()->query($sql) === false) {
			$err = Db::getInstance()->error();
			return false;
		}
		
		return true;
	}
}