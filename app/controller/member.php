<?php

class Member extends AppController{
	var $libMember;

	function __construct($app) {
		parent::__construct($app);
		$this->libMember = m("LibMember");
	}
	
	function index(){
    	return array(
    	);
	}
	
	function signin() {
		return array(
			'pageTitle'=>'iPrank.TV - Sign in', 'pageCanonical'=>_signin(),
			'pageDescription'=>"Sign in to iPrank.TV with your account or "
					."connect us with your Facebook account.",
			'pageJs' => array('member/signin.js'), 'redirect'=>get_redirect_url(),
		);
	}
	
	function signup() {
		require_once (APP_PATH . '/corex/solvemedialib.php');
		return array(
			'pageTitle'=>'iPrank.TV - Sign up', 'pageCanonical'=>_signup(),
			'pageDescription'=>"Sign up to iPrank.TV with your eamil or "
					."connect us with your Facebook account.",
			'pageJs' => array('member/signup.js'),
			'countries'=>m('Countries')->getCountries(),
		);
	}
	
	function signoff() {
		$this->libMember->signoff();
		redirect(get_redirect_url());
	}
	
	function activate() {
		$ok = false; $result = array(
			'pageTitle'=>'iPrank.TV - Activate your account',
			'pageCanonical'=>_act_acct_link(),
			'pageDescription'=>"Activate your iPrank account",
		);
		
		$verificationCode = $this->app->getRequest('code', '');
		if (!empty($verificationCode)) {
			if(!$this->libMember->activate($verificationCode)) {
				$result['error'] = 'Invalid account activation code.';
			}
		}
		return $result;
	}
	
	function reset_password() {
		$result = array(
			'pageTitle'=>'iPrank.TV - Reset your password',
			'pageCanonical'=>_pwd_reset_link(),
			'pageDescription'=>"Reset the password of your iPrank account",
		);
		
		$token = $this->app->getRequest('token', '');
		if (empty($token)) {
			$result['hideFrom'] = true;
			$result['errtip'] = 'A valid token is required for this opeartion.';
			return $result;
		}
		
		$err = NULL; $secret = NULL;
		$mMemberSecrets = m('MembersSecrets');
		if (!$mMemberSecrets->validateSecretToken($token, $secret, $err)) {
			$result['hideFrom'] = true; $result['errtip'] = $err; 
			return $result;
		}
		
		if (!empty($_POST['reset_password'])) {
			$libMember = m('LibMember');
			import('corex/validation/ChangePwdForm');
			$validation = new ChangePwdForm($libMember, false);
			$_POST['userid'] = $secret['fk_member_id'];
			if ($validation->check($_POST) !== true) {
				$result['errors'] = $validation->errors;
				return $result;
			}
			
			if ($mMemberSecrets->invalidateSecretToken($token, $err)) {
				if ($libMember->changePwd($_POST, $err)) {
					$result['successtip'] = 'You password is already reset.';
					return $result;
				}
			}
			
			$result['errtip'] = $err; 
			return $result;
		}
	}
	
	function profile() {
		$mid = $this->app->getRequest('id', '');
		$mname = $this->app->getRequest('name', '');
		
		$condition = array();
		if (!empty($mid)) $condition['where'] = array('pk_id'=>$mid);
		else if (!empty($mname)) $condition['where'] = array('username'=>$mname);
		else redirect(_404());
		$member = end(m('Members')->getMembers($condition));
		
		return array(
			'pageTitle'=>"iPrank.TV - {$member['username']}'s profile page",
			'pageCanonical'=>_mp($member['pk_id'], $member['username']),
			'pageDescription'=>"Check out {$member['username']}'s profile page",
			'pageJs' => array('bootstrap-paginator.min.js', 'member/profile.js'),
			'countries'=>m('Countries')->getCountries(), 'member'=>$member,
		);
	}
	
	function upload() {
		$this->libMember->checkLoginstatus(_upload());
		
		return array(
			'pageTitle'=>"iPrank.TV - Upload your prank", 
			'pageCanonical'=>_upload(),
			'pageDescription'=>"Upload your prank videos to iPrank.TV",
			'pageJs' => array('member/upload.js'),
		);
	}
	
	function fill_upload_detail() {
		$this->libMember->checkLoginstatus();
		
		$ytVid = $this->app->getRequest('ytv_id', '');
		$finalVinfo = array();
		if (!empty($ytVid)) {
			$sdkYouTube = new Corex_YouTube();
			$videoInfo = $sdkYouTube->getYtVideoInfo(array('id'=>$ytVid), 'snippet');
			
			if (!empty($videoInfo)) {
				$item = end($videoInfo->getItems());
				$snippet = $item['snippet'];
				$watchUrl = m('LibYoutube')->assembleYtWatchUri($ytVid);
				
				$finalVinfo = array(
					'title'=>$snippet['title'],
					'description'=>$snippet['description'],
					'thumbnail'=>$snippet['thumbnails']['medium']['url'],
					'src_type'=>'YouTube', 'ytv_id'=>$ytVid, 'watch_url'=>$watchUrl,
				);
			} 
		}
		
		if (empty($finalVinfo)) redirect(_upload());
		$channels = m('LibChannel')->getChannels();
		return array(
			'pageTitle'=>"iPrank.TV - Fill details for your upload", 'pageCanonical'=>_fud(),
			'pageDescription'=>"Fill details for your upload to iPrank.TV",
			'pageJs'=>array('twitter.js', 'bootstrap-tagsinput.min.js', 'member/fill_upload_detail.js'),
			'videoInfo'=>$finalVinfo, 'channels'=>$channels, 'dummies'=>c('dummy_users'),
		);
	}
	
	function edit_post() {
		$postid = intval($this->app->getRequest('pid', 0));
		$post = end(m('posts')->getPosts(
			array('where'=>array('pk_id'=>$postid)), true, false, false, false, true)
		);
		if (empty($post)) redirect(_404()); 
		
		$channels = m('LibChannel')->getChannels();
		return array(
			'pageTitle'=>"iPrank.TV - Edit post",
			'pageCanonical'=>_editp(), 'pageDescription'=>"Edit your post",
			'pageJs'=>array('twitter.js', 'bootstrap-tagsinput.min.js', 'member/editpost.js'),
			'post'=>$post, 'postid'=>$postid,
			'channels'=>$channels, 'dummies'=>c('dummy_users'),
		);
	}
}
