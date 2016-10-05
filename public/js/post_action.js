var g_postActboxPrefix='#post-actionbox-';

function getSelBtnLike(pid) { return g_postActboxPrefix + pid + ' .btn-like'; };
function getSelCntLike(pid) { return g_postActboxPrefix + pid + ' .cnt-like'; };
function getSelBtnDislike(pid) { return g_postActboxPrefix + pid + ' .btn-dislike'; };
function getSelCntDislike(pid) { return g_postActboxPrefix + pid + ' .cnt-dislike'; };
function getSelBtnBookmark(pid) { return g_postActboxPrefix + pid + ' .btn-bookmark'; }; 

function _likeOrDislikePost(pid, action) {
	var selBtnLike = getSelBtnLike(pid); var selCntLike = getSelCntLike(pid);
	var selBtnDislike = getSelBtnDislike(pid); var selCntDislike = getSelCntDislike(pid);
	
	var url = "/?c=ajax&a=likepost"; var todoSelBtn = selBtnLike; var todoSelCnt = selCntLike;
	if (action == 'dislike') { 
		url = "/?c=ajax&a=dislikepost"; todoSelBtn = selBtnDislike; todoSelCnt = selCntDislike; 
	}
	var active = $(todoSelBtn).hasClass('active');

	$(todoSelBtn).attr("disabled", "disabled");
	if (active) $(todoSelBtn).removeClass('active'); 
	else $(todoSelBtn).addClass('active');
	
	$.ajax({
		url: url, type: "get", dataType: "json",
		data: { "postid" : pid, 'format' : 'json', },
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				if (data.stat.liked_value > 0) {
					$(selBtnLike).addClass('active'); $(selBtnDislike).removeClass('active'); 
				} else if (data.stat.liked_value < 0) {
					$(selBtnLike).removeClass('active'); $(selBtnDislike).addClass('active');
				} else {
					$(selBtnLike).removeClass('active'); $(selBtnDislike).removeClass('active');
				}
				$(selCntLike).text(data.stat.num_liked); $(selCntDislike).text(data.stat.num_disliked);
			} else if (data.errtip) showErrorTip(data.errtip);
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			$(selBtnLike).removeClass('active'); $(selBtnDislike).removeClass('active');
			if (jqXHR.status == 401) {
				var msg = "You need to sign in to do this action. Press 'OK' to sign in.";
				if (confirm(msg)) showLoginBox(document.URL);
			} else showSystemErrorTip();
		},
		complete: function(xhr, statusText){
			$(todoSelBtn).removeAttr('disabled');
		}
	});
}

function likePost(pid) { _likeOrDislikePost(pid, 'like'); }
function dislikePost(pid) { _likeOrDislikePost(pid, 'dislike'); }

function bookmarkPost(pid) {
	var selBtnBookmark = getSelBtnBookmark(pid);
	var active = $(selBtnBookmark).hasClass('active');

	$(selBtnBookmark).attr("disabled", "disabled");
	if (active) $(selBtnBookmark).removeClass('active'); 
	else $(selBtnBookmark).addClass('active');

	$.ajax({
		url: '/?c=ajax&a=favourpost', 
		type: "get", dataType: "json",
		data: { "postid" : pid, 'format' : 'json', },
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				if (data.stat.favorited_value != 0) { $(selBtnBookmark).addClass('active'); }
				else { $(selBtnBookmark).removeClass('active'); };
			} else if (data.errtip) showErrorTip(data.errtip);
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			$(selBtnBookmark).removeClass('active');
			if (jqXHR.status == 401) {
				var msg = "You need to sign in to do this action. Press 'OK' to sign in.";
				if (confirm(msg)) showLoginBox(document.URL);
			} else showSystemErrorTip();
		},
		complete: function(xhr, statusText){
			$(selBtnBookmark).removeAttr('disabled');
		}
	});
}