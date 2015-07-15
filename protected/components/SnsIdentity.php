<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class SnsIdentity extends CUserIdentity
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
		
		$record = Sns::model()->findByAttributes(array('snsid'=>$this->username, 'type'=>$this->password));		
		if($record === null){
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}else{
			$clientIp = Common::getClientIp();
			//Sns::model()->updateByPk($record->id, array('latest_logtime'=>date('Y-m-d H:i:s'), 'last_ip'=>$clientIp));
			
			$LoginLog = new LoginLog();
			$LoginLog->attributes = array('uid'=>$record->id, 'type'=>$this->password, 'ip'=>$clientIp, 'campaign_id'=>$_SESSION['campaign_id']);
			$LoginLog->save();
					
			$this->setState('id', $record->id);
            $this->setState('name', $record->nickname);
            $this->setState('type', $this->password);
            if($this->password=='sina' && isset($_SESSION['token']))
            	$this->setState('token', $_SESSION['token']);
            
            if($this->password=='tx' && isset( $_SESSION['t_access_token']))
            	$this->setState('t_access_token', $_SESSION['t_access_token']);
            if($this->password=='tx' && isset( $_SESSION['t_expire_in']))
            	$this->setState('t_expire_in', $_SESSION['t_expire_in']);
            if($this->password=='tx' && isset( $_SESSION['t_code']))
            	$this->setState('t_code', $_SESSION['t_code']);
            if($this->password=='tx' && isset( $_SESSION['t_openid']))
             	$this->setState('t_openid', $_SESSION['t_openid']);
            if($this->password=='tx' && isset( $_SESSION['t_openkey']))
            	$this->setState('t_openkey', $_SESSION['t_openkey']);
            
            if($this->password=='renren' && isset( $_SESSION['access_token']))
            	$this->setState('access_token', $_SESSION['access_token']);
            
            if($this->password=='kaixin' && isset( $_SESSION['access_token']))
            	$this->setState('access_token', $_SESSION['access_token']);
            if($this->password=='kaixin' && isset( $_SESSION['refresh_token']))
            	$this->setState('refresh_token', $_SESSION['refresh_token']);
            
            $this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
}