<?php
$this->layout('default_admin');
?>
<div class="admin-crawler-wrapper">
	<ul class="nav-tabbar">
	<?php foreach($availableTabs as $each): ?>
		<?php $active = (($each==$tab) ? 'active' : '') ?>
		<li class='<?=$active?>'>
			<a href="<?=_admin(array('c'=>'crawler', 'a'=>'youtube', 'tab'=>$each))?>"><?=$each?></a>
		</li>
	<?php endforeach; ?>
	</ul>
	<div class="crawling-op-area">
		<div class="part-filter">
			<p>
				<span class="op-tip">Enter YouTube video id(s):</span>&nbsp;&nbsp;
				<input type="text" name="ytvids" value="<?=implode(',', $ytvids)?>" style="min-width:480px;"
					placeholder="Enter YouTube video id(s) seperated by comma..." />
			</p>
			<p class="seperator-line"></p>
			<p>
				<span class="op-tip">Please select a Youtube channel:</span>&nbsp;&nbsp;&nbsp;&nbsp;
				<select class="select-filter-channel" name="channel">
					<option value="">All</option>
					<?php foreach($ytChannels as $channel):?>
					<?php $selected = ($ytchannel == $channel['pk_channel_id']) ? 'selected' : ''; ?>
					<option value="<?=$channel['pk_channel_id']?>" <?=$selected?>><?=$channel['title']?></option>
					<?php endforeach;?>
				</select>
			</p>
			<p>
				<span class="op-tip">Choose a publish date filter range:</span>&nbsp;
				<input class="filter-datepicker" type="date" name="start" value="<?=$start?>" />&nbsp;to&nbsp;
				<input class="filter-datepicker" type="date" name="end" value="<?=$end?>" />
			</p>
			<p>	
				<span class="op-tip" title="Specifies the query term to search for...">Search Query Term: </span>
				<input type="text" name="q" placeholder="Specifies the query term to search for..." 
					value="<?=$queryparam?>" style="min-width:520px;" />&nbsp;
			</p>
			<p>
				<span class="op-tip" title="Max request for each channel crawl request">maxResult: </span>
				<?php $maxresult = $maxresult ? $maxresult : 50 ?>
				<input type="text" name="maxResult" value="<?=$maxresult?>" />&nbsp;
				<?php $threshode = $threshode ? $threshode : 10000 ?>
				<span class="op-tip" title="Threshode for the whole crawl request">threshode: </span>
				<input type="text" name="threshode" value="<?=$threshode?>"/>&nbsp;
				<span class="op-tip" title="Iterate deep to crawl each channel">Iterate: </span>
				<?php $checked = $iterate ? 'checked' : '' ?>
				<input type="checkbox" name="iterate" value="1" <?=$checked?> />&nbsp;
			</p>
		</div>
		<div class="part-action">
			<button class="btn btn-info btn-filter" onclick="trigger_action('filter');">filter</button>
			<button class="btn btn-info btn-filter" onclick="trigger_action('refresh');">refresh</button>
			<button class="btn btn-info btn-crawl" onclick="trigger_action('crawl');">crawl</button>
		</div>
	</div>
	<div class="content-wrapper">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-post-title"><span>post title</span></th>
					<th>user name</th><th>post channel</th><th>YT video id</th>
					<th class="col-yt-channel">YT channel</th>
					<th>publish date</th><th>actions</th>
				</tr>
			</thead>
			<?php if(!empty($ytVideos)): ?>
			<tbody>
			<?php foreach($ytVideos as $ytVideo): ?>
				<tr>
					<td class="col-post-title">
						<?php 
							$postid = $ytVideo['post']['pk_id']; $postTitle = $ytVideo['post']['btitle'];
						?>
						<a target="_blank" href="<?=_ppv($postid, $postTitle, $ytVideo['post']['type'])?>" 
							title="<?=$postTitle?>"><?=$postTitle?></a>				
					</td>
					<td>
						<?php
							$memberId = $ytVideo['post']['fk_member_id']; $username = $ytVideo['post']['fk_user_name'];
						?>
						<a target="_blank" href="<?=_mp($memberId, $username)?>"><?=$username?></a>
					</td>
					<td>
						<?php
							$channelId = $ytVideo['post']['fk_channel_id']; $channelName = $ytVideo['post']['fk_channel_name'];
						?>
						<a target="_blank" href="<?=_chv($channelId, $channelName)?>"><?=$channelName?></a>
					</td>
					<td>
						<?php $ytEmbededUrl = m('LibYoutube')->assembleYtWatchUri($ytVideo['pk_ytvid']); ?>
						<a target="_blank" href="<?=$ytEmbededUrl?>"><?=$ytVideo['pk_ytvid']?></a>
					</td>
					<td class="col-yt-channel" title="<?=$ytVideo['yt_chtitle']?>"><?=$ytVideo['yt_chtitle']?></td>
					<td><?=get_tzlocalized_time($ytVideo['published_at'])?></td>
					<td class="col-actions">
						<a class="btn btn-default btn-admin" target="_blank" href="<?=_editp($postid)?>" role="button">edit</a>
						<?php if($ytVideo['post']['status'] != 1): ?>
						<? $online = _admin(array('c'=>'member', 'a'=>'reviewpost', 'status'=>1, 'pid'=>$postid)); ?>
						<a class="btn btn-default btn-admin" href="<?=$online?>" role="button">online</a>
						<?php endif; ?>
						<?php if($ytVideo['post']['status'] != -1): ?>
						<? $offline = _admin(array('c'=>'member', 'a'=>'reviewpost', 'status'=>-1, 'pid'=>$postid)); ?>
						<a class="btn btn-default btn-admin" href="<?=$offline?>" role="button">offline</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr><td colspan="7">
					<div class="ip-pagination-box"><ul class="bootstrap-paginator" style="margin: 15px 15px 15px 0;"></ul></div>
				</td></tr>
			</tfoot>
			<? else: ?>
			<tbody><tr><td colspan="7">No records.</td></tr></tbody>
			<?php endif; ?>
		</table>
	</div>
