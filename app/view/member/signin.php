<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-signin-wrapper">
		<section class="ip-left-panel">
			<h1>Sign in</h1>
			<div class="signin-area">
				<section class="fb-signin-wrapper">
					<h1>Sign in with Facebook</h1>
					<h2>It's fast and easy.</h2>
					<div class="fb-signin-button">
						<button type="button" class="btn btn-lg btn-fb-signup btn-fb-signin" onclick="fb_connect('<?=$redirect?>');">
							<img src="/public/images/fb-signin.png" alt="sign in with facebook" class="img-responsive" />
						</button>
						<span class="fb-connect-loading" style="display:none"><i class="fa fa-spinner fa-spin fa-lg"></i></span>
					</div>
					<span class="fb-signin-hint">We will never post anything without your permission.</span>
				</section>
				<section class="email-signin-wrapper">
					<h1>Sign in with your Email</h1>
					<form  id="signin-form" action="/?c=ajax&a=signin&format=json&redirect=<?=$redirect?>" method="post">
						<div class="form-group">
							<div class="input-group">
								<label class="sr-only" for="email">Email address</label>
								<input type="email" class="form-control" name="email" 
									placeholder="Enter your email" required="required">
							</div>
							<span class="error-tip email-errtip" style="display:none"></span>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label class="sr-only" for="password">Password</label>
								<input type="password" class="form-control" name="password" 
									placeholder="Enter your password" required="required">
							</div>
							<span class="error-tip password-errtip" style="display:none"></span>
						</div>
						<div class="forgot-password-hint"><a href="" onclick="showResetPwdBox(); return false;">Forgot your password?</a></div>
						<button type="submit" class="btn btn-default btn-signin">Sign in</button>
					</form>
				</section>
			</div>
		</section>
		<aside class="ip-sidebar">
			<article class="signin-instruction">
				<p>Sign in with iPrank to upload, share and comment etc.</p>
				<section class="new-to-iprank">
					<h1>New to iPrank?</h1>
					<p>You can sign up for an account <a href="<?=_signup()?>">here</a>.</p>
				</section>
				<section class="forgot-your-pwd">
					<h1>Forgot your password?</h1>
					<p>You can reset your password <a  onclick="showResetPwdBox(); return false;" href="">here</a>.</p>
				</section>
			</article>
		</aside>
	</div>
</div>