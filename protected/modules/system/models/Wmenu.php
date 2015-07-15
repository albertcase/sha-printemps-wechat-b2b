<?php

class Wmenu{
	private $_db = NULL;
	
	public function __construct()
	{
		$this->_db = Yii::app()->db;	
	}

	public function MenuLlist($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;
		$search = isset($data['search']) ? $data['search'] : '';
		$offset = ($page-1)*$rows;
		$where = '1';
		
		if($search){
			$where .= " AND title like '%".$search."%' ";
		}

		$sqlCount = "SELECT count(id) AS num FROM same_wmenu WHERE $where";
		$count = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT * FROM same_wmenu WHERE $where ORDER BY id DESC  limit $offset,$rows";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>$count,"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function getMenuById($id)
	{
		$sql="SELECT * FROM same_wmenu where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function getPmenu()
	{
		$sql="SELECT id,name FROM same_wmenu where pid=0";
		$rs=$this->_db->createCommand($sql)->select()->queryAll();
		$ary = array(array('id'=>0,'name'=>'无'));
		for($i=0;$i<count($rs);$i++){
			$ary[]=$rs[$i];
		}
		return json_encode($ary);
	}

	public function menuUpdate($data)
	{
		$result = array('code'=>'','msg'=>'');
		$id = $data['id'];
		$pid = $data['pid'];
		$name = $data['name'];
		$event = $data['event'];
		$eventkey = $data['eventkey'];
		$eventurl = $data['eventurl'];

		if($event=='click'){
			$eventurl = '';
		}else{
			$eventkey = '';
		}

		try{
			$sql = "UPDATE same_wmenu SET pid=:pid,name=:name, event=:event,eventkey=:eventkey, eventurl=:eventurl WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$id,PDO::PARAM_INT);
			$command->bindParam(':pid',$pid,PDO::PARAM_STR);
			$command->bindParam(':name',$name,PDO::PARAM_STR);
			$command->bindParam(':event',$event,PDO::PARAM_STR);
			$command->bindParam(':eventkey',$eventkey,PDO::PARAM_STR);
			$command->bindValue(':eventurl',$eventurl,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function menuAdd($data)
	{
		$result = array('code'=>'','msg'=>'');	
		$pid = $data['pid'];	
		$name = $data['name'];
		$event = $data['event'];
		$eventkey = $data['eventkey'];
		$eventurl = $data['eventurl'];

		if($event=='click'){
			$eventurl = '';
		}else{
			$eventkey = '';
		}

		try{
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "INSERT INTO same_wmenu SET pid=:pid,name=:name, event=:event,eventkey=:eventkey, eventurl=:eventurl";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':pid',$pid,PDO::PARAM_STR);
			$command->bindParam(':name',$name,PDO::PARAM_STR);
			$command->bindParam(':event',$event,PDO::PARAM_STR);
			$command->bindParam(':eventkey',$eventkey,PDO::PARAM_STR);
			$command->bindValue(':eventurl',$eventurl,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function getMenuAll()
	{
		$sql = "SELECT id,pid,name,event AS type,eventkey AS `key`,eventurl AS url FROM same_wmenu WHERE 1 order by id";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menu = $this->getTreeData(0,$menuAll);	
		$menu1['button'] = $menu;

		return $menu1;
	}

	public function menuDelete($data)
	{
		$sql = "DELETE FROM same_wmenu WHERE id=".$data['id'];
		$this->_db->createCommand($sql)->execute();
		$result['code'] = 1;
		$result['msg']  = '删除成功';
		return json_encode($result);

	}


	/**
	 * 
	 * 遍历递归菜单
	 * @param array $AryData
	 * @param int $pid
	 * @return array
	 */	
	private function & getTreeData($pid,$AryData) {
		$TreeArray   = Array();
		$ArrayCode   = 0;
		for($i=0; $i<count($AryData); $i++) 
		{
			if($AryData[$i]['pid'] == $pid) 
			{
				//$AryData[$i]['attributes']['url'] = $AryData[$i]['url'];
				$TreeArray[$ArrayCode] = $AryData[$i];
				$menuTemp = & $this->getTreeData($AryData[$i]['id'],$AryData);
				if(!empty($menuTemp)){
					$TreeArray[$ArrayCode]['sub_button'] = $menuTemp;			
				}
				$ArrayCode++;
			}
		}
		return $TreeArray;
	}

	public function eventList($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;
		$search = isset($data['search']) ? $data['search'] : '';
		$offset = ($page-1)*$rows;
		$where = '1';		
		

		$sqlCount = "SELECT count(id) AS num FROM same_wmenu_event WHERE $where";
		$count = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT A.*,B.name AS mname FROM same_wmenu_event A left join same_wmenu B ON B.id=A.mid WHERE $where ORDER BY A.id DESC  limit $offset,$rows";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>$count,"rows"=>$menuAll);
		return json_encode($menuAll);
	}



	public function getPmenuForChild()
	{
		$sql="SELECT id,name FROM same_wmenu where id not in(select distinct(pid) from same_wmenu)";
		$rs=$this->_db->createCommand($sql)->select()->queryAll();
		$ary = array(array('id'=>0,'name'=>'无'));
		for($i=0;$i<count($rs);$i++){
			$ary[]=$rs[$i];
		}
		return json_encode($ary);
	}

	public function getEventById($id)
	{
		//$sql="SELECT A.*,B.name AS mname FROM same_wmenu_event A left join same_wmenu B ON B.id=A.mid where A.id=".$id;
		$sql="SELECT * FROM same_wmenu_event where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function eventUpdate($data)
	{
		$result = array('code'=>'','msg'=>'');
		$id = $data['id'];
		$mid = $data['mid'];
		$event = $data['event'];
		$msgtype = $data['msgtype'];
		$content = $data['content'];
		$title = $data['title'];
		$description = $data['description'];
		$picUrl = $data['picUrl'];
		$url = $data['url'];
		$keyword = $data['keyword'];

		if($msgtype=='text'){
			$title = '';
			$description = '';
			$picUrl = '';
			$url = '';
		}else if($msgtype=='news'){
			$content = '';
		}

		if($event!='click'){
			$mid = 0;
		}

		if( $event!='text'&& $msgtype!='news'){
			if($event!='subscribe'){
				$sqlCheck = "SELECT id FROM same_wmenu_event WHERE mid=:mid AND event=:event AND id<>".$id;
				$command = $this->_db->createCommand($sqlCheck);
				$command->bindParam(':mid',$mid,PDO::PARAM_STR);
				$command->bindParam(':event',$event,PDO::PARAM_STR);
				$rsCheck = $command->select()->queryScalar();
				if($rsCheck){
					$result['code'] = 2;
					$result['msg']  = '该菜单已经有了该事件，请编辑修改！';
					return json_encode($result);
				}
			}else{
				$sqlCheck = "SELECT id FROM same_wmenu_event WHERE mid=:mid AND event=:event and msgtype=:msgtype AND id<>".$id;
				$command = $this->_db->createCommand($sqlCheck);
				$command->bindParam(':mid',$mid,PDO::PARAM_STR);
				$command->bindParam(':event',$event,PDO::PARAM_STR);
				$command->bindParam(':msgtype',$msgtype,PDO::PARAM_STR);
				$rsCheck = $command->select()->queryScalar();
				if($rsCheck){
					$result['code'] = 2;
					$result['msg']  = '该菜单已经有了该事件，请编辑修改！';
					return json_encode($result);
				}
			}
		}

		try{
			$sql = "UPDATE same_wmenu_event SET mid=:mid, event=:event, msgtype=:msgtype,content=:content, title=:title, description=:description, picUrl=:picUrl, url=:url, keyword=:keyword WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$id,PDO::PARAM_INT);
			$command->bindParam(':mid',$mid,PDO::PARAM_STR);
			$command->bindParam(':event',$event,PDO::PARAM_STR);
			$command->bindParam(':msgtype',$msgtype,PDO::PARAM_STR);
			$command->bindParam(':content',$content,PDO::PARAM_STR);
			$command->bindValue(':title',$title,PDO::PARAM_STR);
			$command->bindValue(':description',$description,PDO::PARAM_STR);
			$command->bindValue(':picUrl',$picUrl,PDO::PARAM_STR);
			$command->bindValue(':url',$url,PDO::PARAM_STR);
			$command->bindValue(':keyword',$keyword,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function eventAdd($data)
	{
		$result = array('code'=>'','msg'=>'');
		$mid = $data['mid'];
		$event = $data['event'];
		$msgtype = $data['msgtype'];
		$content = $data['content'];
		$title = $data['title'];
		$description = $data['description'];
		$picUrl = $data['picUrl'];
		$url = $data['url'];
		$keyword = $data['keyword'];

		if($msgtype=='text'){
			$title = '';
			$description = '';
			$picUrl = '';
			$url = '';
		}else if($msgtype=='news'){
			$content = '';
		}

		if($event!='click'){
			$mid = 0;
		}
		if( $event!='text' && $msgtype!='news'){
			if($event!='subscribe'){
				$sqlCheck = "SELECT id FROM same_wmenu_event WHERE mid=:mid AND event=:event";
				$command = $this->_db->createCommand($sqlCheck);
				$command->bindParam(':mid',$mid,PDO::PARAM_STR);
				$command->bindParam(':event',$event,PDO::PARAM_STR);
				$rsCheck = $command->select()->queryScalar();
				if($rsCheck){
					$result['code'] = 2;
					$result['msg']  = '该菜单已经有了该事件，请编辑修改！';
					return json_encode($result);
				}
			}else{
				$sqlCheck = "SELECT id FROM same_wmenu_event WHERE mid=:mid AND msgtype=:msgtype";
				$command = $this->_db->createCommand($sqlCheck);
				$command->bindParam(':mid',$mid,PDO::PARAM_STR);
				$command->bindParam(':msgtype',$msgtype,PDO::PARAM_STR);
				$rsCheck = $command->select()->queryScalar();
				if($rsCheck){
					$result['code'] = 2;
					$result['msg']  = '该菜单已经有了该事件，请编辑修改！';
					return json_encode($result);
				}
			}
		}

		try{
			$sql = "INSERT INTO same_wmenu_event SET mid=:mid, event=:event, msgtype=:msgtype,content=:content, title=:title, description=:description, picUrl=:picUrl, url=:url, keyword=:keyword";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':mid',$mid,PDO::PARAM_STR);
			$command->bindParam(':event',$event,PDO::PARAM_STR);
			$command->bindParam(':msgtype',$msgtype,PDO::PARAM_STR);
			$command->bindParam(':content',$content,PDO::PARAM_STR);
			$command->bindValue(':title',$title,PDO::PARAM_STR);
			$command->bindValue(':description',$description,PDO::PARAM_STR);
			$command->bindValue(':picUrl',$picUrl,PDO::PARAM_STR);
			$command->bindValue(':url',$url,PDO::PARAM_STR);
			$command->bindValue(':keyword',$keyword,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function eventDelete($data)
	{
		$sql = "DELETE FROM same_wmenu_event WHERE id=".$data['id'];
		$this->_db->createCommand($sql)->execute();
		$result['code'] = 1;
		$result['msg']  = '删除成功';
		return json_encode($result);

	}
	public function qrcodeList($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;
		$search = isset($data['search']) ? $data['search'] : '';
		$offset = ($page-1)*$rows;
		$where = '1';		
		

		$sqlCount = "SELECT count(id) AS num FROM same_qrcode WHERE $where";
		$count = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT * FROM same_qrcode WHERE $where ORDER BY id DESC  limit $offset,$rows";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>$count,"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function getQrcodeById($id)
	{
		//$sql="SELECT A.*,B.name AS mname FROM same_wmenu_event A left join same_wmenu B ON B.id=A.mid where A.id=".$id;
		$sql="SELECT * FROM same_qrcode where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function qrcodeUpdate($data)
	{
		$result = array('code'=>'','msg'=>'');
		$id = $data['id'];
		$bak = $data['bak'];
		$scene_id = $data['sceneId'];
		$ticket = $data['ticket'];

		try{
			$sql = "UPDATE same_qrcode SET bak=:bak, scene_id=:scene_id, ticket=:ticket WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$id,PDO::PARAM_INT);
			$command->bindParam(':bak',$bak,PDO::PARAM_STR);
			$command->bindParam(':scene_id',$scene_id,PDO::PARAM_STR);
			$command->bindParam(':ticket',$ticket,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function qrcodeAdd($data)
	{
		$result = array('code'=>'','msg'=>'');
		$bak = $data['bak'];
		$scene_id = $data['sceneId'];
		$ticket = $data['ticket'];


		try{
			$sql = "INSERT INTO same_qrcode SET bak=:bak, scene_id=:scene_id, ticket=:ticket";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':bak',$bak,PDO::PARAM_STR);
			$command->bindParam(':scene_id',$scene_id,PDO::PARAM_STR);
			$command->bindParam(':ticket',$ticket,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function qrcodeDelete($data)
	{
		$sql = "DELETE FROM same_qrcode WHERE id=".$data['id'];
		$this->_db->createCommand($sql)->execute();
		$result['code'] = 1;
		$result['msg']  = '删除成功';
		return json_encode($result);

	}

	public function storeList($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;
		$search = isset($data['search']) ? $data['search'] : '';
		$offset = ($page-1)*$rows;
		$where = '1';			

		$sqlCount = "SELECT count(id) AS num FROM same_store WHERE $where";
		$count = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT * FROM same_store WHERE $where ORDER BY id DESC  limit $offset,$rows";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>$count,"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function getStoreById($id)
	{
		$sql="SELECT * FROM same_store where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function storeUpdate($data)
	{
		$result = array('code'=>'','msg'=>'');

		try{
			$sql = "UPDATE same_store SET country = :country, city = :city, name=:name, address=:address, telphone=:telphone, open=:open, lat=:lat, lng=:lng, picUrl=:picUrl, mapUrl=:mapUrl WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id', $data['id'], PDO::PARAM_INT);
			$command->bindParam(':country', $data['country'], PDO::PARAM_STR);
			$command->bindParam(':city', $data['city'], PDO::PARAM_STR);
			$command->bindParam(':name', $data['name'], PDO::PARAM_STR);
			$command->bindParam(':address', $data['address'], PDO::PARAM_STR);
			$command->bindParam(':telphone', $data['telphone'], PDO::PARAM_STR);
			$command->bindParam(':open', $data['open'], PDO::PARAM_STR);
			$command->bindParam(':lat', $data['lat'], PDO::PARAM_STR);
			$command->bindParam(':lng', $data['lng'], PDO::PARAM_STR);
			$command->bindParam(':picUrl', $data['picUrl'], PDO::PARAM_STR);
			$command->bindParam(':mapUrl', $data['mapUrl'], PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function storeAdd($data)
	{
		$result = array('code'=>'','msg'=>'');

		try{
			$sql = "INSERT INTO same_store SET country = :country, city = :city, name=:name, address=:address, telphone=:telphone, open=:open, lat=:lat, lng=:lng, picUrl=:picUrl, mapUrl=:mapUrl";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':country', $data['country'], PDO::PARAM_STR);
			$command->bindParam(':city', $data['city'], PDO::PARAM_STR);
			$command->bindParam(':name', $data['name'], PDO::PARAM_STR);
			$command->bindParam(':address', $data['address'], PDO::PARAM_STR);
			$command->bindParam(':telphone', $data['telphone'], PDO::PARAM_STR);
			$command->bindParam(':open', $data['open'], PDO::PARAM_STR);
			$command->bindParam(':lat', $data['lat'], PDO::PARAM_STR);
			$command->bindParam(':lng', $data['lng'], PDO::PARAM_STR);
			$command->bindParam(':picUrl', $data['picUrl'], PDO::PARAM_STR);
			$command->bindParam(':mapUrl', $data['mapUrl'], PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function storeDelete($data)
	{
		$sql = "DELETE FROM same_store WHERE id=".$data['id'];
		$this->_db->createCommand($sql)->execute();
		$result['code'] = 1;
		$result['msg']  = '删除成功';
		return json_encode($result);

	}

	public function pageList($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;
		$search = isset($data['search']) ? $data['search'] : '';
		$offset = ($page-1)*$rows;
		$where = '1';		
		

		$sqlCount = "SELECT count(id) AS num FROM trio_wechat_page WHERE $where";
		$count = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT * FROM trio_wechat_page WHERE $where ORDER BY id DESC  limit $offset,$rows";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>$count,"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function getPageById($id)
	{
		$sql="SELECT * FROM trio_wechat_page where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function pageAdd($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$mp4='';
			if($data['img']){
				$mp4ary=explode("|",$data['img']);
				for($i=0;$i<count($mp4ary)-1;$i++){
					$mp4.=$this->renameUploadFile($mp4ary[$i],'img')."|";
				}
			}
			$sql = "INSERT INTO trio_wechat_page SET title=:title,img=:img, content=:content";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':title',$data['title'],PDO::PARAM_INT);
			$command->bindParam(':img',$mp4,PDO::PARAM_STR);
			$command->bindParam(':content',$data['content'],PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}
	public function pageUpdate($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			//$mImg = $this->renameUploadFile($data['bimg'],'img');
			$mp4='';
			if($data['img']){
				$mp4ary=explode("|",$data['img']);
				for($i=0;$i<count($mp4ary)-1;$i++){
					$mp4.=$this->renameUploadFile($mp4ary[$i],'img')."|";
				}
			}
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "UPDATE trio_wechat_page SET title=:title,img=:img, content=:content WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$data['id'],PDO::PARAM_INT);
			$command->bindParam(':title',$data['title'],PDO::PARAM_INT);
			$command->bindParam(':img',$mp4,PDO::PARAM_STR);
			$command->bindParam(':content',$data['content'],PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}
	protected function renameUploadFile($fliePath,$type)
	{
		$root = YiiBase::getPathOfAlias('webroot');
		if(strstr($fliePath,'temp')){
			$img = $root.$fliePath;				
			$newImg = '/upload/'.$type.'/'.date('Ymd').'/'.basename($img);

			$folder = '/upload/'.$type.'/'.date("Ymd").'/';
			if(!is_dir($root.$folder)){        	
				if(!mkdir($root.$folder, 0777, true))	
				{	
					throw new Exception('创造文件夹失败...');
				}
				chmod($root.$folder,0777);
			}
			rename($img, $root.$newImg);
		}else{
			$newImg = $fliePath;
		}
		return $newImg;
	}

	public function textList($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;
		$search = isset($data['search']) ? $data['search'] : '';
		$offset = ($page-1)*$rows;
		$where = '1';		
		

		$sqlCount = "SELECT count(id) AS num FROM trio_weichat_text WHERE $where";
		$count = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT * FROM trio_weichat_text WHERE $where ORDER BY id DESC  limit $offset,$rows";
		$command = $this->_db->createCommand($sql);		
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>$count,"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function getTextById($id)
	{
		$sql="SELECT * FROM trio_weichat_text where id=".$id;
		$rs=$this->_db->createCommand($sql)->select()->queryRow();
		return $rs;
	}

	public function textAdd($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$pic=$this->renameUploadFile($data['pic'],'');
			$sql = "INSERT INTO trio_weichat_text SET title=:title,pic=:pic, content=:content, dt=:dt, url=:url";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':title',$data['title'],PDO::PARAM_INT);
			$command->bindParam(':pic',$pic,PDO::PARAM_STR);
			$command->bindParam(':content',$data['content'],PDO::PARAM_STR);
			$command->bindParam(':dt',$data['dt'],PDO::PARAM_STR);
			$command->bindParam(':url',$data['url'],PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}
	public function textUpdate($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$pic=$this->renameUploadFile($data['pic'],'');
			$sql = "UPDATE trio_weichat_text SET title=:title,pic=:pic, content=:content, dt=:dt, url=:url WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$data['id'],PDO::PARAM_INT);
			$command->bindParam(':title',$data['title'],PDO::PARAM_INT);
			$command->bindParam(':pic',$pic,PDO::PARAM_STR);
			$command->bindParam(':content',$data['content'],PDO::PARAM_STR);
			$command->bindParam(':dt',$data['dt'],PDO::PARAM_STR);
			$command->bindParam(':url',$data['url'],PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){print_r($e);
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

}