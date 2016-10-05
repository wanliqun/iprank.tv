////////////////////// Profile tab page /////////////////
var g_tabSelMap = {
	'likes':'.tab-likes', 'favourites':'.tab-favourites', 
	'posts':'.tab-posts', 'comments':'.tab-comments',
	'settings':'.tab-settings', 
};

var g_tabContentSelMap = {
	'likes':'.ip-posts-wrapper', 'favourites':'.ip-posts-wrapper', 
	'posts':'.ip-posts-wrapper', 'comments':'.ip-comments-wrapper',
	'settings':'.ip-settings-wrapper', 
};

function getPresetTab() {
	var presetTab = window.location.hash;
	if (presetTab) { // Strip '#'
		presetTab = presetTab.replace('#', '');
		presetTab = presetTab.toLowerCase();
	} 
	
	if (!(presetTab in g_tabContentSelMap)) { presetTab = 'posts'; }
	return presetTab;
}

function getTabBySel(sel, refSelMap) {
	for(var key in refSelMap){ if(refSelMap[key] == sel) return key; }
	return null;
}

function loadProfile(username, tab, page) {
	$('.loading-indicator').show();

	$.ajax({
		url: "/?c=ajax&a=loadprofile", 
		type: "get",
		data: { 'username' : username, 'tab' : tab, 'page' : page, 'format' : 'json', },
		dataType: "json",
		success: function(data, textStatus, jqXHR) {		
			var containerSel = '.profile-post-cards';
			var paginatorSel = '#posts-paginator';
			if (tab == 'comments') {
				containerSel = '.profile-comment-cards'; 
				paginatorSel = '#comments-paginator';
			}

			if (data.html) $(containerSel).html(data.html);
			else {
				var noitemsHtml = '<span class="ip-post-tips">No related stuffs found under this tab!</span>';
				$(containerSel).html(noitemsHtml);
			}
			
			if (data.pager) {
				var options = { 
					bootstrapMajorVersion: 3, 
					size: 'small', numberOfPages: 10, 
					currentPage: data.pager.page, 
					totalPages: data.pager.totalPages,
					itemContainerClass: function (type, page, current) {
						return (page===current) ? "active" : "pointer-cursor";
					},
					onPageChanged: function(e, oldPage, newPage){
						loadProfile(username, tab, newPage);
					}
				}	
				$(paginatorSel).bootstrapPaginator(options);
			} else {
				$(paginatorSel).bootstrapPaginator({bootstrapMajorVersion:3});
				$(paginatorSel).bootstrapPaginator('destroy');
			}
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			showSystemErrorTip();
		},
		complete: function() {
			$('.loading-indicator').hide();
		}
	});
}

function switchProfileTab(tab) {
	// Change the location url hash fragment.
	window.location.hash = '#' + tab;
	// Focus the tab.
	$('div.profile-nav-box ul li').removeClass('active');
	$(g_tabSelMap[tab]).addClass('active');
	
	// Show the tab content.
	$("div.navtab-content > div").hide();
	$(g_tabContentSelMap[tab]).show();

	// Ajax load data.
	if (tab == 'settings') return;
	var username = $('.username').text();
	loadProfile(username, tab, 1);
}

$("#form-editprofile").submit(function(event) {
	spinSignupBtn('form#form-editprofile .btn-save');
	var post_data = $('form#form-editprofile').serialize();
	$.ajax({
		url:  $("form#form-editprofile").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				if (data.redirect) {
					$(location).attr('href', data.redirect);
					location.reload(true);
				}
			} else {
				if(data.errtip) {
					showErrorTip(data.errtip);
				}
			}
		},
		error: function() {
			showSystemErrorTip();
		},
		complete: function() {
			recoverSignupBtn('form#form-editprofile .btn-save', 'Save your change');
		}
	});
	event.preventDefault();
});

