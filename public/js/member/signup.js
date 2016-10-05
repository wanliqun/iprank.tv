function goCheckEmail(mail) {
	services = {"gmail.com": "https://gmail.com", "hotmail.com": "https://hotmail.com"};
	atPos = mail.indexOf("@");
	hoster = mail.substring(atPos + 1);
	url = "http://mail." + hoster;
	if (services[hoster]) url = services[hoster];
	window.open(this.url);
}

$("#signup-form").submit(function(event) {
	spinSignupBtn('form#signup-form .btn-signup');
	$('form#signup-form .error-tip').hide();

	var post_data = $('form#signup-form').serialize();
	$.ajax({
		url:  $("form#signup-form").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				var title = '<strong>Please activate your account</strong>';
				var message = "<p>We've sent an activation link to <em>" + data.activation_email
					+"</em>,  please click on the activation link to validate your email address and complete the registration.</p>";
				BootstrapDialog.show({
					title: title,
					message: message,
					cssClass: 'activation-dialog',
					buttons: [{
						label: 'Go check my mailbox',
						cssClass: 'btn-primary',
						action: function(dialog){
							goCheckEmail(data.activation_email);
							dialog.close();
						}
					}],
					onhidden: function(dialogRef) {
						$(location).attr('href', "/");
					},
				});
			} else {
				var fieldSels = {'username':'.username-errtip', 'email':'.email-errtip', 'password':'.password-errtip',
					'confirm_password':'.confirm-passord-errtip', 'adcopy_response':'.captcha-errtip', 'accept_tou':'.tou-errtip',
				};
				for(var field in data.errors) {
					var sel = "#signup-form " + fieldSels[field];
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
			recoverSignupBtn('form#signup-form .btn-signup', 'Sign up now');
			// refresh the captcha.
			ACPuzzle.reload();
		}
	});
	event.preventDefault();
});