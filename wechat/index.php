<?php
/**
 *
 *
 */
define('APP_ID','wechat');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/33hao.php')) exit('33hao.php isn\'t exists!');

define('APP_SITE_URL',WECHAT_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('WECHAT_TEMPLATES_URL', WECHAT_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_WECHAT_TEMPLATES_URL', dirname(__FILE__).'/templates/'.TPL_NAME);
define('WECHAT_RESOURCE_SITE_URL',WECHAT_SITE_URL.'/resource');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
?>
