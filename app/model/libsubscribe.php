<?php

class LibSubscribe {
	function __construct() {
	}
	
	function subscribeEmail($email, &$err=null) {
		$condition = array('where'=>array('email'=>$email));
		$mMembersModel = m('Members');
		$member = end($mMembersModel->find($condition));
		
		if (!empty($member)) {
			if (!$member['is_subscribed']) {
				$subInfo = array('is_subscribed'=>1);
				if(!$mMembersModel->update($subInfo, $condition)) {
					$err = $mMembersModel->getError(); return false;
				}
			}
		} else {
			$mSubscribedEmails = m('SubscribedEmails');
			$subscription = $mSubscribedEmails->find($condition);
			$data = array('email'=>$email, 'submitter_ip'=>get_client_ip());
			if (empty($subscription) && !$mSubscribedEmails->add($data)) {
				$err = $mSubscribedEmails->getError(); return false;
			}
		}
		
		return true;
	}
}