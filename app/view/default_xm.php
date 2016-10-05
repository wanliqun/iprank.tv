<?php
/**
 * 默认XML布局文件
 * default_xml.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage view
 * @version 2014-05-08
 */
header('Content-type: text/xml');
?>
<itbeing>
	<controller><?php echo $this->app->controllerName?></controller>
	<action><?php echo $this->app->actionName?></action>
	<content><?php echo $this->content ?></content>
	<copyright>Itbeing</copyright>
	<version><?=date('Y-m-d') ?></version>
</itbeing>