<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-pwdreset-wrapper">
		<section class="ip-left-panel">
			<h1>Reset your password</h1>
			<div class="pwdreset-area">
				<?php if(empty($hideFrom)): ?>
				<p><span>Please enter a new password for your account</span></p>
				<form id="pwdreset-form" action="" method="post">
					<input type="hidden" name="reset_password" value="1"  />
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
					<div style="float:right;"><button type="submit" class="btn btn-default btn-reset-pwd">Reset your password</button></div>
				</form>
				<? endif; ?>
				<?php if(!empty($errtip)): ?><p><span class="error-tip"><?=$errtip;?></span></p><?php endif; ?>
				<?php if(!empty($successtip)): ?><p><span class="success-tip"><?=$successtip;?></span></p><?php endif; ?>
			</div>
		</section>
		<aside class="ip-sidebar">
			<article class="pwdreset-instruction">
				<p>You might need to know:</p>
				<section class="token-expired">
					<h1>Token required or expired?</h1>
					<p>When you requested a password request, we sent you a reset link with a token to your email inbox. </p>
					<p>Only though this link with the token can you set your new password without any previous credentials. </p>
					<p>Please also be noted that the token will be valid  only for 24 hours, after that the token will be expired and you have to request for another password reset.</p>
				</section>
			</article>
		</aside>
	</div>
</div>

<?php if(!empty($errors)): ?>
<?php $this->startBlock('__script');?>
<script type="text/javascript">
var fieldSels = {'confirm_password':'.confirm-passord-errtip', 'new_password':'.new-password-errtip',};
var errors = <?=json_encode($errors)?>;

for(var field in errors) {
	var sel = "#pwdreset-form " + fieldSels[field];
	var reason = errors[field];
	$(sel).html(reason);
	$(sel).show();
}
</script>
<?php $this->endBlock('__script'); ?>
<?php endif; ?>