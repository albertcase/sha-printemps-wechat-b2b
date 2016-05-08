<?php
ini_set("display_errors","on");
require_once dirname(__FILE__).'/wechat.php';
$access_token=file_get_contents(dirname(__FILE__)."/../upload/access_token.txt");
print $access_token."\n";
$wechat = new wechat();
print_r($wechat->backaccesstoken());
echo "\n";
print_r($wechat->deacccesstoken());
