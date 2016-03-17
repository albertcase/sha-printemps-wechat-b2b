<?php
Yii::import('ext.json.JSON',true);
class PermissionsController extends SystemController
{

	public function actionIndex()
	{
		
		$this->render('index');
	}

	public function actionPadd()
	{
		
		$this->render('padd');
	}

	public function actionPedit($id)
	{
		if(isset($id)){
			$permissions = new Permissions();
			$rs = $permissions->pedit($id);
			$rs = json_decode($rs,true);
			if($rs['code']!=1){
				echo $rs['msg'];
				Yii::app()->end();
			}

			$this->render('pedit',array('permissions'=>$rs['msg']));
		}
		
	}

	public function actionList()
	{
		if(isset($_POST)){
			$permissions = new Permissions();
			echo $permissions->plist($_POST);
			Yii::app()->end();
		}
		Yii::app()->end();
	}

	public function actionAdd()
	{
		
		if(isset($_POST)){
			$permissions = new Permissions();
			echo $permissions->add($_POST);
			Yii::app()->end();
		}
		$json = new Services_JSON();
			echo $json->encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionUpdate()
	{
		if(isset($_POST)){
			$permissions = new Permissions();
			echo $permissions->update($_POST);
			Yii::app()->end();
		}
		$json = new Services_JSON();
			echo $json->encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}
}