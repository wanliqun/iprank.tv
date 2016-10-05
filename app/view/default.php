<?php
/**
 * 布局模版
 * default.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage view
 * @version 2014-05-08
 */
$pageTitle = isset($pageTitle) ? $pageTitle : c('meta_title');
$pagekeywords = isset($pagekeywords) ? $pagekeywords : get_page_meta('keywords');
$pageDescription = isset($pageDescription) ? $pageDescription : get_page_meta('description');
$pageCanonical = isset($pageCanonical) ? $pageCanonical : '';
$pageCss = isset($pageCss) ? $pageCss : array();
$pageJs = isset($pageJs) ? $pageJs : array();
$fbSettings = isset($fbSettings) ? $fbSettings : c('facebook');
$ogTags = isset($ogTags) ? $ogTags : array(); 
$twCard = isset($twCard) ? $twCard : array();
$controllerName = $this->app->controllerName;
$actionName = $this->app->actionName;
$pageHeader = isset($pageHeader) ? $pageHeader  : "comm/header";
$pageFooter = isset($pageFooter) ? $pageFooter  : "comm/footer";
$loginUser = c('__user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- title -->
<title><?=$pageTitle;?></title>
<!-- meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="generator" content="http://www.coolphp.org" />
<meta name="author" content="http://www.itbeing.com" />
<?php if(!empty($pagekeywords)): ?><meta name="keywords" content="<?=$pagekeywords;?>" /><?php endif;?>
<?php if(!empty($pageDescription)): ?><meta name="description" content="<?=$pageDescription;?>" /><?php endif;?>
<!-- canonical -->
<?php if(!empty($pageCanonical)): ?><link rel="canonical" href="<?=$pageCanonical?>" /><?php endif;?>
<!-- icon -->
<link href="<?=media('favicon.ico','images')?>" rel="shortcut icon" type="image/x-icon" />
<link href="<?=media('favicon.ico','images')?>" rel="icon" type="image/x-icon" /> 
<link href="<?=media('favicon.ico','images')?>" rel="bookmark" type="image/x-icon" />
<!-- css -->
<?php if(true): ?>
<link href="<?=_minify('css')?>" media="screen" rel="stylesheet" type="text/css">
<?php else: ?>
<!-- for development use only -->
<link rel="stylesheet/less" type="text/css" href="/less/style.less" />
<!-- set options before less.js script -->
<script type="application/javascript">
  var less = {
      env: "development",
      async: false,
      fileAsync: false,
      poll: 1000,
      functions: {},
      dumpLineNumbers: "mediaquery",
      relativeUrls: false,
      rootpath: "",
    };
</script>
<script src="/less/less.js/dist/less-1.7.0.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (!empty($pageCss)): ?>
<link href="<?=_minify('', $pageCss, 'css')?>" media="screen" rel="stylesheet" type="text/css">
<?php endif ?>
<?php echo $this->loadBlock('__style');?>
<!-- open graph -->
<?php if(!empty($ogTags)): ?>
<?php if(!empty($fbSettings)): ?>
<meta property="fb:app_id" content="<?=$fbSettings['appid']?>" />
<?php endif; ?>
<?php foreach($ogTags as $ogKey=>$ogValue): ?>
<meta property="og:<?=$ogKey?>" content="<?=$ogValue?>" />
<?php endforeach; ?>
<?php endif; ?>
<!-- twitter -->
<?php if(!empty($twCard)): ?>
<?php foreach($twCard as $twKey=>$twValue): ?>
<meta property="twitter:<?=$twKey?>" content="<?=$twValue?>" />
<?php endforeach; ?>
<?php endif; ?>
<!-- js -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- GA Tag -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-55813316-1', 'auto');
ga('send', 'pageview');
</script>
<!-- End GA tag -->
</head>
<body>
<!-- body content -->
<?php echo $this->load( $pageHeader);?> 
<?php echo $this->content; ?> 
<?php echo $this->load( $pageFooter );?> 
<!-- js -->
<script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5396b6603feab6c1&async=1&domready=1"></script>
<script src="<?=_minify('js')?>"></script>
<?php if(!empty($pageJs)): ?>
<script src="<?=_minify('', $pageJs, 'js')?>"></script>
<?php endif; ?>
<?php echo trim( $this->loadBlock('__script') );?>
<script type="text/javascript">
$("li.logined").hover(function() { $('.nav-user-dropmenu').show(); }, function() { $('.nav-user-dropmenu').hide(); });
$("li.unlogined #login-signup-btn").click(function(e) { showLoginBox('<?=_this()?>'); e.preventDefault(); });
</script>
<!-- CNZZ tag -->
<div style="display:none;">
	<script type="text/javascript">
	var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
	document.write(unescape("%3Cspan id='cnzz_stat_icon_1254054597'%3E%3C/span%3E%3Cscript src='" + 
			cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1254054597' type='text/javascript'%3E%3C/script%3E"));
	</script>
</div>
<!-- End CNZZ tag -->
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=10222422; 
var sc_invisible=1; 
var sc_security="2bdc2a1d"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" + scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="shopify visitor
statistics" href="http://statcounter.com/shopify/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/10222422/0/2bdc2a1d/1/"
alt="shopify visitor statistics"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
<!-- Quantcast Tag -->
<script type="text/javascript">
var _qevents = _qevents || [];
(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();
_qevents.push({
qacct:"p-Qgf4jfCPaNNdp"
});
</script>
<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-Qgf4jfCPaNNdp.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->
</body>
</html>
<?php if(c('after_view_html')) echo c('after_view_html'); ?>