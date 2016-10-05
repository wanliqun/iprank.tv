$('#tags-input-field').tagsinput({
	trimValue: true
});

$("form#editpost-form").submit(function(event) {
	spinSignupBtn('form#editpost-form .save-btn');
	$('form#editpost-form .error-tip').hide();

	var post_data = $('form#editpost-form').serialize();
	$.ajax({
		url:  $("form#editpost-form").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				$(location).attr('href', data.redir);
			} else {
				var fieldSels = {'title':'.title-errtip', 'description':'.description-errtip', 
					'channel':'.channel-errtip', 'tags':'.tags-errtip', };
				for(var field in data.errors) {
					var sel = "form#editpost-form " + fieldSels[field];
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
			recoverSignupBtn('form#editpost-form .save-btn', 
				'Save Details&nbsp;<i class="fa fa-angle-double-right"></i>');
		}
	});
	event.preventDefault();
});
