<?php

class Weixin{

	private $_TOKEN = 'printempsb2b';
	private $_appid = 'wx5724db07982c3896';
	private $_secret = 'd9e9ae55f59bb02fa1b71146520cda03';
	private $_eventKey = array('A1','B1','C1','C2','B2','B4','A2');
	private $_db = null;
	private $_fromUsername = null;
	private $_toUsername = null;
	private $_memcache;
	private $_postStr;

	public function __construct()
	{
		$this->_memcache = new memcaches();
		if( $this->_db===null)
			$this->_db = Yii::app()->db;
	}

	public function valid($echoStr)
    {
       if($this->checkSignature()){
        	return $echoStr;

        }
    }

    public function responseMsg($postStr)
    {
		if (!empty($postStr)){
								$this->_postStr = $postStr;
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $this->_fromUsername=$fromUsername = $postObj->FromUserName;
                $this->_toUsername=$toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $msgType = $postObj->MsgType;
                $time = time();
				if($msgType=='text'){
					//return $this->sendService($fromUsername, $toUsername);
                	$this->systemLog($postStr,$fromUsername,$msgType);
                	$sql = "SELECT * FROM same_wmenu_event WHERE keyword=:keyword ORDER BY id DESC";
                	$command = $this->_db->createCommand($sql);
                	$command->bindParam(':keyword',$keyword,PDO::PARAM_STR);
                	$rs = $command->select()->queryAll();
                	if(!$rs){
			        	$sql="SELECT * FROM `same_wmenu_event` WHERE instr( :keyword, keyword ) >0 and mohu=1";
                		$command = $this->_db->createCommand($sql);
                		$command->bindParam(':keyword',$keyword,PDO::PARAM_STR);
                		$rsLike=$command->select()->queryAll();
                		if($rsLike){
                			$rs=$rsLike;
                		}else{
                			return $this->sendMsgtoCustomer($fromUsername, $toUsername);
                		}
                	}
                	if(in_array($rs[0]['content'], $this->_eventKey)){
                		$sql = "SELECT B.* FROM `same_wmenu` A left join same_wmenu_event B ON A.id=B.mid WHERE A.`eventkey`='".$rs[0]['content']."' ORDER BY id DESC";
						$rs = $this->_db->createCommand($sql)->select()->queryAll();
						if($rs[0]['msgtype']=='text'){
	                		return $this->sendMsgForText($fromUsername, $toUsername, $time, "text", $rs[0]['content']);
	                	}else if($rs[0]['msgtype']=='news'){
	                		$data = array();

	                		for($i=0;$i<count($rs);$i++){
	                			if($rs[$i]['msgtype']!='news'){
	                				continue;
	                			}
	                			$data[] = array('title'=>$rs[$i]['title'],'description'=>$rs[$i]['description'],'picUrl'=>$rs[$i]['url']);
	                		}
	                		return $this->sendMsgForNews($fromUsername, $toUsername, $time, $data);
	                	}else{
	                		return $this->sendMsgtoCustomer($fromUsername, $toUsername);
	                	}
                	}
                	if($rs[0]['msgtype']=='text'){
                		$rs[0]['content'] = str_replace("{openid}", $fromUsername, $rs[0]['content']);
                		return $this->sendMsgForText($fromUsername, $toUsername, $time, "text", $rs[0]['content']);
                	}else if($rs[0]['msgtype']=='news'){
                		$data = array();

                		for($i=0;$i<count($rs);$i++){
                			if($rs[$i]['msgtype']!='news'){
                				continue;
                			}
                			$data[] = array('title'=>$rs[$i]['title'],'description'=>$rs[$i]['description'],'picUrl'=>Yii::app()->request->hostInfo.'/'.Yii::app()->request->baseUrl.'/'.$rs[$i]['picUrl'],'url'=>$rs[$i]['url']);
                		}
                		return $this->sendMsgForNews($fromUsername, $toUsername, $time, $data);
                	}

				}else if($msgType=='event'){
					$event = strtolower($postObj->Event);
					$eventKey = $postObj->EventKey;
					if($event=='click'){
						$sql = "SELECT B.* FROM `same_wmenu` A left join same_wmenu_event B ON A.id=B.mid WHERE A.`eventkey`='{$eventKey}' ORDER BY id";
						$rs = $this->_db->createCommand($sql)->select()->queryAll();
						$this->systemLog($postStr,$fromUsername,$msgType,$event,$eventKey);
	                	if($rs[0]['msgtype']=='text'){
	                		return $this->sendMsgForText($fromUsername, $toUsername, $time, "text", $rs[0]['content']);
	                	}else if($rs[0]['msgtype']=='news'){
	                		$data = array();

	                		for($i=0;$i<count($rs);$i++){
	                			if($rs[$i]['msgtype']!='news'){
	                				continue;
	                			}
	                			$data[] = array('title'=>$rs[$i]['title'],'description'=>$rs[$i]['description'],'picUrl'=>Yii::app()->request->hostInfo.'/'.Yii::app()->request->baseUrl.'/'.$rs[$i]['picUrl'],'url'=>$rs[$i]['url']);
	                		}
	                		return $this->sendMsgForNews($fromUsername, $toUsername, $time, $data);
	                	}else if($rs[0]['msgtype'] == 'transfer_customer'){//tranfer customer
								// $mi = mt_rand(0, (count($rs)-1));
								// return $this->transferCustomer($fromUsername, $toUsername ,trim($rs[$mi]['content']));//tranfer customer
								return $this->transferCustomer($fromUsername, $toUsername ,$eventKey); //send to grata
					}
					}else if($event=='subscribe'){
						if($eventKey){
							$ticket=$postObj->Ticket;
						}else{
							$ticket="";
						}
						$this->sceneLog($fromUsername,1,$ticket);
						$this->systemLog($postStr,$fromUsername,'news',$event,$eventKey);
						return $this->sendMsgForSubscribe($fromUsername, $toUsername, $time, "text");
					}else if($event=='view'){
						$this->systemLog($postStr,$fromUsername,$msgType,$event,$eventKey);
						return;
					}else if($event=='location'){
						$this->systemLog($postStr,$fromUsername,$msgType,$event);
						return;
					}else if($event=='scan'){
						$ticket=$postObj->Ticket;
						$this->sceneLog($fromUsername,2,$ticket);
						$this->systemLog($postStr,$fromUsername,$msgType,$event,$eventKey);
						return;
					}
				}else if($msgType=='location'){
					$this->systemLog($postStr,$fromUsername,$msgType);
					//LBS
					$x = $postObj->Location_X;
					$y = $postObj->Location_Y;

					$baidu = file_get_contents("http://api.map.baidu.com/geoconv/v1/?coords={$y},{$x}&from=3&to=5&ak=Z5FOXZbjH3AEIukiiRTtD7Xy");
					$baidu = json_decode($baidu, true);
					$lat = $baidu['result'][0]['x'];
					$lng = $baidu['result'][0]['y'];
					$squares = $this->returnSquarePoint($lng,$lat,100000);
					$latbig = $squares['right-bottom']['lat'] > $squares['left-top']['lat'] ? $squares['right-bottom']['lat'] : $squares['left-top']['lat'];
					$latsmall = $squares['right-bottom']['lat'] > $squares['left-top']['lat'] ? $squares['left-top']['lat'] : $squares['right-bottom']['lat'];
					$lngbig = $squares['left-top']['lng'] > $squares['right-bottom']['lng'] ? $squares['left-top']['lng'] : $squares['right-bottom']['lng'];
					$lngsmall = $squares['left-top']['lng'] > $squares['right-bottom']['lng'] ? $squares['right-bottom']['lng'] : $squares['left-top']['lng'];
					$info_sql = "select * from `same_store` where lat<>0 and (lat between {$latsmall} and {$latbig}) and (lng between {$lngsmall} and {$lngbig})";
					$rs = Yii::app()->db->createCommand($info_sql)->queryAll();
					if(!$rs){
						return $this->sendMsgForText($fromUsername, $toUsername, $time, "text", '很抱歉，您的附近没有门店');
					}
					$datas = array();
					$data = array();
            		for($i=0;$i<count($rs);$i++){
            			$meter = $this->getDistance($lat,$lng,$rs[$i]['lat'],$rs[$i]['lng']);
            			$meters = "(距离约" . $meter ."米)";
            			$datas[$meter] = array('title'=>$rs[$i]['name'].$meters,'description'=>$rs[$i]['name'],'picUrl'=>Yii::app()->request->hostInfo.'/'.Yii::app()->request->baseUrl.'/vstyle/imgs/store/'.$rs[$i]['id'].'.jpg','url'=>Yii::app()->request->hostInfo.'/site/store?id='.$rs[$i]['id']);
            		}
					ksort($datas);
					$i=0;
					foreach($datas as $value){
						$data[$i] = $value;
						$i++;
					}
            		return $this->sendMsgForNews($fromUsername, $toUsername, $time, $data);
				}else if($msgType=='image'){
					$this->systemLog($postStr,$fromUsername,$msgType);
					return;
				}else if($msgType=='voice'){
					$this->systemLog($postStr,$fromUsername,$msgType);
					return;
				}else if($msgType=='video'){
					$this->systemLog($postStr,$fromUsername,$msgType);
					return;
				}else if($msgType=='link'){
					$this->systemLog($postStr,$fromUsername,$msgType);
					return;
				}




        }else {
        	return "";
        	exit;
        }
    }

