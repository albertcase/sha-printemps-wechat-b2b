<?php

class SysuserController extends SystemController
{

	public function actionIndex()
	{
		$department = new Department() ;
		$departmentJson = $department->listForCombotree();
		$permissions = new Permissions();
		$permissionsJson = $permissions->listForcombobox();
		$this->render('index',array('department'=>$departmentJson,'permissions'=>$permissionsJson));
	}

	public function actionList()
	{
		if(isset($_POST)){
			$sysuser = new Sysuser();
			$sysuserJson = $sysuser->listForEdit($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
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
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
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
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}
}