$("#form-changepwd").submit(function(event) {
	spinSignupBtn('form#form-changepwd .btn-change-pwd');
	$('form#form-changepwd .error-tip').hide();
	$("form#form-changepwd .changepwd-tip").hide();

	var post_data = $('form#form-changepwd').serialize();
	$.ajax({
		url:  $("form#form-changepwd").attr('action'),
		type: 'post',
		dataType: 'json',
		data: post_data,
		success: function(data) {
			if (data.success) {
				$(".changepwd-tip").text('Password saved.');
				$(".changepwd-tip").show();
			} else {
				var fieldSels = {
					'old_password':'.old-password-errtip', 'new_password':'.new-password-errtip',
					'confirm_password':'.confirm-passord-errtip', };
				for(var field in data.errors) {
					var sel = "#form-changepwd " + fieldSels[field];
					console.log(sel);
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
			recoverSignupBtn('form#form-changepwd .btn-change-pwd', 'Change Your Password');
		}
	});
	event.preventDefault();
});

$('div.profile-nav-box ul li').click(function(e) {
	var tabSel = '.' + $(this).attr('class');
	var tab = getTabBySel(tabSel, g_tabSelMap);
	switchProfileTab(tab);
});

var presetTab = getPresetTab();
switchProfileTab(presetTab);

////////////////////// Image uploading /////////////////
var g_defaultCoverImgUrl = null; var g_defaultAvatarImgUrl = null; 
var g_uploadCoverImgUrl = null; var g_uploadAvatarImgUrl = null;

$('.btn-change-cover').click(function(e) { $('#input-cover-img').trigger('click'); });
$('.btn-change-avatar').click(function(e) { $('#input-avatar-img').trigger('click'); });

$('#input-cover-img').change(function(e) {
	var file = (e.target.files)[0];	// FileList object
    if (!file.type.match('image.*')) { // Only process image files.
		alert('Only image file is accepted.'); return;
	} 
	///////////////////// File reader ///////////////////
	var reader = new FileReader();
	reader.onload = function(e) { 
		var dataUrl = e.target.result;
		resizeCover(dataUrl, function(rDataUrl){
			g_uploadCoverImgUrl = rDataUrl;

			if(!g_defaultCoverImgUrl) g_defaultCoverImgUrl = $('.cover-img-layer').css('background-image');
			$('.cover-img-layer').css('background-image', 'url(' + rDataUrl + ')');
			$('.btn-change-cover').hide();
			$('.action-cover-confirm-area').show();
		});
	};
	reader.onerror = function(e) {
		alert('Invalid image file.'); return;
	};
	reader.readAsDataURL(file); // Read in the image file as a data URL.
});

$('.btn-cover-confirm-ok').click(function(e) {
	$('.btn-change-cover').show();
	$('.action-cover-confirm-area').hide();

	if (g_uploadCoverImgUrl) {
		var dataUrl = g_uploadCoverImgUrl;
		$.ajax({
			url: "/?c=ajax&a=changepicture&type=cover&format=json", 
			type: "post",
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			data: { "dataurl" : dataUrl, },
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if(!data.success) {
					if(data.errtip) showErrorTip(data.errtip);
				} else g_defaultCoverImgUrl = dataUrl;
			},
			error: function(jqXHR, textStatus, errorThrown ) { 
				showSystemErrorTip();
			},
		});
		g_uploadCoverImgUrl = null;
	}
});

$('.btn-cover-confirm-cancel').click(function(e) {
	$('.btn-change-cover').show();
	$('.action-cover-confirm-area').hide();

	if (g_defaultCoverImgUrl) {
		$('.cover-img-layer').css('background-image',  g_defaultCoverImgUrl);
	}
});

//========================================================

$('#input-avatar-img').change(function(e) {
	var file = (e.target.files)[0];	// FileList object
    if (!file.type.match('image.*')) { // Only process image files.
		alert('Only image file is accepted.'); return;
	} 
	///////////////////// File reader ///////////////////
	var reader = new FileReader();
	reader.onload = function(e) { 
		var dataUrl = e.target.result;
		resizeAvatar(dataUrl, function(rDataUrl){
			g_uploadAvatarImgUrl = rDataUrl;

			if(!g_defaultAvatarImgUrl) g_defaultAvatarImgUrl = $('.avatar-thumbnail').attr('src');
			$('.avatar-thumbnail').attr('src', rDataUrl);
			$('.btn-change-avatar').hide();
			$('.action-avatar-confirm-area').show();
		});
	};
	reader.onerror = function(e) {
		alert('Invalid image file.'); return;
	};
	reader.readAsDataURL(file); // Read in the image file as a data URL.
});

$('.btn-avatar-confirm-ok').click(function(e) {
	$('.btn-change-avatar').show();
	$('.action-avatar-confirm-area').hide();

	if (g_uploadAvatarImgUrl) {
		var dataUrl = g_uploadAvatarImgUrl;
		$.ajax({
			url: "/?c=ajax&a=changepicture&type=avatar&format=json", 
			type: "post",
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			data: { "dataurl" : dataUrl, },
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if(!data.success) {
					if(data.errtip) showErrorTip(data.errtip);
				} else g_defaultAvatarImgUrl = dataUrl;
			},
			error: function(jqXHR, textStatus, errorThrown ) { 
				showSystemErrorTip();
			},
		});
		g_uploadAvatarImgUrl = null;
	}
});

$('.btn-avatar-confirm-cancel').click(function(e) {
	$('.btn-change-avatar').show();
	$('.action-avatar-confirm-area').hide();

	if (g_defaultAvatarImgUrl) {
		$('.avatar-thumbnail').attr('src', g_defaultAvatarImgUrl);
	}
});

// ====================================================

function resizeAvatar(imgDataUrl, resizeCompletdeHandler) {
	var MAX_WIDTH = 164;
	var MAX_HEIGHT = 164;
	_resizeImg(imgDataUrl, MAX_WIDTH, MAX_HEIGHT, resizeCompletdeHandler);
}

function resizeCover(imgDataUrl, resizeCompletdeHandler) {
	var MAX_WIDTH = 1200;
	var MAX_HEIGHT = 320;
	_resizeImg(imgDataUrl, MAX_WIDTH, MAX_HEIGHT, resizeCompletdeHandler);
}

function _resizeImg(imgDataUrl, maxW, maxH, resizeCompletdeHandler) {
	var tempImg = new Image();
	tempImg.onload = function() { 
		var MAX_WIDTH = maxW;
		var MAX_HEIGHT = maxH;
		var ASPECT = MAX_WIDTH / MAX_HEIGHT;
		var tempW = tempImg.width;
		var tempH = tempImg.height;
		var aspect = tempW / tempH;

		newW = Math.min(MAX_WIDTH, tempW);
		newH = Math.min(MAX_HEIGHT, tempH);

		var canvas = document.createElement('canvas');
        canvas.width = newW;
        canvas.height = newH;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(this, (tempW-newW)/2.0, (tempH-newH)/2.0, newW, newH, 0, 0, newW, newH);
        var dataURL = canvas.toDataURL("image/jpeg");
		resizeCompletdeHandler(dataURL);
	}
	tempImg.src = imgDataUrl;
}

if (typeof window.FileReader === 'undefined') { // Html5 FileReader API not supported.
	$('.btn-change-cover').hide(); $('.btn-change-avatar').hide();
}