</div>

<?php $this->startBlock('__script');?>
<script type="text/javascript">
var datefield=document.createElement("input")
datefield.setAttribute("type", "date")
if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
	document.write('<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />\n')
    document.write('<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.js"><\/script>\n')
}
if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
	$(document).ready(function(e) {
		$('.filter-datepicker').datepicker();
	});
}

function trigger_action(action) {
	var baseUrl = '<?=$baseUrl?>';
	var params = {'trigger':action }; 
	var ytvids = $("input[name='ytvids']").val();
	if (ytvids) {
		params['ytvids'] = ytvids;
	} else {
		params['channel'] = $("select[name='channel']").val();
		if (action=="crawl") {
			params['maxResult'] = $("input[name='maxResult']").val();
			params['threshode'] = $("input[name='threshode']").val();
			if($("input[name='iterate']").is(":checked")) {
				params['iterate'] = $("input[name='iterate']").val();
			}
			params['q'] = $("input[name='q']").val();
		} else {
			params['start'] = $("input[name='start']").val();
			params['end'] = $("input[name='end']").val();
		}
	}

	var url = baseUrl + '&' + $.param(params);
	$(location).attr('href', url);
}

<?php if($pager['totalPages'] > 1): ?>
var options = { 
	bootstrapMajorVersion: 3, 
	size: 'small', 
	numberOfPages: 10, 
	useBootstrapTooltip: false,
	currentPage: <?=$pager['page']?>, 
	totalPages: <?=$pager['totalPages']?>, 
	pageUrl: function(type, page, current){
		var pageUrl = '<?=$pager['pageUrl']?>';
		pageUrl = pageUrl.replace("&trigger=crawl", "");
		return pageUrl + "&page=" + page;
	} 
}
$('.bootstrap-paginator').bootstrapPaginator(options);
<?php endif; ?>
</script>
<?php $this->endBlock('__script'); ?>