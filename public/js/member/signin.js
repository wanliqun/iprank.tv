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
				if(data.redirect) {
					$(location).attr('href', data.redirect);
				}
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