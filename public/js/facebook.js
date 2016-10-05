function _connectFb(redirect) {
	$('.btn-fb-signup').attr('disabled', true);
	$('.fb-connect-loading').show();
	$.ajax({
		url:  '/?c=ajax&a=connectfb&format=json&redirect=' + redirect,
		type: 'get',
		dataType: 'json',
		success: function(data) {
			if (data.status === 'failed') {
				showErrorTip(data.error);
			} else if (data.status === 'signup_fb') {
				BootstrapDialog.closeAll();
				BootstrapDialog.show({
					title: '',
					message: $("<div class='dialog-msg-body'><img src='/public/images/loading.gif' alt='loading indicator'></img></div>"),
					cssClass: 'fb-connecting-dialog',
					onshown: function(dialogRef){
						var $url = '/?c=dialog&a=signup_fb&redirect=' + redirect;
						var $data = {'email':data.fb['email'], 'username':data.fb['name'], 'userid':data.fb['userid'],};
						var $complete = function(response, status, xhr) {
							if ( status !== "success" ) {
								var msg = "Sorry but there was an error: " +  xhr.status + " " + xhr.statusText;
								dialogRef.getModalBody().find(".dialog-msg-body" ).html('<span>' + msg + '</span>');
							}
						};
						dialogRef.getModalBody().find(".dialog-msg-body" ).load($url, $data, $complete);
					},
				}); 
			} else {
				if(data.redirect) {
					$(location).attr('href', data.redirect);
				} else window.close();
			}
		},
		error: function() {
			showSystemErrorTip();
		},
		complete: function() {
			$('.btn-fb-signup').removeAttr('disabled');
			$('.fb-connect-loading').hide();
		}
	});
}

function fb_login(callback, permissions) {
	var scopes = 'public_profile,email,user_birthday,user_hometown';
	if(permissions) scopes = permissions;

	FB.login(function(response) { 
		callback(response);
		if (response.status !== 'connected') {
			showErrorTip('Please authorize us to get your facebook information.');
		}
	}, {scope: scopes});
}

function fb_connect(redirect) {
	fb_login(function(response){
		if (response.status === 'connected') {
			_connectFb(redirect);
		}
	});
}

window.fbAsyncInit = function() {
	FB.init({
		appId      : '1513728292172568',
		cookie     : true,  // enable cookies to allow the server to access the session
		xfbml      : true,  // parse social plugins on this page
		version    : 'v2.1', // use version 2.1
	});
};

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));