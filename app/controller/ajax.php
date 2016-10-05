<?php
class Ajax extends AppController {
	var $libAjax;
	
	function __construct($app) {
		parent::__construct($app);
		$this->libAjax = m('LibAjax');
	}
	
	function loadmore() {
		$result = array(); $pageSize = c('ajax_num_loadmore');
		$lastTimestamp = $this->app->getRequest('last_timestamp', -1);
		$ajaxPosts = m('RecentlyPosts')->getMostRecentlyPosts($lastTimestamp, $pageSize);
		
		if (!empty($ajaxPosts)) {
			if (count($ajaxPosts) == $pageSize) {
				$lastPost = end($ajaxPosts);
				$result['lastTimestamp'] = strtotime($lastPost['created_at']);
				$result['hasMore'] = true;
			}
			
			$postids = array();
			foreach($ajaxPosts as $post) {
				$result['html'] .= LibAjax::genAjaxPostHtml($post);
				$postids[] = $post['pk_id'];
			}
			$postidss = implode(',', $postids);
			$params = array('a'=>'post_actionbox', 'postids'=>$postidss);
			$jsuri = _js($params);
			$result['jsUri'] = $jsuri;
		}
		return $result;
	}
	
	function load_mostpopular() {
		$result = array('');
		
		$filter = $this->app->getRequest('filter', 'always');
		$mostPopulars = m('PopularPosts')->getMostPopularPosts($filter);
		
		foreach ($mostPopulars as $popular) {
			$result['html'] .= LibAjax::genAjaxMostPopularHtml($popular);
		}
		
		return $result;
	}
	
	function loadpopular() {
		$result = array();
	
		$acc = $this->app->getRequest('acc', 'most-viewed');
		$filter = $this->app->getRequest('filter', 'always');
		$populars = m('PopularPosts')->getPopularPosts($acc, $filter);
		
		$config = array('showview'=>true); 
		if ($acc == 'most-liked') {
			$config = array('showlike'=>true);
		} else if ($acc == 'most-commented') {
			$config = array('showcomment'=>true);
		}
		
		foreach ($populars as $popular) {
			$result['html'] .= LibAjax::genAjaxPopularHtml($popular, $config);
		}
		
		return $result;
	}
	
	function loadprofile() {
		$username = $this->app->getRequest('username', '');
		if (empty($username)) {
			return array('success'=>false, 'errtip'=>'No user is specified.');
		}
		
		$myProfile = (ME::name() == $username);
		$tab = $this->app->getRequest('tab', 'posts');
		$tab = strtolower($tab);
		switch($tab) 
		{
		case 'comments': break;
		case 'likes': case 'favourites': case 'posts': 
		default:
			$posts = array(); $pager = NULL;
			if ($tab == 'likes') {
				$condition = array( 
					'where'=>array('fk_user_name'=>$username, 'liked_value'=>1), 
					'order'=>'liked_at DESC',
				);
				$posts = m('LikedPosts')->getPaginatedPosts($condition, 16, $pager);
			} else if ($tab == 'favourites') {
				$condition = array(
					'where'=>array('fk_user_name'=>$username), 
					'order'=>'favorited_at DESC',
				);
				$posts = m('FavoritedPosts')->getPaginatedPosts($condition, 16, $pager);
			} else {
				$where = array('fk_user_name'=>$username);
				if ($myProfile) $where['status !='] = -1;
				else $where ['status'] = 1;
				
				$condition = array('where'=>$where, 'order'=>'created_at DESC',);
				$posts = m('Posts')->getPaginatedPosts($condition, 16, $pager);
			}
			
			foreach($posts as $post) {
				$result['html'] .= LibAjax::genAjaxProfileHtml($post, $myProfile);
			}
			if ($pager['totalPages'] > 1) {
				$result['pager'] = $pager;
			}
			break;
		}
		
		return $result;
	}
	
