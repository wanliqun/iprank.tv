<?php
$this->layout('dialog');
?>
<section class="ip-loginbox-container">
	<h1><span>Sign in to iPrank</span></h1>
	<div class="signin-area">
		<div class="fb-signin-wrapper">
			<section class="fb-signin-button">
				<button type="button" class="btn btn-lg btn-fb-signup btn-fb-signin" onclick="fb_connect('<?=$redirect?>');">
					<img src="/public/images/fb-signin.png" alt="sign in with facebook" class="img-responsive" style="max-width:100%;" />
				</button>
				<span class="fb-connect-loading" style="display:none"><i class="fa fa-spinner fa-spin fa-lg"></i></span>
			</section>
		</div>
		<div class="email-signin-wrapper">
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
		</div>
	</div>
	<div class="signup-area">
		<div class="signup-hint">
			<span>New to iPrank?&nbsp;<a href="<?=_signup();?>">Sign up now<i class="fa fa-angle-double-right"></i></a></span>
		</div>
	</div>
</section>

<?php $this->startBlock('__script');?>
<script type="text/javascript">
$("#signin-form").submit(function(event) {
	spinSignupBtn('form#signin-form .btn-signin');
	$('form#signin-form .error-tip').hide();

	var post_data = $('form#signin-form').serialize();
	$.ajax({
		url:  $("form#signin-form").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				if (data.redirect) {
					$(location).attr('href', data.redirect);
				} else window.close();
			} else {
				var fieldSels = {'email':'.email-errtip', 'password':'.password-errtip', };
				for(var field in data.errors) {
					var sel = "#signin-form " + fieldSels[field];
					var reason = data.errors[field];
					$(sel).html(reason);
					$(sel).show();
				}
				if(data.errtip) {
					showErrorTip(data.errtip);
				}
			}
		},
		error: function() {
			showSystemErrorTip();
		},
		complete: function() {
			recoverSignupBtn('form#signin-form .btn-signin', 'Sign In');
		}
	});
	event.preventDefault();
});
</script>
<?php $this->endBlock('__script'); ?>