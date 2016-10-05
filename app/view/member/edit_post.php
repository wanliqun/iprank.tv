<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-fill-upload-detail-wrapper">
		<section class="ip-fill-content-wrapper">
			<header>
				<h1>Edit Post ('<?=$post['btitle']?>')</h1>
			</header>
			<form class="form-horizontal" id='editpost-form' role="form" action="/?c=ajax&a=editpost&format=json" method="post">
				<input type="hidden"  name="postid" value="<?=$postid?>" />
				<div class="left-side">
					<div class="content-thumbnail">
						<a href="<?=$post['detail']['src_from']?>" target="_blank" rel="nofollow">
							<img src="<?=$post['cover_url']?>" alt="[<?=$post['btitle']?>]" class="upload-video-thumbnail">
						</a>
					</div>
					<div class="content-option">
						<div class="checkbox">
							<?php $nsfw_checked = $post['is_nsfw'] ? "checked='checked'":''; ?>
							<label>
								<input type="checkbox" name="is_nsfw" value="1" <?=$nsfw_checked?>>
								This content is NSFW (Not Safe For Work)
							</label>
						</div>
						<div class="checkbox">
							<?php $original_checked = $post['is_original'] ? '':"checked='checked'"; ?>
							<label>
								<input type="checkbox" name="is_original" value="0" <?=$original_checked?>>
								This is not my original work
							</label>
						</div>
					</div>
				</div>
				<div class="right-side">
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">title</span>
							<?php 
								$post_title = $post['btitle'];
								if (!empty($post['detail']['title'])) $post_title  = $post['detail']['title'];
							 ?>
  							<input type="text" class="form-control" placeholder="Title" name="title" 
								value="<?=htmlentities($post_title)?>" required="required"></input>
						</div>
						<span class="error-tip title-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">description</span>
							<?php
								$post_description = $post['bdescription'];
								if (!empty($post['detail']['description'])) $post_description = $post['detail']['description'];
							?>
							<textarea class="form-control" rows="10" placeholder="Description" 
								name="description" required="required"><?=$post_description?></textarea>
						</div>
						<span class="error-tip description-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">channel</span>
							<select class="form-control" name="channel" required="required" >
								<option value="">Please choose a channel</option>
								<?php foreach($channels as $channel):?>
								<?php $opt_selected = ($post['fk_channel_id'] == $channel['pk_id']) ? "selected='selected'" : ''; ?>
								<option value="<?=$channel['pk_id']?>" <?=$opt_selected?>><?=$channel['name']?></option>
								<?php endforeach;?>
							</select>
						</div>
						<span class="error-tip channel-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">tags</span>
							<?php $post_tags = !empty($post['tags']) ? implode(',', array_column($post['tags'], 'name')) : ''; ?>
							<input class="form-control" type="text" name="tags" value="<?=$post_tags?>" data-role="tagsinput" 
								placeholder="Press enter to add tag" required="required"/>
						</div>
						<span class="error-tip tags-errtip" style="display:none"></span>
					</div>
					<?php if(ME::isAdmin()): ?>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">publish as:</span>
							<select class="form-control" name="username" required="required">
								<optgroup label="== Original User ==">
									<option><?=$post['fk_user_name']?></option>
								</optgroup>
								<optgroup label="== Me ==">
									<option><?=ME::name()?></option>
								</optgroup>
								<optgroup label="== Dummy Users ==">
								<?php foreach($dummies as $dummy): ?>
									<option><?=$dummy['username']?></option>
								<?php endforeach; ?>
								</optgroup>
							</select>
						</div>
						<span class="error-tip username-errtip" style="display:none"></span>
					</div>
					<?php endif; ?>
					<div class="row">
						<button class="btn btn-default btn-lg btn-block save-btn" type="submit">Save Details&nbsp;<i class="fa fa-angle-double-right"></i></button>
					</div>
				</div>
			</form>
		</section>
		<aside class="ip-sidebar">
			<article class="filling-instruction">
				<h1>Some Helpful tips</h1>
				<section class="inputfield-tips">
					<h1>Title</h1>
					<p>Enter a title that best describes your video. The title will be used wherever the video is featured.  Post without a meaningful title like "Title" "Lol" will not be featured. The title can't be more than 300 characters.</p>
				</section>
				<section class="inputfield-tips">
					<h1>Description</h1>
					<p>Be creative. The description could be 2,500 characters in length but keep in mind that the first two lines (100 characters) will show up with your video in search results so don’t be too long winded from the get go. Once a user ends up on the video page, they will be able to expand the description to read the rest of your essay.</p>
				</section>
				<section class="inputfield-tips">
					<h1>Channel</h1>
					<p>Tell us which channel your video fits in.</p>
				</section>
				<section class="inputfield-tips">
					<h1>Tags</h1>
					<p>Think of tags that best describe your video. These tags drive search results and our related algorithm. You can add tags by pressing "Enter" on your keyboard.</p>
				</section>
			</article>
		</aside>
	</div>
</div>