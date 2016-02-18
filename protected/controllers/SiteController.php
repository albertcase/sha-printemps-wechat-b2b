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
		$this->render('login');
	}

	public function actionStore($id, $categorie ='')
	{
		$sql = "select * from same_store where id = ".intval($id);
		$store = Yii::app()->db->createCommand($sql)->queryRow();
		switch ($categorie) {
			case '1':
				$categorie = 'ACCESSORIES & JEWELLERY 时尚配饰与奢华精品';
				break;

			case '2':
				$categorie = 'BEAUTY 美容护肤';
				break;

			case '3':
				$categorie = 'WOMEN 女士';
				break;

			case '4':
				$categorie = 'MEN 男士';
				break;

			case '5':
				$categorie = 'CHILDREN 儿童时尚';
				break;

			case '6':
				$categorie = 'ACCESSORIES 时尚配饰';
				break;

			case '7':
				$categorie = 'BEAUTY 美容护肤';
				break;

			case '8':
				$categorie = 'WATCHES & JEWELLERY 配饰与奢华精品';
				break;

			default:
				$categorie = '';
				break;
		}
		$this->render('store', array('store' => $store, 'categorie' => $categorie));
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