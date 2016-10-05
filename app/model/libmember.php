<?php

class LibMember {
	var $mEncrypter;
	
	function __construct() {
		import('corex/clencrypt');
		$this->mEncrypter = new CLEncrypt();
	}
	
	function checkLoginstatus($redir='') {
		if (!Me::isLogined()) {
			redirect(_signin($redir));
		} 
	}
	
	function signup($formData, &$err=null) {
		$member = $this->_signup($formData, $err);
		if (!empty($member)) {
			$email = $member['email'];
			$username = $member['username'];
			$verificationCode = $member['verification_code'];
			
			$libNotify = m('libnotify');
			if(!$libNotify->sendAccountActivationEmail($email, $username, $verificationCode, $err)) {
				$mModel->remove($memberId);
				return false;
			} 
			
			return true;
		}
	}
	
	function signin($formData, &$err=null) {
		$email = $formData['email'];
		$password = $formData['password'];
		
		$mMembers = m('Members');
		$cond = array('where'=>array('email'=>$email));
		$member = end($mMembers->getMembers($cond));
		
		if (!empty($member)) {
			$encyptPwd = $member['password'];
			$decryptedPwd = $this->mEncrypter->decrypt($encyptPwd, c('encrypt_key'));
			
			if ($password == $decryptedPwd) {
				if (!$member['is_verified'] && !$member['is_fbconnected']) {
					$err = "The email for this account has not been verified yet.";
					return false;
				} else {
					$this->_writeMemberSession($member);
					return true;
				}
			}
		}
		
		$err = "The email or password you entered is incorrect.";
		return false;
	}
	
	function signoff() {
		Session::getInstance()->clear();
	}
	
	function editProfile($formData, &$err=NULL) {
		$memberId = $formData['userid'];
		$member = array();
		
		$gender = $formData['gender'];
		if (!empty($gender))  $member['gender'] = $gender;
		
		$country = $formData['country'];
		if (!empty($country)) $member['fk_country_iso2'] = $country;
		
		$avatarUrl = $formData['avatar_url'];
		if (!empty($avatarUrl)) $member['avatar_url'] = $avatarUrl;
		
		$coverUrl = $formData['cover_url'];
		if (!empty($coverUrl)) $member['cover_url'] = $coverUrl;
		
		$birthMon = $formData['birthday_month'];
		$birthYear = $formData['birthday_year'];
		$birthDay = $formData['birthday_day'];
		if (!empty($birthYear) && !empty($birthMon) && !empty($birthDay)) {
			$birthDate = "{$birthYear}-{$birthMon}-{$birthDay}";
			$member['birthday'] = $birthDate;
		}
		
		if (!empty($member)) {
			$cond = array('where'=>array('pk_id'=>$memberId));
			$mModel = m('members');
			if(!$mModel->update($member, $cond)) {
				$err = $mModel->getError();
				return false;
			}
		}
		
		return true;
	}
	
	function resetPwd($email, &$err=NULL) {
		$cond = array('where'=>array('email'=>$email));
		$mMembersModel = m('Members');
		$member = end($mMembersModel->find($cond));
		if (empty($member)) {
			$err = "No member record found for this email."; 
			return false;
		}
		
		$secret = sha1(uniqid($email, true));
		$expires = 24 * 60 * 60; // 1 days.
		
		$mMembersSecretsModel = m('MembersSecrets');
		if($mMembersSecretsModel->addSecretToken($member['pk_id'], 'pwd_reset', $secret, $expires, $err)) {
			$libNotify = m('libnotify');
			return $libNotify->sendPasswordResetEmail($email, $member['username'], $secret, $err);
		}
	}
	
