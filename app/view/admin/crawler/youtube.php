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
	<div class="addyt-channel-op-area">
		<form method="post" action="" >
			<input type="hidden" name="add_ytchannels" value="1" />
			<span class="op-tip" style="font-weight:bold;">Add YouTube Channel(s) By Username(s): </span>&nbsp;
			<input type="text" placeholder="input username(s) separated by comma..." 
				name="for_ytusers" style="min-width:250px;" />&nbsp;&nbsp;
			<span class="op-tip" style="font-weight:bold;">Or By Channel Id(s): </span>&nbsp;
			<input type="text" placeholder="input channel id(s) separated by comma..." 
				name="chids" style="min-width:250px;" />&nbsp;
			<button class="btn btn-info btn-sm" type="submit" style="min-width:64px;">Add</button>
		</form>
	</div>
	<div class="content-wrapper">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-channel-title"><span>channel title</span></th><th>user name</th>
					<th>views</th><th>comments</th><th>subscribers</th><th>videos</th>
					<th>publish date</th><th>update at</th><th>actions</th>
				</tr>
			</thead>
			<?php if(!empty($ytChannels)): ?>
			<tbody>
			<?php foreach($ytChannels as $ytChannel): ?>
				<tr>
					<td class="col-channel-title" style="max-width:280px;">
						<a target="_blank" href="<?=m('LibYoutube')->assembleYtChannelUrl($ytChannel['pk_channel_id'])?>" 
							title="<?=$ytChannel['title']?>"><?=$ytChannel['title']?></a>				
					</td>
					<td>
						<a target="_blank"  href="<?=m('LibYoutube')->assembleYtUserHomepageUrl($ytChannel['for_username'])?>">
							<?=$ytChannel['for_username']?></a>
					</td>
					<td><?=$ytChannel['views_count']?></td><td><?=$ytChannel['comments_count']?></td>
					<td><?=$ytChannel['subscribers_count']?></td><td><?=$ytChannel['videos_count']?></td>
					<td><?=get_tzlocalized_time($ytChannel['published_at'])?></td>
					<td><?=get_tzlocalized_time($ytChannel['updated_on'])?></td>
					<td class="col-actions">
						<?php $refresh = _admin(array('c'=>'crawler', 'a'=>'refresh_ytchannel', 'chid'=>$ytChannel['pk_channel_id'])) ?>
						<a class="btn btn-default btn-admin" href="<?=$refresh?>" role="button">refresh</a>
						<?php $delete = _admin(array('c'=>'crawler', 'a'=>'delete_ytchannel', 'chid'=>$ytChannel['pk_channel_id'])) ?>
						<a class="btn btn-default btn-admin" href="<?=$delete?>" role="button">delete</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr><td colspan="9">
					<div class="ip-pagination-box"><ul class="bootstrap-paginator" style="margin: 15px 15px 15px 0;"></ul></div>
				</td></tr>
			</tfoot>
			<? else: ?>
			<tbody><tr><td colspan="9">No records.</td></tr></tbody>
			<?php endif; ?>
		</table>
	</div>
</div>

<?php if($pager['totalPages'] > 1): ?>
	<?php $this->startBlock('__script');?>
<script type="text/javascript">
var options = { 
	bootstrapMajorVersion: 3, 
	size: 'small', 
	numberOfPages: 10, 
	useBootstrapTooltip: false,
	currentPage: <?=$pager['page']?>, 
	totalPages: <?=$pager['totalPages']?>, 
	pageUrl: function(type, page, current){
		var pageUrl = '<?=$pager['pageUrl']?>';
		return pageUrl + "&page=" + page;
	} 
}
$('.bootstrap-paginator').bootstrapPaginator(options);
</script>
	<?php $this->endBlock('__script'); ?>
<?php endif; ?>