#!/bin/bash
result=$(ps -aux|grep forwardJobrun.php|grep -v grep)
if [[ $result ]]
then
	echo 'this service already runing';
	exit;
fi
php ~+/protected/models/Customs/forwardJobrun.php &
