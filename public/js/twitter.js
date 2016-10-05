var TW = {
	'twStatus' : 'unknown',
	'getLoginStatus' : function(callback) {
		$.ajax({
			url: '/?c=ajax&a=oauth_tw&format=json',
			type: 'get',
			dataType: 'json',
			data: {},
			success: function(data) {
				callback(data);
			},
			error: function() {
				showSystemErrorTip();
			},
			complete: function() {
			}
		});
	},
	'login' : function(callback) {
		if (!TW.oauthUrl) {
 			callback({'success':false}); return;
		}

		var oauthUrl = TW.oauthUrl;
		var w = 520, h=480;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		var param = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width='+w
			+',height='+h+',top='+top+',left='+left;
		var oauthWindow = window.open(oauthUrl, "_blank", param);
		var interval = window.setInterval(function() {
			try {
				if (oauthWindow == null || oauthWindow.closed) {
					callback({'success':false, 'errtip':'Please authorize us to tweet for you.'});
				}
			} catch (e) { }
		}, 1000);
		TW.oauthCheckInterval = interval;
	},
};

TW.getLoginStatus(function(response){
	if (response.authorized) {
		TW.twStatus = 'connected';
	} else {
		TW.twStatus = 'not_authorized';
		TW.oauthUrl = response.oauth_url;
	}
});