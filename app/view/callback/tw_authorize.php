<!DOCTYPE html>
<html lang="en">
<body>
<p style="font-size:1.1em;">Please close this window if it does not close automatically.</p>
</body>
<?php $this->startBlock('__script');?>
<script type="text/javascript">
var result = <?=json_encode($result);?>;
if (window.opener) {
	window.opener.Tw_OAuthCallback(result);
}
window.close();
</script>
<?php $this->endBlock('__script'); ?>
<?php echo trim( $this->loadBlock('__script') );?>
</html>