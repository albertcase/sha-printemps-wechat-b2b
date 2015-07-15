<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$db = Yii::app()->db;
		$sql = "SELECT * FROM same_sys_user WHERE uname=:uname";
		$command = $db->createCommand($sql);
		$command->bindParam(':uname',$this->username,PDO::PARAM_STR);
		$record = $command->select()->queryRow();
		if(!$record){
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }else if($record['password'] !== md5($this->password)){
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }else if($record['status']!='激活'){
            $this->errorCode = 3;
        }else{
        	$clientIp = Common::getClientIp();
        	
			$sqlLoginLog = "INSERT INTO same_sys_user_login_log (uid,uname,ip) VALUES ('".$record['id']."','".$record['uname']."','".$clientIp."')";
        	$db->createCommand($sqlLoginLog)->execute();
			
        	$this->setState('sysUserId', $record['id']);
        	$this->setState('sysUserName', $record['uname']);
			$this->setState('sysUserRName', $record['rname']);

			$_SESSION['sysUserId'] = $record['id'];
			$_SESSION['sysUserName'] = $record['uname'];
			
			//系统用户权限
			$sqlPermissions = "SELECT role FROM same_sys_permissions WHERE id='".$record['pid']."'";
			$rsPermissions =  $db->createCommand($sqlPermissions)->select()->queryScalar();
			$this->setState('sysPermissions', $rsPermissions);
			

			
        	$this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}
}