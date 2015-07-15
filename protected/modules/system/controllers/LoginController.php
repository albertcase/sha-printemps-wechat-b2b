<?php
class LoginController extends SystemLoginController
{
	private $_identity;

	public function actionIndex()
	{
		if(isset(Yii::app()->user->sysUserId)){
			header("Location:".Yii::app()->request->baseUrl."/system");
			Yii::app()->end();
		}
		$this->render('index');
		
	}

	public function actionLogin()
	{
		if(isset($_POST) && !empty($_POST)){
			if($this->_identity===null){
				$this->_identity=new UserIdentity($_POST['username'],$_POST['password']);
				$this->_identity->authenticate();
			}
			
			if($this->_identity->errorCode===UserIdentity::ERROR_NONE){
				$duration=0;
				Yii::app()->user->login($this->_identity,$duration);
				die("1");
			}else if($this->_identity->errorCode===UserIdentity::ERROR_PASSWORD_INVALID){
				die("2");
			}else if($this->_identity->errorCode==3){
				die("3");
			}
			die("0");
			Yii::app()->end();
		}
	}

	public function actionLoginout()
	{
		Yii::app()->user->logout();
		echo 1;
		Yii::app()->end();
	}
}