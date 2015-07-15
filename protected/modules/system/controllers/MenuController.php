<?php

class MenuController extends SystemController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionList()
	{
		$menu = new Menu();
		echo $menu->menuList();
		Yii::app()->end();
	}
	
	public function actionListForView()
	{
		if(isset($_POST)){
			$menu = new Menu();
			echo $menu->menuListForEdit($_POST);
			Yii::app()->end();
		}
		Yii::app()->end();
	}

	public function actionAdd()
	{
		if(isset($_POST)){
			$menu = new Menu();
			echo $menu->menuadd($_POST);
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
		
	}

	public function actionUpdate()
	{
		if(isset($_POST)){
			$menu = new Menu();
			echo $menu->menuupdate($_POST);
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
		
	}
	
	public function actionDelete()
	{
		if(isset($_POST)){
			$menu = new Menu();
			echo $menu->menudelete($_POST);
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
		
	}
}