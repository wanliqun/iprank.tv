<?php

class MembersFacebook extends AppModel{
	public $tableName = 'members_facebook';
	
	function getMemberfbsKeyByMemberid($condition=null) {
		$memberFbs = $this->find($condition);
		$idKeyedFbs = array();
		foreach($memberFbs as $memberFb) {
			$idKeyedFbs[$memberFb['fk_member_id']] = $memberFb;
		}
		
		return $idKeyedFbs;
	}
}