	function changePwd($formData, &$err=NULL) {
		$userid = $formData['userid'];
		$newPwd = $formData['new_password'];
		$cryptedPwd = $this->mEncrypter->encrypt($newPwd, c('encrypt_key'));
		$member = array('password'=>$cryptedPwd);
		
		$mModel = m('members');
		$cond = array('where'=>array('pk_id'=>$userid));
		if ($mModel->update($member, $cond)) {
			if (ME::isLogined()) {
				Session::getInstance()->set('password', $cryptedPwd);
			}
		} else { 
			$err = $mModel->getError(); 
			return false;
		}
		
		return true;
	}
	
	function adminuser($userid, $status, &$err=null) {
		$condition = array('where'=>array('pk_id'=>$userid));
		$update = array('status'=>$status);
		
		$mMembersModel = m('Members');
		if($mMembersModel->update($update, $condition) === false) {
			$err = $mMembersModel->getError();
			return  false;
		}
		
		return true;
	}
	
	function likePost($userid, $username, $postid, &$data=null, &$err=null) {
		return $this->_likeOrDislikePost('like', $userid, $username, $postid, $data, $err);
	}
	
	function dislikePost($userid, $username, $postid, &$data=null, &$err=null) {
		return $this->_likeOrDislikePost('dislike', $userid, $username, $postid, $data, $err);
	}
	
	function favourPost($userid, $username, $postid, &$data=null, &$err=null) {
		$mFavoredPostsModel = m('FavoritedPosts');
		$where = array('fk_post_id'=>$postid, 'fk_member_id'=>$userid);
		$condition = array('where'=>$where);
		$post = end($mFavoredPostsModel->find($condition));
		if(!empty($post)) $data['favorited_value'] = $post['favorited_value'];
		else $data['favorited_value'] = 0;
		
		$sofarsogood = false;
		if (!empty($post)) {
			$update['favorited_value'] = abs(intval($post['favorited_value'])-1);
			if($mFavoredPostsModel->update($update, $condition)) {
				$sofarsogood = true; 
				$data['favorited_value'] = $update['favorited_value'];
			} else $err = $mFavoredPostsModel->getError();
		} else {
			$favedAt = mdate('%Y-%m-%d %H:%i:%s');
			$post = array(
				'fk_post_id'=>$postid, 'fk_member_id'=>$userid, 'fk_user_name'=>$username,
				'favorited_at'=>$favedAt, 'favorited_value'=>1,
			);
			if ($mFavoredPostsModel->add($post)) {
				$sofarsogood = true; $data['favorited_value'] = 1;
			} else $err = $mFavoredPostsModel->getError();
		}
		
		return $sofarsogood;
	}
	
	function featurePost($postid, $status=1, &$err=null) {
		if ($status == 1) {
			$data = array('status'=>1); 
			$condition = array('where'=>array('pk_id'=>$postid));
			$mPostModel = m('posts');
			if($mPostModel->update($data, $condition) === false) {
				$err = $mPostModel->getError(); 
				return  false;
			}
		}
		
		$condition = array('where'=>array('fk_post_id'=>$postid));
		$data = array('status'=>$status, 'featured_at'=>mdate('%Y-%m-%d %H:%i:%s'));
		$mFeaturedPostsModel = m('FeaturedPosts');
		$featurePost = end($mFeaturedPostsModel->find($condition));
		
		if (empty($featurePost)) {
			$data['fk_post_id'] = $postid;
			if($mFeaturedPostsModel->add($data) === false) {
				$err = $mFeaturedPostsModel->getError();
				return false;
			}
		} else if($featurePost['status'] != $status) {
			if($mFeaturedPostsModel->update($data, $condition) === false) {
				$err = $mFeaturedPostsModel->getError();
				return false;
			}
		}
		
		return true;
	}
	
	function reviewpost($postid, $status=1, &$err=null) {
		$data = array('status'=>$status);
		$condition = array('where'=>array('pk_id'=>$postid));
		$mPostsModel = m('posts');
		
		if($mPostsModel->update($data, $condition) === false) {
			$err = $mPostsModel->getError();
			return false;
		} else {
			$mCrawlerYtVideosModel = m('CrawlerYtVideos');
			$condition = array('where'=>array('fk_post_id'=>$postid));
			$mCrawlerYtVideosModel->update(array('status'=>$status), $condition);
		}
		
		if ($status != 1) {
			return $this->featurePost($postid, -1, $err);
		}
		
		return true;
	}
	
