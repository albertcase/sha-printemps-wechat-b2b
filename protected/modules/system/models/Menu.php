<?php 
/**
 * 
 * 管理系统菜单处理Model
 * @author TomHe
 *
 */
class Menu
{
	private $_db = NULL;
	
	public function __construct()
	{
		$this->_db = Yii::app()->db;	
	}
	
	public function menuList()
	{
		$sql = "SELECT id, pid, name AS text, url, sort FROM same_sys_menu/* WHERE id IN (".Yii::app()->user->sysPermissions.")*/ ORDER BY sort ASC";
		$menuAll = $this->_db->createCommand($sql)->select()->queryAll();
		$menu = $this->getTreeData(0,$menuAll);		
		return json_encode($menu);
	}
	
	public function menuListForEdit($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;  
		$rows = isset($data['rows']) ? intval($data['rows']) : 150;  
		$offset = ($page-1)*$rows;

		$sqlCount = "SELECT count(id) AS num FROM same_sys_menu ORDER BY sort ASC";
		$menuCount = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT id, pid AS _parentId, name AS text, url, sort FROM same_sys_menu ORDER BY sort ASC limit $offset,$rows";
		$menuAll = $this->_db->createCommand($sql)->select()->queryAll();
		//$menuAll[] = array('id'=>0,'text'=>'后台管理系统','url'=>'','sort'=>999999);
		$menuAll = array("total"=>count($menuCount),"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function menuadd($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$sysUserId = Yii::app()->user->sysUserId;
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "INSERT INTO same_sys_menu SET pid=:pid,name=:name,url=:url,sort=:sort,uid=:uid,uname=:uname";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':pid',$data['pid'],PDO::PARAM_INT);
			$command->bindParam(':name',$data['name'],PDO::PARAM_STR);
			$command->bindParam(':url',$data['url'],PDO::PARAM_STR);
			$command->bindParam(':sort',$data['sort'],PDO::PARAM_INT);
			$command->bindParam(':uid',$sysUserId,PDO::PARAM_STR);
			$command->bindParam(':uname',$sysUserName,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function menuupdate($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$sysUserId = Yii::app()->user->sysUserId;
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "UPDATE same_sys_menu SET name=:name,url=:url,sort=:sort,uid=:uid,uname=:uname WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$data['id'],PDO::PARAM_INT);
			$command->bindParam(':name',$data['name'],PDO::PARAM_STR);
			$command->bindParam(':url',$data['url'],PDO::PARAM_STR);
			$command->bindParam(':sort',$data['sort'],PDO::PARAM_INT);
			$command->bindParam(':uid',$sysUserId,PDO::PARAM_STR);
			$command->bindParam(':uname',$sysUserName,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
		return json_encode($result);
	}

	public function menudelete($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$sql = "DELETE FROM same_sys_menu WHERE id=:id OR pid=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$data['nid'],PDO::PARAM_INT);
			$command->execute();
		}catch(Exception $e){
			$result['code'] = 0;
			$result['msg']  = '系统服务错误';
			return json_encode($result);
		}
		$result['code'] = 1;
		$result['msg']  = '操作成功';
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
				$AryData[$i]['attributes']['url'] = $AryData[$i]['url'];
				$TreeArray[$ArrayCode] = $AryData[$i];
				$menuTemp = & $this->getTreeData($AryData[$i]['id'],$AryData);
				if(!empty($menuTemp))
					$TreeArray[$ArrayCode]['children'] = $menuTemp;			
				$ArrayCode++;
			}
		}
		return $TreeArray;
	}

	/**
	 * 获取权限列表菜单
	 * @param int $rid
	 * @return json 
	 */
	public static function getListOfJsoan($role=null){
		$sql = "SELECT id, pid, name AS value FROM same_sys_menu ORDER BY sort ASC";
		$rs = Yii::app()->db->createCommand($sql)->select()->queryAll();
		$ary = array();
		$ary[] = array(
				'id'=>0,
				'pId'=>0,
				'name'=>'全选/取消',
				'open'=>'true'
		);
		
		if(!$role){
			foreach($rs as $k=>$v){
				$ary[] = array(
						'id'=>$v['id'],
						'pId'=>$v['pid'],
						'name'=>$v['value'],
				);
			}
			$ary = json_encode($ary);
			return $ary;
		}else{
			$ary[0]['checked'] = true;
			$roleAry = explode(',', $role);
			foreach($rs as $k=>$v){
				$checked = false;
				$open = false;
				for($i=0;$i<count($roleAry);$i++){
					if($roleAry[$i]==$v['id']){
						$checked = true;
						$open = true;
					}
				}
				$ary[] = array(
						'id'=>$v['id'],
						'pId'=>$v['pid'],
						'name'=>$v['value'],
						'checked'=>$checked,
						'open'=>$open,
				);
			}
			$ary = json_encode($ary);
			return $ary;
		}
	
	}
}
?>