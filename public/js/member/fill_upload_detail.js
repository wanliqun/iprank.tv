$('#tags-input-field').tagsinput({
	trimValue: true
});

$('#chck-share-fb').change(function() {
	if($(this).is(":checked")) {
		fb_login(function(response) { 
			if (response.status != 'connected') {
				$('#chck-share-fb').removeAttr('checked');
			}
		}, 'publish_actions');
	}      
});

function Tw_OAuthCallback(result){
	if (!result.success) {
		if(result.errtip) showErrorTip(result.errtip);
		$('#chck-share-tw').removeAttr('checked');
	} else TW.twStatus = 'connected';

	if (TW.oauthCheckInterval) {
		window.clearInterval(TW.oauthCheckInterval);
		TW.oauthCheckInterval = null;
	}
}

$('#chck-share-tw').change(function(event) {
	if(!$(this).is(":checked")) return;

	if(TW.twStatus != 'connected') {
		TW.login(Tw_OAuthCallback);
	}
});

$("form#fillupload-form").submit(function(event) {
	spinSignupBtn('form#fillupload-form .save-btn');
	$('form#fillupload-form .error-tip').hide();

	var post_data = $('form#fillupload-form').serialize();
	$.ajax({
		url:  $("form#fillupload-form").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				if(data.redir) $(location).attr('href', data.redir);
			} else {
				var fieldSels = {'title':'.title-errtip', 'description':'.description-errtip', 
					'channel':'.channel-errtip', 'tags':'.tags-errtip', };
				for(var field in data.errors) {
					var sel = "#fillupload-form " + fieldSels[field];
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
			recoverSignupBtn('form#fillupload-form .save-btn', 
				'Save Details&nbsp;<i class="fa fa-angle-double-right"></i>');
		}
	});
	event.preventDefault();
});