	function reportSpam($userid, $username, $postid, &$err=null) {
		$mSpamPostsModel = m('SpamPosts');
		$where = array('fk_post_id'=>$postid, 'fk_member_id'=>$userid);
		$condition = array('where'=>$where);
		$spam = end($mSpamPostsModel->find($condition));
		
		if (empty($spam)) {
			$spam = array(
				'fk_post_id'=>$postid, 'fk_member_id'=>$userid, 'fk_user_name'=>$username,
				'reported_at'=>mdate('%Y-%m-%d %H:%i:%s'),
			);
			if (!$mSpamPostsModel->add($spam)) {
				$err = $mSpamPostsModel->getError(); return false;
			}
		}
		
		return true;
	}
	
	function changeCover($userid, $username, $coverPath, &$err=null) {
		$condition = array('where'=>array('pk_id'=>$userid, 'username'=>$username));
		$coverInfo = array('cover_url'=>$coverPath);
		
		$mMemberModel = m('Members');
		if(!$mMemberModel->update($coverInfo, $condition)) {
			$err = $mMemberModel->getError(); return false;
		}
		
		return true;
	}
	
	function changeAvatar($userid, $username, $avatarPath, &$err=null) {
		$condition = array('where'=>array('pk_id'=>$userid, 'username'=>$username));
		$avatarInfo = array('avatar_url'=>$avatarPath);
		
		$mMemberModel = m('Members');
		if(!$mMemberModel->update($avatarInfo, $condition)) {
			$err = $mMemberModel->getError(); return false;
		}
		
		return true;
	}
	
	function checkChangePwdForm($fieldValue, $field='', $validation=null){
		helper('validation');
		
		if ($field == 'old_password') {
			$oldPwd = $validation->data['old_password'];
			$myPwd = $this->mEncrypter->decrypt(ME::password(), c('encrypt_key'));
			
			if ($oldPwd != $myPwd) {
				$validation->setError($field, "Wrong password.");
				return false;
			}
		}
		
		if(in_array($field, array('confirm_password', 'new_password'))
			&& ($validation->data['confirm_password'] != $validation->data['new_password']))
		{
			$validation->setError($field, "Two passwords don't match.");
			return false;
		}
		
		return true;
	}
	
	function checkSignupForm($fieldValue, $field='username', $validation=null){
		helper('validation');
		
		if($field=='username'){
			if (!is_valid_username($fieldValue)) {
				$validation->setError($field, 'User name is not valid.');
				return false;
			} 
			if(!m('members')->isUsernameAvailable($fieldValue)) {
				$validation->setError($field, 'User name is already taken.');
				return false;
			} 
		}
		
		if ($field=='email') {
			if(!m('members')->isEmailAvailable($fieldValue)) {
				$validation->setError($field, 'Email is already taken.');
				return false;
			}
		}
		
		if(in_array($field, array('confirm_password', 'password'))
			 && ($validation->data['password'] != $validation->data['confirm_password'])) 
		{
			$validation->setError($field, "Two passwords don't match.");
			return false;
		}
		
		if ($field == 'adcopy_response') {
			require_once (APP_PATH . '/corex/solvemedialib.php');
			$vkey = c('sm_vkey'); $hkey = c('sm_hkey');
			$solvemedia_response = solvemedia_check_answer($vkey,
					$_SERVER["REMOTE_ADDR"], $validation->data["adcopy_challenge"],
					$validation->data["adcopy_response"], $hkey);
			if (!$solvemedia_response->is_valid) {
				$validation->setError($field, $solvemedia_response->error);
				return false;
			}
		}
		
		return true;
	}
	
