<?php

class MembersSecrets extends AppModel{
	public $tableName = 'members_secrets';
	
	function addSecretToken($userId, $secretType, $secretToken, $expires=2592000, &$err=null) {
		$now = time();
		$toexpire = time() + $expires;
		$createdAt = mdate('%Y-%m-%d %H:%i:%s', $now);
		$expiredAt = mdate('%Y-%m-%d %H:%i:%s', $toexpire);
		$data = array(
			'fk_member_id'=>$userId, 'secret_type'=>$secretType, 'secret_value'=>$secretToken,
			'created_at'=>$createdAt, 'expired_at'=>$expiredAt,
		);
		
		$condition = array('where'=>array('fk_member_id'=>$userId));
		$rs = end($this->find($condition));
		if (empty($rs)) {
			if (!$this->add($data)) {
				$err = $this->getError(); return false;
			}
		} else {
			if (!$this->update($data, $condition)) {
				$err = $this->getError(); return false;
			}
		}
		
		return true;
	}
	
	function validateSecretToken($secretToken, &$secretDetail=null, &$err=null) {
		$condition = array('where'=>array('secret_value'=>$secretToken));
		$secretDetail = end($this->find($condition));
		
		if (!empty($secretDetail)) {
			$toexpire = get_timestamp($secretDetail['expired_at']);
			if ($toexpire >= time()) return true;
			else $err = "Token is already expired.";
		} else {
			$err = "The token is invalid.";
		}
		return false;
	}
	
	function invalidateSecretToken($secretToken, &$err=null) {
		$expiredAt = mdate('%Y-%m-%d %H:%i:%s', 1);
		$data = array('expired_at'=>$expiredAt,);
		$condition = array('where'=>array('secret_value'=>$secretToken));
		if(!$this->update($data, $condition)) {
			$err = $this->getError(); 
			return false;
		}
		
		return true;
	}
}