  private function transferCustomer($fromUsername, $toUsername ,$newkfaccount){
		// if($oldkfaccount = $this->_memcache->getData('oncustomer:'.$fromUsername)){
		// 	$this->closeCustomer($fromUsername);
		// }
		if($this->checkopenid($fromUsername))
			return $this->useCustomer($fromUsername, $toUsername ,$newkfaccount);
		return $this->sendMsgForText($fromUsername, $toUsername, time(), "text", "对不起!你还不是导购无法使用该功能,请点击以下链接登陆\nhttp://printempsb2b.samesamechina.com/site/login");
	}

	private function useCustomer($fromUsername, $toUsername ,$kfaccount){
		$this->_memcache->addData('oncustomer:'.$fromUsername, $kfaccount, '3600');
		// return $this->transferService($fromUsername, $toUsername ,$kfaccount);
		if($kfaccount == 'A3'){
			$feedback = '卢浮春天百货客服为您服务';
		}else{
			$feedback = '奥斯曼旗舰店客服为您服务';
		}
		$this->sendtoGrata();
		return $this->sendMsgForText($fromUsername, $toUsername, time(), "text", $feedback);
	}

	private function sendMsgtoCustomer($fromUsername, $toUsername){
		if($kfaccount = $this->_memcache->getData('oncustomer:'.$fromUsername)){
			$this->_memcache->addData('oncustomer:'.$fromUsername, $kfaccount, '3600');
			// return $this->sendService($fromUsername, $toUsername);
			$this->sendtoGrata();
			return "";
		}
		return $this->sendMsgForText($fromUsername, $toUsername, time(), "text", "如有需要，您可以在服务时间期间，通过《关于我们》联系奥斯曼旗舰店客服或卢浮春天百货客服。");
	}

