<?php
$this->layout('default_admin');
?>
<div class="admin-posts-wrapper">
	<ul class="nav-tabbar">
	<?php foreach($availableTabs as $each): ?>
		<?php $active = (($each==$tab) ? 'active' : '') ?>
		<li class='<?=$active?>'>
			<a href="<?=_admin(array('c'=>'post', 'tab'=>$each))?>"><?=$each?></a>
		</li>
	<?php endforeach; ?>
	</ul>
	<div class="content-wrapper">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-post-title"  style="text-align: center;">title</th>
					<th>user name</th>
					<th>channel name</th>
					<th>publish date</th>
					<th>actions</th>
				</tr>
			</thead>
			<?php if(!empty($posts)): ?>
			<tbody>
			<?php foreach($posts as $post): ?>
				<tr>
					<td class="col-post-title">
						<span>
							<a href="<?=_ppv($post['pk_id'], $post['btitle'], $post['type'])?>" title="<?=$post['btitle']?>"  
								target="_blank"><?=$post['btitle']?></a>
						</span>
					</td>
					<td>
						<a href="<?=_mp($post['fk_member_id'], $post['fk_user_name'])?>" target="_blank"><?=$post['fk_user_name']?></a>
					</td>
					<td>
						<a href="<?=_chv($post['fk_channel_id'], $post['fk_channel_name'])?>" target="_blank"><?=$post['fk_channel_name']?></a>
					</td>
					<td><?=get_tzlocalized_time($post['created_at'])?></td>
					<td>
						<a class="btn btn-default btn-admin" target="_blank" href="<?=_editp($post['pk_id'])?>" role="button">edit</a>
						<?php if($post['status'] != 1): ?>
						<? $online = _admin(array('c'=>'member', 'a'=>'reviewpost', 'status'=>1, 'pid'=>$post['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$online?>" role="button">online</a>
						<?php endif; ?>
						<?php if($post['status'] != -1): ?>
						<? $offline = _admin(array('c'=>'member', 'a'=>'reviewpost', 'status'=>-1, 'pid'=>$post['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$offline?>" role="button">offline</a>
						<?php endif; ?>
						<?php if($tab != 'featured'): ?>
						<? $feature = _admin(array('c'=>'member', 'a'=>'featurepost', 'status'=>1, 'pid'=>$post['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$feature?>" role="button">feature it!</a>
						<? else: ?>
						<? $unfeature = _admin(array('c'=>'member', 'a'=>'featurepost', 'status'=>0, 'pid'=>$post['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$unfeature?>" role="button">unfeature it!</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr><td colspan="5">
					<div class="ip-pagination-box"><ul class="bootstrap-paginator" style="margin: 15px 15px 15px 0;"></ul></div>
				</td></tr>
			</tfoot>
			<? else: ?>
			<tbody><tr><td colspan="5">No records.</td></tr></tbody>
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