	function activate($verifyCode) {
		$mModel = m('members');
		$condition = array('where'=>array('verification_code'=>$verifyCode));
		$data = array('is_verified'=>1);
		if ($mModel->update($data, $condition)) {
			$member = end(m('Members')->find($condition));
			$this->_writeMemberSession($member);
			return true;
		}
	}
	
	function isFbConnected($email) {
		$mMembers = m('Members');
		$cond = array('where'=>array('email'=>$email, 'is_fbconnected'=>'1'));
		return $mMembers->count($cond) > 0;
	}
	
	function connectFb($member, $session, $profile, &$err=null) {
		if ($member['is_fbconnected']) {
			$this->_writeMemberSession($member);
			return true;
		}
		
		$data = $this->_adaptFbAccount($session, $profile);
		$fbData = $data['facebook'];
		$fbData['fk_member_id'] = $member['pk_id'];
		
		if($this->_saveFbProfile($fbData, $err)) {
			$mMembers = m('Members');
			$cond = array('where'=>array('pk_id'=>$member['pk_id']));
			$updates = array('is_fbconnected'=>1, 'avatar_url'=>$data['avatar_url']);
			
			if (!$mMembers->update($updates, $cond)) {
				$cond = array('where'=>array('fk_member_id'=>$member['pk_id']));
				$mFbMembers = m('MembersFacebook');
				$mFbMembers->remove($cond);
				$err = $mMembers->getError();
			} else {
				$member['avatar_url'] = $data['avatar_url'];
				$this->_writeMemberSession($member);
				return true;
			}
		}		
	}
	
	function signupFb($formData, &$err=null) {
		$mFacebook = new Corex_Facebook();
		$session = null;
		$prof = $mFacebook->getFbProfile($session, $err);
	
		if(!empty($prof)) {
			$data = $this->_adaptFbAccount($session, $prof);
			$formData = array_merge($formData, $data);
			$member = $this->_signup($formData, $err);
	
			if (!empty($member)) {
				$fbData = $formData['facebook'];
				$fbData['fk_member_id'] = $member['pk_id'];
				$mFbMembers = m('MembersFacebook');
				if(!$mFbMembers->add($fbData)) {
					$cond = array('where'=>array('pk_id'=>$member['pk_id']));
					$mMembers = m('Members');
					$mMembers->remove($cond);
					
					$err = $mFbMembers->getError();
				} else {
					$this->_writeMemberSession($member);
					return true;
				}
			}
		}
	}
	
	function publishPost($formData, &$err=null) {
		$post = m('libPost')->newPost($_POST, $err);
		if (empty($post)) return false;
		
		$tags = $formData['tags'];
		if (!empty($tags)) {
			if (!m('LibTags')->addTagsForPost($tags, $post, $err)) return false;
		}
		
		$pvUrl = _pv($post['pk_id'], $post['btitle'], $post['type']);
		$shareFb = $formData['share_fb'];
		if ($shareFb) {
			$mFacebook = new Corex_Facebook();
			$mFacebook->postFbLink($sess=null, $post['btitle'], $pvUrl, $err);
		}
		
		$shareTw = $formData['share_tw'];
		if ($shareTw) {
			$tokenCredentials = $_SESSION['tw_token_credentials'];
			if (!empty($tokenCredentials)) {
				$msg = $post['btitle'];
				if (mb_strlen($msg) > 117) {
					$truncated = mb_substr($msg, 0, 114);
					$truncated .= '...';
					$msg = $truncated;
				}
				$statusText = $msg . ' ' . $pvUrl;
				$mTwitter = new Corex_Twitter();
				$mTwitter->tweetTwStatus($tokenCredentials, $statusText, $err);
			}
		}
		
		return $post;
	}
	
	function editPost($post, $formData, &$err=null) {
		if (!m('LibPost')->updatePost($post, $formData, $err)) return false;
		$tags = $formData['tags'];
		if (!empty($tags)) {
			if (!m('LibTags')->updateTagsForPost($tags, $post, $err)) return false;
		}
		
		return true;
	}
	
