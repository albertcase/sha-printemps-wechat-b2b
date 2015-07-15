<?php 
date_default_timezone_set('PRC');
// change the following paths if necessary
$yii=dirname(__FILE__).'/protected/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
//新浪微博key
define( "WB_AKEY_SINA" , '499590258' );
define( "WB_SKEY_SINA" , '7dc72e72ff36443d2983cb5a05d6f9ad' );
define( "WB_CALLBACK_URL_SINA" , 'http://'.$_SERVER['SERVER_NAME'].'/sinaWeibo/callback' );

session_start();

require_once($yii);
Yii::createWebApplication($config)->run();
?>
