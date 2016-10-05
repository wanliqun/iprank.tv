<?php
$this->layout('default_admin');
?>
<div class="admin-tags-wrapper">
	<ul class="nav-tabbar">
		<li class="active">online</li><li>trashed</li><li>all</li>
	</ul>
	<div class="content-wrapper">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-tag-name"><input type="checkbox" class="checkall"/><span>tag name</span></th>
					<th>user name</th>
					<th>created at</th>
					<th>actions</th>
					<th style="width:15%;"></th>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0;$i<25;$i++): ?>
				<tr>
					<td class="col-tag-name">
						<input type="checkbox" class="checkone" /> 
						<input type="text" style="width:90%;" placeholder="Tag name here">				
					</td>
					<td><a href="#">wanliqun</a></td>
					<td>2014-06-01 23:01:50</td>
					<td>
						<button><a href="#" role="button">edit</a></button>
					</td>
					<td></td>
				</tr>
				<?php endfor; ?>
			</tbody>
		</table>
		<div class="seperator-line" style="padding-top:5px; margin: 0;">
			<input type="checkbox" class="checkall" style="margin-left:8px;" /><i>check all</i>
			<span class="action-tip">&nbsp;&nbsp;&nbsp;&nbsp;with selected:</span><button>delete</button>
			<div class="ip-pagination-box"><ul class="bootstrap-paginator" style="margin: 0 15px 15px 0;"></ul></div>
		</div>
	</div>
</div>

<?php $this->startBlock('__script');?>
<script type="text/javascript">
var options = { 
	currentPage: 1, 
	totalPages: 15, 
	bootstrapMajorVersion: 3, 
	size: 'small', 
	numberOfPages: 10, 
	useBootstrapTooltip: false, 
}
$('.bootstrap-paginator').bootstrapPaginator(options);
</script>
<?php $this->endBlock('__script'); ?>