	public function sendtoGrata(){
		require_once dirname(__FILE__).'/Grata/forwordGrata.php';
		$forwordGrata = new forwordGrata();
		$forwordGrata->addforwardJob($this->_postStr);
		$forwordGrata->runforwardJob();
	}

	private function closeCustomer($fromUsername){
		$kfaccount = $this->_memcache->getData('oncustomer:'.$fromUsername);
		$this->_memcache->delData('oncustomer:'.$fromUsername);
		$access_token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/customservice/kfsession/close?access_token='.$access_token;
		$param = array(
		    'kf_account' => $kfaccount,
		    'openid' => $fromUsername,
		    'text' => '客户已经结束会话'
		);
		$out = $this->post_data($url, $param);
	}

    private function sceneLog($openid,$type,$ticket)
    {
    	try{
	    	$sql = "INSERT INTO scenelog SET openid=:openid,type=:type,ticket=:ticket,timeint=:timeint";
			$command=$this->_db->createCommand($sql);
			$command->bindParam(":openid",$openid,PDO::PARAM_STR);
			$command->bindParam(":type",$type,PDO::PARAM_STR);
			$command->bindParam(":ticket",$ticket,PDO::PARAM_STR);
			$command->bindParam(":timeint",time(),PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			print_r($e);
			return;
		}

    }
    private function systemLog($content,$openid,$msgtype,$event=null,$eventkey=null)
    {
    	try{
	    	$sql = "INSERT INTO same_getlog SET content=:content,openid=:openid,msgtype=:msgtype,event=:event,eventkey=:eventkey";
			$command=$this->_db->createCommand($sql);
			$command->bindParam(":content",$content,PDO::PARAM_STR);
			$command->bindParam(":openid",$openid,PDO::PARAM_STR);
			$command->bindParam(":msgtype",$msgtype,PDO::PARAM_STR);
			$command->bindParam(":event",$event,PDO::PARAM_STR);
			$command->bindParam(":eventkey",$eventkey,PDO::PARAM_STR);
			//$command->bindParam(":timeint",time(),PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			print_r($e);
			return;
		}

    }

    private function sendMsgForText($fromUsername, $toUsername, $time, $msgType, $contentStr)
    {
    	$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";
	    return sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	}

	private function sendMsgForNews($fromUsername, $toUsername, $time, $data)
    {

    	$xmlTpl = '<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>%s</ArticleCount>
					<Articles>';
		try{

			$xml = sprintf($xmlTpl, $fromUsername, $toUsername, $time, 'news', count($data));
		}catch(Exception $e){
			print_r($e);
		}

		for($i=0;$i<count($data);$i++){
			$xmlxmlTpl1 = '<item>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>';
			$xml .= sprintf($xmlxmlTpl1, $data[$i]["title"], $data[$i]["description"], $data[$i]["picUrl"], $data[$i]["url"]);
		}

		$xml .= '</Articles></xml>';

		return $xml;
    }

	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

		$token = $this->_TOKEN;;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	public function getAccessToken()
	{
		$time=file_get_contents("upload/time.txt");
		$access_token=file_get_contents("upload/access_token.txt");
		if (!$time || (time() - $time >= 3600)){
			$rs = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_secret);
			$rs = json_decode($rs,true);
			if(isset($rs['access_token'])){
				$time = time();
				$access_token = $rs['access_token'];
				$ticketfile = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi");
				$ticketfile = json_decode($ticketfile, true);
				$ticket = $ticketfile['ticket'];
				$fp = fopen("upload/time.txt", "w");
				fwrite($fp,$time);
				fclose($fp);
				$fp = fopen("upload/access_token.txt", "w");
				fwrite($fp,$access_token);
				fclose($fp);
				$fp = fopen("upload/ticket.txt", "w");
				fwrite($fp,$ticket);
				fclose($fp);
				return $rs['access_token'];
			}else{
				throw new Exception($rs['errcode']);
			}
		}
		return $access_token;
	}

