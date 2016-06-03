<?php

class ApiController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	private $_alpha=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

	public function actionBrand($store, $categorie = '')
	{
		switch ($store) {
			case '1':
				$storename = 'PRINTEMPS HAUSSMANN 奥斯曼旗舰店';
				break;

			case '2':
				$storename = 'PRINTEMPS DU LOUVRE 卢浮春天百货';
				break;

			default:
				$storename = 'PRINTEMPS HAUSSMANN 奥斯曼旗舰店';
				break;
		}
		switch ($categorie) {
			case '1':
				$categorie = 'ACCESSORIES & JEWELLERY 时尚配饰与奢华精品';
				$sql = "select * from same_brand where categorie='".$categorie."' order by brandtitle,brand";
				break;

			case '2':
				$storename = 'PRINTEMPS HAUSSMANN 奥斯曼旗舰店';
				$categorie = 'BEAUTY 美容护肤';
				$sql = "select * from same_brand where categorie='".$categorie."' and store='".$storename."'  order by brandtitle,brand";
				break;

			case '3':
				$categorie = 'WOMEN 女士';
				$sql = "select * from same_brand where categorie='".$categorie."' order by brandtitle,brand";
				break;

			case '4':
				$categorie = 'MEN 男士';
				$sql = "select * from same_brand where categorie='".$categorie."' order by brandtitle,brand";
				break;

			case '5':
				$categorie = 'CHILDREN & HOME 儿童家居';
				$sql = "select * from same_brand where categorie='".$categorie."' order by brandtitle,brand";
				break;

			case '6':
				$categorie = 'ACCESSORIES 时尚配饰';
				$sql = "select * from same_brand where categorie='".$categorie."' order by brandtitle,brand";
				break;

			case '7':
			    $storename = 'PRINTEMPS DU LOUVRE 卢浮春天百货';
				$categorie = 'BEAUTY 美容护肤';
				$sql = "select * from same_brand where categorie='".$categorie."' and store='".$storename."'  order by brandtitle,brand";
				break;

			case '8':
				$categorie = 'WATCHES & JEWELLERY 配饰与奢华精品';
				$sql = "select * from same_brand where categorie='".$categorie."' order by brandtitle,brand";
				break;

			default:
				$sql = "select * from same_brand where store='".$storename."' order by brandtitle,brand";
				break;
		}

		$alpha=array();
		$other=array();
		$rs = Yii::app()->db->createCommand($sql)->select()->queryAll();
		for($i=0;$i<count($rs);$i++){
			if(in_array($rs[$i]['brandtitle'], $this->_alpha))
				$alpha[$rs[$i]['brandtitle']][]=$rs[$i];
			else
				$other[]=$rs[$i];
		}
		if(count($other)>=1)
			$alpha['others']=$other;
		echo json_encode($alpha);
		Yii::app()->end();
	}
	public function actionTest(){
		$_SESSION['openid']=1;
	}
	public function actionCheck(){
		if (!isset($_SESSION['openid'])) {
			echo json_encode(array('code' => '0', 'msg' => '未登录'));
			Yii::app()->end();
		}
		$tag = false;
		$cardnum = isset($_POST['cardnum']) ? $_POST['cardnum'] : $tag = true;
		$name = isset($_POST['name']) ? $_POST['name'] : $tag = true;
		if ($tag) {
			echo json_encode(array('code' => '2', 'msg' => '参数错误'));
			Yii::app()->end();
		}
		$sql = 'SELECT * FROM same_login WHERE cardno=:cardno and firstname=:firstname';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':cardno',$cardnum,PDO::PARAM_STR);
		$command->bindParam(':firstname',$name,PDO::PARAM_STR);
		$rs = $command->queryRow();
		if (!$rs) {
			echo json_encode(array('code' => '3', 'msg' => '验证失败'));
			Yii::app()->end();
		}
		if ($rs['openid']!='') {
			echo json_encode(array('code' => '4', 'msg' => '该导游号已经绑定过了'));
			Yii::app()->end();
		}
		$sql ="UPDATE same_login set openid=:openid where id=:id";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':openid',$_SESSION['openid'],PDO::PARAM_STR);
		$command->bindParam(':id',$rs['id'],PDO::PARAM_STR);
		$command->execute();
		echo json_encode(array('code' => '1', 'msg' => '验证通过'));
		Yii::app()->end();
	}

	public function actionSubmit(){
		$tag = false;
		$sex = isset($_POST['sex']) ? $_POST['sex'] : $tag = true;
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : $tag = true;
		$secondname = isset($_POST['secondname']) ? $_POST['secondname'] : $tag = true;
		$ddata = isset($_POST['ddata']) ? $_POST['ddata'] : $tag = true;
		$dtime = isset($_POST['dtime']) ? $_POST['dtime'] : $tag = true;
		$contacttype = isset($_POST['contacttype']) ? $_POST['contacttype'] : $tag = true;
		$contact = isset($_POST['contact']) ? $_POST['contact'] : $tag = true;
		$product = isset($_POST['product']) ? $_POST['product'] : $tag = true;
		$brandname = isset($_POST['brandname']) ? $_POST['brandname'] : $tag = true;
		if ($tag) {
			echo json_encode(array('code' => '2', 'msg' => '验证失败'));
			Yii::app()->end();
		}
		$sql = "insert into same_order set sex=:sex,firstname=:firstname,secondname=:secondname,ddata=:ddata,dtime=:dtime,contacttype=:contacttype,contact=:contact,product=:product,brandname=:brandname";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':sex',$sex,PDO::PARAM_STR);
		$command->bindParam(':firstname',$firstname,PDO::PARAM_STR);
		$command->bindParam(':secondname',$secondname,PDO::PARAM_STR);
		$command->bindParam(':ddata',$ddata,PDO::PARAM_STR);
		$command->bindParam(':dtime',$dtime,PDO::PARAM_STR);
		$command->bindParam(':contacttype',$contacttype,PDO::PARAM_STR);
		$command->bindParam(':contact',$contact,PDO::PARAM_STR);
		$command->bindParam(':product',$product,PDO::PARAM_STR);
		$command->bindParam(':brandname',$brandname,PDO::PARAM_STR);
		$command->execute();
		$a = new swiftmail(); //send enmail
		$data = array(
			'sex' => $sex,
			'firstname' => $firstname,
			'secondname' => $secondname,
			'ddata' => $ddata,
			'dtime' => $dtime,
			'contacttype' => $contacttype,
			'contact' => $contact,
			'product' => $product,
			'brandname' => $brandname,
		);
		$a->pushmail($data);
		$a->send();//send enmail end
		echo json_encode(array('code' => '1', 'msg' => '提交成功'));
		Yii::app()->end();
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

	public function actionStore($id)
	{
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

	public function actionName()
	{
		//导入
		$csv = 'upload/mingdan.csv';
		$handle = fopen($csv,"r");
		$total=0;
		$ok=0;
		while(!feof($handle)){
			$line = fgets($handle,4096);
			$line = str_replace(' ','',$line);
			$line = str_replace('\r','',$line);
			$line = str_replace('\r\n','',$line);
			$lineAry = explode(",", $line);
			if(count($lineAry)!=2){
				continue;
			}
			$total++;
			if($total==1){
				continue;
			}
			$sql = "INSERT INTO same_login SET cardno=:cardno, firstname=:firstname";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(':cardno',preg_replace("/[^a-zA-Z0-9_.-]+/","", $lineAry[0]),PDO::PARAM_STR);
			$command->bindParam(':firstname',preg_replace("/[^a-zA-Z0-9_.-]+/","", $lineAry[1]),PDO::PARAM_STR);
			// $command->bindParam(':secondname',preg_replace("/[^a-zA-Z0-9_.-]+/","", $lineAry[2]),PDO::PARAM_STR);
			// $command->bindParam(':bak',preg_replace("/[^a-zA-Z0-9_.-]+/","", $lineAry[3]),PDO::PARAM_STR);
			$command->execute();
			$ok++;
		}
		fclose($handle);
		echo $total.'|'.$ok;
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
