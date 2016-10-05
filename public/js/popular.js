function _ajaxLoadPopular(filter, according, tabPanelSelector) {
	var loadingHtml = "<img class='loading-now' src='/public/images/loading.gif' alt='loading'></img>";
	$(tabPanelSelector).html(loadingHtml);
	$.ajax({
		url: "/?c=ajax&a=loadpopular", 
		type: "get",
		data: { "filter" : filter, 'acc' : according, 'format' : 'json', },
		dataType: "json",
		success: function(data, textStatus, jqXHR) {
			if (data.html) {
				$(tabPanelSelector).html('<div class="popular-post-cards">' + data.html + '</div>');
			} else {
				var noitemsHtml = '<span class="ip-post-tips">No related stuffs found at this moment!</span>';
				$(tabPanelSelector).html(noitemsHtml);
			}
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			var failureHtml = '<span class="ip-post-tips">Unable to load, please try again.</span>';
			$(tabPanelSelector).html(failureHtml);
		},
	});
}

$('a[data-toggle="tab"]').click(function (e) {
	var filter = $('#tab-filter-select').val();
	var tabPanelId = $(this).attr('href');
	var according = tabPanelId.substring(5);
	_ajaxLoadPopular(filter, according, tabPanelId);

	e.preventDefault();
	$(this).tab('show');
})

function ajaxLoadPopular() {
	var filter = $('#tab-filter-select').val();
	var tabPanelId = $('.tab-pane.active').attr('id');
	var according = tabPanelId.substring(4);
	_ajaxLoadPopular(filter, according, '#' + tabPanelId);
}