///////////////// Facebook
window.fbAsyncInit = function() {
	FB.init({
		appId      : '500223283437768',
		xfbml      : true,
		version    : 'v2.0'
	});
};
(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

///////////////// Twitter
(function(d,s,id){
	var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
	if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}
})(document, 'script', 'twitter-wjs');

//////////////// Client Timezone Detection
function clientTimezoneDetection() {
	if (!$.cookie("tz")) {
		var tz = jstz.determine(); 
		$.cookie("tz", tz.name(), { path: '/' });
	}
}

//////////////// UI Action
function spinSignupBtn(signupSel) {
	$(signupSel).html('<i class="fa fa-spinner fa-spin fa-lg"></i>');
	$(signupSel).addClass('disabled'); 
	$(signupSel).prop('disabled', true);
}

function recoverSignupBtn(signupSel, txt) {
	$(signupSel).html(txt);
	$(signupSel).removeClass('disabled');
	$(signupSel).removeProp('disabled');
}

function showErrorTip(error) {
	alert(error);
}

function showSystemErrorTip() {
	showErrorTip('Unknown errors happened, please contact the administrator or try it again.');
}

function showResetPwdBox() {
	BootstrapDialog.closeAll();
	return BootstrapDialog.show({
		title: '',
		closable: true,
		message: $("<div class='dialog-msg-body'><img src='/public/images/loading.gif' alt='loading indicator'></img></div>"),
		cssClass: 'reset-pwd-dialog',
		onshown: function(dialogRef){
			var $url = '/?c=dialog&a=resetpwd_box';
			var $data = {};
			var $complete = function(response, status, xhr) {
				if ( status !== "success" ) {
					var msg = "Sorry but there was an error: " +  xhr.status + " " + xhr.statusText;
					dialogRef.getModalBody().find(".dialog-msg-body" ).html('<span>' + msg + '</span>');
				}
			};
			dialogRef.getModalBody().find('.dialog-msg-body').load($url, $data, $complete);
		},
	});
}

function showLoginBox(redirect) {
	BootstrapDialog.closeAll();
	return BootstrapDialog.show({
		title: '',
		closable: true,
		message: $("<div class='dialog-msg-body'><img src='/public/images/loading.gif' alt='loading indicator'></img></div>"),
		cssClass: 'login-box-dialog',
		onshown: function(dialogRef){
			var $url = '/?c=dialog&a=login_box&redirect=' + redirect;
			var $data = {};
			var $complete = function(response, status, xhr) {
				if ( status !== "success" ) {
					var msg = "Sorry but there was an error: " +  xhr.status + " " + xhr.statusText;
					dialogRef.getModalBody().find(".dialog-msg-body" ).html('<span>' + msg + '</span>');
				}
			};
			dialogRef.getModalBody().find('.dialog-msg-body').load($url, $data, $complete);
		},
	});
}

///////////////// Ajax
function ajaxLoadMostPopular() {
	var loadingHtml = "<img src='/public/images/loading.gif' alt='loading indicator'></img>";
	var streamContainerSel = ".article-popular-stream";
	$(streamContainerSel).html(loadingHtml);

	var value = $('#popular-filter-select').val();
	$.ajax({
		url: "/?c=ajax&a=load_mostpopular", 
		type: "get",
		data: { "filter" : value, 'format' : 'json', },
		dataType: "json",
		success: function(data, textStatus, jqXHR) {
			if (data.html) {
				$(streamContainerSel).html(data.html);
			} else {
				var noitemsHtml = '<span class="ip-post-tips">No related stuffs found at this moment!</span>';
				$(streamContainerSel).html(noitemsHtml);
			}
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			var failureHtml = '<span class="ip-post-tips">Unable to load, please try again.</span>';
			$(streamContainerSel).html(failureHtml);
		},
	});
}

///////////////// Utility
function validateEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

////////////////  Entrance
$(document).ready(function () {
	// bootstrap-select.
	if ($('.selectpicker').selectpicker) {
		$('.selectpicker').selectpicker();
	}

	clientTimezoneDetection();
	if(!(typeof addthis === 'undefined')) addthis.init();
});