	function editprofile() {
		if ($this->app->getRequest('userid', '0') == ME::id()) {
			$redirect = get_redirect_url(); $errtip = '';
			$libMember = m('LibMember');
			
			if ($libMember->editProfile($_POST, $errtip)) {
				return array('success'=>true, 'redirect'=>$redirect);
			} else {
				return array('success'=>false, 'errtip'=>$errtip, );
			}
		} else {
			return array('success'=>false, 'errtip'=>'You are not authorized to do this action.', );
		}
	}
	
	function resetpwd() {
		helper('validation');
		
		$email = $this->app->getRequest('email', ''); 
		if (empty($email) || !is_valid_email($email)) {
			return array('success'=>false, 'errtip'=>'Invalid email address.', );
		}
		
		$err = ""; $libMember = m('LibMember'); 
		$result = $libMember->resetPwd($email, $err);
		return array('success'=>$result, 'errtip'=>$err, );
	}
	
	function changepwd() {
		if ($this->app->getRequest('userid', '0') == ME::id()) {
			$libMember = m('LibMember');
			import('corex/validation/ChangePwdForm');
			$validation = new ChangePwdForm($libMember);
			
			if ($validation->check($_POST) === true) {
				$errtip = '';
				if ($libMember->changePwd($_POST, $errtip)) {
					return array('success'=>true,);
				}  else {
					return array('success'=>false, 'errtip'=>$errtip, );
				}
			} else {
				$errors = $validation->errors;
				return array('success'=>false, 'errors'=>$errors, );
			}
		} else {
			return array('success'=>false, 'errtip'=>'You are not authorized to do this action.', );
		}
	}
	
	function signin() {
		$libMember = m('LibMember');
		import('corex/validation/MemberSigninForm');
		$validation = new MemberSigninForm();
		
		if ($validation->check($_POST) === true) {
			$errtip = '';
			if ($libMember->signin($_POST, $errtip)) {
				return array('success'=>true, 'redirect'=>get_redirect_url('/'));
			}  else {
				return array('success'=>false, 'errtip'=>$errtip, );
			}
		} else {
			$errors = $validation->errors;
			return array('success'=>false, 'errors'=>$errors, );
		}
	}
	
	function signup() {
		$libMember = m('LibMember');
		import('corex/validation/MemberSignupForm');
		$validation = new MemberSignupForm($libMember);
			
		if($validation->check($_POST) === true) {
			$errtip = '';
			if ($libMember->signup($_POST, $errtip)) {
				return array(
					'success'=>true, 'activation_email'=>$_POST['email'],
				);
			} else {
				return array(
					'success'=>false, 'errtip'=>$errtip,
				);
			}
		} else {
			$errors = $validation->errors;
			return array(
				'success'=>false, 'errors'=>$errors,
			);
		}
	}
	
	function connectfb() {
		$session = null; $error = null;
		$mFacebook = new Corex_Facebook();
		$profile = $mFacebook->getFbProfile($session, $error);
		if (empty($profile)) {
			return array('status'=>'failed', 'error'=>$error);
		}
		
		$mMembers = m('Members');
		$email = $profile->getProperty('email');
		$cond = array('where'=>array('email'=>$email));
		$member = end($mMembers->find($cond));
		
		if (!empty($member)) {
			$libMember = m('LibMember');
			if ($libMember->connectFb($member, $session, $profile, $error)) {
				return array('status'=>'connected', 'redirect'=>get_redirect_url('/'));
			} else {
				return array('status'=>'failed', 'error'=>$error);
			}
		} else {
			$name = $profile->getProperty('name');
			$userid = $profile->getProperty('id');
			return array (
				'status'=>'signup_fb', 'redirect'=>get_redirect_url('/'),
				'fb'=>array(
					'email'=>$email, 'name'=>$name, 'userid'=>$userid,
				),
			);
		}
	}
	
	function signupfb() {
		$libMember = m('LibMember');
		import('corex/validation/FBSignupForm');
		$validation = new FBSignupForm($libMember);
			
		if($validation->check($_POST) === true){
			$errtip = '';
			if($libMember->signupFb($_POST, $errtip)) {
				return array(
					'success'=>true, 'redirect'=>get_redirect_url('/'),
				);
			} else {
				return array('success'=>false, 'errtip'=>$errtip);
			}
		} else {
			$errors = $validation->errors;
			return array(
				'success'=>false, 'errors'=>$errors,
			);
		}
	}
	
