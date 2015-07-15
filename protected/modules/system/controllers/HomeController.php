<?php
class HomeController extends SystemController
{
	public function actionFoot()
	{
		$this->render('foot');
	}

	public function actionFootlist()
	{
		if(isset($_POST)){
			$sysuser = new Foot();
			$sysuserJson = $sysuser->footlist($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionFootadd()
	{
		if(isset($_POST)){
			$sysuser = new Foot();
			$sysuserJson = $sysuser->footadd($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionFootupdate()
	{
		if(isset($_POST)){
			$sysuser = new Foot();
			$sysuserJson = $sysuser->footupdate($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionActive()
	{
		$this->render('active');
	}

	public function actionActivelist()
	{
		if(isset($_POST)){
			$home = new Home();
			$activelistJson = $home->activelist($_POST);
			echo $activelistJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionActiveAdd()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->activeadd($_POST);
			echo $rs;
		}else{
			$this->render('activeAdd');
		}
	}

	public function actionActiveEdit($id)
	{
		$home = new Home();
		$activeMsg = $home->getActiveById($id);
		$this->render('activeEdit',array('activeMsg'=>$activeMsg));
	}

	
	public function actionActiveUpdate()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->activeupdate($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}

	public function actionActiveDelete()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->activeDelete($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}

	public function actionMassage()
	{
		$this->render('massage');
	}
	
	public function actionBackground()
	{
		$this->render('background');
	}

	public function actionBackgroundlist()
	{
		if(isset($_POST)){
			$sysuser = new Home();
			$sysuserJson = $sysuser->getbackground();
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}
	
	public function actionEditbackground($id)
	{
		
		$sysuser = new Home();
		$subjectMsg=$sysuser->getbackgroundbyid($id);
		$this->render('editbackground',array('subjectMsg'=>$subjectMsg));
	}
	
	public function actionBackgroundUpdate()
	{
		
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->backgroundupdate($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}

	public function actionMassagelist()
	{
		if(isset($_POST)){
			$home = new Home();
			$activelistJson = $home->massagelist($_POST);
			echo $activelistJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionMassageEdit($id)
	{
		$home = new Home();
		$massageMsg = $home->getMassageById($id);
		$this->render('massageEdit',array('massageMsg'=>$massageMsg));
	}

	public function actionMassageUpdate()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->massageupdate($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}

	public function actionMassageAdd()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->massageadd($_POST);
			echo $rs;
		}else{
			$this->render('massageAdd');
		}
	}

	public function actionMassageDelete()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Home();
			$rs = $home->massageDelete($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}

	public function actionUpload()
	{
		$uploader = new QQFileUploader();

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array("jpeg","jpg","png","gif");

		// Specify max file size in bytes.
		$uploader->sizeLimit = 2 * 1024 * 1024;

		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile';

		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uploader->chunksFolder = 'chunks';

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$root = YiiBase::getPathOfAlias('webroot');
        $folder = '/upload/img/'.date("Ymd").'/';
		if(!is_dir($root.$folder)){        	
			if(!mkdir($root.$folder, 0777, true))	
			{	
				throw new Exception('创造文件夹失败...');
			}
			chmod($root.$folder,0777);
		}
		$result = $uploader->handleUpload($root.$folder, date('His').'-'.rand(100, 999));

		// To save the upload with a specified name, set the second parameter.
		// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());

		// To return a name used for uploaded file you can use the following line.
		$result['uploadName'] = $folder.$uploader->getUploadName();
		//echo $result['uploadName'];die;
		

		header("Content-Type: text/plain");
		echo json_encode($result);
	}

	public function actionKv()
	{
		
		$franchise = new Franchise();
		$cityList = $franchise->cityListForcombobox();
		$this->render('kv',array('cityList'=>$cityList));
	}

	public function actionAddkv()
	{
		
		$franchise = new Franchise();
		$cityList = $franchise->cityListForcombobox();
		$sysuser = new Kv();
		$typeList = $sysuser->typeListForcombobox();
		$this->render('addkv',array('cityList'=>$cityList,'typeList'=>$typeList));
	}

	public function actionEditkv($id)
	{
		
		$franchise = new Franchise();
		$cityList = $franchise->cityListForcombobox();
		$sysuser = new Kv();
		
		$typeList = $sysuser->typeListForcombobox();
			
		$subjectMsg=$sysuser->getkv($id);
		$this->render('editkv',array('cityList'=>$cityList,'typeList'=>$typeList,'subjectMsg'=>$subjectMsg));
	}

	public function actionKvlist()
	{
		if(isset($_POST)){
			$sysuser = new Kv();
			$sysuserJson = $sysuser->kvlist($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionKvadd()
	{
		if(isset($_POST)){
			$sysuser = new Kv();
			$sysuserJson = $sysuser->kvadd($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionKvupdate()
	{
		if(isset($_POST)){
			$sysuser = new Kv();
			$sysuserJson = $sysuser->kvupdate($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionKvtype()
	{
		
		$franchise = new Franchise();
		$cityList = $franchise->cityListForcombobox();
		$this->render('kvtype',array('cityList'=>$cityList));
	}
	
	public function actionAddkvtype()
	{
		
		$franchise = new Franchise();
		$cityList = $franchise->cityListForcombobox();
		$sysuser = new Kv();
		$typeList = $sysuser->typeListForcombobox();
		$this->render('addkvtype');
	}

	public function actionEditkvtype($id)
	{
		$sysuser = new Kv();
		$subjectMsg=$sysuser->getkvtype($id);
		$this->render('editkvtype',array('subjectMsg'=>$subjectMsg));
	}

	public function actionKvtypelist()
	{
		if(isset($_POST)){
			$sysuser = new Kv();
			$sysuserJson = $sysuser->kvtypelist($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionKvtypeadd()
	{
		if(isset($_POST)){
			$sysuser = new Kv();
			$sysuserJson = $sysuser->kvtypeadd($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionKvtypeupdate()
	{
		if(isset($_POST)){
			$sysuser = new Kv();
			$sysuserJson = $sysuser->kvtypeupdate($_POST);
			echo $sysuserJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}

	public function actionKvDelete()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Kv();
			$rs = $home->kvDelete($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}

	public function actionKvtypeDelete()
	{
		if(isset($_POST) && !empty($_POST)){
			$home = new Kv();
			$rs = $home->kvtypeDelete($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}


	public function actionUploadMp4()
	{
		$uploader = new QQFileUploader();

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array("mp4");

		// Specify max file size in bytes.
		$uploader->sizeLimit = 2 * 1024 * 1024;

		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile';

		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uploader->chunksFolder = 'chunks';

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$root = YiiBase::getPathOfAlias('webroot');
        $folder = '/upload/temp/mp4/'.date("Ymd").'/';
		if(!is_dir($root.$folder)){        	
			if(!mkdir($root.$folder, 0777, true))	
			{	
				throw new Exception('创造文件夹失败...');
			}
			chmod($root.$folder,0777);
		}
		$result = $uploader->handleUpload($root.$folder, date('His').'-'.rand(100, 999));

		// To save the upload with a specified name, set the second parameter.
		// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());

		// To return a name used for uploaded file you can use the following line.
		$result['uploadName'] = $folder.$uploader->getUploadName();

		header("Content-Type: text/plain");
		echo json_encode($result);
	}

	public function actionUploadFlv()
	{
		$uploader = new QQFileUploader();

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array("flv");

		// Specify max file size in bytes.
		$uploader->sizeLimit = 2 * 1024 * 1024;

		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile';

		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uploader->chunksFolder = 'chunks';

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$root = YiiBase::getPathOfAlias('webroot');
        $folder = '/upload/temp/flv/'.date("Ymd").'/';
		if(!is_dir($root.$folder)){        	
			if(!mkdir($root.$folder, 0777, true))	
			{	
				throw new Exception('创造文件夹失败...');
			}
			chmod($root.$folder,0777);
		}
		$result = $uploader->handleUpload($root.$folder, date('His').'-'.rand(100, 999));

		// To save the upload with a specified name, set the second parameter.
		// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());

		// To return a name used for uploaded file you can use the following line.
		$result['uploadName'] = $folder.$uploader->getUploadName();

		header("Content-Type: text/plain");
		echo json_encode($result);
	}

	public function actionUploadCsv()
	{
		$uploader = new QQFileUploader();

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array("csv");

		// Specify max file size in bytes.
		$uploader->sizeLimit = 2 * 1024 * 1024;

		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile';

		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uploader->chunksFolder = 'chunks';

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$root = YiiBase::getPathOfAlias('webroot');
        $folder = '/upload/temp/csv/'.date("Ymd").'/';
		if(!is_dir($root.$folder)){        	
			if(!mkdir($root.$folder, 0777, true))	
			{	
				throw new Exception('创造文件夹失败...');
			}
			chmod($root.$folder,0777);
		}
		$result = $uploader->handleUpload($root.$folder, date('His').'-'.rand(100, 999));

		// To save the upload with a specified name, set the second parameter.
		// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());

		// To return a name used for uploaded file you can use the following line.
		$result['uploadName'] = $folder.$uploader->getUploadName();

		header("Content-Type: text/plain");
		echo json_encode($result);
	}

	public function actionUploadZip()
	{
		$uploader = new QQFileUploader();

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array("zip","rar");

		// Specify max file size in bytes.
		$uploader->sizeLimit = 2 * 1024 * 1024;

		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile';

		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uploader->chunksFolder = 'chunks';

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$root = YiiBase::getPathOfAlias('webroot');
        $folder = '/upload/temp/zip/'.date("Ymd").'/';
		if(!is_dir($root.$folder)){        	
			if(!mkdir($root.$folder, 0777, true))	
			{	
				throw new Exception('创造文件夹失败...');
			}
			chmod($root.$folder,0777);
		}
		$result = $uploader->handleUpload($root.$folder, date('His').'-'.rand(100, 999));

		// To save the upload with a specified name, set the second parameter.
		// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());

		// To return a name used for uploaded file you can use the following line.
		$result['uploadName'] = $folder.$uploader->getUploadName();

		header("Content-Type: text/plain");
		echo json_encode($result);
	}

	public function actionNews()
	{
		$this->render('news');
	}
	
	public function actionNewsList()
	{
		if(isset($_POST)){
			$news = new Home();
			$newsListJson = $news->newsList($_POST);
			echo $newsListJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();

	}

	public function actionNewsEdit($id)
	{
		$news = new Home();
		$newsMsg = $news->getNewsById($id);
		$this->render('newsEdit',array('newsMsg'=>$newsMsg));
	}

	public function actionNewsUpdate()
	{
		if(isset($_POST)){
			$news = new Home();
			$newsJson = $news->newUpdate($_POST);
			echo $newsJson;
			Yii::app()->end();
		}
		echo json_encode(array('code'=>'3','msg'=>'参数错误'));
		Yii::app()->end();
	}
	
	public function actionNewsAdd()
	{
		if(isset($_POST) && !empty($_POST)){
			$news = new Home();
			$rs = $news->newsAdd($_POST);
			echo $rs;
		}else{
			$this->render('newsAdd');
		}
	}

	public function actionNewsDelete()
	{
		if(isset($_POST) && !empty($_POST)){
			$news = new Home();
			$rs = $news->newsDelete($_POST);
			echo $rs;
			Yii::app()->end();
		}else{
			echo json_encode(array('code'=>'3','msg'=>'参数错误'));
			Yii::app()->end();
		}
	}
}