	public function getKflist(){
		$access_token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.$access_token;
		return json_decode(file_get_contents($url), true);
	}



	public function createMenu($data)
	{
		$access_token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;

		// $menu = array("button"=>array(
		// 		array('name'=>'吃货活动','sub_button'=>array(array('type'=>'click','name'=>'食命必达','key'=>'A1'))),
		// 		array('name'=>'区域口味','sub_button'=>array(array('type'=>'click','name'=>'七种口味','key'=>'B1'))),
		// 		array('name'=>'营长公告','sub_button'=>array(array('type'=>'click','name'=>'最新活动','key'=>'C1'),array('type'=>'click','name'=>'获奖名单','key'=>'C2'),))),
		// 	);


		$this->dataPost($this->decodeUnicode(json_encode($data)),$url);
		return true;
	}

	public function getqrcode($sceneid)
	{
		$access_token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
		$post_data ='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$sceneid.'}}}';
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
		print $output;
	}

	public function sendService($fromUsername, $toUsername){
		$textTpl = "<xml>
					     <ToUserName><![CDATA[%s]]></ToUserName>
					     <FromUserName><![CDATA[%s]]></FromUserName>
					     <CreateTime>%s</CreateTime>
					     <MsgType><![CDATA[transfer_customer_service]]></MsgType>
					</xml>";
	    return sprintf($textTpl, $fromUsername, $toUsername, time());
	}

	public function transferService($fromUsername, $toUsername ,$kfaccount){
		$textTpl = "<xml>
     			<ToUserName><![CDATA[%s]]></ToUserName>
     			<FromUserName><![CDATA[%s]]></FromUserName>
     			<CreateTime>%s</CreateTime>
     			<MsgType><![CDATA[transfer_customer_service]]></MsgType>
     			<TransInfo>
						<KfAccount><![CDATA[%s]]></KfAccount>
     			</TransInfo>
 					</xml>";
			return sprintf($textTpl, $fromUsername, $toUsername, time(), $kfaccount);
	}

	public function sendMsgForSubscribe($fromUsername, $toUsername, $time, $msgType)
	{
		//查询是否有欢迎语句
		$sql="select * from same_wmenu_event where event='subscribe' and msgtype='text'";
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		$contentStr=$rs['content'];
		$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";
	    return sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	}

	private function dataPost($post_string, $url)
	{
		$context = array (
				'http' => array ('method' => "POST",
				'header' => "Content-type: application/x-www-form-urlencoded\r\n User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Accept: */*",
				'content' => $post_string ));

		$stream_context = stream_context_create ($context);

		$data = file_get_contents ($url, FALSE, $stream_context);
		$rs = json_decode($data,true);
		if($rs['errcode']!=0)
			throw new Exception($rs['errcode']);
		return true;;
	}

	private function decodeUnicode($str) {

		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function( '$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");' ), $str);
	}

	public function getpagebyid($id){
		$sql="SELECT * FROM trio_wechat_page where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function getOauth()
	{
		$callback=Yii::app()->request->hostInfo.'/'.Yii::app()->request->baseUrl.'/weixin/callback';
		$rs = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->_appid.'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		return $rs;

	}

	public function getOauth2()
	{
		$callback=Yii::app()->request->hostInfo.'/'.Yii::app()->request->baseUrl.'/weixin/callback2';
		$rs = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->_appid.'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		return $rs;

	}

	public function getOauthAccessToken($code){
		$rs = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?code='.$code.'&grant_type=authorization_code&appid='.$this->_appid.'&secret='.$this->_secret);
		$rs = json_decode($rs,true);
		if(isset($rs['access_token'])){
			return $rs;
		}

		throw new Exception($rs['errcode']);

		return;
	}

	//获取周围坐标
   public function returnSquarePoint($lng, $lat,$distance = 0.5){
         $earthRadius = 6378138;
        $dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);
        return array(
                       'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
                       'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
                       'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
                       'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
   }
   //计算两个坐标的直线距离

   public function getDistance($lat1, $lng1, $lat2, $lng2){
          $earthRadius = 6378138; //近似地球半径米
          // 转换为弧度
          $lat1 = ($lat1 * pi()) / 180;
          $lng1 = ($lng1 * pi()) / 180;
          $lat2 = ($lat2 * pi()) / 180;
          $lng2 = ($lng2 * pi()) / 180;
          // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
          $calcLatitude = $lat2 - $lat1;
          $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
       $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
          $calculatedDistance = $earthRadius * $stepTwo;
          return round($calculatedDistance);
   }

	 //sub function
	 public function checkopenid($openid){
		 $sql = "select id from same_login where openid = '".trim($openid)."'";
		 $result = Yii::app()->db->createCommand($sql)->queryAll();
		 if(is_array($result) && count($result) > 0 )
		 	return true;
		return false;
	 }
   //post function

   public function post_data($url, $param, $is_file = false, $return_array = true){
	if (! $is_file && is_array ( $param )) {
		$param = $this->JSON ( $param );
	}
	if ($is_file) {
		$header [] = "content-type: multipart/form-data; charset=UTF-8";
	} else {
		$header [] = "content-type: application/json; charset=UTF-8";
	}
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
	curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
	curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $param );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	$res = curl_exec ( $ch );

// 	$flat = curl_errno ( $ch );
// 	if ($flat) {
// 		$data = curl_error ( $ch );
// 		addWeixinLog ( $flat, 'post_data flat' );
// 		addWeixinLog ( $data, 'post_data msg' );
// 	}

	curl_close ( $ch );

	if($return_array)
	  $res = json_decode ( $res, true );
	return $res;
      }

 public function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
	static $recursive_counter = 0;
	if (++ $recursive_counter > 1000) {
		die ( 'possible deep recursion attack' );
	}
	foreach ( $array as $key => $value ) {
		if (is_array ( $value )) {
			$this->arrayRecursive ( $array [$key], $function, $apply_to_keys_also );
		} else {
			$array [$key] = $function ( $value );
		}

		if ($apply_to_keys_also && is_string ( $key )) {
			$new_key = $function ( $key );
			if ($new_key != $key) {
				$array [$new_key] = $array [$key];
				unset ( $array [$key] );
			}
		}
	}
	$recursive_counter --;
      }

  public function JSON($array) {
	$this->arrayRecursive ( $array, 'urlencode', true );
	$json = json_encode ( $array );
	return urldecode ( $json );
    }
}
