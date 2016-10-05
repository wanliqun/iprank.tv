<?php
$this->layout('default');
?>
<div class="ip-wrapper">
	<div class="ip-activate-container ip-recent-container">
		<section class="ip-left-pannel">
			<div id="activate-tip-area">
				<?php if(empty($error)): ?>
				<img src="/public/images/account-activated.jpg" class="account-activated-pic" alt="Account activated" />
				<?php else: ?>
				<span><?=$error?></span>
				<?php endif; ?>
			</div>
		</section>
		<aside class="ip-aside-bar">
			<div class="ads-container"><img src="http://pagead2.googlesyndication.com/simgad/12822508176744299911" style="margin: 0 auto; display:block;" /></div>
		</aside>
	</div> <!--/#main-container-->
</div>