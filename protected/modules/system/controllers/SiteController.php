<?php

class SiteController extends Controller
{	
	
	public function actionIndex()
	{
		
		$this->render('index');
	}

	public function actionServerInfo()
	{
		$max_upload= ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'Disabled';
		$max_ex_time=ini_get('max_execution_time').' seconds';
		
		echo "当前主机名:".$_SERVER['SERVER_NAME'];
		echo "<br>";
		echo "服务器标识的字串:".$_SERVER['SERVER_SOFTWARE'];
		echo "<br>";
		echo "访问页面时的请求方法:".$_SERVER['REQUEST_METHOD'];
		echo "<br>";
		echo "当前运行脚本所在的文档根目录:".$_SERVER['DOCUMENT_ROOT'];
		echo "<br>";
		echo "头信息的内容:".$_SERVER['HTTP_ACCEPT'];
		echo "<br>";
		echo "当前请求的 Host:".$_SERVER['HTTP_HOST'];
		echo "<br>";
		echo "用户使用的浏览器信息:".$_SERVER['HTTP_USER_AGENT'];
		echo "<br>";
		echo "前页面用户的 IP:".$_SERVER['REMOTE_ADDR'];
		echo "<br>";
		echo "用户连接到服务器时所使用的端口:".$_SERVER['REMOTE_PORT'] ;
		echo "<br>";
		echo "php版本:".PHP_VERSION ;
		echo "<br>";
		//echo "MYSQL 版本:".@mysql_get_host_info(Yii::app()->db) ;
		//echo "<br>";
		echo "操作系统:".PHP_OS ;
		echo "<br>";
		echo "最大上传限制:".$max_upload ;
		echo "<br>";
		echo "最大执行时间:".$max_ex_time ;
		echo "<br>";
		echo "服务器当前时间:".date("Y-m-d H:i:s") ;
		echo "<br>";
		
		Yii::app()->end();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}