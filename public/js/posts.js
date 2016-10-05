function switchClasses(content){
	if(content.hasClass("short-text")){  
		content.addClass('full-text').removeClass('short-text');
	} else {
		content.addClass('short-text').removeClass('full-text');
	}
}

function getShowBtnTxt(currentText){
	var newText = '';
	
	if (currentText.toUpperCase() === "SHOW MORE") newText = "Show less";
	else  newText = "Show more"; 
	return newText;
}

function reportSpam(pid) {
	$('.btn-reportspam').attr("disabled", "disabled");
	$('.tip-spam-report').show();
	$.ajax({
		url: '/?c=ajax&a=reportspam', 
		type: "get", dataType: "json",
		data: { "postid" : pid, 'format' : 'json', },
		success: function(data, textStatus, jqXHR) {
			if (!data.success) {
				if (data.errtip) showErrorTip(data.errtip);
				$('.btn-reportspam').removeAttr('disabled');
				$('.tip-spam-report').hide();
			}
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			if (jqXHR.status == 401) {
				var msg = "You need to sign in to do this action. Press 'OK' to sign in.";
				if (confirm(msg)) showLoginBox();
			} else showSystemErrorTip();

			$('.btn-reportspam').removeAttr('disabled');
			$('.tip-spam-report').hide();
		},
		complete: function(xhr, statusText){ },
	});
}

$(document).ready(function(e) {
	$(".btn-show-more").on("click", function() {
		var btn = $(this);
		var content = btn.parent().prev(".text-content");
		var btnTxt = btn.text();
	
		switchClasses(content);
		btn.text(getShowBtnTxt(btnTxt));
	  
		return false;
	});

	$(window).resize(function(e) {
		truncateNavThumbnailHeader();
	});
	
});
