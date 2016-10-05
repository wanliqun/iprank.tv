<?php
$this->layout('default_admin');
?>
<div class="admin-members-wrapper">
	<ul class="nav-tabbar">
		<?php foreach($availableTabs as $each): ?>
		<?php $active = (($each==$tab) ? 'active' : '') ?>
		<li class='<?=$active?>'>
			<a href="<?=_admin(array('c'=>'member', 'tab'=>$each))?>"><?=$each?></a>
		</li>
		<?php endforeach; ?>
	</ul>
	<div class="content-wrapper">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-member-username"><span>user name</span></th>
					<th>email</th><th>source</th><th>country</th><th>gender</th><th>birthday</th><th>member since</th>
					<th>ip</th><th>actions</th>
				</tr>
			</thead>
			<?php if(!empty($members)): ?>
			<tbody>
				<?php foreach($members as $member): ?>
				<tr>
					<td class="col-member-username"><span><?=$member['username']?></span></td>
					<td><?=$member['email']?></td><td><?=$member['source']?></td>
					<td><?=$member['fk_country_iso2']?></td><td><?=$member['gender']?></td><td><?=$member['birthday']?></td>
					<td><?=$member['member_since']?></td><td><?=$member['from_ip']?></td>
					<td class="col-actions">
						<?php if($member['status'] != 0): ?>
						<? $block = _admin(array('c'=>'member', 'a'=>'adminuser', 'status'=>0, 'userid'=>$member['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$block?>" role="button">block</a>
						<?php endif; ?>
						<?php if($member['status'] != -1): ?>
						<? $delete = _admin(array('c'=>'member', 'a'=>'adminuser', 'status'=>-1, 'userid'=>$member['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$delete?>" role="button">delete</a>
						<?php endif; ?>
						<?php if($member['status'] != 1): ?>
						<? $recover = _admin(array('c'=>'member', 'a'=>'adminuser', 'status'=>1, 'userid'=>$member['pk_id'])); ?>
						<a class="btn btn-default btn-admin" href="<?=$recover?>" role="button">recover</a>
						<?php endif; ?>
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