<?php
$this->layout('dialog');
?>
<section class="ip-signup-fb-container">
	<header>
		<h1><span>Sign up with connecting Facebook</span></h1>
		<h2>
			<span>To finish signing up with connecting your Facebook account, 
			please choose your user name and password, and enter them below.</span>
		</h2>
		<div class="greeting-container"> 
			<img alt="[<?=$username?>]" src="<?=_fb_picture($userid)?>" class="fb-thumbnail">
			<span class="greeting-words">Hi, <?=$username?></span>
		</div>
	</header>
	<div class="signup-fb-container">
		<form id="signup-fb-form" role="form" action="/?c=ajax&a=signupfb&format=json&redirect=<?=$redirect?>" method="post">
			<input type="hidden" name="signup_fb" value="1" />
			<div class="form-group">
				<div class="input-group">
					<label for="email" class="input-group-addon ip-required">Email Address</label>
					<input type="email" class="form-control" id="email" name="email" value="<?=$email?>"
						placeholder="Please enter your email address" required="required" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<label for="username" class="input-group-addon ip-required">User Name</label>
					<?php $stripname = strtolower(str_replace(" ", "", $username)); ?>
					<input type="text" class="form-control" id="username" name="username"  value="<?=$stripname?>"
						placeholder="Please enter your user name" required="required" >
				</div>
				<span class="error-tip username-errtip" style="display:none"></span>
			</div>
			<div class="form-group">
				<div class="input-group">
					<label for="password" class="input-group-addon ip-required">Password</label>
					<input type="password" class="form-control" id="password" name="password" 
						placeholder="Please enter your password" required="required" >
				</div>
				<span class="error-tip password-errtip" style="display:none"></span>
			</div>
			<div class="form-group">
				<div class="input-group">
					<label for="confirm_password" class="input-group-addon ip-required">Confirm your password</label>
					<input type="password" class="form-control" id="confirm-password" name="confirm_password" 
						placeholder="Please confirm your password" required="required" >
				</div>
				<span class="error-tip confirm-passord-errtip" style="display:none"></span>
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
			<button type="submit" class="btn btn-default btn-signup">Sign up</button>
		</form>
	</div>
</section>

<?php $this->startBlock('__script');?>
<script type="application/javascript">
function _signupFb(redirect) {
	spinSignupBtn('#signup-fb-form .btn-signup');
	$('#signup-fb-form .error-tip').hide();

	var post_data = $('form#signup-fb-form').serialize();
	$.ajax({
		url:  $("form#signup-fb-form").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				$(location).attr('href', redirect);
			} else {
				var fieldSels = {'username':'.username-errtip', 'email':'.email-errtip', 'password':'.password-errtip',
					'confirm_password':'.confirm-passord-errtip', 'accept_tou':'.tou-errtip',
				};
				for(var field in data.errors) {
					var sel = "#signup-fb-form " + fieldSels[field];
					var reason = data.errors[field];
					$(sel).html(reason);
					$(sel).show();
				}

				if(data.errtip) showErrorTip(data.errtip);
			}
		},
		error: function() {
			showSystemErrorTip();
		},
		complete: function() {
			recoverSignupBtn('#signup-fb-form .btn-signup', 'Sign up');
		}
	});
}

$("#signup-fb-form").submit(function(event) {
	_signupFb('<?=$redirect?>');
	event.preventDefault();
});
</script>
<?php $this->endBlock('__script'); ?>