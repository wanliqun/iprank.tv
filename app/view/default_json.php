<?php
/**
 * 默认JSON布局文件
 * default_json.php
 *
 * @copyright Copyright (c) 2010-2014 Itbeing (Beijing) Tech co.,Ltd
 * @author itbeing<td@itbeing.com>
 * @package iprank.co
 * @subpackage view
 * @version 2014-05-08
 */
$this->vars['controller'] =  $this->app->controllerName;
$this->vars['action'] =  $this->app->actionName;
$this->vars['copyright'] =  'itbeing';
print_r( json_encode( $this->vars ) );
?>