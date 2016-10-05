<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-signup-wrapper">
		<section class="ip-left-panel">
			<h1>Join us today</h1>
			<section class="fb-signup-wrapper">
				<h1>Sign up with Facebook</h1>
				<div class="fb-signup-hints">
					<span>Connecting us with Facebook will allow you to login in with your Facebook  
						credentials and share some awesome stuffs to your Facebook timeline etc.</span>
				</div>
				<div class="fb-signup-button">
					<button type="button" class="btn btn-lg btn-fb-signup" onclick="fb_connect('<?=base_url();?>');">
						<img src="/public/images/fb-connect.png" alt="sign up with facebook" class="img-responsive" style="width:100%;" />
					</button>
					<span class="fb-connect-loading" style="display:none"><i class="fa fa-spinner fa-spin fa-lg"></i></span>
				</div>
			</section>
			<hr class="seperator-line" />
			<section class="email-signup-wrapper">
				<h1>Sign up with your Email</h1>
				<form id="signup-form" role="form" action="/?c=ajax&a=signup&format=json" method="post">
					<input type="hidden" name="signup_now" value="1" />
					<div class="form-group">
						<div class="input-group">
							<label for="username" class="input-group-addon required">User Name</label>
							<input type="text" class="form-control" id="username" name="username" 
								placeholder="Please enter your user name" required="required" >
						</div>
						<span class="error-tip username-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="email" class="input-group-addon required">Email Address</label>
							<input type="email" class="form-control" id="email" name="email" 
								placeholder="Please enter your email address" required="required" >
						</div>
						<span class="error-tip email-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="password" class="input-group-addon required">Password</label>
							<input type="password" class="form-control" id="password" name="password" 
								placeholder="Please enter your password" required="required" >
						</div>
						<span class="error-tip password-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="confirm_password" class="input-group-addon required">Confirm your password</label>
							<input type="password" class="form-control" id="confirm-password" name="confirm_password" 
								placeholder="Please confirm your password" required="required" >
						</div>
						<span class="error-tip confirm-passord-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="birthday"  class="input-group-addon">Birthday</label>
							<div class="form-control birthday-form-controls">
								<select id="birthday-month" name="birthday_month" class="form-control">
									<option value="">Month</option>
									<?php for($i = 1; $i <= 12; $i++): ?><option><?=$i?></option><?php endfor; ?>
								</select>
								<select id="birthday-day" name="birthday_day" class="form-control">
									<option value="">Day</option>
									<?php for($i = 1; $i <= 31; $i++): ?><option><?=$i?></option><?php endfor; ?>
								</select>
								<select id="birthday-year" name="birthday_year" class="form-control">
									<option value="">Year</option>
									<?php for($i = 2004; $i >= 1948; $i--): ?><option><?=$i?></option><?php endfor; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="gender" class="input-group-addon">Gender</label>
							<div class="form-control sex-form-controls">
								<select class="form-control" id="gender" name="gender">
									<option value="">Select your gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="country" class="input-group-addon">Country</label>
							<div class="form-control country-form-controls">
								<select class="form-control" id="country" name="country">
									<option value="">Select where you're from</option>
									<?php foreach($countries as $country): ?>
										<option value="<?=$country['iso2']?>"><?=$country['short_name']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group captcha-input-group">
							<label for="captcha" class="required input-group-addon">Prove you are a human</label>
							<div class="captcha-form-controls">
								<?php print_r(solvemedia_get_html(c('sm_ckey'))); ?>
							</div>
						</div>
						<span class="error-tip captcha-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="subscribe" value="1" checked="checked">
								I'd like to receive accasional update, special offers and other information from iPrank.
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label class="required">
								<input type="checkbox" name="accept_tou" value="1" required="required" >
								I agree to the <a href="<?php print_r(_tou()); ?>" target="_blank">Terms of Use</a> and 
									<a href="<?php print_r(_privacy()); ?>" target="_blank">Privacy Policy</a>.
							</label>
						</div>
						<span class="error-tip tou-errtip" style="display:none"></span>
					</div>
					<button type="submit" class="btn btn-default btn-signup">Sign up now</button>
				</form>
			</section>
		</section>
		<aside class="ip-sidebar">
			<article class="signup-instruction">
				<h1>Some helpful tips</h1>
				<section class="inputfield-tips">
					<h1>User Name</h1>
					<ul>
						<li>
							Your username must be a minimum of four characters and a maximum of twenty characters. 
							It can only include letters, numbers and both underscore and the dash, and must be started with letter.
						</li>
						<li>It will be displayed in your posts and profile.</li>
						<li>Please note that once you submit this form, your user name cannot be changed.</li>
					</ul>
				</section>
				<section class="inputfield-tips">
					<h1>Email Address</h1>
					<ul>
						<li>We'll send you an email to activate your account, so please triple-check that you've typed it correctly.</li>
						<li>You will need it to sign in your account.</li>
					</ul>
				</section>
				<section class="inputfield-tips">
					<h1>Password</h1>
					<ul>
						<li>Your password must be at least 5 characters.</li>
						<li>Be clever, choose something safe.</li>
					</ul>
				</section>
			</article>
		</aside>
	</div>
</div>
