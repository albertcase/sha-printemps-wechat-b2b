<?php

class database
{
  private $sql;

  public function __construct(){
    $this->sql = Yii::app()->db;
  }

  public function getcount($table ,array $data=array()){
    $where = array();
    if(is_array($data)){
      foreach($data as $x => $x_val)
        array_push($where ,$x.'="'.$x_val.'"');
    }
    if(count($where)>0){
      $where = ' where '.implode(' and ', $where);
    }else{
      $where = '';
    }
    $result = $this->Sqlselectall('select count(id) from '.$table.$where);
    return $result[0]['count(id)'];
  }

  public function checkData(array $data=array() , $table){
    if(count($this->searchData($data,array(),$table,1)))
      return true;
    return false;
  }

  public function getpage($numb ,$one ,array $data=array() ,array $dataout=array() , $table,$order = false){
    return $this->searchData($data ,$dataout ,$table ,$one ,$one*($numb-1) ,$order);
  }

  public function searchData(array $data=array() ,array $dataout=array(), $table ,$limit = 0 ,$offset = false ,$order = false){
    $dout = '*';
    $where = array();
    $lim = '';
    $offs = '';
    $orde ='';
    if(is_array($dataout) && count($dataout))
      $dout = implode(',' ,$dataout);
    if(is_array($data)){
      foreach($data as $x => $x_val)
        array_push($where ,$x.'="'.$x_val.'"');
    }
    if(count($where)>0){
      $where = ' where '.implode(' and ', $where);
    }else{
      $where = '';
    }
    if($limit > 0)
      $lim = ' limit '.$limit;
    if($order)
      $orde = ' order by '.$order.' ';
    if($offset)
      $offs = ' offset '.$offset.' ';
    $sql = 'select '.$dout.' from '.$table.$where.$orde.$lim.$offs;
    return $this->Sqlselectall(trim($sql));
  }

//function regexp search

  public function Reggetpage($numb ,$one ,array $data=array() ,array $dataout=array() , $table,$order = false){
    return $this->RegsearchData($data ,$dataout ,$table ,$one ,$one*($numb-1) ,$order);
  }

  public function RegsearchData(array $data=array() ,array $dataout=array(), $table ,$limit = 0 ,$offset = false ,$order = false){
    $dout = '*';
    $where = array();
    $lim = '';
    $offs = '';
    $orde ='';
    if(is_array($dataout) && count($dataout))
      $dout = implode(',' ,$dataout);
    if(is_array($data)){
      foreach($data as $x => $x_val)
        array_push($where ,$x.' REGEXP "'.$x_val.'"');
    }
    if(count($where)>0){
      $where = ' where '.implode(' and ', $where);
    }else{
      $where = '';
    }
    if($limit > 0)
      $lim = ' limit '.$limit;
    if($order)
      $orde = ' order by '.$order.' ';
    if($offset)
      $offs = ' offset '.$offset.' ';
    $sql = 'select '.$dout.' from '.$table.$where.$orde.$lim.$offs;
    return $this->Sqlselectall(trim($sql));
  }

  public function Reggetcount($table ,array $data=array()){
    $where = array();
    if(is_array($data)){
      foreach($data as $x => $x_val)
        array_push($where ,$x.' REGEXP "'.$x_val.'"');
    }
    if(count($where)>0){
      $where = ' where '.implode(' and ', $where);
    }else{
      $where = '';
    }
    $result = $this->Sqlselectall('select count(id) from '.$table.$where);
    return $result[0]['count(id)'];
  }

//end regexp search
  public function insertData(array $data=array(), $table){
    $this->sql->createCommand()->insert($table, $data);
    return $this->sql->getLastInsertID();
  }

  public function insertUData(array $data=array(), $table){
      if($this->checkData($data ,$table)){
      $this->sql->createCommand()->insert($table, $data);
      return $this->sql->getLastInsertID();
    }
    return false;
  }

  public function insertDatas(array $datas=array(), $table){
  	foreach($data as $x){
  	    $this->insertData($x, $table);
  	}
  	return true;
  }

  public function insertUDatas(array $datas=array(), $table){
  	foreach($datas as $x){
  	    if($this->checkData($x, $table))
  		    continue;
  	    $this->insertData($x, $table);
  	}
  	return true;
  }

// sub function

  public function Sqlupdate($table,array $data = array(),$where,$warray){
       $result = $this->sql->createCommand()->update($table, $data ,$where ,$warray);
       if($result){
         return true;
       }else {
         return false;
       }
  }

  public function Sqlselectall($sql){
     $result = $this->sql->createCommand($sql)->queryAll();
     return $result;
  }

  public function Sqldeletes($table, $wheres){
    foreach($wheres as $x){
      $this->Sqldelete($table,$x);
    }
    return true;
  }

  public function Sqldelete($table, array $where = array()){
    $result = false;
    if($where){
      $result = $this->sql->createCommand()->delete($table, $this->buildparm($where), $this->buildval($where));
    }
    return $result;
  }

  // sub function
  public function buildparm($data){
    $data = array_keys($data);
    $keys = array();
    foreach($data as $x){
      $keys[] = $x . '=:'.$x;
    }
    return implode(' and ', $keys);
  }

  public function buildval($data){
    $out = array();
    foreach($data as $x => $x_val){
      $out[':'.$x] = $x_val;
    }
    return $out;
  }

}
