<?php
Yii::import('ext.json.JSON',true);
class SysuserController extends SystemController
{

	public function actionIndex()
	{
		$permissions = new Permissions();
		$permissionsJson = $permissions->listForcombobox();
		$this->render('index',array('permissions'=>$permissionsJson));
	}

	public function actionList()
	{
		if(isset($_POST)){
			$sysuser = new Sysuser();
			$sysuserJson = $sysuser->listForEdit($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		$json = new Services_JSON();
			echo $json->encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionAdd()
	{
		if(isset($_POST)){
			$sysuser = new Sysuser();
			$sysuserJson = $sysuser->add($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		$json = new Services_JSON();
			echo $json->encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionUpdate()
	{
		if(isset($_POST)){
			$sysuser = new Sysuser();
			$sysuserJson = $sysuser->update($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		$json = new Services_JSON();
			echo $json->encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}
}