<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-upload-wrapper">
		<div class="step-wizard">
			<ul class="track-progress">
			  <li class="done"><span><em class="badge-number">1</em>Upload Video</span></li>
			  <li><span><em class="badge-number">2</em>Fill Content</span></li>
			  <li><span><em class="badge-number">3</em>Finish</span></li>
			</ul>
		</div>
		<section class="ip-left-pannel">
			<section class="ip-add-ytvideo-wrapper">
				<h1>Please enter your YouTube video URL:</h1>
				<div class="input-group">
					<input type="text" class="form-control" id="input-ytvideo-uri" placeholder="Enter your YouTube video url here" 
						required="required">
					<span class="input-group-btn">
						<button class="btn btn-default btn-add-video" type="button">Add Video</button>
					</span>
				</div>
			</section>
			<section class="ip-preview-wrapper" style="display: none;">
				<h1>Preview your video:</h1>
				<section class="ip-video-container">
					<iframe width="750px" height="464px" src="" frameborder="0" allowfullscreen></iframe>	
				</section>
				<form action="<?=_fud();?>" method="post">
					<input type="hidden" id="input-ytv-id" name="ytv_id" value="" />
					<button class="btn btn-default btn-lg btn-block" type="submit">Post this video</button>
				</form>
			</section>
		</section>
		<aside class="ip-sidebar">
			<article class="uploading-instruction">
				<section class="tips-for-uploading">
					<h1>Tips for uploading your video:</h1>
					<ul>
						<li>We only accept videos that you've already uploaded to YouTube at this moment. Just send us the URL by filling out 
							the form to the left.</li>
					</ul>
				</section>
				<section class="uploading-rule-part">
					<h1>Uploading rules:</h1>
					<ul class="">
						<li>Don't be off topic: iPrank is not a random stuff sharing website,  don't post random stuff.</li>	
						<li>Be Original and Funny: To get featured.</li>
						<li>Use proper Titles and Tags.</li>
						<li>Use proper grammar and spelling in your content.</li>
						<li>Posting contents containing explicit material such as nudity, horrible injury etc. will lead a permanent ban.</li>
						<li>Don't Spam: Don't post the same content repeatedly.</li>
						<li>Don't Post Your/Other's Personal Information: Posting the full name, employer, or other real-life details of 
							another person is not allowed.</li>
					</ul>
				</section>
			</article>
		</aside>
	</div>
</div>