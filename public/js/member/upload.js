$(".btn-add-video").click(function(e) {
	var ytvUri = $('#input-ytvideo-uri').val();
	if (!ytvUri) {
		showErrorTip('Please input your youtube video url.');
		return;
	}

	spinSignupBtn('.btn-add-video');
	$('ip-preview-wrapper').hide();
	$.ajax({
		url:  '/?c=ajax&a=add_ytvideo&format=json',
		type: 'post',
		data: {'ytv_uri' : ytvUri, },
		dataType: 'json',
		success: function(data) {
			if (data.success) {
				$('.ip-preview-wrapper').show();
				$('.ip-video-container iframe').attr('src', data.em_ytv_uri);
				$('#input-ytv-id').attr('value', data.ytv_id);
			} else {
				showErrorTip(data.error);
			}
		},
		error: function() {
			showSystemErrorTip();
		},
		complete: function() {
			$('.btn-add-video').removeAttr('disabled');
			recoverSignupBtn('.btn-add-video', 'Add Video');
		}
	});
});