	function markviewed() {
		$postid = $this->app->getRequest('postid', 0);
		if (empty($postid)) return array('success'=>false, 'errtip'=>'Post id is empty.');
		
		$mPostStatModel = m('PostStatistics'); $where = array('fk_post_id'=>$postid);
		if($mPostStatModel->setInc('newly_viewed', $where) === false) {
			return array('success'=>false, 'errtip'=>$mPostStatModel->getError());
		}
		return array('success'=>true);
	}
	
	function likepost() { 
		$format = $this->app->getRequest('format', '');
		
		$result = $this->_likeOrDislikePost('like');  
		if (strcasecmp($format, 'json') === 0) return $result;
		else redirect(get_redirect_url());
	}
	
	function dislikepost() { 
		$format = $this->app->getRequest('format', '');
		
		$result = $this->_likeOrDislikePost('dislike');
		if (strcasecmp($format, 'json') === 0) return $result;
		else redirect(get_redirect_url());
	}
	
	function changepicture() {
		if (!ME::isLogined()) return send_http_status(401);
		$dataUrl = $this->app->getRequest('dataurl', '');
		if (empty($dataUrl)) {
			return array (
				'success'=>false, 'errtip'=>'Image data url is empty.',
			);
		}
		
		$picType = $this->app->getRequest('type', 'avatar');
		if (!in_array($picType, array('avatar', 'cover'))) $picType = 'avatar';
		
		$libUpload = m('LibUpload'); $err = null;
		$path = UPLOAD_PATH.DS.'uploads/'.date('Y').'/'.date('m').'/'.date('d');
		$username = ME::name(); $timestamp = time();
		$filename = "{$picType}_{$username}_{$timestamp}";
		if(!$libUpload->uploadImageDataUri($dataUrl, $path, $filename, $err)) {
			return array('success'=>false, 'errtip'=>$err);
		}
		
		$libMember = m('LibMember');
		$dataPath = str_replace(DOCUROOT, '', $path.DS.$filename);
		$method = ($picType == 'cover') ? 'changeCover' : 'changeAvatar';
		if(!$libMember->$method(ME::id(), ME::name(), $dataPath, $err)) {
			return array('success'=>false, 'errtip'=>$err);
		}
		
		return array('success'=>true);
	}
	
	function favourpost() {
		if (!ME::isLogined()) return send_http_status(401);
		$postid = $this->app->getRequest('postid', '');
		if (empty($postid)) return array('success'=>false, 'errtip'=>'Post id is empty.');
		
		$data = array(); $errtip = ''; 
		$success =  m('LibMember')->favourPost(ME::id(), ME::name(), $postid, $data, $errtip);
		return array('success'=>$success, 'stat'=>$data, 'errtip'=>$errtip);
	}
	
	function reportspam() {
		if (!ME::isLogined()) return send_http_status(401);
		$postid = $this->app->getRequest('postid', '');
		if (empty($postid)) return array('success'=>false, 'errtip'=>'Post id is empty.');
		
		$errtip = null;
		$success = m('LibMember')->reportSpam(ME::id(), ME::name(), $postid, $errtip);
		return array('success'=>$success, 'errtip'=>$errtip);
	}
	
	function subscribe_email() {
		helper('validation');
		$format = $this->app->getRequest('format', '');
		$email = $this->app->getRequest('email', '');
		$success = true; $errtip = '';
		if (empty($email)) {
			$success = false; $errtip = 'Email is empty.';
		}
		if($success && !is_valid_email($email)) {
			$success = false; $errtip = 'Not a valid email address.';
		}
		if ($success) {
			$libSubscribe = m('LibSubscribe');
			$success = $libSubscribe->subscribeEmail($email, $errtip);
		}
		
		if (strcasecmp($format, 'json') === 0) {
			return array('success'=>$success, 'errtip'=>$errtip);
		} else {
			$msg = $success ? 'Thanks for your subscription.' : $errtip;
			redirect(get_redirect_url(), 4, $msg);
			exit();
		}
	}
	
