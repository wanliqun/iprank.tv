<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-fill-upload-detail-wrapper">
		<div class="step-wizard">
			<ul class="track-progress">
			  <li><span><em class="badge-number">1</em>Upload Video</span></li>
			  <li class="done"><span><em class="badge-number">2</em>Fill Content</span></li>
			  <li><span><em class="badge-number">3</em>Finish</span></li>
			</ul>
		</div>
		<section class="ip-fill-content-wrapper">
			<header>
				<h1>Please descripe your upload</h1>
				<h2>Please fill out all of the information for all of the fields</h2>
			</header>
			<form class="form-horizontal" id='fillupload-form' role="form"
				action="/?c=ajax&a=fill_uploaddetail&format=json" method="post">
				<div class="left-side">
					<div class="content-thumbnail">
						<a href="<?=$videoInfo['watch_url']?>" target="_blank" rel="nofollow">
							<img src="<?=$videoInfo['thumbnail']?>" alt="[<?=$videoInfo['title']?>]" class="upload-video-thumbnail">
						</a>
						<input type="hidden" name="src_type" value="<?=$videoInfo['src_type']?>" />
						<input type="hidden" name="ytv_id" value="<?=$videoInfo['ytv_id']?>" />
					</div>
					<div class="content-option">
						<div class="checkbox">
							<label><input type="checkbox" name="is_nsfw" value="1">This content is NSFW (Not Safe For Work)</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" name="is_original" value="0">This is not my original work</label>
						</div>
					</div>
					<div class="share-option">
						<span style="display:inline-block; vertical-align:bottom;">Share to:&nbsp;</span>
						<div style="display: inline-block;">
							<div class="checkbox">
								<label class="checkbox-inline"><input type="checkbox" name="share_fb" id="chck-share-fb" value="1">
									<img src="/public/images/fb-logo.png" alt="Image" class="img-responsive" style="max-width:100%;">
								</label>
							</div>
							<div class="checkbox">
								<label class="checkbox-inline"><input type="checkbox" name="share_tw" id="chck-share-tw" value="1">
									<img src="/public/images/tw-logo.png" alt="Image" class="img-responsive" style="max-width:100%;">
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="right-side">
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">title</span>
  							<input type="text" class="form-control" placeholder="Title" name="title" 
								value="<?=htmlentities($videoInfo['title'])?>" required="required"></input>
						</div>
						<span class="error-tip title-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">description</span>
							<textarea class="form-control" rows="10" placeholder="Description" 
								name="description" required="required"><?=$videoInfo['description']?></textarea>
						</div>
						<span class="error-tip description-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">channel</span>
							<select class="form-control" name="channel" required="required" >
								<option value="">Please choose a channel</option>
								<?php foreach($channels as $channel):?>
								<option value="<?=$channel['pk_id']?>"><?=$channel['name']?></option>
								<?php endforeach;?>
							</select>
						</div>
						<span class="error-tip channel-errtip" style="display:none"></span>
					</div>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">tags</span>
							<input id="tags-input-field" class="form-control" type="text" name="tags" value=""
								placeholder="Tags are seperated by comma" required="required"/>
						</div>
						<span class="error-tip tags-errtip" style="display:none"></span>
					</div>
					<?php if(ME::isAdmin()): ?>
					<div class="form-group">
						<div class="input-group">
  							<span class="input-group-addon">publish as:</span>
							<select class="form-control" name="username" required="required">
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
					<p>Be creative. The description could be 2,500 characters in length but keep in mind that the first two lines (100 characters) will show up with your video in search results so don’t be too long winded from the get go. Once a user ends up on the video detail page, they will be able to expand the description to read the rest of your essay.</p>
				</section>
				<section class="inputfield-tips">
					<h1>Channel</h1>
					<p>Tell us which channel your video fits in.</p>
				</section>
				<section class="inputfield-tips">
					<h1>Tags</h1>
					<p>Think of tags that best describe your video. These tags drive search results and our related algorithm. You can add tags by pressing "Enter" or "Comma" on your keyboard.</p>
				</section>
			</article>
		</aside>
	</div>
</div>