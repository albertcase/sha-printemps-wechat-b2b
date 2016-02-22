<?php

class WeixinController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public $layout='//layouts/managementnone';
	public function actionIndex()
	{
		$wechatObj = new Weixin();
		//echo $wechatObj->valid($_GET["echostr"]);
		//Yii::app()->end();
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		echo $wechatObj->responseMsg($postStr);
		Yii::app()->end();
	}
	public function actionTest()
	{
		$postStr=$_GET['data'];
		$wechatObj = new Weixin();
		echo $wechatObj->responseMsg($postStr);
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


	public function actionCreateMenu()
	{
		$wechatObj = new Weixin();
		echo $wechatObj->createMenu();
		Yii::app()->end();
	}

	public function actionPage($id){
		$wechatObj = new Weixin();
		$rs=$wechatObj->getpagebyid($id);
		$this->render('page', array('rs'=>$rs));
	}

	public function actionCreateMedia(){
		 $pic = YiiBase::getPathOfAlias('webroot')."/images/arrow.png";
		 $data=array("media"=>'@'.$pic);
		 $wechatObj = new Weixin();
		 $access_token = $wechatObj->getAccessToken();
		 $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type=TYPE';
		 $urlPost=http_build_query($data);
		echo $this->httpRequest($url,'post',$data);
		
	}

	private function httpRequest($url,$method='get',$params=array()){
		if(trim($url)==''||!in_array($method,array('get','post'))||!is_array($params)){
			return false;
		}
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_HEADER,0 ) ;
		switch($method){
			case 'get':
				$str='?';
				foreach($params as $k=>$v){
					$str.=$k.'='.$v.'&';
				}
				$str=substr($str,0,-1);
				$url.=$str;//$url=$url.$str;
				curl_setopt($curl,CURLOPT_URL,$url);
			break;
			case 'post':
				curl_setopt($curl,CURLOPT_URL,$url);
				curl_setopt($curl,CURLOPT_POST,1 );
				curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
			break;
			default:
				$result='';
			break;
		}
		$result=curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	public function actionOauth($callback)
	{
		$_SESSION['callback_url']=$callback;
		$wechatObj = new Weixin();
		$url=$wechatObj->getOauth();
		Header('Location:'.$url);
		Yii::app()->end();
	}

	public function actionOauth2($callback)
	{
		$_SESSION['callback_url']=$callback;
		$wechatObj = new Weixin();
		$url=$wechatObj->getOauth2();
		Header('Location:'.$url);
		Yii::app()->end();
	}
	public function actionCallback(){
		$code=isset($_GET['code'])&&$_GET['code']!='authdeny'?$_GET['code']:"";
		if(!$code){
			unset($_SESSION['weixin_info_id']);
			Header('Location:'.$_SESSION['callback_url']);
			Yii::app()->end();
		}
		$wechatObj = new Weixin();
		$rs=$wechatObj->getOauthAccessToken($code);
		if(isset($rs['access_token'])){
			$_SESSION['access_token']=$rs['access_token'];
			$_SESSION['openid']=$rs['openid'];	
			$sql="select * from same_weixin_info where openid='".$_SESSION['openid']."'";
			$ins=Yii::app()->db->createCommand($sql)->select()->queryRow();
			if(!isset($ins['id'])){
				$info=file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$_SESSION['access_token']."&openid=".$_SESSION['openid']."&lang=zh_CN");
				$info=json_decode($info,true);
				$sql="insert into same_weixin_info set openid='".$_SESSION['openid']."',nickname='".$info['nickname']."',sex='".$info['sex']."',province='".$info['province']."',city='".$info['city']."',country='".$info['country']."',headimgurl='".$info['headimgurl']."'";
				Yii::app()->db->createCommand($sql)->execute();
				$_SESSION['weixin_info_id']=Yii::app()->db->lastInsertID;
			}else{
				$_SESSION['weixin_info_id']=$ins['id'];
			}
			Header('Location:'.$_SESSION['callback_url']);
			Yii::app()->end();
		}
	}

	public function actionCallback2(){
		$code=isset($_GET['code'])&&$_GET['code']!='authdeny'?$_GET['code']:"";
		if(!$code){
			Header('Location:'.$_SESSION['callback_url']);
			Yii::app()->end();
		}
		$wechatObj = new Weixin();
		$rs=$wechatObj->getOauthAccessToken($code);
		if(isset($rs['openid'])){
			$_SESSION['openid']=$rs['openid'];	
			Header('Location:'.$_SESSION['callback_url']);
			Yii::app()->end();
		}
	}

}