	function add_ytvideo() {
		$ytvUri = $this->app->getRequest('ytv_uri', '');
		
		$libYt = m('LibYoutube');
		$ytVid = $libYt->parseYtVid($ytvUri);
		
		if (empty($ytVid) || !$libYt->validateYtVid($ytVid)) {
			return array('success'=>false, 'error'=>'Invalid YouTube video url.');
		}
		
		$embededYtvUri = $libYt->assembleYtEmbededUri($ytVid);
		return array('success'=>true, 'ytv_id'=>$ytVid, 'em_ytv_uri'=>$embededYtvUri);
	}
	
	function fill_uploaddetail() {
		if (!Me::isLogined()) {
			return array('success'=>false, 'errtip'=>'You are not signed in yet.');
		}
		
		import('corex/validation/FillUploadForm');
		$validation = new FillUploadForm();
		if($validation->check($_POST) === true){
			$errtip = '';
			if (!ME::isAdmin()) {
				unset($_POST['status']); unset($_POST['userid']); unset($_POST['username']);
			}
			
			$post = m('LibMember')->publishPost($_POST, $errtip);
			if (empty($post)) {
				return array('success'=>false, 'errtip'=>$errtip);
			} else {
				$redir = _ppv($post['pk_id'], $post['btitle'], $post['type']);
				return array('success'=>true, 'redir'=>$redir);
			}
		} else {
			$errors = $validation->errors;
			return array(
				'success'=>false, 'errors'=>$errors,
			);
		}
	}
	
	function editpost() {
		if (!Me::isLogined()) {
			return array('success'=>false, 'errtip'=>'You are not signed in yet.');
		}
		
		import('corex/validation/FillUploadForm');
		$validation = new FillUploadForm();
		if ($validation->check($_POST) === true) {
			$postid = intval($_POST['postid']);
			$post = end(m('posts')->find($postid));
			if (empty($post)) { 
				return array( 'success'=>false, 'errtip'=>'Empty post to edit!', );
			}
			
			if (!ME::isAdmin()) {
				if ($post['fk_member_id'] != ME::id()) {
					return array( 'success'=>false, 'errtip'=>'Not authorized to do this action.', );
				}
				$_POST['status'] = 0; unset($_POST['userid']); unset($_POST['username']);
			}
			
			$errtip = '';
			if(m('LibMember')->editPost($post, $_POST, $errtip)) {
				return array( 'success'=>true, 'redir'=>_ppv($post['pk_id'], $post['btitle'], $post['type']));
			} else {
				return array( 'success'=>false, 'errtip'=>$errtip);
			}
		} else {
			$errors = $validation->errors;
			return array( 'success'=>false, 'errors'=>$errors, );
		}
	}
	
	function oauth_tw() {
		$mTwitter = new Corex_Twitter();
		$tokenCredentials = $_SESSION['tw_token_credentials'];
		$twOAuthUrl = $mTwitter->getAuthorizeURL();
		
		if (!empty($tokenCredentials)) {
			$verified = $mTwitter->verifyTwCredentials($tokenCredentials);
			if ($verified) return array('authorized'=>TRUE,);
		} 
		
		return array('authorized'=>FALSE, 'oauth_url'=>$twOAuthUrl);
	}
	
	function _likeOrDislikePost($action='like') {
		if (!ME::isLogined()) return send_http_status(401);
		$postid = $this->app->getRequest('postid', '');
		if (empty($postid)) return array('success'=>false, 'errtip'=>'Post id is empty.');
	
		$data = array(); $errtip = ''; $success = false;
		if ($action == 'like') {
			$success = m('LibMember')->likePost(ME::id(), ME::name(), $postid, $data, $errtip);
		} else {
			$success = m('LibMember')->dislikePost(ME::id(), ME::name(), $postid, $data, $errtip);
		}
		
		$data['num_liked'] = number_format($data['num_liked']);
		$data['num_disliked'] = number_format($data['num_disliked']);
		
		return array('success'=>$success, 'stat'=>$data, 'errtip'=>$errtip);
	}
}
