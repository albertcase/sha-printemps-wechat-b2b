<?php

class SiteController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionOutlet(){
		$this->render('outlet');
	}

	public function actionFlagship(){
		$this->render('flagship');
	}

	public function actionBrand(){
		$this->render('brand');
	}

	public function actionPersonal(){
		$this->render('personal');
	}

	public function actionCongratulation(){
		$this->render('congratulation');
	}

	public function actionLogin(){
		//$_SESSION['openid'] = '123';  //测试用
		if (!isset($_SESSION['openid'])) {
			Header("Location:/weixin/oauth2?callback=/site/login");
			Yii::app()->end();
		}
		$sql = 'SELECT id FROM same_login WHERE openid=:openid';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':openid',$_SESSION['openid'],PDO::PARAM_STR);
		$rs = $command->queryScalar();
		//$rs = 1;   //测试用
		if (!$rs) {
			$rs = 0;
		}
		$this->render('login', array('band' => $rs));
	}

	public function actionSitmap(){
    		$this->render('sitmap');
    }

    public function actionAbout(){
    		$this->render('about');
    }

	public function actionBeauty(){
    		$this->render('pushArticles/beauty');
    }

    public function actionOtherservice(){
        		$this->render('pushArticles/otherservice');
    }

    public function actionRestaurant(){
        		$this->render('pushArticles/restaurant');
    }

    public function actionServicepremium(){
        		$this->render('pushArticles/servicepremium');
    }

    public function actionTaxrefund(){
        		$this->render('pushArticles/taxrefund');
    }

    public function actionTaxrefund2(){
        		$this->render('pushArticles/store2/taxrefund');
    }

    public function actionInstoreservice(){
        		$this->render('pushArticles/store2/instoreservice');
    }

    public function actionQuestion(){
                $this->render('pushArticles/question');
    }

    public function actionCba(){
                $this->render('pushArticles/cba');
    }


	public function actionStore($id){
		$sql = "select * from same_store where id = ".intval($id);
		$store = Yii::app()->db->createCommand($sql)->queryRow();
		$this->render('store', array('store' => $store));
	}

	public function actionImport()
	{
		//导入
		$csv = 'upload/ph.csv';
		$handle = fopen($csv,"r");
		$total=0;
		$ok=0;
		while(!feof($handle)){
			$line = fgets($handle,4096);
			$lineAry = explode("|", $line);
			if(count($lineAry)!=6){
				continue;
			}
			$total++;
			if($total==1){
				continue;
			}
			$floor = substr(trim($lineAry[5]),0,1);
			$btitle = substr(trim($lineAry[1]),0,1);
			$sql = "INSERT INTO same_brand SET store=:store, brand=:brand, building=:building, categorie=:categorie,description=:description,floor=:floors,brandtitle=:brandtitle";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(':store',$lineAry[0],PDO::PARAM_STR);
			$command->bindParam(':brand',$lineAry[1],PDO::PARAM_STR);
			$command->bindParam(':building',$lineAry[2],PDO::PARAM_STR);
			$command->bindParam(':categorie',$lineAry[3],PDO::PARAM_STR);
			$command->bindParam(':description',$lineAry[4],PDO::PARAM_STR);
			$command->bindParam(':floors',$floor,PDO::PARAM_INT);
			$command->bindParam(':brandtitle',$btitle,PDO::PARAM_STR);
			$command->execute();
			$ok++;
		}
		fclose($handle);
		$csv = 'upload/louvre.csv';
		$handle = fopen($csv,"r");
		$total=0;
		$ok=0;
		while(!feof($handle)){
			$line = fgets($handle,4096);
			$lineAry = explode("|", $line);
			if(count($lineAry)!=5){
				continue;
			}
			$total++;
			if($total==1){
				continue;
			}
			$floor = substr(trim($lineAry[4]),0,1);
			$btitle = substr(trim($lineAry[1]),0,1);
			$sql = "INSERT INTO same_brand SET store=:store, brand=:brand,categorie=:categorie,description=:description,floor=:floors,brandtitle=:brandtitle";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(':store',$lineAry[0],PDO::PARAM_STR);
			$command->bindParam(':brand',$lineAry[1],PDO::PARAM_STR);
			$command->bindParam(':categorie',$lineAry[2],PDO::PARAM_STR);
			$command->bindParam(':description',$lineAry[3],PDO::PARAM_STR);
			$command->bindParam(':floors',$floor,PDO::PARAM_INT);
			$command->bindParam(':brandtitle',$btitle,PDO::PARAM_STR);
			$command->execute();
			$ok++;
		}
		fclose($handle);
		Yii::app()->end();
	}

	public function actionGuest(){
		$session = new Session();
		if($session->has('loguser')){
			$this->redirect('/site/list');
			Yii::app()->end();
		}
		$xss = new forbidXss();
		$this->renderPartial('guest',array('xsscode' => $xss->addXsscode()));
	}

	public function actionList()
	{
		$session = new Session();
		if($session->has('loguser')){
			$this->renderPartial('list');
			Yii::app()->end();
		}
		$this->redirect('/site/guest');
	}

	public function actionApi($action ,$xsscode = null){
	$guestApi = new guestApi();
	$forbitlist = array();
	if(in_array($action,$forbitlist)){
		$forbidXss = new forbidXss($xsscode);
		$x = $forbidXss->subCode();
		if($x != '51'){
			echo json_encode($x);
			Yii::app()->end();
		}
	}
	echo json_encode($guestApi->$action());
	Yii::app()->end();
}

public function actionAdminapi($action){
	$guestadmin = new guestadmin();
	$session = new Session();
	if($session->has('loguser')){
		echo json_encode($guestadmin->$action());
		Yii::app()->end();
	}
	echo json_encode('4');/*not login*/
	Yii::app()->end();
}


public function actionAapi(){
	$token = new Weixin();
	print_r($token->getKflist());
	echo json_encode('4444');/*not login*/
	Yii::app()->end();
}

public function actionLogout(){
	$session = new Session();
	$session->clean();
	echo json_encode('11');/*login out*/
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
}
