function optimizeHDThumbnails() {
	if (window.matchMedia('only screen and (min-width: 540px)').matches) {
		$('.hd-thumbnail-holder').each(function(index, holder) {
			var backgroundImage = $(holder).css('background-image');
			if (!$(holder).is(':visible') && (!backgroundImage || backgroundImage.length<=5)) {
				var hdThumbnailUrl = $(holder).data('thumbnail-src');
				var dummyImgStub = $(holder).find('.dummy-stub');

				$(dummyImgStub).error(function(e){}).load(function(){
					var imgWeGetWidth = this.naturalWidth;
					if(typeof imgWeGetWidth === 'undefined') {
						var someTestImage = new Image();
						someTestImage.src = hdThumbnailUrl;
						imgWeGetWidth = someTestImage.width;
					}
					if (imgWeGetWidth>480) { // Not a valid hd thumbnail.
						$(holder).css('background-image', 'url('+hdThumbnailUrl+')');
						$(holder).show();
					}
				}).each(function() {
					if(this.complete) $(this).load();
				}).attr('src', hdThumbnailUrl);
			}
		}); 	
	}
}

function ajaxLoadMore(lastTimestamp) {
	$('.btn-load-more').attr("disabled", 'disabled');
	$(".btn-load-more").html("<img src='/public/images/loading.gif' alt='loading indicator'></img>");

	$.ajax({
		url: "/?c=ajax&a=loadmore", 
		type: "get",
		data: { "last_timestamp" : lastTimestamp, 'format' : 'json', },
		dataType: "json",
		success: function(data, textStatus, jqXHR) {
			$(".ip-article-timeline-stream").append(data.html);
			addthis.toolbox(".addthis_toolbox");

			if (data.hasMore) {
				var onclickAction = 'ajaxLoadMore(' + data.lastTimestamp + ');' + 'return false;';
				$(".btn-load-more").attr('onclick', onclickAction);
				$(".btn-load-more").attr('href', '#');
				$('.btn-load-more').removeAttr("disabled");
				$(".btn-load-more").text('Load more');
			} 
			else {
				$(".btn-load-more").text('No more data to load');
			}
			
			if (data.jsUri) { $.getScript(data.jsUri, function(data, textStatus){}); }
			optimizeHDThumbnails();
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			$('.btn-load-more').removeAttr("disabled");
			$(".btn-load-more").text('Load more');
		},
	});
}

$("form#form-subscribe").submit(function(e) {
	e.preventDefault();
	$(".subscription-tip").hide();
	var email = $("#form-subscribe input[name='email']").val();
	if (!validateEmail(email)) { alert('Not a valid email address.'); return; }

	spinSignupBtn('.btn-subscribe');
	$.ajax({
		url: $("form#form-subscribe").attr('action'),
		type: "get",
		data: { "email" : email, 'format' : 'json', },
		dataType: "json",
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				$('.subscription-tip').text('Thanks for your subscription.');
				$(".subscription-tip").show();
			}
			else if (data.errtip) showErrorTip(data.errtip);
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			showSystemErrorTip();
		},
		complete: function(xhr, statusText){
			recoverSignupBtn('.btn-subscribe', 'Subscribe')
		}
	});
});

$(document).ready(function(e) {
	var s = $("#sidebar-sticker");
	var pos = s.offset().top; 
	var sticked = false; 

	$(window).load(function(e) {
		pos = s.offset().top; // refresh the sidebar sticker offset top.
	});
	
	$(window).scroll(function() {
        var windowpos = $(window).scrollTop() + 50; // 50 is the body top margin.

        if (windowpos >= pos) {
			if (!sticked) {
				var width = s.width();
				s.width(width);
				s.addClass("sticked");
				sticked = true;			
			}
        } else {
			if (sticked) {
				s.css('width', 'auto');
				s.removeClass("sticked"); 
				sticked = false;
			}
        }
    }); 
	
	$( window ).resize(function() {
		if (sticked) {
			var newWidth = $(".most-popular-posts").width();
			s.css('width', newWidth);
		}
	});
});

var g_ytPlayers = {}; var g_ytActPlayer = null;
var g_ytEventsConfig = 
{	
	"onReady": function (event) { 
		var player = event.target; 
		if (player == g_ytActPlayer) {
			var mobile = /(Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini)/g.test(navigator.userAgent);  
			if (!mobile) {
				// Stop all other Youtube Videos
				$.each(g_ytPlayers, function(postid, ytPlayer) {
					if (ytPlayer!=player) ytPlayer.stopVideo();
				});
				player.playVideo();
			} 
		}
	},
	'onStateChange':  function (event) {
		if (event.data == YT.PlayerState.PLAYING) {
			var player = event.target; 
			// Mark this YouTube video played once.
			if (!player.playedOnce) {
				var postid = player.pid; player.playedOnce = true;
				$.ajax({
					url: "/?c=ajax&a=markviewed&format=json", 
					type: "get",
					data: { 'postid' : postid },
					dataType: "json",
					success: function(data, textStatus, jqXHR) { },
					error: function(jqXHR, textStatus, errorThrown ) { },
				});
			} 
		}
	}
};

function playYtVideo(player, ytVideoUrl) {
	// Replace with YouTube iFrame
	var postId = $(player).data('pid');
	var ytIframeSelId = "yt-iframe-"+postId;
	var iframe = $("<iframe src='"+ytVideoUrl+"' id='"+ytIframeSelId+"' frameborder='0' allowfullscreen></iframe>");
	$(player).find('.btn-play').replaceWith(iframe);

	YT_ready(function(){
		var ytPlayer = new YT.Player(ytIframeSelId, {events: g_ytEventsConfig});
		ytPlayer.pid = postId; g_ytPlayers[postId] = ytPlayer; g_ytActPlayer = ytPlayer;
	});
}