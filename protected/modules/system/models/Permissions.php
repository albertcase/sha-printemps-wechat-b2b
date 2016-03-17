<?php 
Yii::import('ext.json.JSON',true);
/**
 * 
 * 管理系统权限处理Model
 * @author TomHe
 *
 */
class Permissions
{
	private $_db = NULL;
	
	public function __construct()
	{
		$this->_db = Yii::app()->db;	
	}

	public function add($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$sysUserId = Yii::app()->user->sysUserId;
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "INSERT INTO same_sys_permissions SET rname=:rname,role=:role,uid=:uid,uname=:uname";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':rname',$data['rname'],PDO::PARAM_INT);
			$command->bindParam(':role',$data['role'],PDO::PARAM_STR);
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

	public function update($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$sysUserId = Yii::app()->user->sysUserId;
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "UPDATE same_sys_permissions SET rname=:rname,role=:role,uid=:uid,uname=:uname WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':rname',$data['rname'],PDO::PARAM_INT);
			$command->bindParam(':role',$data['role'],PDO::PARAM_STR);
			$command->bindParam(':uid',$sysUserId,PDO::PARAM_STR);
			$command->bindParam(':uname',$sysUserName,PDO::PARAM_STR);
			$command->bindParam(':id',$data['id'],PDO::PARAM_INT);
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

	public function pedit($id)
	{
		$result = array('code'=>'','msg'=>'');
		if($id){
			try{
				$sql = "SELECT id,rname,role FROM same_sys_permissions WHERE id=:id";
				$command = $this->_db->createCommand($sql);
				$command->bindParam(':id',$id,PDO::PARAM_INT);
				$rs = $command->select()->queryRow();
			}catch(Exception $e){
				$result['code'] = 0;
				$result['msg']  = '系统服务错误';
				return json_encode($result);
			}
			$result['code'] = 1;
			$result['msg']  = $rs;
			return json_encode($result);
		}
		$result['code'] = 2;
		$result['msg']  = '参数错误';
		return json_encode($result);
	}

	public function plist($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;  
		$rows = isset($data['rows']) ? intval($data['rows']) : 50;  
		$offset = ($page-1)*$rows;

		$sqlCount = "SELECT count(id) AS num FROM same_sys_permissions";
		$pCount = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT id, rname, uname FROM same_sys_permissions limit $offset,$rows";
		$pAll = $this->_db->createCommand($sql)->select()->queryAll();
		$pAll = array("total"=>$pCount,"rows"=>$pAll);
		return json_encode($result);
	}

	
	public function listForcombobox()
	{
		$sql = "SELECT id, rname AS pname FROM same_sys_permissions ";
		$pAll = $this->_db->createCommand($sql)->select()->queryAll();		
		return json_encode($result);
	}

	
}
