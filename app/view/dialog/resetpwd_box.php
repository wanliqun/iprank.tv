<?php
$this->layout('dialog');
?>
<section class="ip-reset-pwd-container">
	<header>
		<h1><span>Forgot your password?</span></h1>
		<div class="forgot-pwd-tip"><span>Please enter your email and we'll send you a link to reset your password.</span></div>
	</header>
	<div class="reset-pwd-box">
		<form id="reset-pwd-form" role="form" action="/?c=ajax&a=resetpwd&format=json" method="get">
			<input type="hidden" name="reset_pwd" value="1" />
			<div class="form-group">
				<div class="input-group">
					<label for="email" class="sr-only">Email Address</label>
					<input type="email" class="form-control" name="email" placeholder="Please enter your email address" required="required" />
				</div>
				<span class="error-tip email-errtip"></span>
			</div>
			<div class="result-tip resetpwd-success-tip"><span>We've sent a password reset link to your e-mail inbox, please check it out.</span></div>
			<div><button type="submit" class="btn btn-default btn-reset-pwd">Reset your password</button></div>
		</form>
	</div>
</section>

<?php $this->startBlock('__script');?>
<script type="text/javascript">
$("form#reset-pwd-form").submit(function(event) {
	spinSignupBtn('form#reset-pwd-form .btn-reset-pwd');
	$('form#reset-pwd-form .error-tip').hide();
	$('form#reset-pwd-form .resetpwd-success-tip').hide();

	var data = $('form#reset-pwd-form').serialize();
	$.ajax({
		url:  $("form#reset-pwd-form").attr('action'),
		type: 'get',
		dataType: 'json',
		data: data,
		success: function(data) {
			if (data.success) {
				$("input[name='email']").prop("disabled", true);
				$('form#reset-pwd-form .btn-reset-pwd').prop("disabled", true);

				$('form#reset-pwd-form .resetpwd-success-tip').show();
			} else if(data.errtip)  {
				$('form#reset-pwd-form .email-errtip').html(data.errtip);
				$('form#reset-pwd-form .email-errtip').show();
			}
		},
		error: function() {
			showSystemErrorTip();
		},
		complete: function() {
			recoverSignupBtn('form#reset-pwd-form .btn-reset-pwd', 'Reset your password');
		}
	});

	event.preventDefault();
});
</script>
<?php $this->endBlock('__script'); ?>