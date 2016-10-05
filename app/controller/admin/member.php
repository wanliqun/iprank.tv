<?php

class Member extends AdminController{
	function index() {
		$availableTabs = array('normal', 'blocked', 'trash', 'verified', 'unverified', 'all');
		$tab = $this->app->getRequest('tab', 'normal');
		if (!in_array($tab, $availableTabs)) {
			redirect(_404());
		}
		
		$result = array( 'tab'=>$tab, 'availableTabs'=>$availableTabs, );
		$condition = array('order'=>'member_since DESC');
		switch($tab) {
			case 'normal':
				$condition['where'] = array('status'=>1); break;
			case 'blocked':
				$condition['where'] = array('status'=>0); break;
			case 'trash':
				$condition['where'] = array('status'=>-1); break;
			case 'verified':
				$condition['where'] = array('is_verified'=>1); break;
			case 'unverified':
				$condition['where'] = array('is_verified'=>0); break;
		}
		$pager = null;
		$result['members'] = m('members')->getPaginatedMembers($condition, 25, $pager);
		$result['pager'] = $pager;
		
		return $result;
	}
	
	function adminuser() {
		$userid = $this->app->getRequest('userid', 0);
		$status = $this->app->getRequest('status', 1);
		
		m('LibMember')->adminuser($userid, $status);
		redirect(get_redirect_url());
	}
	
	function featurepost() {
		$postid = $this->app->getRequest('pid', 0);
		$status = $this->app->getRequest('status', -1);
		
		m('LibMember')->featurePost($postid, $status);
		redirect(get_redirect_url());
	}
	
	function reviewpost() {
		$postid = $this->app->getRequest('pid', 0);
		$status = $this->app->getRequest('status', 0);
		
		m('LibMember')->reviewpost($postid, $status);
		redirect(get_redirect_url());
	}
	
	function signin() {
		if ($_POST['signin_now']) {
			$libMember = m('LibMember'); $errors = array(); 
			import('corex/validation/MemberSigninForm');
			$validation = new MemberSigninForm();
			
			if ($validation->check($_POST) === true) {
				$errtip = '';
				if($libMember->signin($_POST, $errtip)) {
					redirect(_admin(array('c'=>'home')));
				} else $errors[] = $errtip;
			} else {
				$errors = $validation->errors;
			}
			
			return array('errors'=>$errors);
		}
	}
	
	function checkAuth(){
		if (!in_array($this->app->actionName, array('signin'))) {
			parent::checkAuth();
		}
	}
}
