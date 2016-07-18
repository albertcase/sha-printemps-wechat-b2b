<?php 
/**
 * 
 * 管理系统后台用户处理Model
 * @author TomHe
 *
 */
class Sysuser
{
	private $_db = NULL;
	
	public function __construct()
	{
		$this->_db = Yii::app()->db;	
	}

	public function listForEdit($data)
	{
		$page = isset($data['page']) ? intval($data['page']) : 1;  
		$rows = isset($data['rows']) ? intval($data['rows']) : 150;  
		$department = isset($data['department']) ? intval($data['department']) : '';
		$permissions = isset($data['permissions']) ? intval($data['permissions']) : '';
		$userName = isset($data['userName']) ? intval($data['userName']) : '';
		$offset = ($page-1)*$rows;
		$where = '1';
		if($department){
			$where .= " AND did=$department";
		}
		if($permissions){
			$where .= " AND pid=$permissions";
		}
		if($userName){
			$where .= " AND uname like '%$userName%'";
		}

		$sqlCount = "SELECT count(id) AS num FROM same_sys_user WHERE $where";
		$menuCount = $this->_db->createCommand($sqlCount)->select()->queryScalar();

		$sql = "SELECT id, uname, '******' AS password, rname, did, dname, pid, pname, status, ouname, createtime FROM same_sys_user WHERE $where limit $offset,$rows";
		$command = $this->_db->createCommand($sql);
		$command->bindParam(':department',$data['department'],PDO::PARAM_INT);
		$command->bindParam(':permissions',$data['permissions'],PDO::PARAM_STR);
		$menuAll = $command->select()->queryAll();
		$menuAll = array("total"=>count($menuCount),"rows"=>$menuAll);
		return json_encode($menuAll);
	}

	public function add($data)
	{
		$result = array('code'=>'','msg'=>'');
		try{
			$sysUserId = Yii::app()->user->sysUserId;
			$sysUserName = Yii::app()->user->sysUserName;
			$sql = "INSERT INTO same_sys_user SET uname=:uname, password=:password, rname=:rname, did=:did, dname=:dname, pid=:pid, pname=:pname, status=:status, ouid=:ouid, ouname=:ouname";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':uname',$data['uname'],PDO::PARAM_STR);
			$command->bindValue(':password',md5($data['password']),PDO::PARAM_STR);
			$command->bindParam(':rname',$data['rname'],PDO::PARAM_STR);
			$command->bindParam(':did',$data['did'],PDO::PARAM_INT);
			$command->bindParam(':dname',$data['dname'],PDO::PARAM_STR);
			$command->bindParam(':pid',$data['pid'],PDO::PARAM_INT);
			$command->bindParam(':pname',$data['pname'],PDO::PARAM_STR);
			$command->bindParam(':status',$data['status'],PDO::PARAM_STR);			
			$command->bindParam(':ouid',$sysUserId,PDO::PARAM_STR);
			$command->bindParam(':ouname',$sysUserName,PDO::PARAM_STR);
			$command->execute();
		}catch(Exception $e){
			if($e->errorInfo[1]=='1062'){
				$result['code'] = 2;
				$result['msg']  = '用户名“'.$data['uname'].'”重复，请更换一个用户名';
			}else{
				$result['code'] = 0;
				$result['msg']  = '系统服务错误';
			}
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
			$sql = "UPDATE same_sys_user SET uname=:uname, ";
			if('******'!=$data['password'] && !empty($data['password']))
				$sql .= " password=:password, ";
			$sql .= " rname=:rname, did=:did, dname=:dname, pid=:pid, pname=:pname, status=:status, ouid=:ouid, ouname=:ouname WHERE id=:id";
			$command = $this->_db->createCommand($sql);
			$command->bindParam(':id',$data['uid'],PDO::PARAM_INT);
			$command->bindParam(':uname',$data['uname'],PDO::PARAM_STR);
			if('******'!=$data['password'] && !empty($data['password']))
				$command->bindValue(':password',md5($data['password']),PDO::PARAM_STR);
			$command->bindParam(':rname',$data['rname'],PDO::PARAM_STR);
			$command->bindParam(':did',$data['did'],PDO::PARAM_INT);
			$command->bindParam(':dname',$data['dname'],PDO::PARAM_STR);
			$command->bindParam(':pid',$data['pid'],PDO::PARAM_INT);
			$command->bindParam(':pname',$data['pname'],PDO::PARAM_STR);
			$command->bindParam(':status',$data['status'],PDO::PARAM_STR);			
			$command->bindParam(':ouid',$sysUserId,PDO::PARAM_STR);
			$command->bindParam(':ouname',$sysUserName,PDO::PARAM_STR);
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
}