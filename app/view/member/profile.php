<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-profile-wrapper">
		<section class="ip-left-panel">
			<header>
				<div class="cover-img-layer" style="background-image:url('<?=$member['cover_url']?>'); ">
					<?php $isMe=(ME::id() == $member['pk_id']); ?>
					<?php if($isMe): ?>
					<div class="cover-action-area" >
						<input type="file" id="input-cover-img" accept="image/*" style="display:none;" />
						<button class="btn btn-sm btn-change-cover">
							<i class="fa fa-pencil"></i><span>&nbsp;Change Cover</span>
						</button>
						<div class="action-confirm-area action-cover-confirm-area">
							<button class="btn btn-sm btn-confirm btn-cover-confirm-ok">
								<img src="<?=media('ok.png', 'images')?>" alt='[OK]' /><span> OK</span>
							</button>
							<button class="btn btn-sm btn-confirm btn-cover-confirm-cancel">
								<img src="<?=media('cancel.png', 'images')?>" alt="[Cancel]" /><span> Cancel</span>
							</button>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div class="avatar-info-wrapper">
					<div class="avatar-info-box">
						<img class="avatar-thumbnail" alt="[<?=$member['username']?>]" src="<?=$member['avatar_url']?>" />
						<?php if($isMe): ?>
						<div class="cover-action-area">
							<input type="file" id="input-avatar-img" accept="image/*" style="display:none;" />
							<button class="btn btn-default btn-change-avatar">
								<i class="fa fa-pencil"></i><span>&nbsp;Change Avatar</span>
							</button>
							<div class="action-confirm-area action-avatar-confirm-area">
								<button class="btn btn-sm btn-confirm btn-avatar-confirm-ok">
									<img src="<?=media('ok.png', 'images')?>" alt='[OK]' /><span> OK</span>
								</button>
								<button class="btn btn-sm btn-confirm btn-avatar-confirm-cancel">
									<img src="<?=media('cancel.png', 'images')?>" alt="[Cancel]" /><span> Cancel</span>
								</button>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="profile-nav-wrapper">
					<div class="profile-info-box">
						<p><span class="username"><?=$member['username']?></span></p>
						<?php 
							$gender=$member['gender']; $genderSymbol='';
							if ($gender=='Male') { $gender='Male, '; $genderSymbol='♂, '; }
							elseif ($gender=='Female') { $gender='Female, '; $genderSymbol='♀, '; }
							
							$age=0; $ageAbbr=''; $ageDetail=''; $birthday = $member['birthday'];
							if (!empty($birthday)) {
								$age=get_age_from_birthday($birthday); $ageAbbr=$age.' y/o, '; $ageDetail=$age.' years old, ';
							}
						
							$country=$member['country']; $fromAbbr=''; $fromDetail='';
							if(!empty($country)) {
								$fromAbbr=$country['iso3']; $fromDetail="from {$country['short_name']}";
							}
							if (empty($ageAbbr) && empty($fromAbbr)) { $fromAbbr= $fromDetail="maybe from Mars"; }
						?>
						<p>	
							<span class="detail" title="<?=$gender?><?=$ageDetail?><?=$fromDetail?>">
								<?=$genderSymbol?><?=$ageAbbr?><?=$fromAbbr?>
							</span>
						</p>
					</div>
					<div class="profile-nav-box" id="sm-profile-navbox">
						<ul>
							<li class="tab-posts">Posts</li>
							<li class="tab-likes">Likes</li>
							<li class="tab-favourites">Favourites</li>
							<?php if($isMe): ?><li class="tab-settings">Settings</li><?php endif; ?>
						</ul>
					</div>
				</div>
				<div class="profile-nav-box" id="xs-profile-navbox">
					<ul>
						<li class="tab-posts">Posts</li>
						<li class="tab-likes">Likes</li>
						<li class="tab-favourites">Favourites</li>
						<?php if($isMe): ?><li class="tab-settings">Settings</li><?php endif; ?>
					</ul>
				</div>
			</header>
			<div class="navtab-content">
				<div class="ip-posts-wrapper" style="display:none;">
					<div class='loading-indicator'><img src='/public/images/loading.gif' alt='loading indicator'></img></div>
					<div class="profile-post-cards"></div>
					<div class="ip-pagination-box" style="display:inline-block;"><ul class="bootstrap-paginator" id='posts-paginator'></ul></div>
				</div>
				<div class="ip-comments-wrapper" style="display:none;">
					<div class='loading-indicator'><img src='/public/images/loading.gif' alt='loading indicator'></img></div>	
					<div class="profile-comment-cards"></div>
					<div class="ip-pagination-box" style="display:inline-block;"><ul class="bootstrap-paginator" id='comments-paginator'></ul></div>
				</div>
				<?php if($isMe): ?>
				<?php $redirect = _mp($member['pk_id'], $member['username'], 'settings'); ?>
				<div class="ip-settings-wrapper" style="display:none;">
					<section class="basic-info-settings">
						<header>
							<img src="/public/images/basic-profile.png" class="img-responsive" alt="Basic Profile"/>
							<h1>Edit Basic Information</h1>
						</header>
						<div class="edit-area">
							<form id="form-editprofile" role="form" method="post" action="/?c=ajax&a=editprofile&format=json">
								<input type="hidden" name="userid" value="<?=$member['pk_id']?>" />
								<input type="hidden" name="redirect" value="<?=$redirect?>" />
								<div class="form-group">
									<div class="input-group">
										<label for="username" class="input-group-addon">User Name</label>
										<input type="text" class="form-control" id="username" name="username" value="<?=$member['username']?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<label for="email" class="input-group-addon">Email Address</label>
										<input type="email" class="form-control" id="email" name="email" value="<?=$member['email']?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<label for="gender" class="input-group-addon">Gender</label>
										<div class="form-control sex-form-controls">
											<select class="form-control" id="gender" name="gender">
												<?php 
													$maleSel=''; $femaleSel='';
													if($member['gender']=='Male') { $maleSel='selected'; } 
													else { $femaleSel='selected'; }
												?>
												<option <?=$maleSel?>>Male</option><option <?=$femaleSel?>>Female</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<?php 
										$birthday = date_create($birthday);  
										$year = $birthday->format('Y'); $month = $birthday->format('n'); $day = $birthday->format('j');
									?>
									<div class="input-group">
										<label for="birthday"  class="input-group-addon">Birthday</label>
										<div class="form-control birthday-form-controls">
											<select id="birthday-month" name="birthday_month" class="form-control">
												<option value="">Month</option>
												<?php for($i=1;$i<=12;$i++): ?>
												<option <?php if($i==$month): ?>selected<?php endif?>><?=$i?></option>
												<?php endfor; ?>
											</select>
											<select id="birthday-day" name="birthday_day" class="form-control">
												<option value="">Day</option>
												<?php for($i=1;$i<=31;$i++): ?>
												<option <?php if($i==$day): ?>selected<?php endif?>><?=$i?></option>
												<?php endfor; ?>
											</select>
											<select id="birthday-year" name="birthday_year" class="form-control">
												<option value="">Year</option>
												<?php if(empty($year)) $year=1988; ?>
												<?php for($i=2004;$i>=1948;$i--): ?>
												<option <?php if($i==$year): ?>selected<?php endif?>><?=$i?></option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<label for="country" class="input-group-addon">Country</label>
										<div class="form-control country-form-controls">
											<select class="form-control" id="country" name="country">
												<option value="">Where are you from</option>
												<?php foreach($countries as $eachCountry): ?>
												<option value="<?=$eachCountry['iso2']?>" 
													<?php if($eachCountry['iso2']==$country['iso2']):?>selected<?php endif; ?>>
													<?=$eachCountry['short_name']?>
												</option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-default btn-save">Save your change</button>
							</form>
						</div>
					</section>
					<section class="change-pwd-settings">
						<header>
							<img src="/public/images/safety-lock.png" class="img-responsive" alt="Account Safety" />
							<h1>Change your Password</h1>
						</header>
						<div class="edit-area">
							<form role="form" id="form-changepwd" method="post" action="/?c=ajax&a=changepwd&format=json">
								<input type="hidden" name="userid" value="<?=$member['pk_id']?>" />
								<div class="form-group">
									<div class="input-group">
										<label for="old_password" class="input-group-addon">Old Password</label>
										<input type="password" class="form-control" id="old-password" name="old_password" 
											placeholder="Enter your current password" required="required">
									</div>
									<span class="error-tip old-password-errtip" style="display:none"></span>
								</div>
								<div class="form-group">
									<div class="input-group">
										<label for="new_password" class="input-group-addon">New Password</label>
										<input type="password" class="form-control" id="new-password" name="new_password" 
											placeholder="Enter your new password" required="required">
									</div>
									<span class="error-tip new-password-errtip" style="display:none"></span>
								</div>
								<div class="form-group">
									<div class="input-group">
										<label for="confirm_password" class="input-group-addon">Confirm Password</label>
										<input type="password" class="form-control" id="confirm-password" name="confirm_password" 
											placeholder="Confirm your new password" required="required">
									</div>
									<span class="error-tip confirm-passord-errtip" style="display:none"></span>
								</div>
								<span class="result-tip changepwd-tip" style="display:none"></span>
								<button type="submit" class="btn btn-default btn-change-pwd">Change Your Password</button>
							</form>
						</div>
					</section>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<aside class="ip-sidebar">
			<section class="ads-container" style="margin-top:10px;">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Sidebar-Responsive-AdUnit-06 -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="ca-pub-1824985805781840"
				     data-ad-slot="1318185615"
				     data-ad-format="auto"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
			</section>
		</aside>
	</div>
</div>