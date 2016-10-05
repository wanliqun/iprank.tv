<?php

class Members extends AppModel{
	public $tableName = 'members';
	
	function getPaginatedMembers($condition, $pageSize=15, &$pager=null) {
		c('pagesize', $pageSize);
		$modelPager = new Corex_ModelPager($this, $condition);
		$modelPager->exec();
		$members = $modelPager->getRs();
		$pager = $modelPager->getPager();
	
		return $members;
	}
	
	function getMembers($condition, $getFb=FALSE) {
		$members = $this->find($condition);
		if (empty($members)) return;
		
		// set default cover & avtar 
		foreach($members as &$member) {
			if (empty($member['cover_url'])) {
				$member['cover_url'] = Resources::defaultMemberCover();
			}
			if (empty($member['avatar_url'])) {
				$member['avatar_url'] = Resources::defaultMemberAvatar();
			}
			unset($member);
		}
		
		// iso2 countries
		$countryISO2s = array();
		foreach($members as $member) {
			$iso2 = $member['fk_country_iso2'];
			if (empty($iso2)) continue;
			
			$iso2 = strtoupper($iso2);
			$countryISO2s[] = $iso2;
		}
		$countryISO2s = array_unique($countryISO2s);
		$condition = array('where'=>array('iso2'=>$countryISO2s), );
		$countries = m('Countries')->getCountriesKeyByIso2($condition);
		
		foreach($members as &$member) {
			$iso2 = $member['fk_country_iso2'];
			if (empty($iso2)) {
				unset($member);
				continue;
			}
			
			$iso2 = strtoupper($iso2);
			$member['country'] = $countries[$iso2];
			unset($member);
		}
		
		// facebook detail
		if ($getFb) {
			$memberIds = array();
			foreach($members as &$member) {
				if ($member['is_fbconnected']) {
					$memberIds[] = $member['pk_id'];
				}
				unset($member);
			}
			
			$condition = array('where'=>array('fk_member_id'=>$memberIds));
			$memberFbs = m('MembersFacebook')->getMemberfbsKeyByMemberid($condition);
			foreach($members as &$member) {
				$member['facebook'] = $memberFbs[$member['pk_id']];
				unset($member);
			}
		}
		
		return $members;
	}
	
	function isUsernameAvailable($username) {
		return !$this->count(array('username'=>$username));
	}
	
	function isEmailAvailable($email) {
		return !$this->count(array('email'=>$email));
	}
}