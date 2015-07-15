<?php
class WmenuController extends SystemController
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionList()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->menuLlist($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionEdit($id)
	{
		$wmenu = new Wmenu();
		$wmenuMsg = $wmenu->getMenuById($id);
		$pmenu = $wmenu->getPmenu();
		$this->render('edit',array('wmenu'=>$wmenuMsg,'pmenu'=>$pmenu));
	}

	public function actionMenuupdate()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->menuUpdate($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionAdd()
	{
		$wmenu = new Wmenu();
		$pmenu = $wmenu->getPmenu();
		$this->render('add',array('pmenu'=>$pmenu));
	}

	public function actionMenudelete()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->menuDelete($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionMenuadd()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->menuAdd($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionCreate()
	{
		$wmenu = new Wmenu();
		$menu = $wmenu->getMenuAll();
		$this->render('create',array('menu'=>$menu));
	}

	public function actionCreateok()
	{
		$wmenu = new Wmenu();
		$menu = $wmenu->getMenuAll();

		$weixin = new Weixin();
		echo $weixin->createMenu($menu);
		Yii::app()->end();
	}

	public function actionEvent()
	{
		$this->render('event');
	}

	public function actionEventlist()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->eventList($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionEditevent($id)
	{
		$wmenu = new Wmenu();
		$wmenuMsg = $wmenu->getEventById($id);
		$pmenu = $wmenu->getPmenuForChild();
		$this->render('editevent',array('wmenu'=>$wmenuMsg,'pmenu'=>$pmenu));
	}

	public function actionEventupdate()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->eventUpdate($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionAddevent()
	{
		$wmenu = new Wmenu();
		$pmenu = $wmenu->getPmenuForChild();
		$this->render('addevent',array('pmenu'=>$pmenu));
	}

	public function actionEventadd()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->eventAdd($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionEventdelete()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->eventDelete($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionQrcode()
	{
		$this->render('qrcode');
	}

	public function actionGetQrcode()
	{
		$weixin = new Weixin();
		$sceneId=$_POST['sceneId'];
		$rs=$weixin->getqrcode($sceneId);
		print_r($rs);
	}

	public function actionQrcodelist()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->qrcodeList($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionEditQrcode($id)
	{
		$wmenu = new Wmenu();
		$wmenuMsg = $wmenu->getEventById($id);
		$pmenu = $wmenu->getPmenuForChild();
		$this->render('editqrcode',array('wmenu'=>$wmenuMsg,'pmenu'=>$pmenu));
	}

	public function actionQrcodeupdate()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->qrcodeUpdate($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionAddQrcode()
	{
		$this->render('addqrcode');
	}

	public function actionQrcodeadd()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->qrcodeAdd($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionQrcodedelete()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->qrcodeDelete($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionStore()
	{
		$this->render('store');
	}

	public function actionStorelist()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->storeList($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionEditStore($id)
	{
		$wmenu = new Wmenu();
		$storeMsg = $wmenu->getStoreById($id);
		$this->render('editstore',array('storeMsg'=>$storeMsg));
	}

	public function actionStoreupdate()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->storeUpdate($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionAddStore()
	{
		$this->render('addstore');
	}

	public function actionStoreadd()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->storeAdd($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionStoredelete()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->storeDelete($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionCreatePage()
	{
		$this->render("createpage");
	}

	public function actionPagelist()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$pagelist = $wmenu->pageList($_POST);
			echo $pagelist;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionAddPage()
	{
		$this->render("addpage");
	}
	public function actionPageAdd()
	{
		$wmenu = new Wmenu();
		$pagelist = $wmenu->pageAdd($_POST);
		echo $pagelist;
		Yii::app()->end();
	}
	public function actionEditPage($id)
	{
		$wmenu = new Wmenu();
		$rs = $wmenu->getPageById($id);

		$this->render("editpage",array('subjectMsg'=>$rs));
	}

	public function actionUpdatepage()
	{
		$wmenu = new Wmenu();
		$pagelist = $wmenu->pageUpdate($_POST);
		echo $pagelist;
		Yii::app()->end();
	}
	public function actionText()
	{
		$this->render('text');
	}
	public function actionTextlist()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->textList($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionTextedit($id)
	{
		$wmenu = new Wmenu();
		$wmenuMsg = $wmenu->getTextById($id);
		$this->render('edittext',array('wmenu'=>$wmenuMsg));
	}

	public function actionTextupdate()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->textUpdate($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionTextadd()
	{
		
		$this->render('addtext');
	}

	public function actionAddtext()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->textAdd($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionTextdelete()
	{
		if(isset($_POST)){
			$wmenu = new Wmenu();
			$wmenuJson = $wmenu->textDelete($_POST);
			echo $wmenuJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}
}