	private function _writeMemberSession($member) {
		Session::getInstance()->set('userid', $member['pk_id']);
		Session::getInstance()->set('username', $member['username']);
		Session::getInstance()->set('password', $member['password']);
		Session::getInstance()->set('email', $member['email']);
		Session::getInstance()->set('avatar', _mavatar($member));
		Session::getInstance()->set('cover', $member['cover_url']);
	}
	
	private function _adaptFbAccount($session, $profile) {
		$fbUserId = $profile->getProperty('id');
		$fbBirthday = $profile->getProperty('birthday');
		$fbEmail = $profile->getProperty('email');
		$fbName = $profile->getProperty('name');
		$fbGender = $profile->getProperty('gender');
		$fbLink = $profile->getProperty('link');
		$fbVerified = $profile->getProperty('verified');
		$fbTimezone = $profile->getProperty('timezone');
		$fbLocale = $profile->getProperty('locale');
		$fbHometown = $profile->getProperty('hometown');
		$fbLocationId = $fbHometown->getProperty('id');
		$fbLocationName = $fbHometown->getProperty('name');
		$fbAccessToken = $session->getToken();
		
		$data = array(
			'email'=>$fbEmail, 'source'=>'Facebook', 
			'is_fbconnected'=>1, 'gender'=>ucfirst($fbGender),
			'is_verified'=>$fbVerified,
		);
		$data['country'] = trim(end(explode(',', $fbLocationName)));
		$data['avatar_url'] = _fb_picture($fbUserId);
		
		$birthDate = DateTime::createFromFormat('m/d/Y', $fbBirthday);
		$data['birthday_month'] = $birthDate->format("m");
		$data['birthday_day'] = $birthDate->format("d");
		$data['birthday_year'] = $birthDate->format("Y");
		
		$data['facebook'] = array(
			'fb_user_id' => $fbUserId, 'fb_access_token' => $fbAccessToken,
			'fb_name' => $fbName, 'fb_email' => $fbEmail, 'fb_link' => $fbLink,
			'fb_locale' => $fbLocale, 'fb_timezone' => $fbTimezone,
			'fb_location_id' => $fbLocationId, 'fb_location_name' => $fbLocationName,
		);
		
		return $data;
	}
	
	private function _signup($formData, &$err=null) {
		$passwd = $formData['password'];
		$cryptedPwd = $this->mEncrypter->encrypt($passwd, c('encrypt_key'));
		$username = $formData['username'];
		$email = $formData['email'];
		$fromIp = get_client_ip();
		$member = array(
			'username'=>$username,
			'email'=>$email,
			'password'=>$cryptedPwd,
			'from_ip'=>$fromIp,
		);
		
		$source = $formData['source'];
		if (!empty($source)) {
			$member['source'] = $source;
		}
		
		$isFbConnected = $formData['is_fbconnected'];
		if (!empty($isFbConnected)) {
			$member['is_fbconnected'] = $isFbConnected;
		}
		
		$gender = $formData['gender'];
		if (!empty($gender)) {
			$member['gender'] = $gender;
		}
		
		$country = $formData['country'];
		if (!empty($country)) {
			$condition = array(
				'where'=>array('iso2'=>$country, 'short_name'=>$country, '__logic'=>'OR'),
				'limit'=>1,
			);
			$country = end(m('Countries')->find($condition));
			if (!empty($country)) $member['fk_country_iso2'] = $country['iso2'];
		}
		
		$avatarUrl = $formData['avatar_url'];
		if (!empty($avatarUrl)) {
			$member['avatar_url'] = $avatarUrl;
		}
		
		$birthMon = $formData['birthday_month'];
		$birthYear = $formData['birthday_year'];
		$birthDay = $formData['birthday_day'];
		if (!empty($birthYear) && !empty($birthMon) && !empty($birthDay)) {
			$birthDate = "{$birthYear}-{$birthMon}-{$birthDay}";
			$member['birthday'] = $birthDate;
		}
		
		$member['is_subscribed'] = intval($formData['subscribe']);
		$member['member_since'] = mdate('%Y-%m-%d %H:%i:%s');
		$verificationCode = md5(time() + $email + $passwd + $fromIp);
		$member['verification_code'] = $verificationCode;
		$member['is_verified'] = $formData['is_verified'];
		
		$mModel = m('members');
		$memberId = $mModel->add($member);
		if (!empty($memberId)) {
			$member['pk_id'] = $memberId;
		} else {
			$err = $mModel->getError();
		}
		
		return $member;
	}
	
	private function _saveFbProfile($fbData, &$error=null) {
		$mFbMembers = m('MembersFacebook');
		if(!$mFbMembers->add($fbData)) {
			$err = $mFbMembers->getError();
			return false;
		}
		return true;
	}
	
	private function _likeOrDislikePost($action='like', $userid, $username, $postid, &$data=null, &$err=null) {
		$mLikedPostsModel = m('LikedPosts');
		$where = array('fk_post_id'=>$postid, 'fk_member_id'=>$userid);
		$condition = array('where'=>$where);
		$post = end($mLikedPostsModel->find($condition));
		if(!empty($post)) $data['liked_value'] = $post['liked_value'];
		else $data['liked_value'] = 0;
	
		$mStatisticsModel = m('PostStatistics');
		$where = array('fk_post_id'=>$postid);
		$sofarsogood = false;
		if ($post['liked_value'] > 0) {
			if ($action == 'like') {
				if($mStatisticsModel->setDec('newly_liked', $where)) {
					$data['liked_value'] = 0; $sofarsogood = true;
				} else $err = $mStatisticsModel->getError();
			} else {
				if ($mStatisticsModel->setInc(array('newly_disliked'=>1,'newly_liked'=>-1), $where)) {
					$data['liked_value'] = -1; $sofarsogood = true;
				} else $err = $mStatisticsModel->getError();
			}
		} else if($post['liked_value'] < 0) {
			if ($action == 'like') {
				if ($mStatisticsModel->setInc(array('newly_liked'=>1,'newly_disliked'=>-1), $where)) {
					$data['liked_value'] = 1; $sofarsogood = true;
				} else $err = $mStatisticsModel->getError();
			} else {
				if($mStatisticsModel->setDec('newly_disliked', $where)) {
					$data['liked_value'] = 0; $sofarsogood = true;
				} else $err = $mStatisticsModel->getError();
			}
		} else {
			$field = 'newly_liked'; $likedValue = 1;
			if ($action != 'like') { $field = 'newly_disliked'; $likedValue = -1; }
			if($mStatisticsModel->setInc($field, $where)) {
				$data['liked_value'] = $likedValue; $sofarsogood = true;
			} else $err = $mStatisticsModel->getError();
		}
	
		if ($sofarsogood) {
			if (!empty($post)) {
				if (!$mLikedPostsModel->update($data, $condition)) {
					$sofarsogood = false; $err = $mLikedPostsModel->getError();
				}
			} else {
				$likeAt = mdate('%Y-%m-%d %H:%i:%s');
				$post = array(
						'fk_post_id'=>$postid, 'fk_member_id'=>$userid, 'fk_user_name'=>$username,
						'liked_at'=>$likeAt, 'liked_value'=>$data['liked_value'],
				);
				if (!$mLikedPostsModel->add($post)) {
					$sofarsogood = false; $err = $mLikedPostsModel->getError();
				}
			}
		}
	
		$statistics = $mStatisticsModel->getPostStatistics($postid);
		$data['num_liked'] = $statistics['num_liked'];
		$data['num_disliked'] = $statistics['num_disliked'];
		return $sofarsogood;
	}
}